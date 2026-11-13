<?php
/**
 * Smoke test: the theme is active and its modules loaded alongside WooCommerce.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @coversNothing
 */
final class ThemeBootTest extends TestCase {

	public function test_theme_and_dependencies_are_loaded(): void {
		$this->assertSame( 'kwv-theme-2026', get_stylesheet() );
		$this->assertTrue( wp_is_block_theme() );
		$this->assertTrue( class_exists( 'WooCommerce' ) );
		$this->assertTrue( function_exists( 'Kwv\asset_version' ) );
		$this->assertTrue( function_exists( 'Kwv\get_queried_term_banner' ) );
	}
}
