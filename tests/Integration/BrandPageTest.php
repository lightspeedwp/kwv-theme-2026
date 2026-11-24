<?php
/**
 * Integration tests for inc/brand-page.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\BrandPage as B;

/**
 * @covers ::Kwv\BrandPage\is_brand_page
 * @covers ::Kwv\BrandPage\products_per_page
 * @covers ::Kwv\BrandPage\print_js_flag
 * @covers ::Kwv\BrandPage\enqueue_assets
 * @covers ::Kwv\BrandPage\clean_product_summary
 * @covers ::Kwv\BrandPage\brand_base
 * @covers ::Kwv\BrandPage\showcase_rule
 * @covers ::Kwv\BrandPage\register_showcase_route
 * @covers ::Kwv\BrandPage\add_query_var
 * @covers ::Kwv\BrandPage\showcase_url
 * @covers ::Kwv\BrandPage\showcase_template_hierarchy
 * @covers ::Kwv\BrandPage\register_showcase_template_type
 * @covers ::Kwv\BrandPage\product_showcase_brand
 * @covers ::Kwv\BrandPage\product_redirect_url
 */
final class BrandPageTest extends TestCase {

	private const SUMMARY = '<div class="wp-block-woocommerce-product-summary has-small-font-size"><div class="wp-block-woocommerce-product-summary__content" style="color:red">'
		. '<table style="width:1876px" width="1876" height="40" align="center" data-start="1" data-end="9"><tbody><tr><td style="text-align:center" data-col="2">Aged</td></tr></tbody></table>'
		. '<p style="margin:0" data-pasted="x" class="keep">Text</p></div></div>';

	/**
	 * Visit a brand's showcase (the slider page).
	 */
	private function goToBrand(): void {
		$brand = $this->makeTerm( 'product_brand', 'Brand' );
		$this->go_to( B\showcase_url( $brand ) );
	}

	/**
	 * A published simple product in the given brands.
	 *
	 * @param \WP_Term[] $brands Brands.
	 * @return int
	 */
	private function makeBrandedProduct( array $brands ): int {
		$id = $this->makeProduct();
		wp_set_object_terms( $id, wp_list_pluck( $brands, 'term_id' ), 'product_brand' );
		return $id;
	}

	public function tear_down(): void {
		unset( $_GET['add-to-cart'], $_SERVER['REQUEST_METHOD'] );
		parent::tear_down();
	}

	private function swiperBundleExists(): bool {
		return is_readable( WP_PLUGIN_DIR . '/' . B\SWIPER_DIR . 'swiper-bundle.min.js' )
			&& is_readable( WP_PLUGIN_DIR . '/' . B\SWIPER_DIR . 'swiper-bundle.min.css' );
	}

	public function test_a_brand_archive_is_a_brand_page(): void {
		$this->goToBrand();
		$this->assertTrue( B\is_brand_page() );
	}

	public function test_the_brand_shop_is_not_the_showcase(): void {
		$this->go_to( get_term_link( $this->makeTerm( 'product_brand' ) ) );

		$this->assertTrue( is_tax( 'product_brand' ) );
		$this->assertFalse( B\is_brand_page() );
		$this->assertSame( 12, apply_filters( 'loop_shop_per_page', 16 ), 'The brand shop pages its grid like the shop.' );
	}

	public function test_a_category_archive_and_the_home_page_are_not_brand_pages(): void {
		$this->go_to( get_term_link( $this->makeTerm( 'product_cat' ) ) );
		$this->assertFalse( B\is_brand_page() );

		$this->go_to( home_url( '/' ) );
		$this->assertFalse( B\is_brand_page() );
	}

	public function test_a_brand_page_shows_the_whole_range_on_one_page(): void {
		$this->goToBrand();

		$this->assertSame( B\MAX_PRODUCTS, apply_filters( 'loop_shop_per_page', 16 ) );
	}

	public function test_other_archives_keep_the_shop_per_page_value(): void {
		$this->go_to( get_term_link( $this->makeTerm( 'product_cat' ) ) );

		$this->assertSame( 12, apply_filters( 'loop_shop_per_page', 16 ) );
	}

