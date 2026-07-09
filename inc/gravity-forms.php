<?php
/**
 * Gravity Forms integration: KWV newsletter form styling, scoped to form id 2.
 *
 * The look is defined in assets/styles/gravity-forms.css and scoped to
 * `#gform_wrapper_2` (the newsletter form on both local and dev share form id 2).
 * The sheet is tied to the `gravityforms/form` block with wp_enqueue_block_style(),
 * so it lazy-loads whenever the block renders on the front end or in the editor —
 * no block style to apply from the Styles panel.
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
			'ver'    => wp_get_theme()->get( 'Version' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\enqueue_block_style', 20 );
