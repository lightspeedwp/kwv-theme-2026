/**
 * assets/js/hero-slider.js — gate hero autoplay on prefers-reduced-motion.
 */
import { describe, it, expect, vi } from 'vitest';
import {
	deferReady,
	fixture,
	loadScript,
	mediaQuery,
	render,
} from '../helpers/load.js';

const SCRIPT = 'assets/js/hero-slider.js';

function heroWithSwiper() {
	render( fixture( 'hero-slider.html' ) );
	const block = document.querySelector( '.cb-hero-slider' );
	const autoplay = { start: vi.fn(), stop: vi.fn() };
	block.querySelector( '.swiper' ).swiper = { autoplay };
	return { block, autoplay };
}

describe( 'hero-slider', () => {
	it( 'leaves authored autoplay on when motion is allowed', async () => {
		const mq = mediaQuery( false );
		vi.stubGlobal( 'matchMedia', mq.matchMedia );
		const { block, autoplay } = heroWithSwiper();

		await loadScript( SCRIPT );

		expect( mq.matchMedia ).toHaveBeenCalledWith(
			'(prefers-reduced-motion: reduce)'
		);
		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'true' );
		expect( block.dataset.kwvAutoplay ).toBe( 'true' );
		expect( autoplay.start ).toHaveBeenCalled();
	} );

	it( 'turns autoplay off before the plugin reads it when motion is reduced', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( true ).matchMedia );
		render( fixture( 'hero-slider.html' ) );
		const block = document.querySelector( '.cb-hero-slider' );

		await loadScript( SCRIPT );

		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'false' );
	} );

	it( 'stops a live Swiper when motion is reduced', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( true ).matchMedia );
		const { autoplay } = heroWithSwiper();

		await loadScript( SCRIPT );

		expect( autoplay.stop ).toHaveBeenCalled();
		expect( autoplay.start ).not.toHaveBeenCalled();
	} );

	it( 'follows the setting live in both directions, remembering the authored intent', async () => {
		const mq = mediaQuery( false );
		vi.stubGlobal( 'matchMedia', mq.matchMedia );
		const { block, autoplay } = heroWithSwiper();
		await loadScript( SCRIPT );

		mq.set( true );
		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'false' );
		expect( autoplay.stop ).toHaveBeenCalledTimes( 1 );

		mq.set( false );
		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'true' );
		expect( autoplay.start ).toHaveBeenCalledTimes( 2 );
	} );

	it( 'supports the legacy addListener API', async () => {
		const mq = mediaQuery( false, { legacy: true } );
		vi.stubGlobal( 'matchMedia', mq.matchMedia );
		const { block } = heroWithSwiper();
		await loadScript( SCRIPT );

		mq.set( true );

		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'false' );
	} );

	it( 'tolerates a MediaQueryList with no listener API', async () => {
		vi.stubGlobal( 'matchMedia', () => ( { matches: false } ) );
		const { autoplay } = heroWithSwiper();

		await loadScript( SCRIPT );

		expect( autoplay.start ).toHaveBeenCalled();
	} );

	it( 'treats a missing matchMedia as "motion allowed"', async () => {
		vi.stubGlobal( 'matchMedia', undefined );
		const { block, autoplay } = heroWithSwiper();

		await loadScript( SCRIPT );

		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'true' );
		expect( autoplay.start ).toHaveBeenCalled();
	} );

	it( 'never turns on autoplay for a block authored without it', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		const { block, autoplay } = heroWithSwiper();
		block.removeAttribute( 'data-cb-autoplay' );

		await loadScript( SCRIPT );

		expect( block.dataset.kwvAutoplay ).toBe( 'false' );
		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'false' );
		expect( autoplay.stop ).toHaveBeenCalled();
	} );

	it( 'skips blocks whose Swiper is not built yet, then catches up on window load', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( true ).matchMedia );
		render( fixture( 'hero-slider.html' ) );
		const block = document.querySelector( '.cb-hero-slider' );
		await loadScript( SCRIPT );

		const autoplay = { start: vi.fn(), stop: vi.fn() };
		block.querySelector( '.swiper' ).swiper = { autoplay };
		window.dispatchEvent( new Event( 'load' ) );

		expect( autoplay.stop ).toHaveBeenCalled();
	} );

	it( 'ignores a hero with no .swiper element or no autoplay module', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		render(
			'<div class="cb-hero-slider" data-cb-autoplay="true"></div><div class="cb-hero-slider" data-cb-autoplay="true"><div class="swiper"></div></div>'
		);
		document.querySelectorAll( '.swiper' )[ 0 ].swiper = {};

		await loadScript( SCRIPT );

		document
			.querySelectorAll( '.cb-hero-slider' )
			.forEach( ( b ) =>
				expect( b.getAttribute( 'data-cb-autoplay' ) ).toBe( 'true' )
			);
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( true ).matchMedia );
		render( fixture( 'hero-slider.html' ) );
		const block = document.querySelector( '.cb-hero-slider' );
		const ready = deferReady();

		await loadScript( SCRIPT );
		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'true' );

		ready();
		expect( block.getAttribute( 'data-cb-autoplay' ) ).toBe( 'false' );
	} );
} );
