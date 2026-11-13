/**
 * assets/js/aws-search.js — header search fold-out.
 */
import { describe, it, expect, vi } from 'vitest';
import { screen } from '@testing-library/dom';
import userEvent from '@testing-library/user-event';
import { deferReady, fixture, loadScript, render } from '../helpers/load.js';

const SCRIPT = 'assets/js/aws-search.js';

async function setup() {
	render(
		`<header>${ fixture( 'aws-search.html' ) }</header><main><a href="#x">Elsewhere</a></main>`
	);
	await loadScript( SCRIPT );
	return {
		container: document.querySelector( '.aws-container' ),
		field: document.querySelector( '.aws-search-field' ),
		toggle: document.querySelector( '.kwv-search-toggle' ),
		close: document.querySelector( '.kwv-search-close' ),
	};
}

describe( 'aws-search', () => {
	it( 'injects a toggle before the form and a close button inside it', async () => {
		const { container, toggle, close } = await setup();

		expect( toggle.nextElementSibling ).toBe(
			container.querySelector( '.aws-search-form' )
		);
		expect( close.parentElement ).toBe(
			container.querySelector( '.aws-search-form' )
		);
		expect( toggle.type ).toBe( 'button' );
		expect( toggle.getAttribute( 'aria-expanded' ) ).toBe( 'false' );
		expect( screen.getByRole( 'button', { name: 'Search' } ) ).toBe(
			toggle
		);
		expect( screen.getByRole( 'button', { name: 'Close search' } ) ).toBe(
			close
		);
	} );

	it( 'uses localised labels when kwvSearchL10n is present', async () => {
		vi.stubGlobal( 'kwvSearchL10n', { search: 'Soek', close: 'Maak toe' } );
		await setup();

		expect( screen.getByRole( 'button', { name: 'Soek' } ) ).toBeTruthy();
		expect(
			screen.getByRole( 'button', { name: 'Maak toe' } )
		).toBeTruthy();
	} );

	it( 'opens on toggle click and focuses the field', async () => {
		const user = userEvent.setup();
		const { container, toggle, field } = await setup();

		await user.click( toggle );

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			true
		);
		expect( toggle.getAttribute( 'aria-expanded' ) ).toBe( 'true' );
		expect( document.activeElement ).toBe( field );
	} );

	it( 'closes with Escape, clears the query, notifies the plugin and returns focus', async () => {
		const user = userEvent.setup();
		const { container, toggle, field } = await setup();
		await user.click( toggle );
		await user.type( field, 'pinotage' );
		const input = vi.fn();
		field.addEventListener( 'input', input );
		await user.keyboard( '{Escape}' );

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			false
		);
		expect( toggle.getAttribute( 'aria-expanded' ) ).toBe( 'false' );
		expect( field.value ).toBe( '' );
		expect( input ).toHaveBeenCalledTimes( 1 );
		expect( document.activeElement ).toBe( toggle );
	} );

	it( 'accepts the legacy "Esc" key name', async () => {
		const { container, toggle } = await setup();
		toggle.click();

		container.dispatchEvent(
			new KeyboardEvent( 'keydown', { key: 'Esc', bubbles: true } )
		);

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			false
		);
	} );

	it( 'ignores other keys', async () => {
		const { container, toggle } = await setup();
		toggle.click();

		container.dispatchEvent(
			new KeyboardEvent( 'keydown', { key: 'a', bubbles: true } )
		);

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			true
		);
	} );

	it( 'closes from the close button and returns focus to the toggle', async () => {
		const user = userEvent.setup();
		const { container, toggle, close } = await setup();

		await user.click( toggle );
		await user.click( close );

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			false
		);
		expect( document.activeElement ).toBe( toggle );
	} );

	it( 'leaves an empty query alone when closing', async () => {
		const { toggle, close, field } = await setup();
		const input = vi.fn();
		field.addEventListener( 'input', input );

		toggle.click();
		close.click();

		expect( input ).not.toHaveBeenCalled();
	} );

	it( 'does nothing when closing an already-closed search', async () => {
		const { container, close, toggle } = await setup();
		const focus = vi.spyOn( toggle, 'focus' );

		close.click();

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			false
		);
		expect( focus ).not.toHaveBeenCalled();
	} );

	it( 'closes on a click outside without moving focus', async () => {
		const { container, toggle } = await setup();
		toggle.click();
		const focus = vi.spyOn( toggle, 'focus' );

		document.querySelector( 'main a' ).click();

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			false
		);
		expect( focus ).not.toHaveBeenCalled();
	} );

	it( 'stays open for clicks inside the container or the results dropdown', async () => {
		const { container, toggle, field } = await setup();
		const results = document.createElement( 'div' );
		results.className = 'aws-search-result';
		results.innerHTML = '<a href="#p">Product</a>';
		document.body.appendChild( results );
		toggle.click();

		field.click();
		results.querySelector( 'a' ).click();

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			true
		);
	} );

	it( 'ignores outside clicks while closed', async () => {
		const { container } = await setup();

		document.querySelector( 'main a' ).click();

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			false
		);
	} );

	it( 'tolerates click targets without closest() (e.g. the document)', async () => {
		const { container, toggle } = await setup();
		toggle.click();

		document.dispatchEvent( new MouseEvent( 'click', { bubbles: true } ) );

		expect( container.classList.contains( 'kwv-search-open' ) ).toBe(
			false
		);
	} );

	it( 'skips a container that has no form or no field', async () => {
		render(
			'<div class="aws-container is-style-kwv-header-search"><p>No form</p></div>' +
				'<div class="aws-container is-style-kwv-header-search"><form class="aws-search-form"></form></div>'
		);
		await loadScript( SCRIPT );

		expect( document.querySelector( '.kwv-search-toggle' ) ).toBeNull();
	} );

	it( 'ignores AWS containers without the KWV header style', async () => {
		render(
			fixture( 'aws-search.html' ).replace(
				'is-style-kwv-header-search',
				''
			)
		);
		await loadScript( SCRIPT );

		expect( document.querySelector( '.kwv-search-toggle' ) ).toBeNull();
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( fixture( 'aws-search.html' ) );
		const ready = deferReady();
		await loadScript( SCRIPT );

		expect( document.querySelector( '.kwv-search-toggle' ) ).toBeNull();
		ready();
		expect( document.querySelector( '.kwv-search-toggle' ) ).not.toBeNull();
	} );
} );
