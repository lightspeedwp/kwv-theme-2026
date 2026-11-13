/**
 * Vitest config for the theme's front-end scripts.
 *
 * The scripts in assets/js ship as plain browser files with no build step, so
 * the tests load each file exactly as it ships (see tests/js/helpers/load.js)
 * and V8 coverage maps straight back to it.
 */
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vitest/config';

export default defineConfig( {
	resolve: {
		alias: {
			// mega-menu.js is a script module importing the Interactivity API,
			// which WordPress provides at runtime. Tests get a recording stub.
			'@wordpress/interactivity': fileURLToPath(
				new URL( './tests/js/stubs/interactivity.js', import.meta.url )
			),
		},
	},
	test: {
		environment: 'jsdom',
		include: [ 'tests/js/**/*.test.js' ],
		setupFiles: [ 'tests/js/setup.js' ],
		restoreMocks: true,
		unstubGlobals: true,
		coverage: {
			provider: 'v8',
			include: [ 'assets/js/**/*.js' ],
			reporter: [ 'text', 'html', 'lcov', 'json-summary' ],
			reportsDirectory: 'coverage',
			thresholds: {
				statements: 80,
				branches: 80,
				functions: 80,
				lines: 80,
			},
		},
	},
} );
