/**
 * KWV shop mega menu — panel height.
 *
 * The fold-out columns in the native-nav mega menu (`is-style-mega-menu-nav`)
 * are `position:absolute` so they can sit co-planar, all top-aligned, instead of
 * hanging off each parent row. Being out of flow, they can never grow the panel
 * that contains them — which is why the panel used to carry a hard-coded
 * `min-block-size`. That number is a guess about the deepest branch, and it goes
 * stale the moment an editor adds a category: the eighth item in a column simply
 * falls out of the bottom of the white panel.
 *
 * So measure it instead. Every column — the level-1 list and every submenu
 * container at every depth — is anchored to the top of the nav, so the panel
 * only ever needs to be as tall as the tallest one. That height is published on
 * the nav as `--kwv-mega-menu-panel-block-size`, which
 * assets/styles/ollie-mega-menu.css feeds into the nav's `min-block-size`.
 *
 * Measuring works while the menu is shut: Ollie closes the panel with
 * visibility/opacity (see the plugin's mega-menu style-index.css), never
 * `display:none`, so the columns are laid out from first paint.
 *
 * A ResizeObserver keeps the value honest across late web fonts, viewport
 * changes (the row padding is a fluid clamp) and any editor-side DOM changes.
 *
 * Progressive enhancement: with no JS the CSS fallback keeps the old fixed
 * floor, so the panel looks exactly as it does today.
 *
 * @package kwv
 */
( function () {
	'use strict';

	var NAV = '.kwv-mega-menu-nav-wrap .wp-block-navigation.is-style-mega-menu-nav';
	var COLUMN = '.wp-block-navigation__container, .wp-block-navigation__submenu-container';
	var PROP = '--kwv-mega-menu-panel-block-size';

	/**
	 * Natural height of one column: its first row's top to its last row's bottom.
	 *
	 * Not `offsetHeight`. The level-1 list is a flex item of the nav, so it
	 * stretches to whatever height we just published — reading its box back would
	 * feed our own answer into the next measurement and the panel would never
	 * grow past its starting floor. The rows themselves are content-sized, so
	 * their extent is the honest number.
	 *
	 * @param {Element} column A navigation list at any depth.
	 * @return {number} Height in pixels, 0 when the column is empty.
	 */
	function columnHeight( column ) {
		var first = column.firstElementChild;
		var last = column.lastElementChild;
		var styles;

		if ( ! first || ! last ) {
			return 0;
		}

		styles = window.getComputedStyle( column );

		return Math.ceil(
			last.getBoundingClientRect().bottom -
			first.getBoundingClientRect().top +
			parseFloat( styles.paddingTop ) +
			parseFloat( styles.paddingBottom )
		);
	}

	/**
	 * Tallest column inside a mega-menu nav, in CSS pixels.
	 *
	 * Nested submenu containers are themselves absolutely positioned, so a
	 * parent column's own height never includes its children's — every column
	 * measures independently, which is what "tallest" needs.
	 *
	 * @param {Element} nav The mega-menu navigation block.
	 * @return {number} Height in pixels, 0 when nothing is measurable.
	 */
	function tallestColumn( nav ) {
		var columns = nav.querySelectorAll( COLUMN );
		var tallest = 0;
		var height;
		var i;

		for ( i = 0; i < columns.length; i++ ) {
			height = columnHeight( columns[ i ] );
			if ( height > tallest ) {
				tallest = height;
			}
		}

		return tallest;
	}

	/**
	 * Publish the measured height on the nav, if it has actually moved.
	 *
	 * @param {Element} nav The mega-menu navigation block.
	 */
	function measure( nav ) {
		var tallest = tallestColumn( nav );
		var value;

		if ( ! tallest ) {
			return;
		}

		value = tallest + 'px';

		if ( nav.style.getPropertyValue( PROP ) !== value ) {
			nav.style.setProperty( PROP, value );
		}
	}

	/**
	 * Measure one nav and keep watching its columns.
	 *
	 * @param {Element} nav The mega-menu navigation block.
	 */
	function watch( nav ) {
		var scheduled = false;
		var observer;

		function remeasure() {
			if ( scheduled ) {
				return;
			}
			scheduled = true;
			window.requestAnimationFrame( function () {
				scheduled = false;
				measure( nav );
			} );
		}

		measure( nav );

		if ( 'undefined' === typeof window.ResizeObserver ) {
			window.addEventListener( 'resize', remeasure );
			return;
		}

		observer = new window.ResizeObserver( remeasure );
		Array.prototype.forEach.call( nav.querySelectorAll( COLUMN ), function ( column ) {
			observer.observe( column );
		} );
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
