<?php
/**
 * This file adds functions to the KWV WordPress theme.
 *
 * @package kwv
 * @author  LightSpeed
 * @license GNU General Public License v2 or later
 * @link    https://kwv.co.za
 */

namespace Kwv;

/**
 * Cache-busting version string for a theme asset.
 *
 * Returns the asset's last-modified time so that any edit to the file changes
 * its enqueued URL — which means no browser, device cache, CDN, or page cache
 * (e.g. WP Rocket) can ever serve a stale copy after a deploy. Falls back to
 * the theme version if the file can't be read (e.g. on a symlinked mount).
 *
 * Use this for every locally-shipped stylesheet/script `$ver`; never hardcode
 * a version string, and don't lean on the theme `Version` header — it isn't
 * bumped per asset edit, so it goes stale exactly like a literal would.
 *
 * @param string $relative_path Asset path relative to the theme root,
 *                              e.g. 'assets/styles/woocommerce.css'.
 * @return string Version string suitable for wp_enqueue_* `$ver`.
 */
function asset_version( $relative_path ) {
	$file  = get_theme_file_path( $relative_path );
	$mtime = is_readable( $file ) ? filemtime( $file ) : false;

	return (string) ( false !== $mtime ? $mtime : wp_get_theme()->get( 'Version' ) );
}

/**
 * Set up theme defaults and register various WordPress features.
 */
function setup() {

	// Enqueue editor styles and fonts.
	add_editor_style( 'style.css' );

	// Remove core block patterns.
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' );


/**
 * Enqueue styles.
 */
function enqueue_style_sheet() {
	wp_enqueue_style( sanitize_title( __NAMESPACE__ ), get_template_directory_uri() . '/style.css', array(), asset_version( 'style.css' ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_style_sheet' );


/**
 * Add block style variations.
 */
function register_block_styles() {

	$block_styles = array(
		'core/list'         => array(
			'list-check' => __( 'Check', 'kwv' ),
		),
		'core/post-excerpt' => array(
			'excerpt-truncate-3' => __( 'Truncate 3 Lines', 'kwv' ),
		),
		'core/group'        => array(
			'background-blur' => __( 'Background Blur', 'kwv' ),
		),
		'core/separator'    => array(
			'separator-thin' => __( 'Thin', 'kwv' ),
		),
		'core/categories'   => array(
			'categories-chevron' => __( 'Chevron', 'kwv' ),
		),
	);

	foreach ( $block_styles as $block => $styles ) {
		foreach ( $styles as $style_name => $style_label ) {
			register_block_style(
				$block,
				array(
					'name'  => $style_name,
					'label' => $style_label,
				)
			);
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\register_block_styles' );


/**
 * Load per-block CSS only when the block is used.
 *
 * Convention: `assets/styles/core-<block>.css` maps to the core block `core/<block>`
 * (e.g. `core-button.css` → `core/button`) and is lazy-enqueued when that block renders.
 * Only `core-*.css` files participate; sheets for non-core blocks (e.g. `woocommerce.css`,
 * `ollie-mega-menu.css`) are enqueued by their own modules in `inc/`.
 */
function enqueue_custom_block_styles() {

	// Scan our styles folder for per-core-block stylesheets.
	$files = glob( get_template_directory() . '/assets/styles/core-*.css' );

	foreach ( $files as $file ) {

		// Get the filename and core block name (core-button -> core/button).
		$filename   = basename( $file, '.css' );
		$block_name = str_replace( 'core-', 'core/', $filename );

		wp_enqueue_block_style(
			$block_name,
			array(
				'handle' => "kwv-block-{$filename}",
				'src'    => get_theme_file_uri( "assets/styles/{$filename}.css" ),
				'path'   => get_theme_file_path( "assets/styles/{$filename}.css" ),
				'ver'    => asset_version( "assets/styles/{$filename}.css" ),
			)
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\enqueue_custom_block_styles' );


/**
 * Register pattern categories.
 */
function pattern_categories() {

	$block_pattern_categories = array(
		'kwv/card'           => array(
			'label' => __( 'Cards', 'kwv' ),
		),
		'kwv/call-to-action' => array(
			'label' => __( 'Call To Action', 'kwv' ),
		),
		'kwv/features'       => array(
			'label' => __( 'Features', 'kwv' ),
		),
		'kwv/hero'           => array(
			'label' => __( 'Hero', 'kwv' ),
		),
		'kwv/pages'          => array(
			'label' => __( 'Pages', 'kwv' ),
		),
		'kwv/posts'          => array(
			'label' => __( 'Posts', 'kwv' ),
		),
		'kwv/pricing'        => array(
			'label' => __( 'Pricing', 'kwv' ),
		),
		'kwv/testimonial'    => array(
			'label' => __( 'Testimonials', 'kwv' ),
		),
		'kwv/menu'    => array(
			'label' => __( 'Menu', 'kwv' ),
		),
		'kwv/about'          => array(
			'label' => __( 'About Page', 'kwv' ),
		),
		'kwv/history'        => array(
			'label' => __( 'History Page', 'kwv' ),
		),
		'kwv/visit'          => array(
			'label' => __( 'Visit Us Page', 'kwv' ),
		),
		'kwv/winemakers-club' => array(
			'label' => __( 'Winemaker’s Club', 'kwv' ),
		),
	);

	foreach ( $block_pattern_categories as $name => $properties ) {
		register_block_pattern_category( $name, $properties );
	}
}
add_action( 'init', __NAMESPACE__ . '\pattern_categories', 9 );


/**
 * Remove last separator on blog/archive if no pagination exists.
 */
function is_paginated() {
	global $wp_query;
	if ( $wp_query->max_num_pages < 2 ) {
		echo '<style>.blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .archive .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .blog .wp-block-post-template .wp-block-post:last-child .entry-content + .wp-block-separator, .search .wp-block-post-template .wp-block-post:last-child .wp-block-post-excerpt + .wp-block-separator { display: none; }</style>';
	}
}
add_action( 'wp_head', __NAMESPACE__ . '\is_paginated' );


/**
 * Add a Sidebar template part area
 */
function template_part_areas( array $areas ) {
	$areas[] = array(
		'area'        => 'sidebar',
		'area_tag'    => 'section',
		'label'       => __( 'Sidebar', 'kwv' ),
		'description' => __( 'The Sidebar template defines a page area that can be found on the Page (With Sidebar) template.', 'kwv' ),
		'icon'        => 'sidebar',
	);

	return $areas;
}
add_filter( 'default_wp_template_part_areas', __NAMESPACE__ . '\template_part_areas' );

/**
 * Load user job titles (job_title user meta + author-role block binding).
 */
require_once get_template_directory() . '/inc/user-job-title.php';

/**
 * Load WooCommerce specific functions.
 */
require_once get_template_directory() . '/inc/woocommerce.php';

/**
 * Load per-term banner images (product category + brand hero images).
 */
require_once get_template_directory() . '/inc/term-banner.php';

/**
 * Load the product-category mega menu renderer.
 */
require_once get_template_directory() . '/inc/mega-menu.php';

/**
 * Load Advanced Woo Search block styles + fold-out behaviour.
 */
require_once get_template_directory() . '/inc/search.php';

/**
 * Load the carousel block (cb/carousel-v2) nav-arrow styling.
 */
require_once get_template_directory() . '/inc/carousel.php';

/**
 * Load Gravity Forms newsletter sign-up styling.
 */
require_once get_template_directory() . '/inc/gravity-forms.php';

/**
 * Load the Age Gate age-verification skin (KWV 2026 brand).
 */
require_once get_template_directory() . '/inc/age-gate.php';