	public function test_the_js_flag_is_printed_only_on_brand_pages(): void {
		$this->goToBrand();
		ob_start();
		B\print_js_flag();
		$this->assertStringContainsString( 'kwv-brand-slider-js', ob_get_clean() );

		$this->go_to( home_url( '/' ) );
		ob_start();
		B\print_js_flag();
		$this->assertSame( '', ob_get_clean() );
	}

	public function test_nothing_is_enqueued_off_brand_pages(): void {
		$this->go_to( get_term_link( $this->makeTerm( 'product_cat' ) ) );
		$this->runEnqueue();

		$this->assertFalse( $this->isQueued( 'style', B\STYLE_HANDLE ) );
		$this->assertFalse( $this->isQueued( 'script', B\SCRIPT_HANDLE ) );
		$this->assertFalse( $this->isQueued( 'script', B\SWIPER_HANDLE ) );
	}

	public function test_the_page_stylesheet_is_enqueued_on_a_brand_page(): void {
		$this->goToBrand();
		$this->runEnqueue();

		$this->assertTrue( $this->isQueued( 'style', B\STYLE_HANDLE ) );
	}

	public function test_swiper_and_the_slider_script_load_only_when_the_bundle_exists(): void {
		$this->goToBrand();
		$this->runEnqueue();

		if ( $this->swiperBundleExists() ) {
			$this->assertTrue( $this->isQueued( 'script', B\SWIPER_HANDLE ) );
			$this->assertTrue( $this->isQueued( 'style', B\SWIPER_HANDLE ) );
			$this->assertTrue( $this->isQueued( 'script', B\SCRIPT_HANDLE ) );
			$this->assertContains( B\SWIPER_HANDLE, wp_scripts()->registered[ B\SCRIPT_HANDLE ]->deps );
			$this->assertContains( B\SWIPER_HANDLE, wp_styles()->registered[ B\STYLE_HANDLE ]->deps );
			$this->assertStringContainsString( 'kwvBrandSlider', (string) wp_scripts()->get_data( B\SCRIPT_HANDLE, 'data' ) );
		} else {
			$this->assertFalse( $this->isQueued( 'script', B\SWIPER_HANDLE ), 'Without the bundle Swiper is not enqueued.' );
			$this->assertFalse( $this->isQueued( 'script', B\SCRIPT_HANDLE ) );
			$this->assertNotContains( B\SWIPER_HANDLE, wp_styles()->registered[ B\STYLE_HANDLE ]->deps );
		}
	}

	public function test_the_swiper_assets_have_exact_urls_versions_dependencies_and_loading(): void {
		if ( ! $this->swiperBundleExists() ) {
			$this->markTestSkipped( 'The Carousel Block plugin (Swiper bundle) is not installed.' );
		}
		$this->goToBrand();
		$this->runEnqueue();
		$dir = WP_PLUGIN_DIR . '/' . B\SWIPER_DIR;

		$style = wp_styles()->registered[ B\SWIPER_HANDLE ];
		$this->assertSame( plugins_url( B\SWIPER_DIR . 'swiper-bundle.min.css' ), $style->src );
		$this->assertSame( array(), $style->deps );
		$this->assertSame( (string) filemtime( $dir . 'swiper-bundle.min.css' ), $style->ver );

		$swiper = wp_scripts()->registered[ B\SWIPER_HANDLE ];
		$this->assertSame( plugins_url( B\SWIPER_DIR . 'swiper-bundle.min.js' ), $swiper->src );
		$this->assertSame( array(), $swiper->deps );
		$this->assertSame( (string) filemtime( $dir . 'swiper-bundle.min.js' ), $swiper->ver );

		$slider = wp_scripts()->registered[ B\SCRIPT_HANDLE ];
		$this->assertSame( get_theme_file_uri( 'assets/js/brand-slider.js' ), $slider->src );
		$this->assertSame( array( B\SWIPER_HANDLE ), $slider->deps );
		$this->assertSame( \Kwv\asset_version( 'assets/js/brand-slider.js' ), $slider->ver );

		foreach ( array( B\SWIPER_HANDLE, B\SCRIPT_HANDLE ) as $handle ) {
			$this->assertSame( 'defer', wp_scripts()->get_data( $handle, 'strategy' ), $handle );
			$this->assertSame( 1, wp_scripts()->get_data( $handle, 'group' ), $handle . ' loads in the footer.' );
		}
	}

