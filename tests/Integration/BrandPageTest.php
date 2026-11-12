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
}
