/**
 * assets/js/brand-slider.js — brand page product slider.
 *
 * The script ships on feat/brand-page-redesign. Until that merges, this suite
 * skips itself on branches that don't have the file.
 *
 * Swiper is a recording fake: it keeps the options each instance was built
 * with and lets the test fire the `on` callbacks, so the script's own wiring
 * (loop/rewind thresholds, thumbs, a11y strings, inert, aria-current) is what
 * gets asserted. Swiper's own sliding is the vendor's concern.
 */
import { existsSync } from 'node:fs';
import { describe, it, expect, vi, beforeEach } from 'vitest';
import {
	deferReady,
	fixture,
	loadScript,
	mediaQuery,
	render,
} from '../helpers/load.js';

const SCRIPT = 'assets/js/brand-slider.js';
const PRESENT = existsSync( `${ process.cwd() }/${ SCRIPT }` );

let instances;

class FakeSwiper {
	constructor( el, options ) {
		this.el = el;
		this.options = options;
		this.slides = Array.from( el.querySelectorAll( '.swiper-slide' ) );
		this.activeIndex = 0;
		this.realIndex = 0;
		this.slideToLoop = vi.fn( ( index ) => this.go( index ) );
		instances.push( this );
		if ( options.on && options.on.afterInit ) {
			options.on.afterInit( this );
		}
	}
	go( index, active = index ) {
		this.realIndex = index;
		this.activeIndex = active;
		this.options.on.slideChange( this );
	}
}

beforeEach( () => {
	instances = [];
	vi.stubGlobal( 'Swiper', FakeSwiper );
	vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
} );

async function setup( { slides = 3, thumbs = true } = {} ) {
	render( fixture( 'brand-slider.html' ) );
	document.documentElement.classList.add( 'kwv-brand-slider-js' );
	document
		.querySelectorAll( '.wc-block-product' )
		.forEach( ( li, i ) => i >= slides && li.remove() );
	if ( ! thumbs ) {
		document.querySelector( '.kwv-brand-slider-thumbs' ).remove();
	}
	await loadScript( SCRIPT );
	const root = document.querySelector( '.kwv-brand-slider' );
	return {
		root,
		main: instances.find( ( s ) => s.el === root ),
		thumbs: instances.find( ( s ) => s.el !== root ),
		strip: document.querySelector( '.kwv-brand-slider__thumbs' ),
		slides: Array.from( root.querySelectorAll( '.wc-block-product' ) ),
	};
}

