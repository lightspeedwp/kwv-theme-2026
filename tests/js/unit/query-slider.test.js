/**
 * assets/js/query-slider.js — LSX Tour Operator slick slider builder.
 *
 * NOTE: this file is not enqueued by the theme (no gate in inc/ loads it) and
 * depends on globals the KWV site never defines (`lsx_to`, `$`, slick). It
 * looks like a leftover from the LSX Tour Operator plugin. It is tested as-is
 * so coverage stays honest; see the JS test report for the removal proposal.
 */
import { describe, it, expect, vi, beforeEach } from 'vitest';
import jQuery from 'jquery';
import { loadScript, render } from '../helpers/load.js';

const SCRIPT = 'assets/js/query-slider.js';

let lsx;
let slick;

beforeEach( () => {
	lsx = {
		pre_build_slider: vi.fn(),
		get_responsive_breakpoints: vi.fn( ( n ) => [
			{ breakpoint: 1028, settings: { slidesToShow: n } },
		] ),
		build_video_slider: vi.fn(),
	};
	// Real slick fires 'init' synchronously while it builds the slider.
	slick = vi.fn( function () {
		this.trigger( 'init', [ {} ] );
		return this;
	} );
	jQuery.fn.slick = slick;
	vi.stubGlobal( 'lsx_to', lsx );
	vi.stubGlobal( '$', jQuery );
} );

async function build( html ) {
	render( html );
	await loadScript( SCRIPT );
	lsx.build_slider( 1200 );
}

const POSTS = ( cls, n ) =>
	`<div class="lsx-to-slider"><div class="wp-block-post-template ${ cls }">${ '<li></li>'.repeat( n ) }</div></div>`;

describe( 'query-slider (unused LSX leftover)', () => {
	it( 'builds a 3-up slick slider by default', async () => {
		await build( POSTS( '', 4 ) );

		expect( lsx.pre_build_slider ).toHaveBeenCalledTimes( 1 );
		expect( slick ).toHaveBeenCalledTimes( 1 );
		expect( slick.mock.calls[ 0 ][ 0 ] ).toMatchObject( {
			slidesToShow: 3,
			slidesToScroll: 1,
			infinite: true,
			dots: true,
			responsive: [ { breakpoint: 1028, settings: { slidesToShow: 3 } } ],
		} );
		expect( lsx.build_video_slider ).toHaveBeenCalled();
	} );

	it( 'takes the column count from a columns-N class', async () => {
		await build( POSTS( 'columns-2', 4 ) );

		// Passed through as the string from the class name.
		expect( slick.mock.calls[ 0 ][ 0 ].slidesToShow ).toBe( '2' );
	} );

	it( 'also builds term templates, but not disabled or video sliders', async () => {
		await build(
			'<div class="lsx-to-slider"><div class="wp-block-term-template"><li></li><li></li></div></div>' +
				POSTS( 'slider-disabled', 4 ) +
				'<div class="lsx-to-slider lsx-block-videos"><div class="wp-block-post-template"><li></li><li></li></div></div>'
		);

		expect( slick ).toHaveBeenCalledTimes( 1 );
	} );

	it( 'leaves a single item alone', async () => {
		await build( POSTS( '', 1 ) );

		expect( lsx.pre_build_slider ).toHaveBeenCalledTimes( 1 );
		expect( slick ).not.toHaveBeenCalled();
	} );

	it( 'builds the travel-information slider 4-up with its own breakpoints', async () => {
		await build(
			'<div class="lsx-travel-information-wrapper lsx-to-slider"><div class="travel-information"><div></div><div></div></div></div>'
		);

		expect( slick.mock.calls[ 0 ][ 0 ] ).toMatchObject( {
			slidesToShow: 4,
			responsive: [ { breakpoint: 1028 }, { breakpoint: 782 } ],
		} );
	} );

	it( 'skips a one-item travel-information slider', async () => {
		await build(
			'<div class="lsx-travel-information-wrapper lsx-to-slider"><div class="travel-information"><div></div></div></div>'
		);

		expect( slick ).not.toHaveBeenCalled();
	} );

	it( 'does not call toModalBootstrap when it is undefined', async () => {
		vi.stubGlobal( 'toModalBootstrap', undefined );
		await build(
			'<div class="lsx-travel-information-wrapper lsx-to-slider"><div class="travel-information"><div></div><div></div></div></div>'
		);
		jQuery( '.travel-information' ).trigger( 'init' );

		expect( slick ).toHaveBeenCalled();
	} );

	it( 'calls toModalBootstrap on a later init event', async () => {
		const boot = vi.fn();
		vi.stubGlobal( 'toModalBootstrap', boot );
		await build(
			'<div class="lsx-travel-information-wrapper lsx-to-slider"><div class="travel-information"><div></div><div></div></div></div>'
		);

		jQuery( '.travel-information' ).trigger( 'init' );

		expect( boot ).toHaveBeenCalledTimes( 1 );
	} );

	// BUG (report #1): the 'init' handler is bound *after* .slick() runs, and slick
	// fires 'init' during construction, so toModalBootstrap never runs on build.
	it.fails(
		'runs toModalBootstrap when the travel-information slider initialises',
		async () => {
			const boot = vi.fn();
			vi.stubGlobal( 'toModalBootstrap', boot );

			await build(
				'<div class="lsx-travel-information-wrapper lsx-to-slider"><div class="travel-information"><div></div><div></div></div></div>'
			);

			expect( boot ).toHaveBeenCalled();
		}
	);
} );
