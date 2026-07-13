<?php
/**
 * WooCommerce specific functions for the KWV theme.
 *
 * @package kwv
 * @author  LightSpeed
 * @license GNU General Public License v2 or later
 * @link    https://kwv.co.za
 */

namespace Kwv;

/**
 * Check once if WooCommerce is active, then register the appropriate hooks.
 */
if ( class_exists( 'WooCommerce' ) ) {

	// WooCommerce is active.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_woocommerce_styles' );
	add_action( 'init', __NAMESPACE__ . '\register_mini_cart_block_styles', 20 );
	add_action( 'init', __NAMESPACE__ . '\unregister_woocommerce_block_patterns', 999 );
	add_filter( 'woocommerce_admin_features', __NAMESPACE__ . '\disable_pattern_toolkit' );
	add_filter( 'loop_shop_per_page', __NAMESPACE__ . '\shop_products_per_page' );

} else {

	// WooCommerce is not active.
	add_filter( 'get_block_templates', __NAMESPACE__ . '\filter_woocommerce_templates', 10, 3 );
	add_action( 'init', __NAMESPACE__ . '\unregister_kwv_woocommerce_patterns', 999 );

}


/**
 * Register KWV block styles on the Mini Cart block.
 *
 * The site ships a light and a dark header, and the Mini Cart quantity badge
 * needs opposite colours in each so the product count stays legible:
 *   - `mini-cart-light` (light header) → contrast (dark) badge, base (light) count.
 *   - `mini-cart-dark`  (dark header)  → base (light) badge, contrast (dark) count.
 *
 * The colours themselves live in `.is-style-*` rules in
 * `assets/styles/woocommerce.css`; `className` is folded onto the block's
 * `.wc-block-mini-cart` wrapper on the front end, so those rules resolve.
 *
 * Registered at priority 20 so WooCommerce has registered the Mini Cart block
 * type first (needed for the styles to appear in the editor).
 */
function register_mini_cart_block_styles() {

	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	$styles = array(
		'mini-cart-light' => __( 'Light Header', 'kwv' ),
		'mini-cart-dark'  => __( 'Dark Header', 'kwv' ),
	);

	foreach ( $styles as $name => $label ) {
		register_block_style(
			'woocommerce/mini-cart',
			array(
				'name'  => $name,
				'label' => $label,
			)
		);
	}
}


/**
 * Enqueue WooCommerce specific stylesheet.
 */
function enqueue_woocommerce_styles() {
	wp_enqueue_style(
		'theme-woocommerce-style',
		get_template_directory_uri() . '/assets/styles/woocommerce.css',
		array(),
		'1.0.0'
	);

	// My Account styling — only on account pages (dashboard, endpoints, and the
	// logged-out login / register / lost-password screens). Split out of the
	// shared sheet above; depends on it so it always cascades after.
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		wp_enqueue_style(
			'theme-woocommerce-account-style',
			get_template_directory_uri() . '/assets/styles/woocommerce-account.css',
			array( 'theme-woocommerce-style' ),
			'1.0.0'
		);
	}

	// Collapsible Product Filters sidebar — only where the filters render.
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		wp_enqueue_script(
			'theme-shop-filters',
			get_template_directory_uri() . '/assets/js/shop-filters.js',
			array(),
			'1.0.0',
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}

	// Relocate the Payflex widget beneath the product description — single products only.
	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_script(
			'theme-payflex-relocate',
			get_template_directory_uri() . '/assets/js/payflex-relocate.js',
			array(),
			'1.0.0',
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}


/**
 * Products per shop / archive page.
 *
 * The Product Collection on the archive inherits the main query, so its count
 * comes from this filter (not the block's `perPage`). 15 = 5 full rows on the
 * 3-wide grid; WooCommerce's default 16 leaves an orphan on the last row.
 */
function shop_products_per_page() {
	return 15;
}


/**
 * Filter out WooCommerce templates when WooCommerce is not active.
 */
function filter_woocommerce_templates( $query_result, $query, $template_type ) {

	// Only filter templates, not template parts.
	if ( 'wp_template' !== $template_type ) {
		return $query_result;
	}

	$woo_templates = array(
		'archive-product',
		'coming-soon',
		'order-confirmation',
		'page-cart',
		'page-checkout',
		'product-search-results',
		'single-product',
	);

	return array_filter(
		$query_result,
		function ( $template ) use ( $woo_templates ) {
			return ! in_array( $template->slug, $woo_templates, true );
		}
	);
}


/**
 * Unregister theme WooCommerce patterns when WooCommerce is not active.
 */
function unregister_kwv_woocommerce_patterns() {
	$registry = \WP_Block_Patterns_Registry::get_instance();
	$patterns = $registry->get_all_registered();

	foreach ( $patterns as $pattern ) {
		if ( strpos( $pattern['name'], 'kwv/woo-' ) === 0 ) {
			unregister_block_pattern( $pattern['name'] );
		}
	}
}


/**
 * Unregister WooCommerce's default block patterns.
 */
function unregister_woocommerce_block_patterns() {
	$all_patterns = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();

	foreach ( $all_patterns as $pattern ) {
		if ( isset( $pattern['name'] ) && strpos( $pattern['name'], 'woocommerce-blocks' ) === 0 ) {
			unregister_block_pattern( $pattern['name'] );
		}
	}
}


/**
 * Disables the WooCommerce Pattern Toolkit Full Composability feature.
 */
function disable_pattern_toolkit( $features ) {
	$key = array_search( 'pattern-toolkit-full-composability', $features );

	if ( false !== $key ) {
		unset( $features[ $key ] );
	}

	return $features;
}
