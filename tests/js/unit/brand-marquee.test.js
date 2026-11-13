/**
 * assets/js/brand-marquee.js — homepage brand rows hand card sizing to CSS.
 */
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { deferReady, fixture, loadScript, render } from '../helpers/load.js';

const SCRIPT = 'assets/js/brand-marquee.js';

/**
 * A stand-in for the Swiper instance the Carousel Block plugin exposes on `.swiper`.
 *
 * @param {Element} viewport         The `.swiper` element.
 * @param {Object}  options          Options.
 * @param {boolean} options.original Whether to include originalParams.
 * @return {Object} The fake instance.
 */
function fakeSwiper( viewport, { original = true } = {} ) {
	const slides = Array.from( viewport.querySelectorAll( '.swiper-slide' ) );
	slides.forEach( ( slide ) => ( slide.style.width = '189px' ) );
	return {
		slides,
		params: {
			slidesPerView: 5,
			breakpoints: { 1024: { slidesPerView: 5 } },
		},
		originalParams: original
			? { slidesPerView: 5, breakpoints: { 1024: {} } }
			: undefined,
		update: vi.fn(),
	};
}

let frames;

beforeEach( () => {
	frames = [];
	vi.stubGlobal( 'requestAnimationFrame', ( fn ) => frames.push( fn ) );
} );

function runFrames( limit = 500 ) {
	let n = 0;
	while ( frames.length && n++ < limit ) {
		frames.shift()();
	}
	return n;
}

describe( 'brand-marquee', () => {
	it( 'switches every built row to slidesPerView:auto and clears inline widths', async () => {
		render( fixture( 'brand-marquee.html' ) );
		const viewports = document.querySelectorAll(
			'.kwv-brand-marquee .swiper'
		);
		expect( viewports.length ).toBeGreaterThan( 0 );
		const swipers = Array.from(
			viewports,
			( v ) => ( v.swiper = fakeSwiper( v ) )
		);

		await loadScript( SCRIPT );

		swipers.forEach( ( swiper ) => {
			expect( swiper.params.slidesPerView ).toBe( 'auto' );
			expect( swiper.params.breakpoints ).toEqual( {} );
			expect( swiper.originalParams.slidesPerView ).toBe( 'auto' );
			expect( swiper.originalParams.breakpoints ).toEqual( {} );
			expect( swiper.update ).toHaveBeenCalledTimes( 1 );
			swiper.slides.forEach( ( slide ) =>
				expect( slide.style.width ).toBe( '' )
			);
		} );
		expect( frames ).toHaveLength( 0 );
	} );

	it( 'copes with a Swiper that has no originalParams', async () => {
		render( fixture( 'brand-marquee.html' ) );
		const viewport = document.querySelector( '.kwv-brand-marquee .swiper' );
		document
			.querySelectorAll( '.kwv-brand-marquee' )
			.forEach( ( b, i ) => i && b.remove() );
		const swiper = ( viewport.swiper = fakeSwiper( viewport, {
			original: false,
		} ) );

		await loadScript( SCRIPT );

		expect( swiper.params.slidesPerView ).toBe( 'auto' );
		expect( swiper.originalParams ).toBeUndefined();
	} );

	it( 'retries each animation frame until the plugin has built the carousel', async () => {
		render( fixture( 'brand-marquee.html' ) );
		document
			.querySelectorAll( '.kwv-brand-marquee' )
			.forEach( ( b, i ) => i && b.remove() );
		const viewport = document.querySelector( '.kwv-brand-marquee .swiper' );

		await loadScript( SCRIPT );
		expect( frames ).toHaveLength( 1 );

		frames.shift()();
		frames.shift()();
		const swiper = ( viewport.swiper = fakeSwiper( viewport ) );
		frames.shift()();

		expect( swiper.params.slidesPerView ).toBe( 'auto' );
		expect( frames ).toHaveLength( 0 );
	} );

	it( 'gives up after 120 frames if the carousel is never built', async () => {
		render( fixture( 'brand-marquee.html' ) );

		await loadScript( SCRIPT );

		expect( runFrames() ).toBe( 120 );
		expect( frames ).toHaveLength( 0 );
	} );

	it( 'adopts each row once, even when booted again on window load', async () => {
		render( fixture( 'brand-marquee.html' ) );
		const viewport = document.querySelector( '.kwv-brand-marquee .swiper' );
		document
			.querySelectorAll( '.kwv-brand-marquee' )
			.forEach( ( b, i ) => i && b.remove() );
		const swiper = ( viewport.swiper = fakeSwiper( viewport ) );

		await loadScript( SCRIPT );
		window.dispatchEvent( new Event( 'load' ) );

		expect( swiper.update ).toHaveBeenCalledTimes( 1 );
		expect(
			document.querySelector( '.kwv-brand-marquee' ).kwvBrandRow.swiper
		).toBe( swiper );
	} );

	it( 'leaves a row without a .swiper viewport pending', async () => {
		render( '<div class="kwv-brand-marquee"><p>Not built</p></div>' );

		await loadScript( SCRIPT );

		expect(
			document.querySelector( '.kwv-brand-marquee' ).kwvBrandRow.adopt()
		).toBe( false );
	} );

	it( 'does nothing on pages without brand rows', async () => {
		render( '<main></main>' );

		await loadScript( SCRIPT );

		expect( frames ).toHaveLength( 0 );
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( fixture( 'brand-marquee.html' ) );
		const viewport = document.querySelector( '.kwv-brand-marquee .swiper' );
		document
			.querySelectorAll( '.kwv-brand-marquee' )
			.forEach( ( b, i ) => i && b.remove() );
		const swiper = ( viewport.swiper = fakeSwiper( viewport ) );
		const ready = deferReady();

		await loadScript( SCRIPT );
		expect( swiper.update ).not.toHaveBeenCalled();

		ready();
		expect( swiper.update ).toHaveBeenCalledTimes( 1 );
	} );
} );