describe.skipIf( ! PRESENT )( 'brand-slider', () => {
	it( 'decorates the product list as a Swiper with arrows', async () => {
		const { root, main } = await setup();

		expect( root.classList.contains( 'swiper' ) ).toBe( true );
		expect(
			root
				.querySelector( '.wc-block-product-template' )
				.classList.contains( 'swiper-wrapper' )
		).toBe( true );
		expect( main.slides ).toHaveLength( 3 );
		const prev = root.querySelector( '.kwv-brand-slider__arrow--prev' );
		const next = root.querySelector( '.kwv-brand-slider__arrow--next' );
		expect( prev.getAttribute( 'aria-label' ) ).toBe( 'Previous product' );
		expect( next.getAttribute( 'aria-label' ) ).toBe( 'Next product' );
		expect( prev.type ).toBe( 'button' );
		expect( main.options.navigation ).toEqual( {
			prevEl: prev,
			nextEl: next,
		} );
		expect( main.options.keyboard ).toEqual( {
			enabled: true,
			onlyInViewport: true,
		} );
		expect( main.options.speed ).toBe( 450 );
	} );

	it( 'loops with three or more products', async () => {
		const { main } = await setup( { slides: 3 } );

		expect( main.options.loop ).toBe( true );
		expect( main.options.rewind ).toBe( false );
		expect( main.options.loopPreventsSliding ).toBe( false );
	} );

	it( 'rewinds instead of looping with exactly two products', async () => {
		const { main } = await setup( { slides: 2 } );

		expect( main.options.loop ).toBe( false );
		expect( main.options.rewind ).toBe( true );
	} );

	it( 'leaves a single product as it is', async () => {
		const { root } = await setup( { slides: 1 } );

		expect( root.classList.contains( 'kwv-brand-slider--single' ) ).toBe(
			true
		);
		expect( root.classList.contains( 'swiper' ) ).toBe( false );
		expect( instances ).toHaveLength( 0 );
	} );

	it( 'does nothing for an empty collection or one without a product list', async () => {
		render(
			'<div class="kwv-brand-slider"><ul class="wc-block-product-template"></ul></div><div class="kwv-brand-slider"><p>No products</p></div>'
		);

		await loadScript( SCRIPT );

		expect( instances ).toHaveLength( 0 );
		expect(
			document.querySelector( '.kwv-brand-slider--single' )
		).toBeNull();
	} );

	it( 'takes WooCommerce’s dead "#" image link out of the tab order', async () => {
		const { slides } = await setup();

		expect(
			slides[ 0 ]
				.querySelector( 'a[href="#"]' )
				.getAttribute( 'tabindex' )
		).toBe( '-1' );
	} );

	it( 'builds a thumbnail strip from each slide’s own image, with names as fallback', async () => {
		const { strip, thumbs, main } = await setup();
		const buttons = strip.querySelectorAll( '.kwv-brand-slider__thumb' );

		expect(
			strip.parentElement.classList.contains( 'kwv-brand-slider-thumbs' )
		).toBe( true );
		expect( strip.getAttribute( 'role' ) ).toBe( 'group' );
		expect( strip.getAttribute( 'aria-label' ) ).toBe( 'Choose a product' );
		expect( buttons ).toHaveLength( 3 );
		expect( buttons[ 0 ].getAttribute( 'aria-label' ) ).toBe(
			'Show Cruxland Gin 750ml'
		);
		expect( buttons[ 0 ].querySelector( 'img' ).src ).toMatch(
			/\/wp-content\/uploads\/cruxland-gin\.png$/
		);
		expect( buttons[ 0 ].querySelector( 'img' ).alt ).toBe( '' );
		expect( buttons[ 2 ].querySelector( 'img' ) ).toBeNull();
		expect( buttons[ 2 ].textContent ).toBe( 'Cruxland Gift Pack' );
		expect( thumbs.options ).toMatchObject( {
			slidesPerView: 'auto',
			freeMode: true,
			a11y: { enabled: false },
		} );
		expect( main.options.thumbs ).toEqual( { swiper: thumbs } );
	} );

	it( 'skips the strip when no slide has an image', async () => {
		render( fixture( 'brand-slider.html' ) );
		document
			.querySelectorAll( '.kwv-brand-slide__media' )
			.forEach( ( m ) => m.remove() );
		await loadScript( SCRIPT );

		expect(
			document.querySelector( '.kwv-brand-slider__thumbs' )
		).toBeNull();
		expect( instances ).toHaveLength( 1 );
		expect( instances[ 0 ].options.thumbs ).toBeUndefined();
	} );

	it( 'works without a thumbnails host', async () => {
		const { strip, main } = await setup( { thumbs: false } );

		expect( strip ).toBeNull();
		expect( main.options.thumbs ).toBeUndefined();
	} );

	it( 'keeps only the visible product reachable, and marks the current thumbnail', async () => {
		const { main, strip } = await setup();
		const thumbs = strip.querySelectorAll( '.kwv-brand-slider__thumb' );

		expect( main.slides.map( ( s ) => s.inert ) ).toEqual( [
			false,
			true,
			true,
		] );
		expect( thumbs[ 0 ].getAttribute( 'aria-current' ) ).toBe( 'true' );

		main.go( 2 );

		expect( main.slides.map( ( s ) => s.inert ) ).toEqual( [
			true,
			true,
			false,
		] );
		expect( thumbs[ 0 ].hasAttribute( 'aria-current' ) ).toBe( false );
		expect( thumbs[ 2 ].getAttribute( 'aria-current' ) ).toBe( 'true' );
	} );

	it( 're-syncs inert after loop mode moves slides', async () => {
		const { main } = await setup();
		main.activeIndex = 1;

		main.options.on.loopFix( main );

		expect( main.slides.map( ( s ) => s.inert ) ).toEqual( [
			true,
			false,
			true,
		] );
	} );

	it( 'thumbnail click (or Enter/Space) slides to that product', async () => {
		const { main, strip } = await setup();

		strip.querySelectorAll( '.kwv-brand-slider__thumb' )[ 1 ].click();

		expect( main.slideToLoop ).toHaveBeenCalledWith( 1 );
	} );

	it( 'passes the a11y strings, localised when kwvBrandSlider is set', async () => {
		vi.stubGlobal( 'kwvBrandSlider', {
			previous: 'Vorige',
			next: 'Volgende',
			slide: 'Produk {{index}}',
			thumbnails: 'Kies',
			show: 'Wys %s',
		} );
		const { main, strip, root } = await setup();

		expect( main.options.a11y ).toEqual( {
			prevSlideMessage: 'Vorige',
			nextSlideMessage: 'Volgende',
			slideLabelMessage: 'Produk {{index}}',
			slideRole: 'listitem',
			containerRoleDescriptionMessage: null,
			itemRoleDescriptionMessage: null,
		} );
		expect( strip.getAttribute( 'aria-label' ) ).toBe( 'Kies' );
		expect(
			strip.querySelector( 'button' ).getAttribute( 'aria-label' )
		).toBe( 'Wys Cruxland Gin 750ml' );
		expect(
			root
				.querySelector( '.kwv-brand-slider__arrow--prev' )
				.getAttribute( 'aria-label' )
		).toBe( 'Vorige' );
	} );

	it( 'uses the default slide label', async () => {
		const { main } = await setup();

		expect( main.options.a11y.slideLabelMessage ).toBe(
			'Product {{index}} of {{slidesLength}}'
		);
	} );

	it( 'drops transition speed to 0 under reduced motion', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( true ).matchMedia );
		const { main, thumbs } = await setup();

		expect( main.options.speed ).toBe( 0 );
		expect( thumbs.options.speed ).toBe( 0 );
	} );

	it( 'stands down without Swiper, restoring the full list', async () => {
		vi.stubGlobal( 'Swiper', undefined );
		const { root } = await setup();

		expect(
			document.documentElement.classList.contains( 'kwv-brand-slider-js' )
		).toBe( false );
		expect( root.classList.contains( 'swiper' ) ).toBe( false );
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( fixture( 'brand-slider.html' ) );
		const ready = deferReady();
		await loadScript( SCRIPT );
		expect( instances ).toHaveLength( 0 );

		ready();
		expect( instances.length ).toBeGreaterThan( 0 );
	} );

	// BUG (report #2): the script reads window.matchMedia unguarded at load, so in
	// an environment without it (old embedded webviews) it throws and the slider
	// never starts. Every other theme script guards matchMedia.
	it.fails( 'survives a browser without matchMedia', async () => {
		vi.stubGlobal( 'matchMedia', undefined );

		await setup();

		expect( instances.length ).toBeGreaterThan( 0 );
	} );
} );
