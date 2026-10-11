/**
 * Lazy background video — load on approach, play on screen, respect reduced motion.
 *
 * Pairs with inc/lazy-video.php, which renders opted-in Cover videos as
 * `<video data-kwv-lazy-video data-src="…" preload="none" poster="…">` with no
 * `src`, so nothing is downloaded and the poster image shows.
 *
 *   - Not near the viewport  → nothing is fetched; poster stays.
 *   - Near the viewport      → `src` is set from `data-src` and playback starts.
 *   - Scrolled away again    → paused (saves CPU/battery); resumes on return.
 *   - prefers-reduced-motion → never loaded or played; poster only. The setting
 *                              is re-checked live, in both directions.
 *
 * @package kwv
 */

( function () {
	var QUERY = '(prefers-reduced-motion: reduce)';
	var MARGIN = '300px 0px';
	var list = window.matchMedia ? window.matchMedia( QUERY ) : null;
	var observer = null;

	/**
	 * Whether the visitor has asked the OS for less motion.
	 *
	 * @return {boolean} True when motion should be avoided.
	 */
	function reduced() {
		return !! ( list && list.matches );
	}

	/**
	 * Attach the source the first time the video is allowed to play.
	 *
	 * @param {HTMLVideoElement} video Lazy video element.
	 */
	function ensureSource( video ) {
		if ( ! video.getAttribute( 'src' ) && video.dataset.src ) {
			video.setAttribute( 'src', video.dataset.src );
			video.load();
		}
	}

	/**
	 * Start playback; autoplay can still be refused, in which case the poster stays.
	 *
	 * @param {HTMLVideoElement} video Lazy video element.
	 */
	function play( video ) {
		ensureSource( video );

		var promise = video.play();

		if ( promise && promise.catch ) {
			promise.catch( function () {} );
		}
	}

	/**
	 * Apply the current visibility + motion state to a single video.
	 *
	 * @param {HTMLVideoElement} video   Lazy video element.
	 * @param {boolean}          visible Whether it is near the viewport.
	 */
	function update( video, visible ) {
		if ( visible && ! reduced() ) {
			play( video );
		} else if ( ! video.paused ) {
			video.pause();
		}
	}

	function init() {
		var videos = document.querySelectorAll( 'video[data-kwv-lazy-video]' );

		if ( ! videos.length ) {
			return;
		}

		// Videos are muted + inline by markup; make sure the properties agree.
		Array.prototype.forEach.call( videos, function ( video ) {
			video.muted = true;
			video.playsInline = true;
		} );

		if ( ! ( 'IntersectionObserver' in window ) ) {
			// No observer support: load straight away unless motion is unwanted.
			Array.prototype.forEach.call( videos, function ( video ) {
				update( video, true );
			} );
			return;
		}

		observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					entry.target.kwvVisible = entry.isIntersecting;
					update( entry.target, entry.isIntersecting );
				} );
			},
			{ rootMargin: MARGIN }
		);

		Array.prototype.forEach.call( videos, function ( video ) {
			observer.observe( video );
		} );

		// React when the motion preference changes mid-session.
		function onChange() {
			Array.prototype.forEach.call( videos, function ( video ) {
				update( video, !! video.kwvVisible );
			} );
		}

		if ( list ) {
			if ( list.addEventListener ) {
				list.addEventListener( 'change', onChange );
			} else if ( list.addListener ) {
				list.addListener( onChange );
			}
		}
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
