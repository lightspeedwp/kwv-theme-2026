<?php
/**
 * Integration tests for inc/woocommerce.php: asset gating per page type.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @covers ::Kwv\enqueue_woocommerce_styles
 * @covers ::Kwv\enqueue_product_grid_block_style
 * @covers ::Kwv\register_mini_cart_block_styles
 * @covers ::Kwv\unregister_woocommerce_block_patterns
 * @covers ::Kwv\unregister_kwv_woocommerce_patterns
 * @covers ::Kwv\filter_woocommerce_templates
 * @covers ::Kwv\shop_products_per_page
 * @covers ::Kwv\disable_pattern_toolkit
 */
final class WooCommerceTest extends TestCase {

	private const SHOP_FILTERS = array( 'theme-woocommerce-shop-filters-style', 'theme-shop-filters' );

	private function assertStyle( string $handle, bool $expected, string $message = '' ): void {
		$this->assertSame( $expected, $this->isQueued( 'style', $handle ), '' !== $message ? $message : $handle );
	}

	private function assertScript( string $handle, bool $expected, string $message = '' ): void {
		$this->assertSame( $expected, $this->isQueued( 'script', $handle ), '' !== $message ? $message : $handle );
	}

	private function makePageFor( string $option ): int {
		$id = self::factory()->post->create(
			array(
				'post_type'  => 'page',
				'post_title' => $option,
			)
		);
		update_option( $option, $id );

		return $id;
	}

