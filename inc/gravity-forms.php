<?php
/**
 * Gravity Forms integration: KWV block style for newsletter-style forms.
 *
 * Registers a **block style** on the `gravityforms/form` block — "KWV Newsletter"
 * (`is-style-kwv-newsletter`) — so it can be applied to any Gravity Form from the
 * editor's Styles panel. The look is defined in assets/styles/gravity-forms.css and
 * tied to the block with wp_enqueue_block_style(), so the sheet lazy-loads with the
 * block on the front end and in the editor.
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
const STYLE_NAME   = 'kwv-newsletter';
const STYLE_HANDLE = 'kwv-block-gravity-forms';

/**
 * Register the "KWV Newsletter" block style on the Gravity Forms block.
 *
 * Runs at priority 20 so the Gravity Forms block type is registered first —
 * register_block_style() only takes effect once the target block exists.
 */
function register_styles() {

	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	register_block_style(
		BLOCK_NAME,
		array(
			'name'  => STYLE_NAME,
			'label' => __( 'KWV Newsletter', 'kwv' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_styles', 20 );

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
