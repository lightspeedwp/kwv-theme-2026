<?php
/**
 * Integration tests for inc/mobile-menu.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\MobileMenu as M;

/**
 * @covers ::Kwv\MobileMenu\unpin_close_icon_colour
 * @covers ::Kwv\MobileMenu\enqueue_assets
 */
final class MobileMenuTest extends TestCase {

	private const NAV = '<style>#nav-9 .wp-block-navigation__responsive-container-open svg,#nav-9 .wp-block-navigation__responsive-container-close svg { fill: red !important; }</style><nav class="wp-block-navigation"></nav>';

	public function set_up(): void {
		parent::set_up();
		$this->resetAssets();
		set_current_screen( 'front' );
	}

	public function test_the_render_block_filter_unpins_the_close_button(): void {
		$out = $this->applyRenderBlock( self::NAV, array( 'blockName' => 'core/navigation' ) );

		$this->assertStringNotContainsString( 'responsive-container-close svg', $out );
		$this->assertStringContainsString( 'responsive-container-open svg', $out );
	}

	public function test_only_the_first_match_is_rewritten(): void {
		$two = self::NAV . self::NAV;
		$out = M\unpin_close_icon_colour( $two, array( 'blockName' => 'core/navigation' ) );

		$this->assertSame( 1, substr_count( $out, 'responsive-container-close svg' ) );
	}

	public function test_the_script_is_not_loaded_without_ollie_menu_designer(): void {
		$this->assertFalse( function_exists( 'MenuDesigner\MobileMenu\add_mobile_menu_to_navigation' ), 'Precondition: plugin absent.' );

		M\enqueue_assets();

		$this->assertFalse( $this->isQueued( 'script', M\SCRIPT_HANDLE ) );
	}
}
