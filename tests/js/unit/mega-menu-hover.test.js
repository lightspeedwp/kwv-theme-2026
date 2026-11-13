/**
 * assets/js/mega-menu-hover.js — sticky hover for the shop mega menu columns.
 */
import { describe, it, expect, vi } from 'vitest';
import userEvent from '@testing-library/user-event';
import {
	deferReady,
	fixture,
	flushObservers,
	loadScript,
	render,
} from '../helpers/load.js';

const SCRIPT = 'assets/js/mega-menu-hover.js';

async function setup() {
	render( `<ul>${ fixture( 'mega-menu-nav.html' ) }</ul>` );
	await loadScript( SCRIPT );
	const nav = document.querySelector( '.is-style-mega-menu-nav--8' ); // the Shop menu
	const top = Array.from( nav.querySelectorAll( ':scope > ul > li' ) );
	return {
		nav,
		top,
		toggle: nav
			.closest( '.wp-block-ollie-mega-menu' )
			.querySelector( '.wp-block-ollie-mega-menu__toggle' ),
	};
}

const open = ( el ) => el.classList.contains( 'is-kwv-open' );
const openLabels = ( nav ) =>
	Array.from( nav.querySelectorAll( '.is-kwv-open' ), ( li ) =>
		li.querySelector( 'a' ).textContent.trim()
	);

describe( 'mega-menu-hover', () => {
	it( 'marks the nav ready and pre-opens the first parent only', async () => {
		const { nav, top } = await setup();

		expect( nav.classList.contains( 'has-hover-intent' ) ).toBe( true );
		expect( open( top[ 0 ] ) ).toBe( true );
		expect( openLabels( nav ) ).toHaveLength( 1 );
		// Both mega menus in the header (About, Shop) use the native-nav style.
		expect( document.querySelectorAll( '.has-hover-intent' ) ).toHaveLength(
			2
		);
	} );

	it( 'keeps a column open until a sibling row is hovered', async () => {
		const user = userEvent.setup();
		const { top } = await setup();
		const red = top[ 0 ].querySelector( ':scope > ul > li.has-child' );
		const redLink = red.querySelector( 'a' );

		await user.hover( redLink );
		expect( open( red ) ).toBe( true );

		// Leaving the row (moving into its column, or anywhere outside) keeps it open.
		await user.unhover( redLink );
		expect( open( red ) ).toBe( true );

		// Hovering a sibling row is the only thing that closes it.
		const sibling = red.nextElementSibling;
		await user.hover( sibling.querySelector( 'a' ) );
		expect( open( red ) ).toBe( false );
		expect( open( sibling ) ).toBe( true );
	} );

	it( 'opens the whole ancestry of a deep row and closes the other parents', async () => {
		const { top } = await setup();
		const deep = top[ 1 ].querySelector( 'li.has-child li a' );

		deep.dispatchEvent( new Event( 'pointerover', { bubbles: true } ) );

		expect( open( top[ 1 ] ) ).toBe( true );
		expect( open( top[ 0 ] ) ).toBe( false );
		expect( top[ 0 ].querySelectorAll( '.is-kwv-open' ) ).toHaveLength( 0 );
		expect( open( deep.closest( 'li.has-child' ) ) ).toBe( true );
	} );

	it( 'a leaf row closes its siblings without opening itself', async () => {
		const { top } = await setup();
		const mixers = top[ 2 ];
		const leaf = mixers.querySelector( ':scope > ul > li:not(.has-child)' );
		const branch = mixers.querySelector( ':scope > ul > li.has-child' );
		branch
			.querySelector( 'a' )
			.dispatchEvent( new Event( 'pointerover', { bubbles: true } ) );
		expect( open( branch ) ).toBe( true );

		leaf.querySelector( 'a' ).dispatchEvent(
			new Event( 'pointerover', { bubbles: true } )
		);

		expect( open( branch ) ).toBe( false );
		expect( open( leaf ) ).toBe( false );
	} );

	it( 'follows keyboard focus the same way', async () => {
		const { top } = await setup();

		top[ 1 ].querySelector( 'a' ).focus();

		expect( open( top[ 1 ] ) ).toBe( true );
		expect( open( top[ 0 ] ) ).toBe( false );
	} );

	it( 'ignores events whose target has no closest() (text nodes)', async () => {
		const { nav, top } = await setup();
		const text = document.createTextNode( 'x' );
		nav.appendChild( text );

		text.dispatchEvent( new Event( 'pointerover', { bubbles: true } ) );

		expect( open( top[ 0 ] ) ).toBe( true );
	} );

	it( 'resets to the default state when the panel closes', async () => {
		const { nav, top, toggle } = await setup();
		top[ 1 ]
			.querySelector( 'a' )
			.dispatchEvent( new Event( 'pointerover', { bubbles: true } ) );

		toggle.setAttribute( 'aria-expanded', 'true' );
		await flushObservers();
		expect( open( top[ 1 ] ) ).toBe( true );

		toggle.setAttribute( 'aria-expanded', 'false' );
		await flushObservers();
		expect( openLabels( nav ) ).toHaveLength( 1 );
		expect( open( top[ 0 ] ) ).toBe( true );
	} );

	it( 'still works (without panel reset) when the menu has no toggle', async () => {
		render(
			`<div class="kwv-mega-menu-nav-wrap"><nav class="wp-block-navigation is-style-mega-menu-nav"><ul class="wp-block-navigation__container"><li class="wp-block-navigation-item has-child"><a href="#">A</a><ul><li class="wp-block-navigation-item"><a href="#">A1</a></li></ul></li></ul></nav></div>`
		);

		await loadScript( SCRIPT );

		expect(
			document
				.querySelector( 'li.has-child' )
				.classList.contains( 'is-kwv-open' )
		).toBe( true );
	} );

	it( 'skips the panel reset when MutationObserver is unavailable', async () => {
		vi.stubGlobal( 'MutationObserver', undefined );
		const { nav } = await setup();

		expect( nav.classList.contains( 'has-hover-intent' ) ).toBe( true );
	} );

	it( 'handles an empty nav', async () => {
		render(
			'<div class="kwv-mega-menu-nav-wrap"><nav class="wp-block-navigation is-style-mega-menu-nav"></nav></div>'
		);

		await loadScript( SCRIPT );

		expect(
			document
				.querySelector( 'nav' )
				.classList.contains( 'has-hover-intent' )
		).toBe( true );
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( `<ul>${ fixture( 'mega-menu-nav.html' ) }</ul>` );
		const ready = deferReady();
		await loadScript( SCRIPT );
		expect( document.querySelector( '.has-hover-intent' ) ).toBeNull();

		ready();
		expect( document.querySelector( '.has-hover-intent' ) ).not.toBeNull();
	} );
} );
