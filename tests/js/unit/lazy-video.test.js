/**
 * assets/js/lazy-video.js — load background video on approach, respect reduced motion.
 */
import { describe, it, expect, vi, beforeEach } from 'vitest';
import {
	deferReady,
	fixture,
	loadScript,
	mediaQuery,
	render,
} from '../helpers/load.js';

const SCRIPT = 'assets/js/lazy-video.js';

let observers;

/** Records the callback and options each IntersectionObserver is built with. */
class FakeIntersectionObserver {
	constructor( callback, options ) {
		this.callback = callback;
		this.options = options;
		this.observed = [];
		observers.push( this );
	}
	observe( el ) {
		this.observed.push( el );
	}
	fire( target, isIntersecting ) {
		this.callback( [ { target, isIntersecting } ] );
	}
}

function stubMedia( { playResult } = {} ) {
	const play = vi
		.spyOn( HTMLMediaElement.prototype, 'play' )
		.mockImplementation( function () {
			Object.defineProperty( this, 'paused', {
				configurable: true,
				value: false,
			} );
			return playResult === undefined ? Promise.resolve() : playResult;
		} );
	const pause = vi
		.spyOn( HTMLMediaElement.prototype, 'pause' )
		.mockImplementation( function () {
			Object.defineProperty( this, 'paused', {
				configurable: true,
				value: true,
			} );
		} );
	const load = vi
		.spyOn( HTMLMediaElement.prototype, 'load' )
		.mockImplementation( () => {} );
	return { play, pause, load };
}

beforeEach( () => {
	observers = [];
	vi.stubGlobal( 'IntersectionObserver', FakeIntersectionObserver );
} );

async function setup( { reduced = false, legacy = false } = {} ) {
	const mq = mediaQuery( reduced, { legacy } );
	vi.stubGlobal( 'matchMedia', mq.matchMedia );
	const media = stubMedia();
	render( fixture( 'lazy-video.html' ) );
	const video = document.querySelector( 'video' );
	await loadScript( SCRIPT );
	return { mq, media, video, observer: observers[ 0 ] };
}

describe( 'lazy-video', () => {
	it( 'downloads nothing until the video nears the viewport', async () => {
		const { video, media, observer } = await setup();

		expect( video.getAttribute( 'src' ) ).toBeNull();
		expect( media.play ).not.toHaveBeenCalled();
		expect( observer.options ).toEqual( { rootMargin: '300px 0px' } );
		expect( observer.observed ).toEqual( [ video ] );
		expect( video.muted ).toBe( true );
		expect( video.playsInline ).toBe( true );
	} );

	it( 'sets src from data-src once and plays when it comes into range', async () => {
		const { video, media, observer } = await setup();

		observer.fire( video, true );
		observer.fire( video, false );
		observer.fire( video, true );

		expect( video.getAttribute( 'src' ) ).toBe( video.dataset.src );
		expect( media.load ).toHaveBeenCalledTimes( 1 );
		expect( media.play ).toHaveBeenCalledTimes( 2 );
		expect( media.pause ).toHaveBeenCalledTimes( 1 );
	} );

	it( 'does not pause a video that is already paused', async () => {
		const { video, media, observer } = await setup();

		observer.fire( video, false );

		expect( media.pause ).not.toHaveBeenCalled();
	} );

	it( 'never loads or plays under reduced motion', async () => {
		const { video, media, observer } = await setup( { reduced: true } );

		observer.fire( video, true );

		expect( video.getAttribute( 'src' ) ).toBeNull();
		expect( media.play ).not.toHaveBeenCalled();
	} );

	it( 'reacts to the motion preference changing mid-session', async () => {
		const { video, media, observer, mq } = await setup();
		observer.fire( video, true );

		mq.set( true );
		expect( media.pause ).toHaveBeenCalledTimes( 1 );

		mq.set( false );
		expect( media.play ).toHaveBeenCalledTimes( 2 );
	} );

	it( 'does not start an off-screen video when motion is re-allowed', async () => {
		const { media, mq } = await setup( { reduced: true } );

		mq.set( false );

		expect( media.play ).not.toHaveBeenCalled();
	} );

	it( 'supports the legacy addListener API', async () => {
		const { video, media, observer, mq } = await setup( { legacy: true } );
		observer.fire( video, true );

		mq.set( true );

		expect( media.pause ).toHaveBeenCalled();
	} );

	it( 'swallows a refused autoplay promise', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		const rejected = Promise.reject( new Error( 'NotAllowedError' ) );
		const caught = vi.spyOn( rejected, 'catch' );
		stubMedia( { playResult: rejected } );
		render( fixture( 'lazy-video.html' ) );
		await loadScript( SCRIPT );

		observers[ 0 ].fire( document.querySelector( 'video' ), true );

		expect( caught ).toHaveBeenCalled();
	} );

	it( 'copes with play() returning nothing (older browsers)', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		stubMedia( { playResult: null } );
		render( fixture( 'lazy-video.html' ) );
		await loadScript( SCRIPT );

		expect( () =>
			observers[ 0 ].fire( document.querySelector( 'video' ), true )
		).not.toThrow();
	} );

	it( 'keeps an existing src rather than reloading', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		const media = stubMedia();
		render(
			fixture( 'lazy-video.html' ).replace(
				'<video ',
				'<video src="/already.mp4" '
			)
		);
		await loadScript( SCRIPT );

		observers[ 0 ].fire( document.querySelector( 'video' ), true );

		expect( document.querySelector( 'video' ).getAttribute( 'src' ) ).toBe(
			'/already.mp4'
		);
		expect( media.load ).not.toHaveBeenCalled();
	} );

	it( 'plays straight away without IntersectionObserver support', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		delete window.IntersectionObserver;
		const media = stubMedia();
		render( fixture( 'lazy-video.html' ) );

		await loadScript( SCRIPT );

		expect( media.play ).toHaveBeenCalledTimes( 1 );
	} );

	it( 'still honours reduced motion without IntersectionObserver support', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( true ).matchMedia );
		delete window.IntersectionObserver;
		const media = stubMedia();
		render( fixture( 'lazy-video.html' ) );

		await loadScript( SCRIPT );

		expect( media.play ).not.toHaveBeenCalled();
	} );

	it( 'works without matchMedia (motion assumed allowed, no live updates)', async () => {
		vi.stubGlobal( 'matchMedia', undefined );
		const media = stubMedia();
		render( fixture( 'lazy-video.html' ) );
		await loadScript( SCRIPT );

		observers[ 0 ].fire( document.querySelector( 'video' ), true );

		expect( media.play ).toHaveBeenCalled();
	} );

	it( 'does nothing on a page without lazy videos', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		render( '<video src="/plain.mp4"></video>' );

		await loadScript( SCRIPT );

		expect( observers ).toHaveLength( 0 );
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		vi.stubGlobal( 'matchMedia', mediaQuery( false ).matchMedia );
		stubMedia();
		render( fixture( 'lazy-video.html' ) );
		const ready = deferReady();
		await loadScript( SCRIPT );
		expect( observers ).toHaveLength( 0 );

		ready();
		expect( observers ).toHaveLength( 1 );
	} );
} );
