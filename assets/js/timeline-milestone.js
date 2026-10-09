/**
 * Timeline Milestone — close the hover panel when the card is clicked.
 *
 * The info panel reveals on `:hover` (see assets/styles/core-group.css). Clicking
 * a milestone image opens the core lightbox; when the lightbox closes, the
 * pointer is usually still over the card, so `:hover` alone would leave the
 * panel open. A click therefore marks the card `is-info-dismissed`, which keeps
 * the panel shut until the pointer genuinely leaves the card (or focus moves
 * elsewhere on the page).
 *
 * Opening the lightbox drops its overlay over the card, which fires
 * `pointerleave` / moves focus into the overlay. Those are ignored, otherwise
 * the dismissal would be undone before the lightbox is even closed.
 *
 * @package kwv
 */

( function () {
	var CARD = '.is-style-timeline-milestone';
	var DISMISSED = 'is-info-dismissed';
	var OVERLAY = '.wp-lightbox-overlay';

	/**
	 * Whether a node belongs to the core lightbox overlay.
	 *
	 * @param {?Node} node Node to test.
	 * @return {boolean} True when inside the overlay.
	 */
	function inOverlay( node ) {
		return !! ( node && node.closest && node.closest( OVERLAY ) );
	}

	/**
	 * Re-arm the hover reveal on a dismissed card.
	 *
	 * @param {Element} card Milestone card.
	 */
	function restore( card ) {
		card.classList.remove( DISMISSED );
	}

	document.addEventListener( 'click', function ( event ) {
		var card = event.target.closest && event.target.closest( CARD );

		if ( card ) {
			card.classList.add( DISMISSED );
		}
	} );

	document.addEventListener(
		'pointerleave',
		function ( event ) {
			var card = event.target;

			if (
				! ( card instanceof Element ) ||
				! card.matches( CARD ) ||
				! card.classList.contains( DISMISSED ) ||
				inOverlay( event.relatedTarget )
			) {
				return;
			}

			restore( card );

			// The lightbox hands focus back to its trigger on close. If it was
			// closed with Esc, that focus counts as keyboard (:focus-visible) and
			// would reopen the panel the moment it's re-armed. The pointer has
			// left, so the card no longer needs focus.
			if ( card.contains( document.activeElement ) ) {
				document.activeElement.blur();
			}
		},
		true
	);

	// Keyboard users never fire pointerleave: re-arm once focus moves on to
	// something outside the card (but not into the lightbox it just opened).
	document.addEventListener( 'focusin', function ( event ) {
		if ( inOverlay( event.target ) ) {
			return;
		}

		Array.prototype.forEach.call(
			document.querySelectorAll( CARD + '.' + DISMISSED ),
			function ( card ) {
				if ( ! card.contains( event.target ) ) {
					restore( card );
				}
			}
		);
	} );
} )();
