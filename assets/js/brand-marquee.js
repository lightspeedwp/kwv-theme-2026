/**
 * Homepage brand marquees — kwv/front-page "Wine / Spirits / Agency Brands".
 *
 * The brand rows are ordinary `cb/carousel-v2` blocks. Everything an editor
 * would reasonably want to change is left to the block's own Inspector panel
 * and is *not* touched here — which slides there are, autoplay on/off, the
 * animation speed, the autoplay delay, pause-on-hover, the arrows, the loop,
 * and the per-row scroll direction (the block's "RTL (Right-to-Left)" toggle,
 * which is what makes the three rows counter-scroll).
 *
 * This file therefore does not build its own carousel. It adopts the instance
 * the plugin already created and adds only what the plugin has no setting for:
 *
 *   1. Card sizing. The block can save exactly one breakpoint, so it can only
 *      express "N across below X, M across above it" — which is what made the
 *      cards jump size mid-range. Switching Swiper to `slidesPerView: 'auto'`
 *      hands sizing to a single `clamp()` in assets/styles/carousel-block.css:
 *      constant gap, a hard 420px ceiling, a two-up floor on phones.
 *   2. A crisp stop on hover, and arrows that respond at a usable speed.
 *   3. Keeping the drift alive — see "Why the watchdog" below.
 *
 * It also stops the drift for `prefers-reduced-motion`, and while focus is
 * inside the row, so a keyboard user can reach the brand links (WCAG 2.2.2).
 *
 * ## Why the watchdog
 *
 * Swiper's autoplay does not run on a timer for the whole sequence. It pauses
 * itself on `beforeTransitionStart` and registers a one-shot `transitionend`
 * listener; only when that fires does it schedule the next slide. The chain is
 * therefore exactly as reliable as that single event.
 *
 * Anything that replaces the running transition — `swiper.update()`, a
 * `loopFix()`, a resize — cancels it, and a cancelled transition fires
 * `transitioncancel`, not `transitionend`. The listener never runs, autoplay
 * stays `paused` and the row stops for good. With `observer: true` and 38
 * lazily-loaded brand images, updates fire repeatedly while the page settles,
 * so this is close to guaranteed rather than a rare edge case.
 *
 * (The one handler that resumes unconditionally is Swiper's own `pointerleave`,
 * which is why a stalled row would mysteriously start moving after the first
 * hover.)
 *
 * Re-kicking after hand-over fixes the load-time case, but not a stall caused
 * by a resize or a late DOM mutation later on. Rather than try to enumerate
 * every event that can cancel a transition, the watchdog below simply notices
 * that a row which should be drifting has been still for a moment, and restarts
 * it. Lost events become self-healing instead of fatal.
 *
 * If this file fails to load, the row still works: it falls back to the plugin's
 * own paged carousel exactly as configured in the editor.
 *
 * @package kwv
 */

