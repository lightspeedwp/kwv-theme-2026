<?php
/**
 * Integration tests: every load-time hook the theme registers, with its exact
 * priority and accepted-argument count.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\Tests\Support\ThemeHooks;

/**
 * Pins the hook table so a changed priority or argument count is a test failure.
 */
final class HooksTest extends TestCase {

	/**
	 * @return array<string,array{0:string,1:string,2:string,3:int,4:int}>
	 */
	public function activeHooks(): array {
		return ThemeHooks::allActive();
	}

	/**
	 * @return array<string,array{0:string,1:string,2:string,3:int,4:int}>
	 */
	public function inactiveWooCommerceHooks(): array {
		$rows = array();
		foreach ( ThemeHooks::whenWooCommerceInactive() as $row ) {
			$rows[ $row[1] . ' -> ' . $row[2] ] = $row;
		}

		return $rows;
	}

	/**
	 * @dataProvider activeHooks
	 */
	public function test_hook_is_registered_with_its_priority_and_argument_count( string $type, string $hook, string $callback, int $priority, int $args ): void {
		$found = 'action' === $type ? has_action( $hook, $callback ) : has_filter( $hook, $callback );

		$this->assertSame( $priority, $found, "{$callback} should run on {$hook} at priority {$priority}." );
		$this->assertTrue( is_callable( $callback ), "{$callback} must exist." );

		$registered = $GLOBALS['wp_filter'][ $hook ]->callbacks[ $priority ];
		$this->assertSame( $args, $registered[ _wp_filter_build_unique_id( $hook, $callback, $priority ) ]['accepted_args'], "{$callback} accepted_args." );
	}

	/**
	 * With WooCommerce active (as in this suite) the inactive-branch hooks must not be present.
	 *
	 * @dataProvider inactiveWooCommerceHooks
	 */
	public function test_the_woocommerce_inactive_hooks_are_absent_while_woocommerce_is_active( string $type, string $hook, string $callback ): void {
		$this->assertTrue( class_exists( 'WooCommerce' ), 'Precondition: WooCommerce is loaded.' );
		$this->assertFalse( 'action' === $type ? has_action( $hook, $callback ) : has_filter( $hook, $callback ) );
	}

	public function test_both_woocommerce_per_page_filters_run_in_the_right_order(): void {
		$this->assertLessThan(
			has_filter( 'loop_shop_per_page', 'Kwv\BrandPage\products_per_page' ),
			has_filter( 'loop_shop_per_page', 'Kwv\shop_products_per_page' ),
			'The brand-page value must be applied after the shop-grid value.'
		);
	}
}
