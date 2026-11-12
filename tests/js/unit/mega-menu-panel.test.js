/**
 * assets/js/mega-menu-panel.js — size the mega menu panel to its tallest column.
 *
 * jsdom has no layout, so each row's getBoundingClientRect is stubbed from a
 * `data-top` / `data-bottom` pair and computed padding from the inline style.
 */
import { describe, it, expect, vi, beforeEach } from 'vitest';
import { deferReady, loadScript, render } from '../helpers/load.js';

const SCRIPT = 'assets/js/mega-menu-panel.js';
const PROP = '--kwv-mega-menu-panel-block-size';

let frames;
let resizeObservers;

class FakeResizeObserver {
	constructor( callback ) {
		this.callback = callback;
		this.observed = [];
		resizeObservers.push( this );
	}
	observe( el ) {
		this.observed.push( el );
	}
}

beforeEach( () => {
	frames = [];
	resizeObservers = [];
	vi.stubGlobal( 'requestAnimationFrame', ( fn ) => frames.push( fn ) );
	vi.stubGlobal( 'ResizeObserver', FakeResizeObserver );
	vi.spyOn( Element.prototype, 'getBoundingClientRect' ).mockImplementation(
		function () {
			const top = Number( this.dataset.top || 0 );
			const bottom = Number( this.dataset.bottom || 0 );
			return {
				top,
				bottom,
				height: bottom - top,
				left: 0,
				right: 0,
				width: 0,
				x: 0,
				y: top,
			};
		}
	);
} );

/** Two levels: a 3-row level-1 list (0→150) and a 5-row submenu (0→260, plus 10px padding). */
function menu() {
	return `<div class="kwv-mega-menu-nav-wrap"><nav class="wp-block-navigation is-style-mega-menu-nav">
		<ul class="wp-block-navigation__container">
			<li data-top="0" data-bottom="50"><a>Wine</a>
				<ul class="wp-block-navigation__submenu-container" style="padding-top:4px;padding-bottom:6px">
					<li data-top="0" data-bottom="52"></li><li></li><li></li><li></li><li data-top="208" data-bottom="260"></li>
				</ul>
			</li>
			<li data-top="50" data-bottom="100"></li>
			<li data-top="100" data-bottom="150"></li>
		</ul>
		<ul class="wp-block-navigation__submenu-container"></ul>
	</nav></div>`;
}

const nav = () => document.querySelector( 'nav' );

describe( 'mega-menu-panel', () => {
	it( 'publishes the tallest column height, including its padding', async () => {
		render( menu() );

		await loadScript( SCRIPT );

		expect( nav().style.getPropertyValue( PROP ) ).toBe( '270px' );
	} );

	it( 'observes every column and re-measures once per frame on resize', async () => {
		render( menu() );
		await loadScript( SCRIPT );
		const [ observer ] = resizeObservers;
		expect( observer.observed ).toHaveLength( 3 );

		nav().querySelector(
			'.wp-block-navigation__submenu-container li:last-child'
		).dataset.bottom = '400';
		observer.callback();
		observer.callback();
		expect( frames ).toHaveLength( 1 );

		frames.shift()();
		expect( nav().style.getPropertyValue( PROP ) ).toBe( '410px' );

		observer.callback();
		expect( frames ).toHaveLength( 1 );
	} );

	it( 'does not rewrite the property when the height is unchanged', async () => {
		render( menu() );
		await loadScript( SCRIPT );
		const set = vi.spyOn( nav().style, 'setProperty' );

		resizeObservers[ 0 ].callback();
		frames.shift()();

		expect( set ).not.toHaveBeenCalled();
	} );

	it( 'leaves the CSS fallback in place when nothing is measurable', async () => {
		render(
			'<div class="kwv-mega-menu-nav-wrap"><nav class="wp-block-navigation is-style-mega-menu-nav"><ul class="wp-block-navigation__container"></ul></nav></div>'
		);

		await loadScript( SCRIPT );

		expect( nav().style.getPropertyValue( PROP ) ).toBe( '' );
	} );

	it( 'falls back to window resize without ResizeObserver', async () => {
		vi.stubGlobal( 'ResizeObserver', undefined );
		render( menu() );
		await loadScript( SCRIPT );

		nav().querySelector(
			'.wp-block-navigation__container > li:last-child'
		).dataset.bottom = '500';
		window.dispatchEvent( new Event( 'resize' ) );
		frames.shift()();

		expect( nav().style.getPropertyValue( PROP ) ).toBe( '500px' );
	} );

	it( 'ignores navs outside the mega menu wrapper', async () => {
		render( menu().replace( 'kwv-mega-menu-nav-wrap', 'other' ) );

		await loadScript( SCRIPT );

		expect( nav().style.getPropertyValue( PROP ) ).toBe( '' );
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( menu() );
		const ready = deferReady();
		await loadScript( SCRIPT );
		expect( nav().style.getPropertyValue( PROP ) ).toBe( '' );

		ready();
		expect( nav().style.getPropertyValue( PROP ) ).toBe( '270px' );
	} );
} );
