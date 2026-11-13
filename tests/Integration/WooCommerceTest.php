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

	/**
	 * Patterns registered before the test, restored afterwards: the unregister
	 * tests delete the theme's real kwv/woo-* patterns, which the smoke tests
	 * need when the suite runs in random order.
	 *
	 * @var array<int,array<string,mixed>>
	 */
	private $patterns = array();

	public function set_up(): void {
		parent::set_up();
		$this->patterns = \WP_Block_Patterns_Registry::get_instance()->get_all_registered();

		// WooCommerce's installer leaves these pointing at pages the test library
		// later deletes. On a fresh database the next post can reuse one of those
		// IDs, so a test's "account page" would also count as the checkout page.
		// Each test sets the one it needs (rolled back with the transaction).
		foreach ( array( 'woocommerce_cart_page_id', 'woocommerce_checkout_page_id', 'woocommerce_myaccount_page_id' ) as $option ) {
			update_option( $option, 0 );
		}
	}

	public function tear_down(): void {
		// Page-type switches a test turned on must not leak into the next one.
		remove_filter( 'woocommerce_is_cart', '__return_true' );
		remove_filter( 'woocommerce_is_checkout', '__return_true' );
		unset( $GLOBALS['wp']->query_vars['order-received'] );

		$registry = \WP_Block_Patterns_Registry::get_instance();
		foreach ( $registry->get_all_registered() as $pattern ) {
			unregister_block_pattern( $pattern['name'] );
		}
		foreach ( $this->patterns as $pattern ) {
			$name = $pattern['name'];
			unset( $pattern['name'] );
			register_block_pattern( $name, $pattern );
		}
		parent::tear_down();
	}

	private function assertStyle( string $handle, bool $expected, string $message = '' ): void {
		$this->assertSame( $expected, $this->isQueued( 'style', $handle ), '' !== $message ? $message : $handle );
	}

	private function assertScript( string $handle, bool $expected, string $message = '' ): void {
		$this->assertSame( $expected, $this->isQueued( 'script', $handle ), '' !== $message ? $message : $handle );
	}

	/**
	 * Assert a handle's exact source file, dependencies and cache-busting version.
	 *
	 * @param string   $kind   'script' or 'style'.
	 * @param string   $handle Handle.
	 * @param string   $file   Path relative to the theme root.
	 * @param string[] $deps   Expected dependencies.
	 */
	private function assertAsset( string $kind, string $handle, string $file, array $deps ): void {
		$registry = 'script' === $kind ? wp_scripts() : wp_styles();
		$asset    = $registry->registered[ $handle ];

		$this->assertSame( get_template_directory_uri() . '/' . $file, $asset->src, $handle . ' source' );
		$this->assertSame( $deps, $asset->deps, $handle . ' dependencies' );
		$this->assertSame( \Kwv\asset_version( $file ), $asset->ver, $handle . ' version' );

		if ( 'script' === $kind ) {
			$this->assertSame( 'defer', wp_scripts()->get_data( $handle, 'strategy' ), $handle . ' strategy' );
			$this->assertSame( 1, wp_scripts()->get_data( $handle, 'group' ), $handle . ' loads in the footer' );
		}
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

	public function test_a_product_tag_archive_gets_the_filter_assets(): void {
		$tag = self::factory()->term->create( array( 'taxonomy' => 'product_tag' ) );
		$this->go_to( get_term_link( $tag, 'product_tag' ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-shop-filters-style', true );
		$this->assertScript( 'theme-shop-filters', true );
	}

	public function test_the_filter_script_loads_deferred_in_the_footer_after_the_shared_sheet(): void {
		$this->go_to( get_post_type_archive_link( 'product' ) );
		$this->runEnqueue();

		$this->assertSame( 'defer', wp_scripts()->get_data( 'theme-shop-filters', 'strategy' ) );
		$this->assertSame( 1, wp_scripts()->get_data( 'theme-shop-filters', 'group' ) );
		$this->assertSame( array( 'theme-woocommerce-style' ), wp_styles()->registered['theme-woocommerce-shop-filters-style']->deps );
	}

	public function test_every_split_stylesheet_depends_on_the_shared_sheet_and_is_versioned_by_mtime(): void {
		$this->go_to( get_permalink( $this->makeProduct() ) );
		$this->runEnqueue();

		$style = wp_styles()->registered['theme-woocommerce-single-product-style'];
		$this->assertSame( array( 'theme-woocommerce-style' ), $style->deps );
		$this->assertSame( \Kwv\asset_version( 'assets/styles/woocommerce-single-product.css' ), $style->ver );
		$this->assertStringContainsString( '/assets/styles/woocommerce-single-product.css', $style->src );
		$this->assertSame( \Kwv\asset_version( 'assets/styles/woocommerce.css' ), wp_styles()->registered['theme-woocommerce-style']->ver );
		$this->assertSame( 'defer', wp_scripts()->get_data( 'theme-payflex-relocate', 'strategy' ) );
	}

	public function test_the_account_and_cart_sheets_depend_on_the_shared_sheet(): void {
		$id = $this->makePageFor( 'woocommerce_myaccount_page_id' );
		$this->go_to( get_permalink( $id ) );
		add_filter( 'woocommerce_is_cart', '__return_true' );
		$this->runEnqueue();
		remove_filter( 'woocommerce_is_cart', '__return_true' );

		$this->assertSame( array( 'theme-woocommerce-style' ), wp_styles()->registered['theme-woocommerce-account-style']->deps );
		$this->assertSame( array( 'theme-woocommerce-style' ), wp_styles()->registered['theme-woocommerce-cart-checkout-style']->deps );
	}

	public function test_the_order_confirmation_sheet_needs_the_order_received_endpoint(): void {
		$id = $this->makePageFor( 'woocommerce_checkout_page_id' );
		$this->go_to( get_permalink( $id ) );
		$this->runEnqueue();

		$this->assertStyle( 'theme-woocommerce-order-confirmation-style', false, 'The checkout page alone is not the confirmation page.' );
	}

	public function test_a_blog_page_loads_none_of_the_page_specific_assets(): void {
		$post = self::factory()->post->create();
		$this->go_to( get_permalink( $post ) );
		$this->runEnqueue();

		foreach ( array( 'theme-woocommerce-account-style', 'theme-woocommerce-single-product-style', 'theme-woocommerce-shop-filters-style', 'theme-woocommerce-cart-checkout-style', 'theme-woocommerce-order-confirmation-style' ) as $handle ) {
			$this->assertStyle( $handle, false );
		}
		$this->assertScript( 'theme-payflex-relocate', false );
		$this->assertScript( 'theme-shop-filters', false );
	}

	public function test_a_toolkit_feature_at_index_zero_is_still_removed(): void {
		$this->assertSame(
			array( 1 => 'analytics' ),
			\Kwv\disable_pattern_toolkit( array( 'pattern-toolkit-full-composability', 'analytics' ) )
		);
	}

	public function test_a_toolkit_feature_listed_twice_loses_only_the_first_entry(): void {
		$result = \Kwv\disable_pattern_toolkit( array( 'a', 'pattern-toolkit-full-composability', 'pattern-toolkit-full-composability' ) );

		$this->assertSame(
			array(
				0 => 'a',
				2 => 'pattern-toolkit-full-composability',
			),
			$result
		);
	}

	public function test_an_empty_feature_list_stays_empty(): void {
		$this->assertSame( array(), \Kwv\disable_pattern_toolkit( array() ) );
	}

	public function test_template_filtering_keeps_other_slugs_that_merely_look_similar(): void {
		$slugs     = array( 'single-product-extra', 'page-cart-old', 'archive-product', 'my-coming-soon', 'coming-soon', 'Single-Product' );
		$templates = array_map( static fn( $slug ) => (object) array( 'slug' => $slug ), $slugs );

		$result = \Kwv\filter_woocommerce_templates( $templates, array(), 'wp_template' );

		$this->assertSame(
			array( 'single-product-extra', 'page-cart-old', 'my-coming-soon', 'Single-Product' ),
			array_values( array_map( static fn( $t ) => $t->slug, $result ) )
		);
	}

	public function test_template_filtering_of_an_empty_list_is_empty(): void {
		$this->assertSame( array(), \Kwv\filter_woocommerce_templates( array(), array(), 'wp_template' ) );
	}

	/**
	 * @return array<string,array{0:string}>
	 */
	public function nonTemplateTypes(): array {
		return array(
			'template part' => array( 'wp_template_part' ),
			'empty'         => array( '' ),
			'navigation'    => array( 'wp_navigation' ),
		);
	}

	/**
	 * @dataProvider nonTemplateTypes
	 */
	public function test_only_the_wp_template_type_is_filtered( string $type ): void {
		$list = array( (object) array( 'slug' => 'page-cart' ) );

		$this->assertSame( $list, \Kwv\filter_woocommerce_templates( $list, array(), $type ) );
	}

	public function test_woocommerce_block_pattern_removal_matches_the_prefix_only(): void {
		$content = '<!-- wp:paragraph --><p>x</p><!-- /wp:paragraph -->';
		foreach ( array( 'woocommerce-blocks/a', 'woocommerce-blocks-extra/b', 'not-woocommerce-blocks/c', 'kwv/woocommerce-blocks-d', 'woocommerce/e' ) as $name ) {
			register_block_pattern(
				$name,
				array(
					'title'   => $name,
					'content' => $content,
				)
			);
		}

		\Kwv\unregister_woocommerce_block_patterns();

		$registry = \WP_Block_Patterns_Registry::get_instance();
		$this->assertFalse( $registry->is_registered( 'woocommerce-blocks/a' ) );
		$this->assertFalse( $registry->is_registered( 'woocommerce-blocks-extra/b' ) );
		$this->assertTrue( $registry->is_registered( 'not-woocommerce-blocks/c' ) );
		$this->assertTrue( $registry->is_registered( 'kwv/woocommerce-blocks-d' ) );
		$this->assertTrue( $registry->is_registered( 'woocommerce/e' ) );
	}

	public function test_theme_woo_pattern_removal_matches_the_prefix_only(): void {
		$content = '<!-- wp:paragraph --><p>x</p><!-- /wp:paragraph -->';
		foreach ( array( 'kwv/woo-a', 'kwv/woo-brand-archive', 'kwv/woocommerce', 'kwv/woo', 'other/kwv/woo-b', 'kwv/hero' ) as $name ) {
			register_block_pattern(
				$name,
				array(
					'title'   => $name,
					'content' => $content,
				)
			);
		}

		\Kwv\unregister_kwv_woocommerce_patterns();

		$registry = \WP_Block_Patterns_Registry::get_instance();
		$this->assertFalse( $registry->is_registered( 'kwv/woo-a' ) );
		$this->assertFalse( $registry->is_registered( 'kwv/woo-brand-archive' ) );
		foreach ( array( 'kwv/woocommerce', 'kwv/woo', 'other/kwv/woo-b', 'kwv/hero' ) as $kept ) {
			$this->assertTrue( $registry->is_registered( $kept ), $kept );
		}
	}

	public function test_mini_cart_styles_have_their_labels(): void {
		\Kwv\register_mini_cart_block_styles();

		$styles = \WP_Block_Styles_Registry::get_instance()->get_registered_styles_for_block( 'woocommerce/mini-cart' );

		$this->assertSame( 'Light Header', $styles['mini-cart-light']['label'] );
		$this->assertSame( 'Dark Header', $styles['mini-cart-dark']['label'] );
	}

	public function test_the_product_grid_style_is_registered_for_the_product_template_block_only(): void {
		$this->resetAssets();
		\Kwv\enqueue_product_grid_block_style();

		$this->applyRenderBlock( '', array( 'blockName' => 'woocommerce/product-collection' ) );
		$this->assertFalse( $this->isQueued( 'style', 'theme-woocommerce-product-grid-style' ) );

		$this->applyRenderBlock( '', array( 'blockName' => 'core/group' ) );
		$this->assertFalse( $this->isQueued( 'style', 'theme-woocommerce-product-grid-style' ) );
	}

	public function test_shop_per_page_ignores_the_incoming_value(): void {
		$this->assertSame( 15, \Kwv\shop_products_per_page( 3 ) );
		$this->assertSame( 15, apply_filters( 'loop_shop_per_page', 100 ) );
	}

	public function test_the_shared_stylesheet_has_the_exact_source_and_no_dependencies(): void {
		$this->go_to( home_url( '/' ) );
		$this->runEnqueue();

		$this->assertAsset( 'style', 'theme-woocommerce-style', 'assets/styles/woocommerce.css', array() );
	}

	public function test_the_product_page_assets_have_exact_sources_and_dependencies(): void {
		$this->go_to( get_permalink( $this->makeProduct() ) );
		$this->runEnqueue();

		$this->assertAsset( 'style', 'theme-woocommerce-single-product-style', 'assets/styles/woocommerce-single-product.css', array( 'theme-woocommerce-style' ) );
		$this->assertAsset( 'script', 'theme-payflex-relocate', 'assets/js/payflex-relocate.js', array() );
	}

	public function test_the_shop_filter_assets_have_exact_sources_and_dependencies(): void {
		$this->go_to( get_post_type_archive_link( 'product' ) );
		$this->runEnqueue();

		$this->assertAsset( 'style', 'theme-woocommerce-shop-filters-style', 'assets/styles/woocommerce-shop-filters.css', array( 'theme-woocommerce-style' ) );
		$this->assertAsset( 'script', 'theme-shop-filters', 'assets/js/shop-filters.js', array() );
	}

	public function test_the_account_stylesheet_has_the_exact_source_and_dependency(): void {
		$this->go_to( get_permalink( $this->makePageFor( 'woocommerce_myaccount_page_id' ) ) );
		$this->runEnqueue();

		$this->assertAsset( 'style', 'theme-woocommerce-account-style', 'assets/styles/woocommerce-account.css', array( 'theme-woocommerce-style' ) );
	}

	public function test_the_cart_checkout_stylesheet_has_the_exact_source_and_dependency(): void {
		add_filter( 'woocommerce_is_cart', '__return_true' );
		$this->go_to( home_url( '/' ) );
		$this->runEnqueue();

		$this->assertAsset( 'style', 'theme-woocommerce-cart-checkout-style', 'assets/styles/woocommerce-cart-checkout.css', array( 'theme-woocommerce-style' ) );
	}

	public function test_the_order_confirmation_stylesheet_has_the_exact_source_and_dependency(): void {
		$this->go_to( get_permalink( $this->makePageFor( 'woocommerce_checkout_page_id' ) ) );
		$GLOBALS['wp']->query_vars['order-received'] = '123';
		$this->runEnqueue();

		$this->assertAsset( 'style', 'theme-woocommerce-order-confirmation-style', 'assets/styles/woocommerce-order-confirmation.css', array( 'theme-woocommerce-style' ) );
	}

	public function test_the_product_grid_stylesheet_has_the_exact_source_and_version(): void {
		$this->resetAssets();
		\Kwv\enqueue_product_grid_block_style();
		$this->applyRenderBlock( '', array( 'blockName' => 'woocommerce/product-template' ) );
		do_action( 'wp_enqueue_scripts' );

		$style = wp_styles()->registered['theme-woocommerce-product-grid-style'];
		$this->assertSame( get_theme_file_uri( 'assets/styles/woocommerce-product-grid.css' ), $style->src );
		$this->assertSame( \Kwv\asset_version( 'assets/styles/woocommerce-product-grid.css' ), $style->ver );
		$this->assertSame( array( 'theme-woocommerce-style' ), $style->deps );
	}
}
