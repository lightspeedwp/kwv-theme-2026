/**
 * KWV mobile menu — collapsible submenus in the off-canvas drawer.
 *
 * The drawer is Ollie Menu Designer's `mobileMenuSlug` injection: it renders the
 * `mobile-menu` template part *inside* the header navigation's own overlay
 * (`.wp-block-navigation__mobile-menu-content`). That nesting puts the drawer's
 * own navigation block inside the overlay's Interactivity context — and core
 * binds every submenu toggle as
 *
 *     data-wp-bind--aria-expanded="state.isSubmenuOpen"
 *
 * where `isSubmenuOpen` (see wp-includes/js/dist/script-modules/block-library/
 * navigation/view.js) returns true as soon as `context.overlayOpenedBy` holds
 * any truthy value. Core does that on purpose: in its own overlay every submenu
 * is meant to be permanently expanded. Inherited into the drawer it means each
 * submenu is expanded the moment the drawer opens, and its toggle is inert —
 * a click sets the context, the getter still reads the overlay, nothing moves.
 *
 * So this module takes the drawer's toggles off the Interactivity runtime: each
 * one is swapped for a directive-free clone, and `aria-expanded` is driven from
 * here instead. Nothing else in the drawer is touched, and the header nav's own
 * (hidden) copy of the menu — and the desktop nav — are left entirely alone.
 *
 * Visibility itself is already CSS: assets/styles/core-navigation.css closes
 * `.has-child .wp-block-navigation__submenu-container` by default and opens it
 * for `.wp-block-navigation-submenu__toggle[aria-expanded="true"] ~ …`, so this
 * script only has to keep that attribute honest.
 *
 * Progressive enhancement: with no JS the drawer keeps core's behaviour (all
 * submenus expanded), which is navigable — just long.
 *
 * @package kwv
 */
