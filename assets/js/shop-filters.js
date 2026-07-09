/**
 * KWV shop filters — collapsible sections.
 *
 * Turns each WooCommerce Product Filter section inside `.kwv-shop-filters`
 * (Brands, Price Range, Type, Pack Size, Special Offers) into a disclosure:
 * folded up by default, with a +/- affordance on its heading that expands the
 * section when activated. Presentation (the grid row-collapse, the +/- glyph
 * and the neutral-700 divider) lives in assets/styles/woocommerce.css; this
 * script only toggles state classes and wires accessibility/interaction.
 *
 * The Product Filters block is an Interactivity API region
 * (`data-wp-router-region`): applying a filter re-renders the sidebar and wipes
 * anything we inject. So:
 *   - Interaction is delegated on the document and matches the section's stable
 *     `data-block-name` structure, never a class we add — it keeps working even
 *     if a re-render strips our attributes.
 *   - A MutationObserver re-applies the enhancement after each re-render.
 *   - Open/closed state is remembered per section (keyed by `data-wp-key`, else
 *     heading text) so a section the shopper opened stays open across renders.
 *
 * Progressive enhancement: the container only gets `kwv-filters-collapsible`
 * (which the CSS gates the fold behaviour on) once this runs, so with no JS the
 * sections stay open and fully accessible.
 *
 * @package kwv
 */
( function () {
	'use strict';

	var CONTAINER = '.kwv-shop-filters';
	var READY_CLASS = 'kwv-filters-collapsible';
	var COLLAPSED = 'kwv-filter-collapsed';

	// A collapsible section is a product-filter-* block (price, rating,
	// attribute, taxonomy, status) — the `product-filter-` prefix excludes the
	// `product-filters` container itself, and the direct-heading check below
	// excludes the nested list/slider/chips blocks and the headingless
	// active-filters block.
	var SECTION = '[data-block-name^="woocommerce/product-filter-"]';

	// Remembered open sections, keyed by stable identity. Default = collapsed.
	var openState = {};

	function sectionHeading( section ) {
		var child = section.firstElementChild;
		for ( ; child; child = child.nextElementSibling ) {
			if ( child.classList && child.classList.contains( 'wp-block-heading' ) ) {
				return child;
			}
		}
		return null;
	}

	function sectionKey( section, heading ) {
		return section.getAttribute( 'data-wp-key' ) ||
			( heading.textContent || '' ).trim();
	}

	function applyState( section, heading, key ) {
		var isOpen = !! openState[ key ];
		section.classList.toggle( COLLAPSED, ! isOpen );
		heading.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
	}

	function enhanceSection( section ) {
		var heading = sectionHeading( section );
		if ( ! heading ) {
			return;
		}

		heading.setAttribute( 'role', 'button' );
		if ( ! heading.hasAttribute( 'tabindex' ) ) {
			heading.setAttribute( 'tabindex', '0' );
		}

		applyState( section, heading, sectionKey( section, heading ) );
	}

	function enhanceAll( root ) {
		var sections = root.querySelectorAll( SECTION );
		Array.prototype.forEach.call( sections, enhanceSection );
	}

	function toggleFromHeading( heading ) {
		var section = heading.closest( SECTION );
		// Only the section's own heading toggles — not a nested block's, and not
		// the container's "Filters" title (the container fails the SECTION prefix).
		if ( ! section || sectionHeading( section ) !== heading ) {
			return;
		}
		var key = sectionKey( section, heading );
		openState[ key ] = ! openState[ key ];
		applyState( section, heading, key );
	}

	function headingFromEvent( event ) {
		if ( ! event.target.closest ) {
			return null;
		}
		var heading = event.target.closest( CONTAINER + ' .wp-block-heading' );
		return heading;
	}

	function init() {
		var containers = document.querySelectorAll( CONTAINER );
		if ( ! containers.length ) {
			return;
		}

		Array.prototype.forEach.call( containers, function ( container ) {
			container.classList.add( READY_CLASS );
			enhanceAll( container );

			// Re-apply after the Interactivity Router replaces region nodes.
			// We only mutate attributes/classes (not childList), so this never
			// re-triggers itself.
			var observer = new MutationObserver( function () {
				enhanceAll( container );
			} );
			observer.observe( container, { childList: true, subtree: true } );
		} );

		// Delegated activation survives re-renders (no per-heading binding).
		document.addEventListener( 'click', function ( event ) {
			var heading = headingFromEvent( event );
			if ( heading ) {
				toggleFromHeading( heading );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Enter' !== event.key && ' ' !== event.key && 'Spacebar' !== event.key ) {
				return;
			}
			var heading = headingFromEvent( event );
			if ( heading ) {
				event.preventDefault();
				toggleFromHeading( heading );
			}
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
