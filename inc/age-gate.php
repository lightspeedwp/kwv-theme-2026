<?php
/**
 * Age Gate integration: KWV 2026 brand skin for the age-verification gate.
 *
 * The Age Gate plugin renders a full-screen verification overlay (`.age-gate__*` BEM
 * markup) in front of restricted content. Its background image and colour variables
 * are configured in the plugin's Appearance settings; this module layers the KWV
 * button and typography treatment on top via `assets/styles/age-gate.css`.
 *
 * The stylesheet depends on the plugin's `age-gate` style handle so it always cascades
 * after the plugin defaults, letting equal-specificity rules win on source order. It
 * only loads when the plugin is active.
 *
 * @package kwv
 */

namespace Kwv\AgeGate;

const STYLE_HANDLE = 'kwv-age-gate';

/**
 * Enqueue the KWV age-gate skin whenever the Age Gate plugin's styles are present.
 *
 * Registered at priority 20 so the plugin (default priority 10) has already enqueued
 * its `age-gate` stylesheet, which we declare as a dependency.
 */
function enqueue_style() {

	if ( is_admin() ) {
		return;
	}

	// The Age Gate plugin registers/enqueues its main stylesheet as `age-gate`.
	if ( ! wp_style_is( 'age-gate', 'registered' ) && ! wp_style_is( 'age-gate', 'enqueued' ) ) {
		return;
	}

	wp_enqueue_style(
		STYLE_HANDLE,
		get_theme_file_uri( 'assets/styles/age-gate.css' ),
		array( 'age-gate' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_style', 20 );