( function () {
	var BLOCK_SELECTOR = '.kwv-brand-marquee';
	var MOTION_QUERY = '(prefers-reduced-motion: reduce)';

	/* How quickly a drifting row glides to rest, in ms. */
	var SETTLE_SPEED = 320;

	/* One arrow-driven slide, in ms. The drift speed is far too slow for a click
	 * to feel like a response. */
	var NUDGE_SPEED = 450;

	/* Watchdog. A healthy row is mid-transition almost permanently — the gap
	 * between slides is the block's 1ms autoplay delay — so being still for this
	 * long means the transitionend chain was broken. */
	var STALL_MS = 400;
	var WATCH_INTERVAL = 500;

	/**
	 * Whether the user has asked the OS for reduced motion.
	 *
	 * @return {boolean} True when motion should be suppressed.
	 */
	function prefersReducedMotion() {
		return !! ( window.matchMedia && window.matchMedia( MOTION_QUERY ).matches );
	}

	/**
	 * One brand row.
	 *
	 * @param {Element} block The `.kwv-brand-marquee` block wrapper.
	 */
	function Marquee( block ) {
		this.block = block;
		this.viewport = block.querySelector( '.swiper' );
		this.swiper = null;
		this.bound = false;
		this.nudging = false;

		/* True while the pointer or focus is inside the row. */
		this.held = false;

		/* When the row was first seen standing still; 0 while it is moving. */
		this.stillSince = 0;
	}

	/**
	 * Adopt the plugin's Swiper instance, if it has been created yet.
	 *
	 * @return {boolean} True once this row is set up.
	 */
	Marquee.prototype.adopt = function () {
		if ( this.swiper ) {
			return true;
		}

		if ( ! this.viewport || ! this.viewport.swiper ) {
			return false;
		}

		this.swiper = this.viewport.swiper;
		this.handOverSizing();
		this.bind();
		this.updateMotion();

		return true;
	};

	/**
	 * Hand card sizing from Swiper's breakpoints over to the stylesheet.
	 *
	 * With `slidesPerView: 'auto'` Swiper stops writing an inline width on each
	 * slide and measures whatever CSS produced instead. The saved breakpoint map
	 * has to go too, or Swiper would re-apply its `slidesPerView` on the next
	 * resize and take sizing back.
	 */
	Marquee.prototype.handOverSizing = function () {
		var self = this;
		var swiper = this.swiper;

		swiper.params.slidesPerView = 'auto';
		swiper.params.breakpoints = {};

		// Swiper restores from originalParams whenever it re-evaluates
		// breakpoints, so the same edit has to land there.
		if ( swiper.originalParams ) {
			swiper.originalParams.slidesPerView = 'auto';
			swiper.originalParams.breakpoints = {};
		}

		/*
		 * Swiper stops *writing* inline widths under 'auto' but does not clear
		 * the ones it already wrote while slidesPerView was a number. Left
		 * behind they pin every card to whatever width the first layout
		 * happened to produce, and the clamp never gets a say.
		 */
		this.clearInlineWidths();
		swiper.on( 'resize', function () {
			self.clearInlineWidths();
		} );

		// This is itself one of the calls that can cancel the running
		// transition, hence the restart on the next line.
		swiper.update();
		this.restartDrift();
	};

	/**
	 * Drop the inline slide widths, so the stylesheet's `clamp()` decides.
	 */
	Marquee.prototype.clearInlineWidths = function () {
		Array.prototype.forEach.call( this.swiper.slides, function ( slide ) {
			slide.style.width = '';
		} );
	};

	/**
	 * Wire up the hover, focus and arrow behaviour.
	 */
	Marquee.prototype.bind = function () {
		if ( this.bound ) {
			return;
		}

		this.bound = true;

		var self = this;

		/*
		 * Hover. Gated on the block's own setting, so turning "Pause on mouse
		 * enter" off in the editor still means what it says. Listening on the
		 * block rather than on `.swiper` keeps the row held while the pointer is
		 * over the arrows, which sit outside the viewport.
		 */
		if ( 'true' === this.block.getAttribute( 'data-cb-pause-on-mouse-enter' ) ) {
			this.block.addEventListener( 'pointerenter', function ( event ) {
				if ( 'mouse' === event.pointerType ) {
					self.hold();
				}
			} );

			this.block.addEventListener( 'pointerleave', function ( event ) {
				if ( 'mouse' === event.pointerType ) {
					self.release();
				}
			} );
		}

		/* Keyboard equivalent: stop entirely while focus is inside the row. */
		this.block.addEventListener( 'focusin', function () {
			self.hold();
		} );

		this.block.addEventListener( 'focusout', function ( event ) {
			if ( ! self.block.contains( event.relatedTarget ) ) {
				self.release();
			}
		} );

		/*
		 * Arrows. Swiper reads `params.speed` when its own click handler runs, so
		 * a capture-phase listener can swap in a responsive speed first. A looped
		 * Swiper also refuses to slide while a transition is in flight, hence the
		 * hard freeze before the click lands.
		 */
		var buttons = this.block.querySelectorAll( '.cb-button-prev, .cb-button-next' );

		Array.prototype.forEach.call( buttons, function ( button ) {
			button.addEventListener(
				'click',
				function () {
					self.freeze();
					self.nudge();
				},
				true
			);
		} );

		this.swiper.on( 'transitionEnd', function () {
			self.releaseNudge();
		} );
	};

	/**
	 * Stop, because the pointer or focus is inside the row.
	 */
	Marquee.prototype.hold = function () {
		this.held = true;
		this.settle();
		this.stopDrift();
	};

	/**
	 * Resume, because the pointer or focus has left.
	 */
	Marquee.prototype.release = function () {
		this.held = false;
		this.updateMotion();
	};

	/**
	 * Bring a drifting row gracefully to rest on its current target.
	 *
	 * Re-declaring `transition-duration` alone does not retarget a transition
	 * that is already running, so this ends the running one at the row's live
	 * position and starts a genuinely new, short transition to the same target.
	 * Finishing on the target rather than freezing mid-card keeps the row on its
	 * snap grid, so the next slide after a resume covers a full card at the
	 * authored speed instead of crawling through whatever distance was left.
	 */
	Marquee.prototype.settle = function () {
		var swiper = this.swiper;

		if ( ! swiper || ! swiper.animating ) {
			return;
		}

		var target = swiper.translate;
		var live = swiper.getTranslate();

		if ( Math.abs( target - live ) < 1 ) {
			return;
		}

		swiper.setTransition( 0 );
		swiper.setTranslate( live );

		// Force a reflow, so the translate below is a new transition rather than
		// being coalesced into the one just cancelled.
		void swiper.wrapperEl.offsetHeight;

		swiper.setTransition( SETTLE_SPEED );
		swiper.setTranslate( target );
	};

	/**
	 * Stop a transition dead, so a queued `slideNext()` is not refused.
	 */
	Marquee.prototype.freeze = function () {
		var swiper = this.swiper;

		if ( ! swiper || ! swiper.animating ) {
			return;
		}

		swiper.setTransition( 0 );
		swiper.setTranslate( swiper.getTranslate() );
		swiper.animating = false;
		swiper.updateActiveIndex();
		swiper.updateSlidesClasses();
	};

	/**
	 * Switch to the arrow speed for the next transition.
	 */
	Marquee.prototype.nudge = function () {
		if ( ! this.swiper || this.nudging ) {
			return;
		}

		this.nudging = true;
		this.swiper.params.speed = NUDGE_SPEED;
	};

	/**
	 * Put the authored drift speed back once an arrow move has finished.
	 */
	Marquee.prototype.releaseNudge = function () {
		if ( ! this.swiper || ! this.nudging ) {
			return;
		}

		var authored = parseInt( this.block.getAttribute( 'data-cb-speed' ), 10 );

		if ( ! isNaN( authored ) ) {
			this.swiper.params.speed = authored;
		}

		this.nudging = false;
	};

	/**
	 * Whether this row is supposed to be drifting right now.
	 *
	 * @return {boolean} True when the drift should be running.
	 */
	Marquee.prototype.shouldDrift = function () {
		var swiper = this.swiper;

		if ( ! swiper || ! swiper.autoplay ) {
			return false;
		}

		// Autoplay switched off in the editor stays off.
		if ( ! swiper.params.autoplay || ! swiper.params.autoplay.enabled ) {
			return false;
		}

		return ! this.held && ! prefersReducedMotion();
	};

	/**
	 * Stop the drift.
	 */
	Marquee.prototype.stopDrift = function () {
		if ( this.swiper && this.swiper.autoplay ) {
			this.swiper.autoplay.stop();
		}

		this.stillSince = 0;
	};

	/**
	 * Restart the drift from a known state.
	 *
	 * `stop()` then `start()` rather than `resume()`, because a row that lost its
	 * `transitionend` is left `running` but permanently `paused` — resuming from
	 * there depends on the very state that is broken.
	 */
	Marquee.prototype.restartDrift = function () {
		if ( ! this.shouldDrift() ) {
			return;
		}

		this.swiper.autoplay.stop();
		this.swiper.autoplay.start();
		this.stillSince = 0;
	};

	/**
	 * Start or stop the drift to match hover, focus and reduced-motion state.
	 */
	Marquee.prototype.updateMotion = function () {
		if ( this.shouldDrift() ) {
			this.restartDrift();
		} else {
			this.settle();
			this.stopDrift();
		}
	};

	/**
	 * Notice a row that has stalled, and restart it. See "Why the watchdog".
	 *
	 * @param {number} now Current timestamp in ms.
	 */
	Marquee.prototype.watch = function ( now ) {
		if ( ! this.shouldDrift() ) {
			this.stillSince = 0;
			return;
		}

		if ( this.swiper.animating ) {
			this.stillSince = 0;
			return;
		}

		if ( ! this.stillSince ) {
			this.stillSince = now;
			return;
		}

		if ( now - this.stillSince > STALL_MS ) {
			this.restartDrift();
		}
	};

	var marquees = [];

	/**
	 * Adopt every brand row whose carousel the plugin has finished building.
	 *
	 * @return {boolean} True once every row on the page is set up.
	 */
	function boot() {
		var blocks = document.querySelectorAll( BLOCK_SELECTOR );
		var ready = true;

		Array.prototype.forEach.call( blocks, function ( block ) {
			if ( ! block.kwvMarquee ) {
				block.kwvMarquee = new Marquee( block );
				marquees.push( block.kwvMarquee );
			}

			if ( ! block.kwvMarquee.adopt() ) {
				ready = false;
			}
		} );

		return ready;
	}

	/**
	 * Keep retrying until the plugin's view script has built every carousel.
	 *
	 * @param {number} attemptsLeft Remaining animation frames to wait.
	 */
	function bootUntilReady( attemptsLeft ) {
		if ( boot() || attemptsLeft <= 0 ) {
			return;
		}

		window.requestAnimationFrame( function () {
			bootUntilReady( attemptsLeft - 1 );
		} );
	}

	/**
	 * Re-check every row's motion state.
	 */
	function syncAll() {
		marquees.forEach( function ( marquee ) {
			marquee.updateMotion();
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			bootUntilReady( 120 );
		} );
	} else {
		bootUntilReady( 120 );
	}

	window.addEventListener( 'load', function () {
		bootUntilReady( 120 );
	} );

	window.setInterval( function () {
		if ( document.hidden ) {
			return;
		}

		var now = Date.now();

		marquees.forEach( function ( marquee ) {
			marquee.watch( now );
		} );
	}, WATCH_INTERVAL );

	if ( window.matchMedia ) {
		var list = window.matchMedia( MOTION_QUERY );

		if ( list.addEventListener ) {
			list.addEventListener( 'change', syncAll );
		} else if ( list.addListener ) {
			list.addListener( syncAll );
		}
	}
} )();
