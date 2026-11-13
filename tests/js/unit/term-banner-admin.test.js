/**
 * assets/js/term-banner-admin.js — wp-admin media picker for the term banner.
 *
 * Runs against real jQuery; `wp.media` is a fake frame that records its
 * options and lets the test fire 'select' with a chosen attachment.
 */
import { describe, it, expect, vi, beforeEach } from 'vitest';
import jQuery from 'jquery';
import { loadScript, render } from '../helpers/load.js';

const SCRIPT = 'assets/js/term-banner-admin.js';

/** Markup printed by inc/term-banner.php on the term edit screen. */
const FIELD = `<div class="form-field kwv-banner-field">
	<input type="hidden" class="kwv-banner-input" name="kwv_banner_image_id" value="12">
	<div class="kwv-banner-preview"><img src="/old.jpg" alt=""></div>
	<button type="button" class="button kwv-banner-select">Select image</button>
	<button type="button" class="button kwv-banner-remove">Remove image</button>
</div>`;

let frames;

function fakeMedia( attachment ) {
	return vi.fn( ( options ) => {
		const handlers = {};
		const frame = {
			options,
			on: ( event, fn ) => ( handlers[ event ] = fn ),
			open: vi.fn(),
			state: () => ( {
				get: () => ( {
					first: () => ( { toJSON: () => attachment } ),
				} ),
			} ),
			select: () => handlers.select(),
		};
		frames.push( frame );
		return frame;
	} );
}

beforeEach( () => {
	frames = [];
	vi.stubGlobal( 'jQuery', jQuery );
} );

async function setup( attachment, l10n ) {
	if ( l10n ) {
		vi.stubGlobal( 'kwvTermBanner', l10n );
	}
	vi.stubGlobal( 'wp', { media: fakeMedia( attachment ) } );
	render( FIELD );
	await loadScript( SCRIPT );
	// jQuery delegated handlers live on document; remove them after the test.
	return () => jQuery( document ).off( 'click' );
}

describe( 'term-banner-admin', () => {
	it( 'opens a single-image media frame with the default labels', async () => {
		const off = await setup( { id: 5, url: '/full.jpg' } );

		document.querySelector( '.kwv-banner-select' ).click();

		expect( frames[ 0 ].options ).toEqual( {
			title: 'Select banner image',
			button: { text: 'Use this image' },
			library: { type: 'image' },
			multiple: false,
		} );
		expect( frames[ 0 ].open ).toHaveBeenCalled();
		off();
	} );

	it( 'uses localised labels from kwvTermBanner', async () => {
		const off = await setup(
			{ id: 5, url: '/full.jpg' },
			{ title: 'Kies', button: 'Gebruik' }
		);

		document.querySelector( '.kwv-banner-select' ).click();

		expect( frames[ 0 ].options.title ).toBe( 'Kies' );
		expect( frames[ 0 ].options.button.text ).toBe( 'Gebruik' );
		off();
	} );

	it( 'writes the selection into the field, preferring the medium size', async () => {
		const off = await setup( {
			id: 77,
			url: '/full.jpg',
			sizes: { medium: { url: '/medium.jpg' } },
		} );

		document.querySelector( '.kwv-banner-select' ).click();
		frames[ 0 ].select();

		expect( document.querySelector( '.kwv-banner-input' ).value ).toBe(
			'77'
		);
		const img = document.querySelector( '.kwv-banner-preview img' );
		expect( img.getAttribute( 'src' ) ).toBe( '/medium.jpg' );
		expect( img.getAttribute( 'alt' ) ).toBe( '' );
		expect( img.style.maxWidth ).toBe( '300px' );
		expect(
			document.querySelector( '.kwv-banner-remove' ).style.display
		).not.toBe( 'none' );
		off();
	} );

	it( 'falls back to the full URL when there is no medium size', async () => {
		const off = await setup( {
			id: 8,
			url: '/full.jpg',
			sizes: { thumbnail: { url: '/t.jpg' } },
		} );

		document.querySelector( '.kwv-banner-select' ).click();
		frames[ 0 ].select();

		expect(
			document
				.querySelector( '.kwv-banner-preview img' )
				.getAttribute( 'src' )
		).toBe( '/full.jpg' );
		off();
	} );

	it( 'falls back to the full URL when the attachment has no sizes', async () => {
		const off = await setup( { id: 9, url: '/svg.svg' } );

		document.querySelector( '.kwv-banner-select' ).click();
		frames[ 0 ].select();

		expect(
			document
				.querySelector( '.kwv-banner-preview img' )
				.getAttribute( 'src' )
		).toBe( '/svg.svg' );
		off();
	} );

	it( 'removes the image: clears the ID and preview and hides itself', async () => {
		const off = await setup( { id: 1, url: '/x.jpg' } );
		const remove = document.querySelector( '.kwv-banner-remove' );
		const event = new MouseEvent( 'click', {
			bubbles: true,
			cancelable: true,
		} );

		remove.dispatchEvent( event );

		expect( event.defaultPrevented ).toBe( true );
		expect( document.querySelector( '.kwv-banner-input' ).value ).toBe(
			''
		);
		expect(
			document.querySelector( '.kwv-banner-preview' ).children
		).toHaveLength( 0 );
		expect( remove.style.display ).toBe( 'none' );
		off();
	} );

	it( 'keeps working for a field added after load (AJAX add-term form)', async () => {
		const off = await setup( { id: 3, url: '/late.jpg' } );
		document.body.innerHTML = FIELD.replace( 'value="12"', 'value=""' );

		document.querySelector( '.kwv-banner-select' ).click();
		frames[ 0 ].select();

		expect( document.querySelector( '.kwv-banner-input' ).value ).toBe(
			'3'
		);
		off();
	} );
} );
