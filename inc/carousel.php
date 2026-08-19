<?php
/**
 * Carousel Block (cb/carousel-v2) integration.
 *
 * The carousel is provided by the third-party `carousel-block` plugin (Swiper).
 * This module attaches the KWV nav-arrow stylesheet to that block so it only
 * loads on pages where the carousel renders — mirroring how `woocommerce.php`
 * and `mega-menu.php` scope their non-core block styles.
 *
 * See assets/styles/carousel-block.css for the actual overrides.
 *
 * @package kwv
 */

namespace Kwv\Carousel;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const STYLE_HANDLE   = 'kwv-carousel-block';
const SCRIPT_HANDLE  = 'kwv-hero-slider';
const MARQUEE_HANDLE = 'kwv-brand-marquee';

/**
 * Attach the carousel nav-arrow stylesheet to the plugin's carousel block.
 *
 * Loads only when `cb/carousel-v2` is present on the page.
 */
function register_assets() {
	wp_enqueue_block_style(
		'cb/carousel-v2',
		array(
			'handle' => STYLE_HANDLE,
			'src'    => get_theme_file_uri( 'assets/styles/carousel-block.css' ),
			'path'   => get_theme_file_path( 'assets/styles/carousel-block.css' ),
			'ver'    => \Kwv\asset_version( 'assets/styles/carousel-block.css' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_assets' );

/**
 * Load the hero slideshow's reduced-motion script, and only for that carousel.
 *
 * The plugin has no reduced-motion handling, so the autoplaying home hero needs a
 * few lines of JS (see assets/js/hero-slider.js). The three brand carousels don't
 * autoplay, so this is gated on the hero's own `cb-hero-slider` class rather than
 * attached to every `cb/carousel-v2` on the site.
 *
 * @param string $block_content Rendered block HTML.
 * @return string Unchanged block HTML.
 */
function enqueue_hero_script( $block_content ) {

	if ( false === strpos( (string) $block_content, 'cb-hero-slider' ) ) {
		return $block_content;
	}

	wp_enqueue_script(
		SCRIPT_HANDLE,
		get_theme_file_uri( 'assets/js/hero-slider.js' ),
		array(),
		\Kwv\asset_version( 'assets/js/hero-slider.js' ),
		true
	);

	return $block_content;
}
add_filter( 'render_block_cb/carousel-v2', __NAMESPACE__ . '\enqueue_hero_script' );

/**
 * Load the brand-marquee script, and only for the homepage brand rows.
 *
 * The three "Wine / Spirits / Agency Brands" carousels drift continuously and
 * counter-scroll, neither of which the plugin can express. Gated on the block's
 * own `kwv-brand-marquee` class, the same way the hero script is gated, so no
 * other carousel on the site pays for it. See assets/js/brand-marquee.js.
 *
 * @param string $block_content Rendered block HTML.
 * @return string Unchanged block HTML.
 */
function enqueue_marquee_script( $block_content ) {

	if ( false === strpos( (string) $block_content, 'kwv-brand-marquee' ) ) {
		return $block_content;
	}

	wp_enqueue_script(
		MARQUEE_HANDLE,
		get_theme_file_uri( 'assets/js/brand-marquee.js' ),
		// The plugin's own Swiper bundle; depending on it both orders the two
		// scripts and drops ours entirely if the plugin is ever deactivated.
		array( 'cb-slider-script' ),
		\Kwv\asset_version( 'assets/js/brand-marquee.js' ),
		true
	);

	return $block_content;
}
add_filter( 'render_block_cb/carousel-v2', __NAMESPACE__ . '\enqueue_marquee_script' );
