<?php
/**
 * Gravity Forms integration: KWV newsletter form styling, scoped to form id 2.
 *
 * assets/styles/gravity-forms.css styles the newsletter form (`#gform_wrapper_2`,
 * the same form id on local + dev). Tied to the `gravityforms/form` block with
 * wp_enqueue_block_style(), so it lazy-loads whenever the block renders.
 *
 * The careers job-application form is styled separately by the KWV Enhancements
 * plugin (it renders that form via the [kwv_career_form] shortcode and is deployable
 * to every environment; the theme isn't always writable) — see that plugin's
 * assets/careers-form.css.
 *
 * The style lives in a .css file rather than a block-style JSON `css` field because
 * it relies on `::placeholder`, `:focus` and `:hover`, which the `:where()`-wrapped
 * JSON css field strips or mangles (see the theme AGENTS.md "Styling lives in JSON"
 * note). Gravity Forms markup isn't a core block, so there's no block-style JSON to
 * carry it either.
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
			'ver'    => \Kwv\asset_version( 'assets/styles/gravity-forms.css' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\enqueue_block_style', 20 );
