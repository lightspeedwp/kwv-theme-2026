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
 */
final class BrandPageTest extends TestCase {

	private const SUMMARY = '<div class="wp-block-woocommerce-product-summary has-small-font-size"><div class="wp-block-woocommerce-product-summary__content" style="color:red">'
		. '<table style="width:1876px" width="1876" height="40" align="center" data-start="1" data-end="9"><tbody><tr><td style="text-align:center" data-col="2">Aged</td></tr></tbody></table>'
		. '<p style="margin:0" data-pasted="x" class="keep">Text</p></div></div>';

	private function goToBrand(): void {
		$brand = $this->makeTerm( 'product_brand', 'Brand' );
		$this->go_to( get_term_link( $brand ) );
	}

	private function swiperBundleExists(): bool {
		return is_readable( WP_PLUGIN_DIR . '/' . B\SWIPER_DIR . 'swiper-bundle.min.js' )
			&& is_readable( WP_PLUGIN_DIR . '/' . B\SWIPER_DIR . 'swiper-bundle.min.css' );
	}

	public function test_a_brand_archive_is_a_brand_page(): void {
		$this->goToBrand();
		$this->assertTrue( B\is_brand_page() );
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

		$this->assertSame( 15, apply_filters( 'loop_shop_per_page', 16 ) );
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
}
