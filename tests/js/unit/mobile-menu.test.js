/**
 * assets/js/mobile-menu.js — collapsible submenus in the off-canvas drawer.
 */
import { describe, it, expect } from 'vitest';
import userEvent from '@testing-library/user-event';
import {
	deferReady,
	fixture,
	flushObservers,
	loadScript,
	render,
} from '../helpers/load.js';

const SCRIPT = 'assets/js/mobile-menu.js';
const DRAWER = '.wp-block-navigation__mobile-menu-content';

async function setup() {
	render( fixture( 'mobile-menu.html' ) );
	await loadScript( SCRIPT );
	const drawer = document.querySelector( DRAWER );
	return {
		drawer,
		overlay: document.querySelector(
			'.wp-block-navigation__responsive-container'
		),
		toggles: () =>
			Array.from(
				drawer.querySelectorAll(
					'button.wp-block-navigation-submenu__toggle'
				)
			),
		headerToggles: () =>
			Array.from(
				document.querySelectorAll(
					'.wp-block-navigation__responsive-container-content > ul button.wp-block-navigation-submenu__toggle'
				)
			),
	};
}

describe( 'mobile-menu', () => {
	it( 'replaces each drawer toggle with a directive-free, collapsed button', async () => {
		const { drawer, toggles } = await setup();

		expect( toggles() ).toHaveLength( 3 );
		toggles().forEach( ( toggle ) => {
			expect(
				Array.from( toggle.attributes ).some( ( a ) =>
					a.name.startsWith( 'data-wp-' )
				)
			).toBe( false );
			expect( toggle.getAttribute( 'aria-expanded' ) ).toBe( 'false' );
			expect( toggle.type ).toBe( 'button' );
			const submenu = document.getElementById(
				toggle.getAttribute( 'aria-controls' )
			);
			expect( submenu.parentElement ).toBe( toggle.parentElement );
			expect( submenu.id ).toMatch( /^kwv-mobile-submenu-\d+$/ );
		} );
		expect(
			drawer.querySelectorAll( 'li.kwv-mobile-submenu' )
		).toHaveLength( 3 );
	} );

	it( 'leaves the header nav’s own (hidden) copy of the menu alone', async () => {
		const { headerToggles } = await setup();

		headerToggles().forEach( ( toggle ) => {
			expect( toggle.hasAttribute( 'data-wp-on--click' ) ).toBe( true );
			expect(
				toggle
					.closest( 'li' )
					.classList.contains( 'kwv-mobile-submenu' )
			).toBe( false );
		} );
	} );

	it( 'toggles aria-expanded on click and keyboard activation', async () => {
		const user = userEvent.setup();
		const { toggles } = await setup();
		const [ first, second ] = toggles();

		await user.click( first );
		expect( first.getAttribute( 'aria-expanded' ) ).toBe( 'true' );
		expect( second.getAttribute( 'aria-expanded' ) ).toBe( 'false' );

		second.focus();
		await user.keyboard( '{Enter}' );
		expect( second.getAttribute( 'aria-expanded' ) ).toBe( 'true' );

		await user.click( first );
		expect( first.getAttribute( 'aria-expanded' ) ).toBe( 'false' );
	} );

	it( 'keeps an existing submenu id', async () => {
		render( fixture( 'mobile-menu.html' ) );
		document.querySelector(
			`${ DRAWER } .wp-block-navigation__submenu-container`
		).id = 'kept';

		await loadScript( SCRIPT );

		expect(
			document.querySelector( `${ DRAWER } button[aria-controls="kept"]` )
		).not.toBeNull();
	} );

	it( 'collapses every submenu when the drawer closes', async () => {
		const { overlay, toggles } = await setup();
		overlay.classList.add( 'is-menu-open' );
		await flushObservers();
		toggles().forEach( ( t ) => t.click() );
		expect(
			toggles().every(
				( t ) => t.getAttribute( 'aria-expanded' ) === 'true'
			)
		).toBe( true );

		overlay.classList.add( 'has-modal-open' );
		await flushObservers();
		expect( toggles()[ 0 ].getAttribute( 'aria-expanded' ) ).toBe( 'true' );

		overlay.classList.remove( 'is-menu-open' );
		await flushObservers();
		expect(
			toggles().every(
				( t ) => t.getAttribute( 'aria-expanded' ) === 'false'
			)
		).toBe( true );
	} );

	it( 're-enhances toggles that a re-render puts back', async () => {
		const { drawer, toggles } = await setup();
		const item = toggles()[ 0 ].closest( 'li' );
		item.classList.remove( 'kwv-mobile-submenu' );
		const fresh = document.createElement( 'button' );
		fresh.className = 'wp-block-navigation-submenu__toggle';
		fresh.setAttribute( 'data-wp-on--click', 'actions.toggleMenuOnClick' );
		item.replaceChild( fresh, toggles()[ 0 ] );

		await flushObservers();

		const replaced = item.querySelector( ':scope > button' );
		expect( replaced ).not.toBe( fresh );
		expect( replaced.hasAttribute( 'data-wp-on--click' ) ).toBe( false );
		expect( drawer.contains( replaced ) ).toBe( true );
	} );

	it( 'skips items with no toggle and toggles with no submenu', async () => {
		render( `<div class="wp-block-navigation__mobile-menu-content"><ul>
			<li class="wp-block-navigation-item has-child"><a href="#">No toggle</a></li>
			<li class="wp-block-navigation-item has-child"><button class="wp-block-navigation-submenu__toggle">No submenu</button></li>
		</ul></div>` );

		await loadScript( SCRIPT );

		const [ noToggle, noSubmenu ] = document.querySelectorAll( 'li' );
		expect( noToggle.classList.contains( 'kwv-mobile-submenu' ) ).toBe(
			false
		);
		expect( noSubmenu.classList.contains( 'kwv-mobile-submenu' ) ).toBe(
			true
		);
		expect(
			noSubmenu.querySelector( 'button' ).hasAttribute( 'aria-controls' )
		).toBe( false );
	} );

	it( 'ignores clicks that are not on a drawer toggle', async () => {
		const { toggles } = await setup();

		document.querySelector( `${ DRAWER } a` ).click();
		document.dispatchEvent( new MouseEvent( 'click', { bubbles: true } ) );

		expect(
			toggles().every(
				( t ) => t.getAttribute( 'aria-expanded' ) === 'false'
			)
		).toBe( true );
	} );

	it( 'ignores a toggle that belongs to a different item than the one it sits in', async () => {
		render( `<div class="wp-block-navigation__mobile-menu-content"><ul>
			<li class="wp-block-navigation-item has-child"><span><button class="wp-block-navigation-submenu__toggle" aria-expanded="false">Nested</button></span></li>
		</ul></div>` );
		await loadScript( SCRIPT );
		const button = document.querySelector( 'button' );

		button.click();

		expect( button.getAttribute( 'aria-expanded' ) ).toBe( 'false' );
	} );

	it( 'works when the drawer is not inside an overlay container', async () => {
		render(
			`<div class="wp-block-navigation__mobile-menu-content"><ul><li class="wp-block-navigation-item has-child"><button class="wp-block-navigation-submenu__toggle"></button><ul class="wp-block-navigation__submenu-container"></ul></li></ul></div>`
		);

		await loadScript( SCRIPT );

		expect(
			document.querySelector( 'button' ).getAttribute( 'aria-expanded' )
		).toBe( 'false' );
	} );

	it( 'does nothing without a drawer', async () => {
		render(
			'<nav><button class="wp-block-navigation-submenu__toggle" data-wp-on--click="x"></button></nav>'
		);

		await loadScript( SCRIPT );

		expect(
			document
				.querySelector( 'button' )
				.hasAttribute( 'data-wp-on--click' )
		).toBe( true );
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( fixture( 'mobile-menu.html' ) );
		const ready = deferReady();
		await loadScript( SCRIPT );
		expect( document.querySelector( '.kwv-mobile-submenu' ) ).toBeNull();

		ready();
		expect(
			document.querySelector( '.kwv-mobile-submenu' )
		).not.toBeNull();
	} );
} );
