<?php
/**
 * Gravity Forms integration: KWV form styling.
 *
 * assets/styles/gravity-forms.css carries two independently-scoped looks:
 *  - the newsletter form, scoped to `#gform_wrapper_2` (same form id on local + dev);
 *  - the careers job-application form, scoped to the `.single-kwv_career` body class
 *    WordPress adds on single job pages — chosen over a form id because the shared
 *    application form's id differs per environment, and it holds whether the page
 *    renders from the theme template or a DB template override.
 *
 * The sheet loads two ways: tied to the `gravityforms/form` block via
 * wp_enqueue_block_style() (covers the newsletter form and any block-embedded form),
 * and conditionally on single `kwv_career` pages, where the form is embedded with the
 * [kwv_career_form] shortcode rather than the block (so the block enqueue never fires).
 *
 * The style lives in a .css file rather than a block-style JSON `css` field because
 * it relies on `::placeholder`, `::file-selector-button`, `:focus` and `:hover`, which
 * the `:where()`-wrapped JSON css field strips or mangles (see the theme AGENTS.md
 * "Styling lives in JSON" note). Gravity Forms markup isn't a core block, so there's
 * no block-style JSON to carry it either.
 *
 * @package kwv
 */

namespace Kwv\GravityForms;

const BLOCK_NAME   = 'gravityforms/form';
const STYLE_HANDLE = 'kwv-block-gravity-forms';

/**
 * Tie the stylesheet to the Gravity Forms block so it loads with the block.
 */
function enqueue_block_style() {

	if ( ! function_exists( 'wp_enqueue_block_style' ) ) {
		return;
	}

	wp_enqueue_block_style(
		BLOCK_NAME,
		array(
			'handle' => STYLE_HANDLE,
			'src'    => get_theme_file_uri( 'assets/styles/gravity-forms.css' ),
			'path'   => get_theme_file_path( 'assets/styles/gravity-forms.css' ),
			'ver'    => wp_get_theme()->get( 'Version' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\enqueue_block_style', 20 );

/**
 * Load the sheet on single job pages, where the application form is embedded with
 * the [kwv_career_form] shortcode (so the block-tied enqueue above never fires).
 */
function enqueue_on_single_career() {

	if ( ! is_singular( 'kwv_career' ) ) {
		return;
	}

	wp_enqueue_style(
		STYLE_HANDLE . '-single',
		get_theme_file_uri( 'assets/styles/gravity-forms.css' ),
		array(),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_on_single_career' );
