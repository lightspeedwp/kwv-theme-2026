/**
 * Single Product — relocate the Payflex "buy now, pay later" widget.
 *
 * The Payflex plugin injects its calculator widget
 * (.payflexCalculatorWidgetContainer) as the first child *inside* the Add to
 * Cart form, which drops it above the button in the cramped subtitle row. The
 * KWV design places it lower down, beneath the product description. That target
 * lives in a different DOM container, so it can't be reached with CSS `order` —
 * this small enhancer moves the node once, after which `.kwv-payflex-moved` in
 * assets/styles/woocommerce.css shrinks it to ~80%.
 *
 * If the widget never appears (Payflex disabled for the product/region), nothing
 * happens; if the script never runs, the widget simply stays where the plugin
 * put it. Enqueued only on single product pages — see inc/woocommerce.php.
 *
 * @package kwv
 */
( function () {
	'use strict';

	function relocate() {
		var widget = document.querySelector( '.payflexCalculatorWidgetContainer' );
		var description = document.querySelector( '.wp-block-woocommerce-product-description' );

		if ( ! widget || ! description || widget.classList.contains( 'kwv-payflex-moved' ) ) {
			return false;
		}

		description.after( widget );
		widget.classList.add( 'kwv-payflex-moved' );
		return true;
	}

	if ( relocate() ) {
		return;
	}

	// The Add to Cart block hydrates via the Interactivity API, so the widget (or
	// the description) may land after first paint — watch the tree briefly.
	var observer = new MutationObserver( function () {
		if ( relocate() ) {
			observer.disconnect();
		}
	} );

	observer.observe( document.body, { childList: true, subtree: true } );

	// Safety valve: stop observing after 5s regardless of outcome.
	window.setTimeout( function () {
		observer.disconnect();
	}, 5000 );
}() );
