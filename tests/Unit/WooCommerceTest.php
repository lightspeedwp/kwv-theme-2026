<?php
/**
 * Unit tests for the pure parts of inc/woocommerce.php. Enqueue behaviour is
 * covered against real WordPress in tests/Integration/WooCommerceTest.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * @covers ::Kwv\filter_woocommerce_templates
 * @covers ::Kwv\disable_pattern_toolkit
 * @covers ::Kwv\shop_products_per_page
 * @covers ::Kwv\register_mini_cart_block_styles
 */
final class WooCommerceTest extends TestCase {

	protected function set_up(): void {
		parent::set_up();
		$this->loadThemeFile( 'inc/woocommerce.php' );
	}

	private function template( string $slug ): object {
		return (object) array( 'slug' => $slug );
	}

	public function test_templates_for_woocommerce_are_removed(): void {
		$templates = array(
			$this->template( 'single-product' ),
			$this->template( 'page' ),
			$this->template( 'page-cart' ),
			$this->template( 'archive-product' ),
		);

		$result = \Kwv\filter_woocommerce_templates( $templates, array(), 'wp_template' );

		$this->assertSame( array( 'page' ), array_values( array_map( static fn( $t ) => $t->slug, $result ) ) );
	}

	public function test_template_parts_are_never_filtered(): void {
		$parts = array( $this->template( 'single-product' ) );

		$this->assertSame( $parts, \Kwv\filter_woocommerce_templates( $parts, array(), 'wp_template_part' ) );
	}

	public function test_pattern_toolkit_feature_is_removed_and_others_kept(): void {
		$features = array( 'analytics', 'pattern-toolkit-full-composability', 'navigation' );

		$this->assertSame(
			array(
				0 => 'analytics',
				2 => 'navigation',
			),
			\Kwv\disable_pattern_toolkit( $features )
		);
	}

	public function test_pattern_toolkit_filter_leaves_unrelated_features_alone(): void {
		$this->assertSame( array( 'analytics' ), \Kwv\disable_pattern_toolkit( array( 'analytics' ) ) );
	}

	public function test_shop_shows_fifteen_products_per_page(): void {
		$this->assertSame( 15, \Kwv\shop_products_per_page() );
	}

	public function test_both_mini_cart_styles_are_registered(): void {
		$names = array();
		Functions\when( 'register_block_style' )->alias(
			static function ( $block, $args ) use ( &$names ) {
				$names[] = $block . ':' . $args['name'];
			}
		);

		\Kwv\register_mini_cart_block_styles();

		$this->assertSame(
			array( 'woocommerce/mini-cart:mini-cart-light', 'woocommerce/mini-cart:mini-cart-dark' ),
			$names
		);
	}
}
