/**
 * assets/js/payflex-relocate.js — move the Payflex widget below the description.
 *
 * The Payflex plugin isn't installed locally, so its container is reproduced
 * by hand (a div with the class the plugin prints) inside the real Add to Cart
 * form shape; the description is the captured block.
 */
import { describe, it, expect, vi } from 'vitest';
import {
	fixture,
	flushObservers,
	loadScript,
	render,
} from '../helpers/load.js';

const SCRIPT = 'assets/js/payflex-relocate.js';
const WIDGET =
	'<div class="payflexCalculatorWidgetContainer"><p>Pay in 4 interest-free instalments</p></div>';
const FORM = `<form class="cart">${ WIDGET }<button type="submit" class="single_add_to_cart_button">Add to cart</button></form>`;

const widget = () =>
	document.querySelector( '.payflexCalculatorWidgetContainer' );

describe( 'payflex-relocate', () => {
	it( 'moves the widget straight after the product description', async () => {
		render( FORM + fixture( 'product-description.html' ) );

		await loadScript( SCRIPT );

		expect( widget().previousElementSibling ).toBe(
			document.querySelector(
				'.wp-block-woocommerce-product-description'
			)
		);
		expect( widget().classList.contains( 'kwv-payflex-moved' ) ).toBe(
			true
		);
	} );

	it( 'waits for a widget that arrives after hydration, then stops watching', async () => {
		vi.useFakeTimers( { toFake: [ 'setTimeout' ] } );
		render(
			'<form class="cart"></form>' + fixture( 'product-description.html' )
		);
		const disconnect = vi.spyOn( MutationObserver.prototype, 'disconnect' );
		await loadScript( SCRIPT );

		document
			.querySelector( 'form' )
			.insertAdjacentHTML( 'afterbegin', WIDGET );
		await vi.waitFor( () =>
			expect( widget().classList.contains( 'kwv-payflex-moved' ) ).toBe(
				true
			)
		);

		expect( disconnect ).toHaveBeenCalledTimes( 1 );
		vi.useRealTimers();
	} );

	it( 'keeps watching through unrelated mutations', async () => {
		render( FORM );
		await loadScript( SCRIPT );

		document.body.appendChild( document.createElement( 'span' ) );
		await flushObservers();
		expect( widget().classList.contains( 'kwv-payflex-moved' ) ).toBe(
			false
		);

		document.body.insertAdjacentHTML(
			'beforeend',
			fixture( 'product-description.html' )
		);
		await flushObservers();
		expect( widget().classList.contains( 'kwv-payflex-moved' ) ).toBe(
			true
		);
	} );

	it( 'gives up after five seconds when Payflex never appears', async () => {
		vi.useFakeTimers( { toFake: [ 'setTimeout' ] } );
		render( fixture( 'product-description.html' ) );
		const disconnect = vi.spyOn( MutationObserver.prototype, 'disconnect' );
		await loadScript( SCRIPT );

		vi.advanceTimersByTime( 4999 );
		expect( disconnect ).not.toHaveBeenCalled();
		vi.advanceTimersByTime( 1 );
		expect( disconnect ).toHaveBeenCalledTimes( 1 );
		vi.useRealTimers();
	} );

	it( 'does not move a widget twice', async () => {
		render(
			FORM + fixture( 'product-description.html' ) + '<p id="after"></p>'
		);
		widget().classList.add( 'kwv-payflex-moved' );

		await loadScript( SCRIPT );

		expect( widget().parentElement.tagName ).toBe( 'FORM' );
	} );
} );