( function () {
	'use strict';

	var DRAWER = '.wp-block-navigation__mobile-menu-content';
	var ITEM = 'li.wp-block-navigation-item.has-child';
	var TOGGLE = 'button.wp-block-navigation-submenu__toggle';
	var READY = 'kwv-mobile-submenu';
	var CONTAINER = '.wp-block-navigation__responsive-container';

	var submenuId = 0;

	/**
	 * The submenu toggle belonging to this item (never a descendant's).
	 *
	 * @param {HTMLElement} item The `li.has-child` element.
	 * @return {HTMLButtonElement|null} The toggle, or null if there is none.
	 */
	function ownToggle( item ) {
		var child = item.firstElementChild;
		for ( ; child; child = child.nextElementSibling ) {
			if ( child.matches && child.matches( TOGGLE ) ) {
				return child;
			}
		}
		return null;
	}

	/**
	 * The submenu list belonging to this item.
	 *
	 * @param {HTMLElement} item The `li.has-child` element.
	 * @return {HTMLElement|null} The submenu `ul`, or null if there is none.
	 */
	function ownSubmenu( item ) {
		var child = item.firstElementChild;
		for ( ; child; child = child.nextElementSibling ) {
			if (
				child.classList &&
				child.classList.contains( 'wp-block-navigation__submenu-container' )
			) {
				return child;
			}
		}
		return null;
	}

	/**
	 * Replace a toggle with a copy carrying none of core's `data-wp-*` directives.
	 *
	 * Swapping the node (rather than stripping attributes) makes the takeover
	 * independent of whether the Interactivity runtime has already hydrated:
	 * anything it bound stays bound to the detached original.
	 *
	 * @param {HTMLElement}       item   The `li.has-child` element.
	 * @param {HTMLButtonElement} toggle The toggle rendered by core.
	 * @return {HTMLButtonElement} The replacement toggle.
	 */
	function detachToggle( item, toggle ) {
		var replacement = toggle.cloneNode( true );
		var attributes = Array.prototype.slice.call( replacement.attributes );

		attributes.forEach( function ( attribute ) {
			if ( 0 === attribute.name.indexOf( 'data-wp-' ) ) {
				replacement.removeAttribute( attribute.name );
			}
		} );

		replacement.type = 'button';
		item.replaceChild( replacement, toggle );

		return replacement;
	}

	/**
	 * Open or close one item's submenu.
	 *
	 * @param {HTMLElement} item   The `li.has-child` element.
	 * @param {boolean}     isOpen Whether the submenu should be open.
	 */
	function setOpen( item, isOpen ) {
		var toggle = ownToggle( item );
		if ( toggle ) {
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		}
	}

	/**
	 * Take over one submenu item, leaving it closed.
	 *
	 * @param {HTMLElement} item The `li.has-child` element.
	 */
	function enhanceItem( item ) {
		if ( item.classList.contains( READY ) ) {
			return;
		}

		var toggle = ownToggle( item );
		if ( ! toggle ) {
			return;
		}

		var replacement = detachToggle( item, toggle );
		var submenu = ownSubmenu( item );

		if ( submenu ) {
			if ( ! submenu.id ) {
				submenuId += 1;
				submenu.id = 'kwv-mobile-submenu-' + submenuId;
			}
			replacement.setAttribute( 'aria-controls', submenu.id );
		}

		item.classList.add( READY );
		setOpen( item, false );
	}

	/**
	 * Take over every submenu item inside a drawer.
	 *
	 * @param {HTMLElement} drawer The drawer content element.
	 */
	function enhanceAll( drawer ) {
		Array.prototype.forEach.call( drawer.querySelectorAll( ITEM ), enhanceItem );
	}

	/**
	 * Collapse every submenu in a drawer — used when the drawer itself closes,
	 * so the next open starts from a clean, short list.
	 *
	 * @param {HTMLElement} drawer The drawer content element.
	 */
	function collapseAll( drawer ) {
		Array.prototype.forEach.call(
			drawer.querySelectorAll( ITEM + '.' + READY ),
			function ( item ) {
				setOpen( item, false );
			}
		);
	}

	/**
	 * Watch the overlay this drawer lives in and reset on close.
	 *
	 * @param {HTMLElement} drawer The drawer content element.
	 */
	function watchOverlay( drawer ) {
		var overlay = drawer.closest( CONTAINER );
		if ( ! overlay ) {
			return;
		}

		var wasOpen = overlay.classList.contains( 'is-menu-open' );

		new MutationObserver( function () {
			var isOpen = overlay.classList.contains( 'is-menu-open' );
			if ( wasOpen && ! isOpen ) {
				collapseAll( drawer );
			}
			wasOpen = isOpen;
		} ).observe( overlay, { attributes: true, attributeFilter: [ 'class' ] } );
	}

	function init() {
		var drawers = document.querySelectorAll( DRAWER );
		if ( ! drawers.length ) {
			return;
		}

		Array.prototype.forEach.call( drawers, function ( drawer ) {
			enhanceAll( drawer );
			watchOverlay( drawer );

			// Re-enhance if anything replaces nodes inside the drawer. Our own
			// swap re-triggers this once; the READY guard makes that a no-op.
			new MutationObserver( function () {
				enhanceAll( drawer );
			} ).observe( drawer, { childList: true, subtree: true } );
		} );

		// Delegated so it survives any re-render, and so it can never match the
		// header nav's own hidden copy of the menu (outside DRAWER).
		document.addEventListener( 'click', function ( event ) {
			if ( ! event.target.closest ) {
				return;
			}

			var toggle = event.target.closest( DRAWER + ' ' + TOGGLE );
			if ( ! toggle ) {
				return;
			}

			var item = toggle.closest( ITEM );
			if ( ! item || ownToggle( item ) !== toggle ) {
				return;
			}

			event.preventDefault();
			setOpen( item, 'true' !== toggle.getAttribute( 'aria-expanded' ) );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
