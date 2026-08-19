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
 * the plugin already created and adds only the two things the plugin has no
 * setting for:
 *
 *   1. Card sizing. The block can save exactly one breakpoint, so it can only
 *      express "N across below X, M across above it" — which is what made the
 *      cards jump size mid-range. Switching Swiper to `slidesPerView: 'auto'`
 *      hands sizing to a single `clamp()` in assets/styles/carousel-block.css:
 *      constant gap, a hard 420px ceiling, a two-up floor on phones.
 *   2. A crisp stop. Swiper's pause-on-hover stops *scheduling* the next slide
 *      but lets the transition in flight run to the end — at the slow drift
 *      speed the row keeps gliding for several seconds after the pointer
 *      arrives. Shortening the running transition makes the row settle at once.
 *
 * It also stops the drift for `prefers-reduced-motion`, and while focus is
 * inside the row, so a keyboard user can reach the brand links (WCAG 2.2.2).
 *
 * If this file fails to load, the row still works: it falls back to the plugin's
 * own paged carousel exactly as configured in the editor.
 *
 * @package kwv
 */

( function () {
	var BLOCK_SELECTOR = '.kwv-brand-marquee';
	var MOTION_QUERY = '(prefers-reduced-motion: reduce)';

	/* How quickly a drifting row comes to rest, in ms. */
	var SETTLE_SPEED = 320;

	/* One arrow-driven slide, in ms. The drift speed is far too slow for a click
	 * to feel like a response. */
	var NUDGE_SPEED = 450;

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
		this.syncMotion();

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
		 * happened to produce, and the clamp below never gets a say.
		 */
		this.clearInlineWidths();
		swiper.on( 'resize', function () {
			self.clearInlineWidths();
		} );

		swiper.update();
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
		 * Hover. Swiper has already stopped scheduling by the time this runs
		 * (its own pause-on-hover listener), so all that is left is to bring the
		 * transition already in flight to a stop. Gated on the block's own
		 * setting, so turning "Pause on mouse enter" off in the editor still
		 * means what it says.
		 */
		if ( 'true' === this.block.getAttribute( 'data-cb-pause-on-mouse-enter' ) ) {
			this.block.addEventListener( 'pointerenter', function ( event ) {
				if ( 'mouse' === event.pointerType ) {
					self.settle();
				}
			} );
		}

		/* Keyboard equivalent: stop entirely while focus is inside the row. */
		this.block.addEventListener( 'focusin', function () {
			self.settle();
			self.stop();
		} );

		this.block.addEventListener( 'focusout', function ( event ) {
			if ( ! self.block.contains( event.relatedTarget ) ) {
				self.syncMotion();
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
	 * Bring a drifting row gracefully to rest.
	 *
	 * Re-declaring the transition duration mid-flight restarts it from wherever
	 * the row has got to, toward the same target, over the shorter time.
	 */
	Marquee.prototype.settle = function () {
		if ( this.swiper && this.swiper.animating ) {
			this.swiper.setTransition( SETTLE_SPEED );
		}
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
	 * Stop the drift.
	 */
	Marquee.prototype.stop = function () {
		if ( this.swiper && this.swiper.autoplay ) {
			this.swiper.autoplay.stop();
		}
	};

	/**
	 * Start or stop the drift to match the current reduced-motion setting.
	 */
	Marquee.prototype.syncMotion = function () {
		var swiper = this.swiper;

		if ( ! swiper || ! swiper.autoplay ) {
			return;
		}

		// Autoplay off in the editor stays off.
		if ( ! swiper.params.autoplay || ! swiper.params.autoplay.enabled ) {
			return;
		}

		if ( prefersReducedMotion() ) {
			this.settle();
			swiper.autoplay.stop();
		} else {
			swiper.autoplay.start();
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
	 * Both scripts run on DOMContentLoaded and ours depends on the plugin's, so
	 * it should be ready first — the retry only covers a carousel added later by
	 * the plugin's own mutation observer.
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
	 * Re-check the reduced-motion setting on every row.
	 */
	function syncAll() {
		marquees.forEach( function ( marquee ) {
			marquee.syncMotion();
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

	if ( window.matchMedia ) {
		var list = window.matchMedia( MOTION_QUERY );

		if ( list.addEventListener ) {
			list.addEventListener( 'change', syncAll );
		} else if ( list.addListener ) {
			list.addListener( syncAll );
		}
	}
} )();
