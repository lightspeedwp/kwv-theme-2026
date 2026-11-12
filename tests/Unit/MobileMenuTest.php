<?php
/**
 * Unit tests for inc/mobile-menu.php's close-button colour fix.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * @covers ::Kwv\MobileMenu\unpin_close_icon_colour
 * @covers ::Kwv\MobileMenu\enqueue_assets
 */
final class MobileMenuTest extends TestCase {

	private const RULE = '<style>#nav-12 .wp-block-navigation__responsive-container-open svg, #nav-12 .wp-block-navigation__responsive-container-close svg { fill: var(--wp--preset--color--base) !important; }</style>';

	protected function set_up(): void {
		parent::set_up();
		$this->loadThemeFile( 'inc/mobile-menu.php' );
	}

	public function test_the_close_button_selector_is_removed_and_the_hamburger_kept(): void {
		$out = \Kwv\MobileMenu\unpin_close_icon_colour( self::RULE, array( 'blockName' => 'core/navigation' ) );

		$this->assertStringContainsString( '#nav-12 .wp-block-navigation__responsive-container-open svg', $out );
		$this->assertStringNotContainsString( 'responsive-container-close', $out );
		$this->assertStringContainsString( '{ fill: var(--wp--preset--color--base) !important; }', $out );
	}

	public function test_other_blocks_are_untouched(): void {
		$this->assertSame(
			self::RULE,
			\Kwv\MobileMenu\unpin_close_icon_colour( self::RULE, array( 'blockName' => 'core/group' ) )
		);
	}

	public function test_a_navigation_without_the_rule_is_untouched(): void {
		$html = '<nav class="wp-block-navigation"></nav>';

		$this->assertSame( $html, \Kwv\MobileMenu\unpin_close_icon_colour( $html, array( 'blockName' => 'core/navigation' ) ) );
	}

	public function test_a_block_without_a_name_is_untouched(): void {
		$this->assertSame( 'x', \Kwv\MobileMenu\unpin_close_icon_colour( 'x', array() ) );
	}

	public function test_no_script_in_the_admin(): void {
		Functions\when( 'is_admin' )->justReturn( true );
		Functions\expect( 'wp_enqueue_script' )->never();

		\Kwv\MobileMenu\enqueue_assets();
	}

	public function test_no_script_without_ollie_menu_designer(): void {
		Functions\when( 'is_admin' )->justReturn( false );
		Functions\expect( 'wp_enqueue_script' )->never();

		\Kwv\MobileMenu\enqueue_assets();
	}
}
