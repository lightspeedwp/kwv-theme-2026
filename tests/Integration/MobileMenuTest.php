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

	/**
	 * @return array<string,array{0:string,1:string}> Input rule => expected rule.
	 */
	public function closeButtonRules(): array {
		$tail = ' { fill: red !important; }';

		return array(
			'no space after the comma'  => array(
				'#nav-1 .wp-block-navigation__responsive-container-open svg,#nav-1 .wp-block-navigation__responsive-container-close svg' . $tail,
				'#nav-1 .wp-block-navigation__responsive-container-open svg' . $tail,
			),
			'newline after the comma'   => array(
				"#nav-1 .wp-block-navigation__responsive-container-open svg,\n\t#nav-1 .wp-block-navigation__responsive-container-close svg" . $tail,
				'#nav-1 .wp-block-navigation__responsive-container-open svg' . $tail,
			),
			'id with hyphen underscore' => array(
				'#nav_a-9 .wp-block-navigation__responsive-container-open svg, #nav_a-9 .wp-block-navigation__responsive-container-close svg' . $tail,
				'#nav_a-9 .wp-block-navigation__responsive-container-open svg' . $tail,
			),
			'no space before the brace' => array(
				'#n .wp-block-navigation__responsive-container-open svg, #n .wp-block-navigation__responsive-container-close svg{ fill: red; }',
				'#n .wp-block-navigation__responsive-container-open svg{ fill: red; }',
			),
			'close button listed first' => array(
				'#n .wp-block-navigation__responsive-container-close svg, #n .wp-block-navigation__responsive-container-open svg' . $tail,
				'#n .wp-block-navigation__responsive-container-close svg, #n .wp-block-navigation__responsive-container-open svg' . $tail,
			),
			'close button on its own'   => array(
				'#n .wp-block-navigation__responsive-container-close svg' . $tail,
				'#n .wp-block-navigation__responsive-container-close svg' . $tail,
			),
			'class selector not an id'  => array(
				'.n .wp-block-navigation__responsive-container-open svg, .n .wp-block-navigation__responsive-container-close svg' . $tail,
				'.n .wp-block-navigation__responsive-container-open svg, .n .wp-block-navigation__responsive-container-close svg' . $tail,
			),
		);
	}

	/**
	 * @dataProvider closeButtonRules
	 */
	public function test_only_the_documented_rule_shape_is_rewritten( string $input, string $expected ): void {
		$this->assertSame( $expected, M\unpin_close_icon_colour( $input, array( 'blockName' => 'core/navigation' ) ) );
	}

	/**
	 * @return array<string,array{0:array}>
	 */
	public function otherBlocks(): array {
		return array(
			'navigation link'    => array( array( 'blockName' => 'core/navigation-link' ) ),
			'navigation submenu' => array( array( 'blockName' => 'core/navigation-submenu' ) ),
			'case differs'       => array( array( 'blockName' => 'core/Navigation' ) ),
			'null name'          => array( array( 'blockName' => null ) ),
			'no name key'        => array( array() ),
		);
	}

	/**
	 * @dataProvider otherBlocks
	 */
	public function test_any_other_block_is_returned_byte_for_byte( array $block ): void {
		$this->assertSame( self::NAV, M\unpin_close_icon_colour( self::NAV, $block ) );
		if ( array_key_exists( 'blockName', $block ) ) {
			$this->assertSame( self::NAV, $this->applyRenderBlock( self::NAV, $block ) );
		}
	}

	public function test_the_hamburger_rule_and_the_declaration_survive_untouched(): void {
		$out = M\unpin_close_icon_colour( self::NAV, array( 'blockName' => 'core/navigation' ) );

		$this->assertSame(
			'<style>#nav-9 .wp-block-navigation__responsive-container-open svg { fill: red !important; }</style><nav class="wp-block-navigation"></nav>',
			$out
		);
	}

	public function test_empty_and_unrelated_markup_pass_through(): void {
		$this->assertSame( '', M\unpin_close_icon_colour( '', array( 'blockName' => 'core/navigation' ) ) );
		$this->assertSame( '<nav>x</nav>', M\unpin_close_icon_colour( '<nav>x</nav>', array( 'blockName' => 'core/navigation' ) ) );
	}

	public function test_the_script_handle_is_stable(): void {
		$this->assertSame( 'kwv-mobile-menu', M\SCRIPT_HANDLE );
	}
}
