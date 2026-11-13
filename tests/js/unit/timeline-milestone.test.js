/**
 * assets/js/timeline-milestone.js — dismiss the hover panel after a click.
 */
import { describe, it, expect } from 'vitest';
import { fixture, loadScript, render } from '../helpers/load.js';

const SCRIPT = 'assets/js/timeline-milestone.js';
const DISMISSED = 'is-info-dismissed';

async function setup() {
	render(
		fixture( 'timeline-milestone.html' ) +
			'<div class="wp-lightbox-overlay"><button>Close</button></div><a href="#out" id="out">Out</a>'
	);
	await loadScript( SCRIPT );
	const cards = document.querySelectorAll( '.is-style-timeline-milestone' );
	return {
		card: cards[ 0 ],
		other: cards[ 1 ],
		overlay: document.querySelector( '.wp-lightbox-overlay' ),
		out: document.getElementById( 'out' ),
	};
}

function leave( card, relatedTarget = null ) {
	card.dispatchEvent(
		new MouseEvent( 'pointerleave', { bubbles: false, relatedTarget } )
	);
}

describe( 'timeline-milestone', () => {
	it( 'dismisses the info panel when the card is clicked', async () => {
		const { card, other } = await setup();

		card.querySelector( 'img, a, p' ).click();

		expect( card.classList.contains( DISMISSED ) ).toBe( true );
		expect( other.classList.contains( DISMISSED ) ).toBe( false );
	} );

	it( 'ignores clicks outside any card', async () => {
		const { out } = await setup();

		out.click();
		document.dispatchEvent( new MouseEvent( 'click' ) );

		expect( document.querySelector( `.${ DISMISSED }` ) ).toBeNull();
	} );

	it( 're-arms when the pointer genuinely leaves the card, blurring the restored focus', async () => {
		const { card, out } = await setup();
		card.click();
		const focusable = document.createElement( 'button' );
		card.appendChild( focusable );
		focusable.focus();

		leave( card, out );

		expect( card.classList.contains( DISMISSED ) ).toBe( false );
		expect( document.activeElement ).not.toBe( focusable );
	} );

	it( 'leaves focus alone when it is elsewhere', async () => {
		const { card, out } = await setup();
		card.click();
		out.focus();

		leave( card, null );

		expect( card.classList.contains( DISMISSED ) ).toBe( false );
		expect( document.activeElement ).toBe( out );
	} );

	it( 'ignores the pointerleave caused by the lightbox overlay opening', async () => {
		const { card, overlay } = await setup();
		card.click();

		leave( card, overlay.querySelector( 'button' ) );

		expect( card.classList.contains( DISMISSED ) ).toBe( true );
	} );

	it( 'ignores pointerleave from non-cards, undismissed cards and non-elements', async () => {
		const { card, out } = await setup();

		leave( card );
		out.dispatchEvent( new MouseEvent( 'pointerleave' ) );
		document.dispatchEvent( new MouseEvent( 'pointerleave' ) );

		expect( card.classList.contains( DISMISSED ) ).toBe( false );
	} );

	it( 're-arms for keyboard users when focus moves outside the card', async () => {
		const { card, out } = await setup();
		card.click();
		const inside = document.createElement( 'button' );
		card.appendChild( inside );

		inside.focus();
		expect( card.classList.contains( DISMISSED ) ).toBe( true );

		out.focus();
		expect( card.classList.contains( DISMISSED ) ).toBe( false );
	} );

	it( 'keeps the dismissal while focus is inside the lightbox overlay', async () => {
		const { card, overlay } = await setup();
		card.click();

		overlay.querySelector( 'button' ).focus();

		expect( card.classList.contains( DISMISSED ) ).toBe( true );
	} );
} );
