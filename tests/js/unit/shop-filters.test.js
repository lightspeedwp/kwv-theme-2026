/**
 * assets/js/shop-filters.js — collapsible WooCommerce product-filter sections.
 */
import { describe, it, expect } from 'vitest';
import { screen } from '@testing-library/dom';
import userEvent from '@testing-library/user-event';
import {
	deferReady,
	fixture,
	flushObservers,
	loadScript,
	render,
} from '../helpers/load.js';

const SCRIPT = 'assets/js/shop-filters.js';

async function setup() {
	render( fixture( 'shop-filters.html' ) );
	await loadScript( SCRIPT );
	return {
		container: document.querySelector( '.kwv-shop-filters' ),
		heading: ( name ) => screen.getByRole( 'button', { name } ),
		section: ( name ) =>
			screen.getByRole( 'button', { name } ).parentElement,
	};
}

const collapsed = ( section ) =>
	section.classList.contains( 'kwv-filter-collapsed' );

describe( 'shop-filters', () => {
	it( 'turns each section heading into a collapsed disclosure button', async () => {
		const { container, heading, section } = await setup();

		expect(
			container.classList.contains( 'kwv-filters-collapsible' )
		).toBe( true );
		[ 'Brand', 'Price Range', 'Type', 'Volume' ].forEach( ( name ) => {
			expect( heading( name ).getAttribute( 'tabindex' ) ).toBe( '0' );
			expect( heading( name ).getAttribute( 'aria-expanded' ) ).toBe(
				'false'
			);
			expect( collapsed( section( name ) ) ).toBe( true );
		} );
	} );

	it( 'leaves the container’s own "Filters" title alone', async () => {
		await setup();
		const title = screen.getByRole( 'heading', { name: 'Filters' } );

		title.click();

		expect( title.hasAttribute( 'aria-expanded' ) ).toBe( false );
	} );

	it( 'expands and collapses on click', async () => {
		const user = userEvent.setup();
		const { heading, section } = await setup();

		await user.click( heading( 'Brand' ) );
		expect( heading( 'Brand' ).getAttribute( 'aria-expanded' ) ).toBe(
			'true'
		);
		expect( collapsed( section( 'Brand' ) ) ).toBe( false );
		expect( collapsed( section( 'Type' ) ) ).toBe( true );

		await user.click( heading( 'Brand' ) );
		expect( collapsed( section( 'Brand' ) ) ).toBe( true );
	} );

	it( 'toggles with Enter and Space from the keyboard, and ignores other keys', async () => {
		const user = userEvent.setup();
		const { heading } = await setup();
		heading( 'Price Range' ).focus();

		await user.keyboard( '{Enter}' );
		expect( heading( 'Price Range' ).getAttribute( 'aria-expanded' ) ).toBe(
			'true'
		);

		await user.keyboard( ' ' );
		expect( heading( 'Price Range' ).getAttribute( 'aria-expanded' ) ).toBe(
			'false'
		);

		heading( 'Price Range' ).dispatchEvent(
			new KeyboardEvent( 'keydown', { key: 'Spacebar', bubbles: true } )
		);
		expect( heading( 'Price Range' ).getAttribute( 'aria-expanded' ) ).toBe(
			'true'
		);

		await user.keyboard( 'a' );
		expect( heading( 'Price Range' ).getAttribute( 'aria-expanded' ) ).toBe(
			'true'
		);
	} );

	it( 'prevents the default scroll when Space toggles a heading', async () => {
		const { heading } = await setup();
		const event = new KeyboardEvent( 'keydown', {
			key: ' ',
			bubbles: true,
			cancelable: true,
		} );

		heading( 'Type' ).dispatchEvent( event );

		expect( event.defaultPrevented ).toBe( true );
	} );

	it( 'ignores keys on things that are not filter headings', async () => {
		await setup();
		const event = new KeyboardEvent( 'keydown', {
			key: 'Enter',
			bubbles: true,
			cancelable: true,
		} );

		document.body.dispatchEvent( event );
		document.dispatchEvent(
			new KeyboardEvent( 'keydown', { key: 'Enter', bubbles: true } )
		);

		expect( event.defaultPrevented ).toBe( false );
	} );

	it( 'remembers open sections across an Interactivity router re-render', async () => {
		const { container, heading } = await setup();
		heading( 'Volume' ).click();
		const html = container.innerHTML;

		// The router swaps the region: fresh nodes without our attributes.
		container.innerHTML = html
			.replace( /\s(role|tabindex|aria-expanded)="[^"]*"/g, '' )
			.replace( /kwv-filter-collapsed/g, '' );
		await flushObservers();

		expect( heading( 'Volume' ).getAttribute( 'aria-expanded' ) ).toBe(
			'true'
		);
		expect( heading( 'Brand' ).getAttribute( 'aria-expanded' ) ).toBe(
			'false'
		);
	} );

	it( 'keys state by heading text when a section has no data-wp-key, and keeps an authored tabindex', async () => {
		render( `<div class="kwv-shop-filters">
			<div data-block-name="woocommerce/product-filter-status"><h3 class="wp-block-heading" tabindex="-1">  Status </h3></div>
			<div data-block-name="woocommerce/product-filter-rating"><p>No heading</p></div>
		</div>` );
		await loadScript( SCRIPT );
		const heading = document.querySelector( 'h3' );

		heading.click();

		expect( heading.getAttribute( 'tabindex' ) ).toBe( '-1' );
		expect( heading.getAttribute( 'aria-expanded' ) ).toBe( 'true' );
	} );

	it( 'ignores a click on a nested block’s heading', async () => {
		render(
			`<div class="kwv-shop-filters"><div data-block-name="woocommerce/product-filter-price"><h3 class="wp-block-heading">Price</h3><div><h4 class="wp-block-heading">Nested</h4></div></div></div>`
		);
		await loadScript( SCRIPT );

		document.querySelector( 'h4' ).click();

		expect(
			document.querySelector( 'h3' ).getAttribute( 'aria-expanded' )
		).toBe( 'false' );
	} );

	it( 'treats a heading with no text as an empty key', async () => {
		render(
			`<div class="kwv-shop-filters"><div data-block-name="woocommerce/product-filter-price"><h3 class="wp-block-heading"></h3></div></div>`
		);
		await loadScript( SCRIPT );
		const heading = document.querySelector( 'h3' );
		Object.defineProperty( heading, 'textContent', { get: () => null } );

		heading.click();

		expect( heading.getAttribute( 'aria-expanded' ) ).toBe( 'true' );
	} );

	it( 'does nothing on pages without the filter sidebar', async () => {
		render( '<h3 class="wp-block-heading">Elsewhere</h3>' );
		await loadScript( SCRIPT );

		document.querySelector( 'h3' ).click();

		expect( document.querySelector( 'h3' ).hasAttribute( 'role' ) ).toBe(
			false
		);
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( fixture( 'shop-filters.html' ) );
		const ready = deferReady();
		await loadScript( SCRIPT );
		expect(
			document.querySelector( '.kwv-filters-collapsible' )
		).toBeNull();

		ready();
		expect(
			document.querySelector( '.kwv-filters-collapsible' )
		).not.toBeNull();
	} );
} );
