/**
 * Homepage brand rows — card sizing.
 *
 * The three brand carousels (Wine / Spirits / Agency) are ordinary
 * `cb/carousel-v2` blocks. All of their behaviour — autoplay, transition speed,
 * autoplay delay, pause-on-hover, the arrows, the loop, and each row's scroll
 * direction via the block's "RTL (Right-to-Left)" toggle — is set in the Site
 * Editor. Nothing here touches any of it.
 *
 * The single thing the block cannot express is card *sizing*. It saves one
 * breakpoint, so the most it can say is "two across below 1024, five above",
 * which produces a 2.6x jump in card size across a single pixel of viewport
 * (measured: 494px wide at 1023px, 189px at 1024px) and cards that grow without
 * limit on wide screens (493px at 2560px).
 *
 * Fixing that in CSS alone is not possible. Swiper writes an inline width on
 * every slide and — more importantly — computes its translate and snap maths
 * from that same number. An `!important` rule does override the rendered width,
 * but Swiper keeps paging by its own figure, so the active card drifts out of
 * the viewport as you advance (measured: -20px, -206px, -296px, -362px).
 *
 * So this module changes what Swiper *computes*: `slidesPerView: 'auto'`, at
 * which point Swiper measures the slides instead of sizing them, and the
 * `clamp()` in assets/styles/carousel-block.css decides — a constant 20px gap, a
 * 420px ceiling, a two-up floor on phones, five across in between.
 *
 * That is all it does.
 *
 * If this file fails to load, the rows still work; the cards simply size
 * themselves from the block's saved breakpoint instead.
 *
 * @package kwv
 */

( function () {
	var BLOCK_SELECTOR = '.kwv-brand-marquee';

	/**
	 * One brand row.
	 *
	 * @param {Element} block The `.kwv-brand-marquee` block wrapper.
	 */
	function BrandRow( block ) {
		this.block = block;
		this.viewport = block.querySelector( '.swiper' );
		this.swiper = null;
	}

	/**
	 * Hand card sizing to the stylesheet, once the plugin has built the carousel.
	 *
	 * @return {boolean} True once this row is set up.
	 */
	BrandRow.prototype.adopt = function () {
		if ( this.swiper ) {
			return true;
		}

		if ( ! this.viewport || ! this.viewport.swiper ) {
			return false;
		}

		var swiper = this.viewport.swiper;

		this.swiper = swiper;

		swiper.params.slidesPerView = 'auto';
		swiper.params.breakpoints = {};

		// Swiper restores from originalParams whenever it re-evaluates
		// breakpoints, so the same edit has to land there or the next resize
		// takes sizing back.
		if ( swiper.originalParams ) {
			swiper.originalParams.slidesPerView = 'auto';
			swiper.originalParams.breakpoints = {};
		}

		/*
		 * Swiper stops *writing* inline widths under 'auto' but does not clear
		 * the ones it already wrote while slidesPerView was a number. Left behind
		 * they pin every card to whatever width the first layout produced, and
		 * the clamp never gets a say.
		 *
		 * Once only: with no numeric slidesPerView left in `params` or in
		 * `originalParams`, and no breakpoints to re-apply one, Swiper has no
		 * path back to writing them. Clearing them again on every resize was
		 * redundant, and mutating slide styles from inside Swiper's own resize
		 * handler cancelled the transition its autoplay was waiting on — which
		 * stopped rows dead after a resize.
		 */
		this.clearInlineWidths();

		swiper.update();

		return true;
	};

	/**
	 * Drop the inline slide widths, so the stylesheet's `clamp()` decides.
	 */
	BrandRow.prototype.clearInlineWidths = function () {
		Array.prototype.forEach.call( this.swiper.slides, function ( slide ) {
			slide.style.width = '';
		} );
	};

	/**
	 * Set up every brand row whose carousel the plugin has finished building.
	 *
	 * @return {boolean} True once every row on the page is set up.
	 */
	function boot() {
		var blocks = document.querySelectorAll( BLOCK_SELECTOR );
		var ready = true;

		Array.prototype.forEach.call( blocks, function ( block ) {
			if ( ! block.kwvBrandRow ) {
				block.kwvBrandRow = new BrandRow( block );
			}

			if ( ! block.kwvBrandRow.adopt() ) {
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
} )();
