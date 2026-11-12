<?php
/**
 * Integration tests for inc/search.php (Advanced Woo Search integration).
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\Search as S;

/**
 * @covers ::Kwv\Search\block_names
 * @covers ::Kwv\Search\register_block_styles
 * @covers ::Kwv\Search\apply_block_class
 * @covers ::Kwv\Search\enqueue_assets
 */
final class SearchTest extends TestCase {

	private const FORM = '<div class="aws-container bv-hidden-desktop" data-url="x"><form></form></div>';

	public function set_up(): void {
		parent::set_up();
		$this->resetAssets();
		set_current_screen( 'front' );
	}

	private function block( string $name, string $class ): array {
		return array(
			'blockName' => $name,
			'attrs'     => array( 'className' => $class ),
		);
	}

	public function test_both_block_styles_are_registered_on_both_search_blocks(): void {
		S\register_block_styles();

		$registry = \WP_Block_Styles_Registry::get_instance();
		foreach ( S\block_names() as $block ) {
			$styles = $registry->get_registered_styles_for_block( $block );
			$this->assertArrayHasKey( 'kwv-search', $styles, $block );
			$this->assertArrayHasKey( 'kwv-header-search', $styles, $block );
		}
	}

	public function test_the_style_class_is_merged_into_the_existing_container_classes(): void {
		$out = S\apply_block_class( self::FORM, $this->block( 'advanced-woo-search/search-block', 'is-style-kwv-search' ) );

		$this->assertStringContainsString( 'class="aws-container bv-hidden-desktop is-style-kwv-search"', $out );
	}

	public function test_several_classes_are_all_added(): void {
		$out = S\apply_block_class( self::FORM, $this->block( 'advanced-woo-search/nav-search-block', 'is-style-kwv-header-search  extra' ) );

		$this->assertStringContainsString( 'is-style-kwv-header-search', $out );
		$this->assertStringContainsString( 'extra', $out );
	}

	public function test_other_blocks_are_untouched(): void {
		$this->assertSame( self::FORM, S\apply_block_class( self::FORM, $this->block( 'core/group', 'is-style-kwv-search' ) ) );
	}

	public function test_a_block_without_a_class_is_untouched(): void {
		$this->assertSame( self::FORM, S\apply_block_class( self::FORM, array( 'blockName' => 'advanced-woo-search/search-block' ) ) );
	}

	public function test_a_block_without_a_name_is_untouched(): void {
		$this->assertSame( self::FORM, S\apply_block_class( self::FORM, array() ) );
	}

	public function test_markup_without_a_container_is_untouched(): void {
		$html = '<div class="other"></div>';

		$this->assertSame( $html, S\apply_block_class( $html, $this->block( 'advanced-woo-search/search-block', 'is-style-kwv-search' ) ) );
	}

	public function test_the_filter_runs_on_render_block(): void {
		$out = $this->applyRenderBlock( self::FORM, $this->block( 'advanced-woo-search/search-block', 'is-style-kwv-search' ) );

		$this->assertStringContainsString( 'is-style-kwv-search', $out );
	}

	public function test_assets_do_not_load_without_the_plugin(): void {
		S\enqueue_assets();

		$this->assertFalse( $this->isQueued( 'style', S\STYLE_HANDLE ) );
		$this->assertFalse( $this->isQueued( 'script', S\SCRIPT_HANDLE ) );
	}

	public function test_assets_load_after_the_plugin_style_and_get_translated_labels(): void {
		wp_register_style( 'aws-style', 'https://example.test/aws.css' );

		S\enqueue_assets();

		$this->assertTrue( $this->isQueued( 'style', S\STYLE_HANDLE ) );
		$this->assertSame( array( 'aws-style' ), wp_styles()->registered[ S\STYLE_HANDLE ]->deps );
		$this->assertTrue( $this->isQueued( 'script', S\SCRIPT_HANDLE ) );
		$this->assertStringContainsString( 'kwvSearchL10n', (string) wp_scripts()->get_data( S\SCRIPT_HANDLE, 'data' ) );
	}

	public function test_assets_do_not_load_in_the_admin(): void {
		wp_register_style( 'aws-style', 'https://example.test/aws.css' );
		set_current_screen( 'dashboard' );

		S\enqueue_assets();
		set_current_screen( 'front' );

		$this->assertFalse( $this->isQueued( 'style', S\STYLE_HANDLE ) );
	}
}
