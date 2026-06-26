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
	wp_enqueue_style( sanitize_title( __NAMESPACE__ ), get_template_directory_uri() . '/style.css', array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_style_sheet' );


/**
 * Add block style variations.
 */
function register_block_styles() {

	$block_styles = array(
		'core/list'         => array(
			'list-check'        => __( 'Check', 'kwv' ),
			'list-check-circle' => __( 'Check Circle', 'kwv' ),
			'list-boxed'        => __( 'Boxed', 'kwv' ),
		),
		'core/code'         => array(
			'dark-code' => __( 'Dark', 'kwv' ),
		),
		'core/cover'        => array(
			'blur-image-less' => __( 'Blur Image Less', 'kwv' ),
			'blur-image-more' => __( 'Blur Image More', 'kwv' ),
			'rounded-cover'   => __( 'Rounded', 'kwv' ),
		),
		'core/column'       => array(
			'column-box-shadow' => __( 'Box Shadow', 'kwv' ),
		),
		'core/post-excerpt' => array(
			'excerpt-truncate-2' => __( 'Truncate 2 Lines', 'kwv' ),
			'excerpt-truncate-3' => __( 'Truncate 3 Lines', 'kwv' ),
			'excerpt-truncate-4' => __( 'Truncate 4 Lines', 'kwv' ),
		),
		'core/group'        => array(
			'column-box-shadow' => __( 'Box Shadow', 'kwv' ),
			'background-blur'   => __( 'Background Blur', 'kwv' ),
		),
		'core/separator'    => array(
			'separator-dotted' => __( 'Dotted', 'kwv' ),
			'separator-thin'   => __( 'Thin', 'kwv' ),
		),
		'core/image'        => array(
			'rounded-full' => __( 'Rounded Full', 'kwv' ),
			'media-boxed'  => __( 'Boxed', 'kwv' ),
		),
		'core/preformatted' => array(
			'preformatted-dark' => __( 'Dark Style', 'kwv' ),
		),
		'core/post-terms'   => array(
			'term-button' => __( 'Button Style', 'kwv' ),
		),
		'core/video'        => array(
			'media-boxed' => __( 'Boxed', 'kwv' ),
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
 * Load custom block styles only when the block is used.
 */
function enqueue_custom_block_styles() {

	// Scan our styles folder to locate block styles.
	$files = glob( get_template_directory() . '/assets/styles/*.css' );

	foreach ( $files as $file ) {

		// Get the filename and core block name.
		$filename   = basename( $file, '.css' );
		$block_name = str_replace( 'core-', 'core/', $filename );

		wp_enqueue_block_style(
			$block_name,
			array(
				'handle' => "kwv-block-{$filename}",
				'src'    => get_theme_file_uri( "assets/styles/{$filename}.css" ),
				'path'   => get_theme_file_path( "assets/styles/{$filename}.css" ),
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
		)
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
 * Load the Team post type.
 */
require_once get_template_directory() . '/inc/team.php';

/**
 * Load WooCommerce specific functions.
 */
require_once get_template_directory() . '/inc/woocommerce.php';

/**
 * Load the product-category mega menu renderer.
 */
require_once get_template_directory() . '/inc/mega-menu.php';
