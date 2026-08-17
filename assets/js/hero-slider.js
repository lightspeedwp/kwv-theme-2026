/**
 * Home hero slideshow — honour `prefers-reduced-motion`.
 *
 * The Carousel Block plugin drives autoplay straight from the block's
 * `data-cb-autoplay` attribute and has no reduced-motion handling of its own, so
 * an auto-advancing hero would keep cycling for users who have asked the OS for
 * less motion. This script gates that.
 *
 * It works whether it runs before or after the plugin's own view script:
 *   - before → rewriting `data-cb-autoplay` stops autoplay from ever starting;
 *   - after  → the live Swiper instance (exposed by the plugin on `.swiper`) is
 *              told to stop.
 *
 * The setting is also re-checked when the user changes it mid-session, in both
 * directions — the block's authored intent is remembered so autoplay can resume.
 *
 * @package kwv
 */

( function () {
	var QUERY = '(prefers-reduced-motion: reduce)';

	/**
	 * Start or stop autoplay on every hero slideshow to match the current setting.
	 */
	function sync() {
		var reduce = !! ( window.matchMedia && window.matchMedia( QUERY ).matches );
		var blocks = document.querySelectorAll( '.cb-hero-slider' );

		Array.prototype.forEach.call( blocks, function ( block ) {
			// Remember what the block was authored to do before we override it.
			if ( ! block.dataset.kwvAutoplay ) {
				block.dataset.kwvAutoplay =
					block.getAttribute( 'data-cb-autoplay' ) || 'false';
			}

			var wanted = 'true' === block.dataset.kwvAutoplay && ! reduce;

			block.setAttribute( 'data-cb-autoplay', wanted ? 'true' : 'false' );

			var slider = block.querySelector( '.swiper' );

			if ( ! slider || ! slider.swiper || ! slider.swiper.autoplay ) {
				return;
			}

			if ( wanted ) {
				slider.swiper.autoplay.start();
			} else {
				slider.swiper.autoplay.stop();
			}
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', sync );
	} else {
		sync();
	}

	// Runs again once the plugin has definitely initialised Swiper.
	window.addEventListener( 'load', sync );

	if ( window.matchMedia ) {
		var list = window.matchMedia( QUERY );

		if ( list.addEventListener ) {
			list.addEventListener( 'change', sync );
		} else if ( list.addListener ) {
			list.addListener( sync );
		}
	}
} )();
