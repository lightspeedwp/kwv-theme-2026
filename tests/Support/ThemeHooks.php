<?php
/**
 * The hooks the theme registers at load time, as a table shared by the unit
 * and integration suites.
 *
 * Priorities and accepted-argument counts are behaviour: a filter that runs at
 * 10 instead of 20 can lose to a plugin's rule, and a callback that accepts one
 * argument instead of two never sees the block.
 *
 * @package kwv
 */

namespace Kwv\Tests\Support;

/**
 * Table of load-time hook registrations, grouped by the file that makes them.
 */
final class ThemeHooks {

	/**
	 * Hooks registered by every file regardless of WooCommerce.
	 *
	 * Each row: type, hook, callback, priority, accepted args.
	 *
	 * @return array<string,array<int,array{0:string,1:string,2:string,3:int,4:int}>>
	 */
	public static function byFile(): array {
		return array(
			'functions.php'            => array(
				array( 'action', 'after_setup_theme', 'Kwv\setup', 10, 1 ),
				array( 'action', 'wp_enqueue_scripts', 'Kwv\enqueue_style_sheet', 10, 1 ),
				array( 'action', 'init', 'Kwv\register_block_styles', 10, 1 ),
				array( 'action', 'init', 'Kwv\enqueue_custom_block_styles', 10, 1 ),
				array( 'action', 'init', 'Kwv\pattern_categories', 9, 1 ),
				array( 'action', 'wp_head', 'Kwv\is_paginated', 10, 1 ),
			),
			'inc/age-gate.php'         => array(
				array( 'action', 'wp_enqueue_scripts', 'Kwv\AgeGate\enqueue_style', 20, 1 ),
			),
			'inc/brand-page.php'       => array(
				array( 'filter', 'loop_shop_per_page', 'Kwv\BrandPage\products_per_page', 20, 1 ),
				array( 'action', 'wp_head', 'Kwv\BrandPage\print_js_flag', 1, 1 ),
				array( 'action', 'wp_enqueue_scripts', 'Kwv\BrandPage\enqueue_assets', 20, 1 ),
				array( 'filter', 'render_block_woocommerce/product-summary', 'Kwv\BrandPage\clean_product_summary', 10, 1 ),
			),
			'inc/carousel.php'         => array(
				array( 'action', 'init', 'Kwv\Carousel\register_assets', 10, 1 ),
				array( 'filter', 'render_block_cb/carousel-v2', 'Kwv\Carousel\enqueue_hero_script', 10, 1 ),
				array( 'filter', 'render_block_cb/carousel-v2', 'Kwv\Carousel\enqueue_marquee_script', 10, 1 ),
				array( 'filter', 'render_block_cb/carousel-v2', 'Kwv\Carousel\enqueue_timeline_script', 10, 1 ),
			),
			'inc/gravity-forms.php'    => array(
				array( 'action', 'init', 'Kwv\GravityForms\enqueue_block_style', 20, 1 ),
			),
			'inc/hide-empty-query.php' => array(
				array( 'filter', 'render_block_core/group', 'Kwv\HideEmptyQuery\hide_empty_query_group', 10, 2 ),
			),
			'inc/lazy-video.php'       => array(
				array( 'filter', 'render_block_core/cover', 'Kwv\LazyVideo\defer_cover_video', 10, 2 ),
			),
			'inc/mega-menu.php'        => array(
				array( 'action', 'init', 'Kwv\MegaMenu\register_assets', 10, 1 ),
				array( 'filter', 'render_block_ollie/mega-menu', 'Kwv\MegaMenu\enqueue_nav_scripts', 10, 1 ),
				array( 'action', 'init', 'Kwv\MegaMenu\register_shortcode', 10, 1 ),
				array( 'action', 'init', 'Kwv\MegaMenu\register_search_style', 10, 1 ),
			),
			'inc/mobile-menu.php'      => array(
				array( 'filter', 'render_block', 'Kwv\MobileMenu\unpin_close_icon_colour', 20, 2 ),
				array( 'action', 'wp_enqueue_scripts', 'Kwv\MobileMenu\enqueue_assets', 20, 1 ),
			),
			'inc/search.php'           => array(
				array( 'action', 'init', 'Kwv\Search\register_block_styles', 20, 1 ),
				array( 'filter', 'render_block', 'Kwv\Search\apply_block_class', 20, 2 ),
				array( 'action', 'wp_enqueue_scripts', 'Kwv\Search\enqueue_assets', 20, 1 ),
			),
			'inc/term-banner.php'      => array(
				array( 'action', 'init', 'Kwv\register_banner_meta', 10, 1 ),
				array( 'action', 'admin_init', 'Kwv\register_banner_admin_hooks', 10, 1 ),
				array( 'action', 'admin_enqueue_scripts', 'Kwv\enqueue_banner_admin_assets', 10, 1 ),
				array( 'action', 'init', 'Kwv\register_term_banner_binding', 10, 1 ),
			),
			'inc/user-job-title.php'   => array(
				array( 'action', 'init', 'Kwv\register_job_title_meta', 10, 1 ),
				array( 'action', 'show_user_profile', 'Kwv\render_job_title_field', 10, 1 ),
				array( 'action', 'edit_user_profile', 'Kwv\render_job_title_field', 10, 1 ),
				array( 'action', 'personal_options_update', 'Kwv\save_job_title_field', 10, 1 ),
				array( 'action', 'edit_user_profile_update', 'Kwv\save_job_title_field', 10, 1 ),
				array( 'action', 'init', 'Kwv\register_author_role_binding', 10, 1 ),
			),
		);
	}

	/**
	 * Hooks inc/woocommerce.php registers when WooCommerce is active.
	 *
	 * @return array<int,array{0:string,1:string,2:string,3:int,4:int}>
	 */
	public static function whenWooCommerceActive(): array {
		return array(
			array( 'action', 'wp_enqueue_scripts', 'Kwv\enqueue_woocommerce_styles', 10, 1 ),
			array( 'action', 'init', 'Kwv\enqueue_product_grid_block_style', 10, 1 ),
			array( 'action', 'init', 'Kwv\register_mini_cart_block_styles', 20, 1 ),
			array( 'action', 'init', 'Kwv\unregister_woocommerce_block_patterns', 999, 1 ),
			array( 'filter', 'woocommerce_admin_features', 'Kwv\disable_pattern_toolkit', 10, 1 ),
			array( 'filter', 'loop_shop_per_page', 'Kwv\shop_products_per_page', 10, 1 ),
		);
	}

	/**
	 * Hooks inc/woocommerce.php registers when WooCommerce is not active.
	 *
	 * @return array<int,array{0:string,1:string,2:string,3:int,4:int}>
	 */
	public static function whenWooCommerceInactive(): array {
		return array(
			array( 'filter', 'get_block_templates', 'Kwv\filter_woocommerce_templates', 10, 3 ),
			array( 'action', 'init', 'Kwv\unregister_kwv_woocommerce_patterns', 999, 1 ),
		);
	}

	/**
	 * Every row, for the integration suite (where WooCommerce is active).
	 *
	 * @return array<string,array{0:string,1:string,2:string,3:int,4:int}>
	 */
	public static function allActive(): array {
		$rows = array();
		foreach ( self::byFile() as $file_rows ) {
			foreach ( $file_rows as $row ) {
				$rows[ $row[1] . ' -> ' . $row[2] ] = $row;
			}
		}
		foreach ( self::whenWooCommerceActive() as $row ) {
			$rows[ $row[1] . ' -> ' . $row[2] ] = $row;
		}

		return $rows;
	}
}