	public function test_the_shared_stylesheet_loads_on_every_page(): void {
		$this->go_to( home_url( '/' ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-style', true );
		$this->assertStyle( 'theme-woocommerce-account-style', false );
		$this->assertStyle( 'theme-woocommerce-single-product-style', false );
		$this->assertStyle( 'theme-woocommerce-cart-checkout-style', false );
		$this->assertStyle( 'theme-woocommerce-order-confirmation-style', false );
		$this->assertScript( 'theme-payflex-relocate', false );
		foreach ( array( 'theme-woocommerce-shop-filters-style' ) as $handle ) {
			$this->assertStyle( $handle, false );
		}
		$this->assertScript( 'theme-shop-filters', false );
	}

	public function test_a_product_page_gets_the_single_product_assets_and_payflex_script(): void {
		$this->go_to( get_permalink( $this->makeProduct() ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-single-product-style', true );
		$this->assertScript( 'theme-payflex-relocate', true );
		$this->assertStyle( 'theme-woocommerce-shop-filters-style', false );
		$this->assertStyle( 'theme-woocommerce-cart-checkout-style', false );
	}

	public function test_the_shop_gets_the_filter_assets(): void {
		$this->go_to( get_post_type_archive_link( 'product' ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-shop-filters-style', true );
		$this->assertScript( 'theme-shop-filters', true );
		$this->assertStyle( 'theme-woocommerce-single-product-style', false );
	}

	public function test_a_category_archive_gets_the_filter_assets(): void {
		$this->go_to( get_term_link( $this->makeTerm( 'product_cat' ) ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-shop-filters-style', true );
		$this->assertScript( 'theme-shop-filters', true );
	}

	public function test_a_brand_page_gets_none_of_the_shop_filter_assets(): void {
		$this->go_to( get_term_link( $this->makeTerm( 'product_brand' ) ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-shop-filters-style', false );
		$this->assertScript( 'theme-shop-filters', false );
		$this->assertStyle( 'theme-woocommerce-style', true, 'The shared sheet still loads.' );
	}

	public function test_the_account_page_gets_the_account_stylesheet(): void {
		$id = $this->makePageFor( 'woocommerce_myaccount_page_id' );
		$this->go_to( get_permalink( $id ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-account-style', true );
		$this->assertStyle( 'theme-woocommerce-cart-checkout-style', false );
	}

	public function test_the_cart_and_checkout_pages_get_the_cart_checkout_stylesheet(): void {
		foreach ( array( 'woocommerce_is_cart', 'woocommerce_is_checkout' ) as $filter ) {
			add_filter( $filter, '__return_true' );
			$this->go_to( home_url( '/' ) );
			$this->runEnqueue();

			$this->assertStyle( 'theme-woocommerce-cart-checkout-style', true, $filter );
			$this->assertStyle( 'theme-woocommerce-account-style', false, $filter );
			remove_filter( $filter, '__return_true' );
		}
	}

	public function test_the_order_received_endpoint_gets_the_confirmation_stylesheet(): void {
		$id = $this->makePageFor( 'woocommerce_checkout_page_id' );
		$this->go_to( get_permalink( $id ) );
		$GLOBALS['wp']->query_vars['order-received'] = '123';
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-order-confirmation-style', true );
	}

	public function test_the_product_grid_stylesheet_is_tied_to_the_product_template_block(): void {
		$this->resetAssets();
		\Kwv\enqueue_product_grid_block_style();

		$this->assertSame( array(), wp_styles()->queue, 'Nothing loads until the block renders.' );

		// Simulate the block rendering (on-demand loading) or the enqueue hooks (classic loading).
		$this->applyRenderBlock( '', array( 'blockName' => 'woocommerce/product-template' ) );
		do_action( 'wp_enqueue_scripts' );

		$this->assertTrue( $this->isQueued( 'style', 'theme-woocommerce-product-grid-style' ) );
		$this->assertContains( 'theme-woocommerce-style', wp_styles()->registered['theme-woocommerce-product-grid-style']->deps );
	}

	public function test_the_hooks_for_an_active_woocommerce_are_registered(): void {
		$this->assertNotFalse( has_action( 'wp_enqueue_scripts', 'Kwv\enqueue_woocommerce_styles' ) );
		$this->assertNotFalse( has_filter( 'loop_shop_per_page', 'Kwv\shop_products_per_page' ) );
		$this->assertNotFalse( has_filter( 'woocommerce_admin_features', 'Kwv\disable_pattern_toolkit' ) );
		$this->assertFalse( has_filter( 'get_block_templates', 'Kwv\filter_woocommerce_templates' ), 'The inactive-WooCommerce filter is not registered.' );
	}

	public function test_the_shop_per_page_count_is_fifteen_outside_brand_pages(): void {
		$this->go_to( get_post_type_archive_link( 'product' ) );

		$this->assertSame( 15, apply_filters( 'loop_shop_per_page', 16 ) );
	}

	public function test_mini_cart_block_styles_are_registered_once_the_block_exists(): void {
		\Kwv\register_mini_cart_block_styles();

		$styles = \WP_Block_Styles_Registry::get_instance()->get_registered_styles_for_block( 'woocommerce/mini-cart' );

		$this->assertArrayHasKey( 'mini-cart-light', $styles );
		$this->assertArrayHasKey( 'mini-cart-dark', $styles );
	}

	public function test_woocommerce_template_slugs_are_filtered_when_woocommerce_is_inactive(): void {
		$slugs     = array( 'archive-product', 'coming-soon', 'order-confirmation', 'page-cart', 'page-checkout', 'product-search-results', 'single-product', 'index', 'page' );
		$templates = array_map( static fn( $slug ) => (object) array( 'slug' => $slug ), $slugs );

		$result = \Kwv\filter_woocommerce_templates( $templates, array(), 'wp_template' );

		$this->assertSame( array( 'index', 'page' ), array_values( array_map( static fn( $t ) => $t->slug, $result ) ) );
	}

	public function test_template_parts_are_not_filtered_when_woocommerce_is_inactive(): void {
		$parts = array( (object) array( 'slug' => 'single-product' ) );

		$this->assertSame( $parts, \Kwv\filter_woocommerce_templates( $parts, array(), 'wp_template_part' ) );
	}

	public function test_woocommerce_block_patterns_are_unregistered(): void {
		register_block_pattern(
			'woocommerce-blocks/test-pattern',
			array(
				'title'   => 'Woo test',
				'content' => '<!-- wp:paragraph --><p>x</p><!-- /wp:paragraph -->',
			)
		);

		\Kwv\unregister_woocommerce_block_patterns();

		$this->assertFalse( \WP_Block_Patterns_Registry::get_instance()->is_registered( 'woocommerce-blocks/test-pattern' ) );
	}

	public function test_theme_woo_patterns_are_unregistered_when_woocommerce_is_inactive(): void {
		register_block_pattern(
			'kwv/woo-test',
			array(
				'title'   => 'Test',
				'content' => '<!-- wp:paragraph --><p>x</p><!-- /wp:paragraph -->',
			)
		);
		register_block_pattern(
			'kwv/not-woo',
			array(
				'title'   => 'Test',
				'content' => '<!-- wp:paragraph --><p>x</p><!-- /wp:paragraph -->',
			)
		);

		\Kwv\unregister_kwv_woocommerce_patterns();

		$registry = \WP_Block_Patterns_Registry::get_instance();
		$this->assertFalse( $registry->is_registered( 'kwv/woo-test' ) );
		$this->assertTrue( $registry->is_registered( 'kwv/not-woo' ) );
	}

	public function test_pattern_toolkit_is_removed_from_the_admin_features(): void {
		$features = apply_filters( 'woocommerce_admin_features', array( 'pattern-toolkit-full-composability', 'analytics' ) );

		$this->assertNotContains( 'pattern-toolkit-full-composability', $features );
		$this->assertContains( 'analytics', $features );
	}
}