	public function test_the_localised_strings_for_the_slider_are_exact(): void {
		if ( ! $this->swiperBundleExists() ) {
			$this->markTestSkipped( 'The Carousel Block plugin (Swiper bundle) is not installed.' );
		}
		$this->goToBrand();
		$this->runEnqueue();

		$data = (string) wp_scripts()->get_data( B\SCRIPT_HANDLE, 'data' );

		$this->assertStringContainsString( 'var kwvBrandSlider = ', $data );
		$this->assertStringContainsString( '"previous":"Previous product"', $data );
		$this->assertStringContainsString( '"next":"Next product"', $data );
		$this->assertStringContainsString( '"thumbnails":"Choose a product"', $data );
		$this->assertStringContainsString( '"show":"Show %s"', $data );
		$this->assertStringContainsString( '"slide":"Product {{index}} of {{slidesLength}}"', $data );
	}

	public function test_summary_attributes_are_stripped_but_the_wrappers_are_kept(): void {
		$this->goToBrand();

		$out = B\clean_product_summary( self::SUMMARY );

		// The block's own two wrappers keep their attributes.
		$this->assertStringContainsString( 'class="wp-block-woocommerce-product-summary has-small-font-size"', $out );
		$this->assertStringContainsString( 'class="wp-block-woocommerce-product-summary__content" style="color:red"', $out );

		// Everything inside is cleaned.
		foreach ( array( 'width=', 'height=', 'align=', 'data-start', 'data-end', 'data-col', 'data-pasted', 'margin:0', 'text-align', '1876px' ) as $needle ) {
			$this->assertStringNotContainsString( $needle, $out, $needle );
		}
		$this->assertStringContainsString( 'class="keep"', $out, 'Non-listed attributes survive.' );
		$this->assertStringContainsString( 'Aged', $out );
	}

	public function test_the_summary_is_untouched_off_brand_pages(): void {
		$this->go_to( home_url( '/' ) );

		$this->assertSame( self::SUMMARY, B\clean_product_summary( self::SUMMARY ) );
	}

	public function test_an_empty_summary_is_returned_as_is(): void {
		$this->goToBrand();

		$this->assertSame( '', B\clean_product_summary( '' ) );
	}

	public function test_the_summary_filter_is_hooked_to_the_product_summary_block(): void {
		$this->assertNotFalse( has_filter( 'render_block_woocommerce/product-summary', 'Kwv\BrandPage\clean_product_summary' ) );
	}

	public function test_the_slider_ceiling_is_one_hundred_products(): void {
		$this->assertSame( 100, B\MAX_PRODUCTS );
		$this->goToBrand();

		$this->assertSame( 100, B\products_per_page( 15 ) );
		$this->assertSame( 100, B\products_per_page( 1000 ) );
	}

	public function test_other_pages_pass_the_incoming_per_page_value_through_unchanged(): void {
		$this->go_to( home_url( '/' ) );

		foreach ( array( 1, 12, 15, 99, 100, 101 ) as $value ) {
			$this->assertSame( $value, B\products_per_page( $value ) );
		}
	}

	public function test_the_js_flag_script_adds_the_documented_class(): void {
		$this->goToBrand();

		ob_start();
		B\print_js_flag();
		$out = ob_get_clean();

		$this->assertStringContainsString( "document.documentElement.classList.add('kwv-brand-slider-js');", $out );
		$this->assertSame( 1, substr_count( $out, '<script' ) );
	}

	public function test_the_js_flag_is_hooked_first_in_the_head_so_it_runs_before_paint(): void {
		$this->assertSame( 1, has_action( 'wp_head', 'Kwv\BrandPage\print_js_flag' ) );
	}

