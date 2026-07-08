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

const STYLE_HANDLE = 'kwv-carousel-block';

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
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_assets' );
