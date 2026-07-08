/**
 * KWV header search fold-out.
 *
 * Turns an Advanced Woo Search block styled with `is-style-kwv-header-search`
 * into a single icon in the header icon cluster that folds out into a search bar
 * over the nav when clicked. Injects an accessible toggle and close button (the
 * plugin renders no buttons by default) and wires open/close via click, Escape
 * and click-outside. All presentation lives in assets/styles/aws-search.css.
 *
 * @package kwv
 */
( function () {
	'use strict';

	var OPEN_CLASS = 'kwv-search-open';
	var l10n = window.kwvSearchL10n || { search: 'Search', close: 'Close search' };

	/**
	 * Build a masked-icon button.
	 *
	 * @param {string} className Button class (drives the mask via CSS).
	 * @param {string} label     Accessible label.
	 * @return {HTMLButtonElement}
	 */
	function makeButton( className, label ) {
		var button = document.createElement( 'button' );
		button.type = 'button';
		button.className = className;
		button.setAttribute( 'aria-label', label );
		return button;
	}

	function setupContainer( container ) {
		var form = container.querySelector( '.aws-search-form' );
		var field = container.querySelector( '.aws-search-field' );

		if ( ! form || ! field ) {
			return;
		}

		var toggle = makeButton( 'kwv-search-toggle', l10n.search );
		toggle.setAttribute( 'aria-expanded', 'false' );

		var close = makeButton( 'kwv-search-close', l10n.close );

		container.insertBefore( toggle, form );
		form.appendChild( close );

		function open() {
			container.classList.add( OPEN_CLASS );
			toggle.setAttribute( 'aria-expanded', 'true' );
			field.focus();
		}

		function closeSearch( returnFocus ) {
			if ( ! container.classList.contains( OPEN_CLASS ) ) {
				return;
			}
			container.classList.remove( OPEN_CLASS );
			toggle.setAttribute( 'aria-expanded', 'false' );

			// Clear the query and let the plugin tear down its results dropdown.
			if ( field.value ) {
				field.value = '';
				field.dispatchEvent( new Event( 'input', { bubbles: true } ) );
				field.dispatchEvent( new Event( 'keyup', { bubbles: true } ) );
			}

			if ( returnFocus ) {
				toggle.focus();
			}
		}

		toggle.addEventListener( 'click', open );
		close.addEventListener( 'click', function () {
			closeSearch( true );
		} );

		// Escape closes and restores focus to the toggle.
		container.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key || 'Esc' === event.key ) {
				closeSearch( true );
			}
		} );

		// Click outside the form and outside the results dropdown closes it.
		document.addEventListener( 'click', function ( event ) {
			if ( ! container.classList.contains( OPEN_CLASS ) ) {
				return;
			}
			if ( container.contains( event.target ) ) {
				return;
			}
			if ( event.target.closest && event.target.closest( '.aws-search-result' ) ) {
				return;
			}
			closeSearch( false );
		} );
	}

	function init() {
		var containers = document.querySelectorAll( '.aws-container.is-style-kwv-header-search' );
		Array.prototype.forEach.call( containers, setupContainer );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