	public function test_the_page_stylesheet_depends_on_the_shared_woocommerce_sheet(): void {
		$this->goToBrand();
		$this->runEnqueue();

		$style = wp_styles()->registered[ B\STYLE_HANDLE ];
		$this->assertContains( 'theme-woocommerce-style', $style->deps );
		$this->assertSame( \Kwv\asset_version( 'assets/styles/woocommerce-brand-page.css' ), $style->ver );
		$this->assertStringContainsString( 'assets/styles/woocommerce-brand-page.css', $style->src );
	}

	/**
	 * @return array<string,array{0:string}>
	 */
	public function otherTaxonomies(): array {
		return array(
			'category' => array( 'product_cat' ),
			'tag'      => array( 'product_tag' ),
		);
	}

	/**
	 * @dataProvider otherTaxonomies
	 */
	public function test_other_product_taxonomies_are_not_brand_pages( string $taxonomy ): void {
		$term = self::factory()->term->create( array( 'taxonomy' => $taxonomy ) );
		$this->go_to( get_term_link( $term, $taxonomy ) );

		$this->assertFalse( B\is_brand_page() );
		$this->runEnqueue();
		$this->assertFalse( $this->isQueued( 'style', B\STYLE_HANDLE ) );
	}

	public function test_the_summary_keeps_the_text_and_structure_of_a_pasted_table(): void {
		$this->goToBrand();

		$out = B\clean_product_summary( self::SUMMARY );

		$this->assertSame( 1, substr_count( $out, '<table' ) );
		$this->assertSame( 1, substr_count( $out, '<td' ) );
		$this->assertStringContainsString( '>Text</p>', $out );
	}

	public function test_a_third_element_with_hostile_attributes_is_cleaned(): void {
		$this->goToBrand();
		$html = '<div class="a"><div class="b"><img src="x.jpg" width="500" height="9" style="x:y" align="left" data-x="1" onerror="alert(1)" alt="ok"></div></div>';

		$out = B\clean_product_summary( $html );

		$this->assertStringContainsString( 'src="x.jpg"', $out );
		$this->assertStringContainsString( 'alt="ok"', $out );
		foreach ( array( 'width=', 'height=', 'style=', 'align=', 'data-x' ) as $needle ) {
			$this->assertStringNotContainsString( $needle, $out, $needle );
		}
	}

	public function test_summary_markup_with_only_the_two_wrappers_is_unchanged(): void {
		$this->goToBrand();
		$html = '<div class="a" style="color:red" data-k="1"><div class="b" style="margin:0">text</div></div>';

		$this->assertSame( $html, B\clean_product_summary( $html ) );
	}

	public function test_the_summary_filter_cleans_through_the_real_block_hook(): void {
		$this->goToBrand();

		$out = apply_filters( 'render_block_woocommerce/product-summary', self::SUMMARY, array( 'blockName' => 'woocommerce/product-summary' ) );

		$this->assertStringNotContainsString( '1876px', $out );
	}

	/* --- Showcase URL, route and template -------------------------------- */

	public function test_the_showcase_rule_matches_brand_slug_showcase(): void {
		$this->assertSame( 'brand', B\brand_base() );
		$this->assertSame( '^brand/([^/]+)/showcase/?$', B\showcase_rule() );
		$this->assertSame( 1, preg_match( '#' . B\showcase_rule() . '#', 'brand/kwv-brandy/showcase/' ) );
		$this->assertSame( 0, preg_match( '#' . B\showcase_rule() . '#', 'brand/kwv-brandy/' ) );
	}

	public function test_the_query_var_is_public(): void {
		$this->assertContains( B\VIEW_QUERY_VAR, apply_filters( 'query_vars', array() ) );
	}

	/**
	 * Switch to pretty permalinks, with WooCommerce's brand base rebuilt.
	 *
	 * `set_permalink_structure()` re-registers only core's taxonomies, so the
	 * brand's `/brand/%product_brand%` permastruct is added back by hand.
	 */
	private function usePrettyPermalinks(): void {
		$this->set_permalink_structure( '/%postname%/' );
		get_taxonomy( 'product_brand' )->add_rewrite_rules();
		B\register_showcase_route();
		flush_rewrite_rules( false );
	}

