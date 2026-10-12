/**
 * KWV shop mega menu — sticky hover.
 *
 * The fold-out columns in the native-nav mega menu (`is-style-mega-menu-nav`)
 * are co-planar: every column is anchored to the top of the panel, not to the
 * row that opened it. So a row low in one column (Wine > Fortified Products)
 * opens a short column whose items sit near the top. The pointer has to leave
 * the row to reach them, and with pure CSS `:hover` the column vanishes the
 * moment it does — the client couldn't reach Fortified Products' two brands.
 *
 * This keeps a column open until the visitor hovers (or focuses) a different
 * row in the same column, which is the only thing that should close it. The
 * open row carries `is-kwv-open`; assets/styles/ollie-mega-menu.css reveals its
 * submenu from that class once the nav is marked `has-hover-intent`.
 *
 * When the panel closes, the menu returns to its default state (first parent
 * open), matching what the CSS-only fallback shows.
 *
 * Progressive enhancement: with no JS the nav never gets `has-hover-intent`, so
 * the original `:hover` / `:focus-within` rules keep driving it.
 *
 * @package kwv
 */
( function () {
	'use strict';

	var NAV = '.kwv-mega-menu-nav-wrap .wp-block-navigation.is-style-mega-menu-nav';
	var ITEM = '.wp-block-navigation-item';
	var TOGGLE = '.wp-block-ollie-mega-menu__toggle';
	var READY = 'has-hover-intent';
	var OPEN = 'is-kwv-open';

	/**
	 * Close a row and every open row beneath it.
	 *
	 * @param {Element} item A navigation item.
	 */
	function closeBranch( item ) {
		item.classList.remove( OPEN );
		Array.prototype.forEach.call( item.querySelectorAll( '.' + OPEN ), function ( open ) {
			open.classList.remove( OPEN );
		} );
	}

	/**
	 * Make one row the open one in its column: close its siblings' branches and
	 * open it if it has a submenu. A row without children still closes its
	 * siblings — hovering it is "something else".
	 *
	 * @param {Element} item A navigation item.
	 */
	function select( item ) {
		Array.prototype.forEach.call( item.parentElement.children, function ( sibling ) {
			if ( sibling !== item ) {
				closeBranch( sibling );
			}
		} );

		if ( item.classList.contains( 'has-child' ) ) {
			item.classList.add( OPEN );
		}
	}

	/**
	 * Select the row under an event target and every row above it, so a column
	 * reached by any route still has its whole ancestry open.
	 *
	 * @param {Element} nav    The mega-menu navigation block.
	 * @param {Element} target The event target.
	 */
	function selectFrom( nav, target ) {
		var item = target.closest ? target.closest( ITEM ) : null;
		var path = [];

		while ( item && nav.contains( item ) ) {
			path.unshift( item );
			item = item.parentElement.closest( ITEM );
		}

		path.forEach( select );
	}

	/**
	 * Default state: only the first parent's column open, as the CSS pre-open does.
	 *
	 * @param {Element} nav The mega-menu navigation block.
	 */
	function reset( nav ) {
		var first = nav.querySelector( '.wp-block-navigation__container > ' + ITEM );

		Array.prototype.forEach.call( nav.querySelectorAll( '.' + OPEN ), function ( open ) {
			open.classList.remove( OPEN );
		} );

		if ( first ) {
			select( first );
		}
	}

	/**
	 * Wire one mega-menu nav.
	 *
	 * @param {Element} nav The mega-menu navigation block.
	 */
	function watch( nav ) {
		var menu = nav.closest( '.wp-block-ollie-mega-menu' );
		var toggle = menu ? menu.querySelector( TOGGLE ) : null;

		reset( nav );
		nav.classList.add( READY );

		nav.addEventListener( 'pointerover', function ( event ) {
			selectFrom( nav, event.target );
		} );

		nav.addEventListener( 'focusin', function ( event ) {
			selectFrom( nav, event.target );
		} );

		if ( ! toggle || 'undefined' === typeof window.MutationObserver ) {
			return;
		}

		new window.MutationObserver( function () {
			if ( 'true' !== toggle.getAttribute( 'aria-expanded' ) ) {
				reset( nav );
			}
		} ).observe( toggle, { attributes: true, attributeFilter: [ 'aria-expanded' ] } );
	}

	function init() {
		Array.prototype.forEach.call( document.querySelectorAll( NAV ), watch );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
} )();
