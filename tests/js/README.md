# JavaScript unit tests and linting

Unit tests for the theme's front-end scripts in `assets/js/`, run with
[Vitest](https://vitest.dev/) in a jsdom browser environment, and ESLint with the
WordPress rules. Browser-level tests live separately in `tests/e2e/` (see its
README).

The scripts ship as plain browser files with no build step. The tests load each
file exactly as it ships, so coverage maps to the real code and nothing about how
the scripts are enqueued has to change.

## Setup

Node 20 or later (CI uses 24). From the theme root:

```sh
npm ci
```

All dependencies are dev-only. The theme's front-end still has no build step.

## Run

| Command | What it does |
|---|---|
| `npm test` | Every unit test once, with coverage. Fails below 80% statements, branches, functions or lines. |
| `npm run test:watch` | Re-runs affected tests as you edit. |
| `npm run test:coverage` | Same as `npm test`. The HTML report is written to `coverage/index.html`. |
| `npm run lint` | ESLint over `assets/js`, `tests/js` and the config files. |
| `npm run format` | Prettier (the WordPress fork) over the scripts and tests. |

Run one file: `npx vitest run tests/js/unit/aws-search.test.js`.

## Layout

```text
vitest.config.mjs         jsdom, coverage thresholds, the @wordpress/interactivity alias
eslint.config.mjs         WordPress recommended rules + the reviewed warning baseline
tests/js/
  setup.js                removes document/window listeners after every test
  helpers/load.js         fixture(), render(), loadScript(), deferReady(), mediaQuery(), flushObservers()
  stubs/interactivity.js  stand-in for @wordpress/interactivity (mega-menu.js)
  fixtures/*.html         real markup captured from the site, trimmed
  unit/<script>.test.js   one file per script in assets/js
```

## How a test works

Every script is an IIFE (`mega-menu.js` is the one script module): it runs once,
on load, against whatever DOM and globals exist at that moment. So a test:

1. renders a fixture into `document.body`,
2. stubs the globals the script expects with `vi.stubGlobal` (`Swiper`,
   `matchMedia`, `IntersectionObserver`, `jQuery`, `wp`, localised objects such as
   `kwvSearchL10n`), and
3. calls `loadScript( 'assets/js/<file>.js' )`, which clears the module registry
   and imports the file again, so the IIFE runs fresh.

`tests/js/setup.js` removes every listener a script added to `document` or
`window` after each test, so handlers from one test never fire in the next.
Globals stubbed with `vi.stubGlobal` and spies are restored automatically.

Useful helpers:

- `deferReady()` makes `document.readyState` report `loading` and returns a
  function that fires `DOMContentLoaded`. Use it to test a script's deferred-init
  branch.
- `mediaQuery( matches, { legacy } )` returns a controllable `matchMedia`. Call
  `.set( true )` to simulate the visitor changing a preference mid-session.
- `flushObservers()` waits for queued `MutationObserver` callbacks.

### Worked example: a test for a new script

Say you add `assets/js/back-to-top.js`, which shows a `.kwv-back-to-top` button
after the visitor scrolls and scrolls to the top when it's clicked.

1. Capture the markup it works on (see "Fixtures" below) into
   `tests/js/fixtures/back-to-top.html`.
2. Create `tests/js/unit/back-to-top.test.js`:

```js
import { describe, it, expect, vi } from 'vitest';
import userEvent from '@testing-library/user-event';
import { deferReady, fixture, loadScript, render } from '../helpers/load.js';

const SCRIPT = 'assets/js/back-to-top.js';

describe( 'back-to-top', () => {
	it( 'scrolls to the top when clicked', async () => {
		const scrollTo = vi.fn();
		vi.stubGlobal( 'scrollTo', scrollTo );
		render( fixture( 'back-to-top.html' ) );
		await loadScript( SCRIPT );

		await userEvent.click( document.querySelector( '.kwv-back-to-top' ) );

		expect( scrollTo ).toHaveBeenCalledWith( { top: 0, behavior: 'smooth' } );
	} );

	it( 'does nothing on a page without the button', async () => {
		render( '<main></main>' );
		await expect( loadScript( SCRIPT ) ).resolves.toBeDefined();
	} );

	it( 'waits for DOMContentLoaded while the document is loading', async () => {
		render( fixture( 'back-to-top.html' ) );
		const ready = deferReady();
		await loadScript( SCRIPT );
		// …assert nothing is wired yet…
		ready();
		// …assert it is wired now…
	} );
} );
```

3. Cover each early return, each missing-global fallback, keyboard and focus
   handling, the ARIA state the script changes, and any reduced-motion branch.
   `npm test` fails if coverage for the theme drops below 80%.

Test the script as it ships. Don't change shipped code to make it testable. If
a test finds a real bug, add it as `it.fails( … )` with a `BUG` comment pointing
to the report, and raise it rather than fixing it in the same change.

## Fixtures

Fixtures are real rendered HTML, trimmed. They were captured on 2026-11-12:

- from the local Studio site (`http://localhost:8901`): `aws-search`,
  `hero-slider`, `brand-marquee`, `lazy-video`, `product-description`;
- read-only from the dev site (`https://kwv.lightspeedwp.dev`), where the local
  site has no menus, filters or timeline: `mega-menu-nav`, `mobile-menu`,
  `shop-filters`, `timeline-milestone`;
- hand-built from the block markup where nothing renders it yet:
  `brand-slider` (from `feat/brand-page-redesign`). Each file's first comment
  says where it came from.

To capture a new one:

```sh
curl -s -b 'age_gate=18' http://localhost:8901/some-page/ -o /tmp/page.html
```

Then copy the element the script works on into `tests/js/fixtures/<name>.html`.
Strip `<script>`/`<style>` tags, shorten SVG path data and long `data-wp-context`
JSON, cap long lists at a few items, and replace absolute site URLs with
root-relative ones. Keep classes, ARIA attributes and the element structure
exactly as rendered: those are what the script reads. Start the file with a
comment naming the page and selector.

## Linting

`eslint.config.mjs` uses `@wordpress/eslint-plugin`'s recommended set with
Prettier. Prettier is the WordPress fork (`wp-prettier`, installed as
`prettier`): stock Prettier removes the spaces inside brackets and parentheses
that WordPress style requires.

The scripts are linted as classic browser scripts (ES2020 syntax, browser globals
plus `jQuery`, `wp`, `Swiper`). `no-var` is off because the scripts are
deliberately ES5-style, and `@package` tags are allowed in file headers.

Findings that predate the linter are reported as **warnings** in a marked
baseline block, so CI passes while they're worked off:

- Prettier formatting in every `assets/js` file. Fix with `npm run format` in a
  commit of its own, so it doesn't mix with behaviour changes.
- `timeline-milestone.js` reads `document.activeElement` (rule
  `@wordpress/no-global-active-element`).
- `query-slider.js`: camelCase, unused arguments and irregular whitespace.

Everything else is an error, and new files are linted strictly. Don't run
`eslint --fix` over `assets/js` as part of an unrelated change.

## Updating visual baselines (e2e)

The visual regression cases are in the Playwright suite. See "Visual
regression" in `tests/e2e/README.md`.