	public function test_pretty_showcase_urls_route_to_the_showcase(): void {
		$this->usePrettyPermalinks();
		$brand = $this->makeTerm( 'product_brand', 'Pretty Brand' );

		$url = B\showcase_url( $brand );
		$this->assertSame( home_url( '/brand/pretty-brand/showcase/' ), $url );

		$this->go_to( $url );
		$this->assertTrue( B\is_brand_page() );
		$this->assertSame( $brand->term_id, get_queried_object_id() );
	}

	public function test_a_missing_rule_is_added_and_the_rules_rebuilt_once(): void {
		$this->usePrettyPermalinks();
		$rules = get_option( 'rewrite_rules' );
		unset( $rules[ B\showcase_rule() ] );
		update_option( 'rewrite_rules', $rules );

		B\register_showcase_route();

		$this->assertArrayHasKey( B\showcase_rule(), get_option( 'rewrite_rules' ) );
		$this->assertSame( 'index.php?product_brand=$matches[1]&kwv_brand_view=showcase', get_option( 'rewrite_rules' )[ B\showcase_rule() ] );
	}

	public function test_plain_permalinks_use_the_query_var(): void {
		$this->set_permalink_structure( '' );
		$brand = $this->makeTerm( 'product_brand', 'Plain' );

		$url = B\showcase_url( $brand );
		$this->assertStringContainsString( 'kwv_brand_view=showcase', $url );

		$this->go_to( $url );
		$this->assertTrue( B\is_brand_page() );
	}

	public function test_a_showcase_url_can_select_a_product(): void {
		$brand = $this->makeTerm( 'product_brand' );

		$this->assertStringEndsWith( '#product-42', B\showcase_url( $brand, 42 ) );
		$this->assertStringNotContainsString( '#', B\showcase_url( $brand ) );
		$this->assertSame( B\showcase_url( $brand ), B\showcase_url( $brand->term_id ) );
	}

	public function test_an_unknown_brand_has_no_showcase_url(): void {
		$this->assertSame( '', B\showcase_url( 999999 ) );
	}

	public function test_the_showcase_template_comes_first_only_on_the_showcase(): void {
		$this->goToBrand();
		$this->assertSame( array( 'brand-showcase', 'taxonomy-product_brand' ), B\showcase_template_hierarchy( array( 'taxonomy-product_brand' ) ) );

		$this->go_to( get_term_link( $this->makeTerm( 'product_brand' ) ) );
		$this->assertSame( array( 'taxonomy-product_brand' ), B\showcase_template_hierarchy( array( 'taxonomy-product_brand' ) ) );
	}

	public function test_the_showcase_resolves_to_the_brand_showcase_block_template(): void {
		$this->goToBrand();

		$template = resolve_block_template( 'taxonomy', B\showcase_template_hierarchy( array( 'taxonomy-product_brand', 'taxonomy' ) ), '' );

		$this->assertInstanceOf( \WP_Block_Template::class, $template );
		$this->assertSame( 'brand-showcase', $template->slug );
	}

	public function test_the_showcase_template_is_named_for_the_site_editor(): void {
		$types = get_default_block_template_types();

		$this->assertSame( 'Brand Showcase', $types['brand-showcase']['title'] );
	}

	public function test_the_template_files_point_at_the_right_patterns(): void {
		$dir = $this->themeDir() . '/templates/';

		$this->assertStringContainsString( '"kwv/woo-brand-archive"', file_get_contents( $dir . 'brand-showcase.html' ) );
		$this->assertStringContainsString( '"kwv/woo-product-archive"', file_get_contents( $dir . 'taxonomy-product_brand.html' ) );
	}

	/* --- Product pages → brand showcase ---------------------------------- */

