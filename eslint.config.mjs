/**
 * ESLint flat config: the WordPress recommended set (with Prettier, via
 * the WordPress Prettier config) for the theme's browser scripts and their tests.
 *
 * assets/js ships untranspiled to browsers, so it is linted as classic
 * scripts against browser globals; mega-menu.js is the one script module.
 * The e2e suite in tests/e2e has its own package and tooling.
 */
import wordpress from '@wordpress/eslint-plugin';
import globals from 'globals';

export default [
	{
		ignores: [
			'node_modules/**',
			'coverage/**',
			'tests/e2e/**',
			'vendor/**',
			'build/**',
		],
	},
	...wordpress.configs.recommended,
	{
		files: [ 'assets/js/**/*.js' ],
		languageOptions: {
			ecmaVersion: 2020,
			sourceType: 'script',
			globals: {
				...globals.browser,
				// Provided at runtime by WordPress and plugins (see each script's gate in inc/).
				jQuery: 'readonly',
				wp: 'readonly',
				Swiper: 'readonly',
			},
		},
	},
	{
		files: [ 'assets/js/**/*.js' ],
		rules: {
			// Deliberate: the scripts are dependency-free ES5-style browser code.
			'no-var': 'off',
			// Theme convention: file headers carry `@package kwv`, as in the PHP.
			'jsdoc/empty-tags': 'off',
		},
	},
	{
		/*
		 * Reviewed baseline of findings that predate the linter, reported as
		 * warnings so CI stays green while they are tracked (see the JS test
		 * report). Formatting: run `npm run format` in a dedicated commit.
		 * Anything not listed here is linted strictly.
		 */
		files: [ 'assets/js/**/*.js' ],
		rules: { 'prettier/prettier': 'warn' },
	},
	{
		files: [ 'assets/js/timeline-milestone.js' ],
		rules: { '@wordpress/no-global-active-element': 'warn' },
	},
	{
		files: [ 'assets/js/query-slider.js' ],
		rules: {
			camelcase: 'warn',
			'no-unused-vars': 'warn',
			'no-irregular-whitespace': 'warn',
		},
	},
	{
		files: [ 'assets/js/mega-menu.js' ],
		languageOptions: { sourceType: 'module' },
		rules: {
			// Resolved by WordPress's import map, not node_modules.
			'import/no-unresolved': [
				'error',
				{ ignore: [ '^@wordpress/interactivity$' ] },
			],
		},
	},
	{
		files: [ 'assets/js/query-slider.js' ],
		languageOptions: {
			// LSX Tour Operator globals (this file is not enqueued; see tests/js/README.md).
			globals: {
				lsx_to: 'writable',
				$: 'readonly',
				toModalBootstrap: 'readonly',
			},
		},
	},
	{
		files: [ 'tests/js/**/*.js', '*.config.mjs' ],
		languageOptions: {
			globals: { ...globals.node, ...globals.browser },
		},
		rules: {
			'import/no-extraneous-dependencies': 'off',
		},
	},
];