	public function test_a_branded_product_page_redirects_to_its_brand_showcase(): void {
		$brand = $this->makeTerm( 'product_brand', 'Cruxland' );
		$id    = $this->makeBrandedProduct( array( $brand ) );

		$this->go_to( get_permalink( $id ) );

		$this->assertSame( B\showcase_url( $brand, $id ), B\product_redirect_url() );
	}

	public function test_a_product_in_two_brands_goes_to_the_first_by_name(): void {
		$zulu  = $this->makeTerm( 'product_brand', 'Zulu' );
		$alpha = $this->makeTerm( 'product_brand', 'alpha' );
		$id    = $this->makeBrandedProduct( array( $zulu, $alpha ) );

		$this->go_to( get_permalink( $id ) );

		$this->assertSame( B\showcase_url( $alpha, $id ), B\product_redirect_url() );
	}

	public function test_a_product_without_a_brand_keeps_its_page(): void {
		$this->go_to( get_permalink( $this->makeProduct() ) );

		$this->assertSame( '', B\product_redirect_url() );
	}

	public function test_a_variable_or_hidden_product_keeps_its_page(): void {
		$brand = $this->makeTerm( 'product_brand' );

		$variable = new \WC_Product_Variable();
		$variable->set_name( 'Variable' );
		$variable->set_status( 'publish' );
		$variable_id = $variable->save();
		wp_set_object_terms( $variable_id, $brand->term_id, 'product_brand' );
		$this->assertNull( B\product_showcase_brand( wc_get_product( $variable_id ) ) );

		$hidden_id = $this->makeBrandedProduct( array( $brand ) );
		$hidden    = wc_get_product( $hidden_id );
		$hidden->set_catalog_visibility( 'hidden' );
		$hidden->save();
		$this->assertNull( B\product_showcase_brand( wc_get_product( $hidden_id ) ) );
	}

	public function test_the_brand_choice_can_be_filtered(): void {
		$brand = $this->makeTerm( 'product_brand' );
		$id    = $this->makeBrandedProduct( array( $brand ) );
		$other = $this->makeTerm( 'product_brand', 'Other' );

		add_filter( 'kwv_product_showcase_brand', fn() => $other );
		$this->assertSame( $other->term_id, B\product_showcase_brand( wc_get_product( $id ) )->term_id );

		add_filter( 'kwv_product_showcase_brand', '__return_null', 20 );
		$this->assertNull( B\product_showcase_brand( wc_get_product( $id ) ) );

		add_filter( 'kwv_product_showcase_brand', fn() => 'not a term', 30 );
		$this->assertNull( B\product_showcase_brand( wc_get_product( $id ) ) );
	}

	public function test_add_to_cart_posts_and_links_are_left_alone(): void {
		$id = $this->makeBrandedProduct( array( $this->makeTerm( 'product_brand' ) ) );
		$this->go_to( get_permalink( $id ) );

		$_SERVER['REQUEST_METHOD'] = 'POST';
		$this->assertSame( '', B\product_redirect_url() );

		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_GET['add-to-cart']       = (string) $id;
		$this->assertSame( '', B\product_redirect_url() );
	}

	public function test_previews_and_other_pages_are_left_alone(): void {
		$id = $this->makeBrandedProduct( array( $this->makeTerm( 'product_brand' ) ) );

		$this->go_to( add_query_arg( 'preview', 'true', get_permalink( $id ) ) );
		$this->assertSame( '', B\product_redirect_url() );

		$this->go_to( home_url( '/' ) );
		$this->assertSame( '', B\product_redirect_url() );
	}

	public function test_the_redirect_and_route_hooks_are_registered(): void {
		$this->assertSame( 5, has_action( 'template_redirect', 'Kwv\BrandPage\redirect_product_to_showcase' ) );
		$this->assertSame( 20, has_action( 'init', 'Kwv\BrandPage\register_showcase_route' ) );
		$this->assertNotFalse( has_filter( 'taxonomy_template_hierarchy', 'Kwv\BrandPage\showcase_template_hierarchy' ) );
		$this->assertNotFalse( has_filter( 'default_template_types', 'Kwv\BrandPage\register_showcase_template_type' ) );
	}
}
