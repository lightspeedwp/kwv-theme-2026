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

	/**
	 * @return array<string,array{0:string}>
	 */
	public function lookAlikeBlockNames(): array {
		return array(
			'prefix not at the start' => array( 'x-advanced-woo-search/search-block' ),
			'different namespace'     => array( 'advanced-woo-search-extra/search-block' ),
			'upper case'              => array( 'Advanced-Woo-Search/search-block' ),
			'core block'              => array( 'core/search' ),
		);
	}

	/**
	 * @dataProvider lookAlikeBlockNames
	 */
	public function test_only_blocks_in_the_aws_namespace_are_touched( string $name ): void {
		$this->assertSame( self::FORM, S\apply_block_class( self::FORM, $this->block( $name, 'is-style-kwv-search' ) ) );
	}

	/**
	 * @return array<string,array{0:string}>
	 */
	public function blankClassNames(): array {
		return array(
			'empty'    => array( '' ),
			'spaces'   => array( '   ' ),
			'tabs'     => array( "\t\t" ),
			'newlines' => array( "\n\n" ),
		);
	}

	/**
	 * @dataProvider blankClassNames
	 */
	public function test_a_blank_class_name_leaves_the_markup_untouched( string $class ): void {
		$this->assertSame( self::FORM, S\apply_block_class( self::FORM, $this->block( 'advanced-woo-search/search-block', $class ) ) );
	}

	public function test_a_class_already_on_the_container_is_not_duplicated(): void {
		$html = '<div class="aws-container is-style-kwv-search"></div>';

		$out = S\apply_block_class( $html, $this->block( 'advanced-woo-search/search-block', 'is-style-kwv-search' ) );

		$this->assertSame( 1, substr_count( $out, 'is-style-kwv-search' ) );
	}

	public function test_only_the_first_container_receives_the_class(): void {
		$html = '<div class="aws-container"></div><div class="aws-container"></div>';

		$out = S\apply_block_class( $html, $this->block( 'advanced-woo-search/search-block', 'is-style-kwv-search' ) );

		$this->assertSame( 1, substr_count( $out, 'is-style-kwv-search' ) );
	}

	public function test_a_container_without_a_class_attribute_cannot_be_found(): void {
		$html = '<div id="aws"></div>';

		$this->assertSame( $html, S\apply_block_class( $html, $this->block( 'advanced-woo-search/search-block', 'is-style-kwv-search' ) ) );
	}

	public function test_a_hostile_class_name_cannot_inject_markup(): void {
		$out = S\apply_block_class( self::FORM, $this->block( 'advanced-woo-search/search-block', '"><script>alert(1)</script>' ) );

		$this->assertStringNotContainsString( '<script>', $out );
	}

	public function test_the_rest_of_the_form_markup_is_preserved(): void {
		$out = S\apply_block_class( self::FORM, $this->block( 'advanced-woo-search/search-block', 'is-style-kwv-search' ) );

		$this->assertStringContainsString( 'data-url="x"><form></form></div>', $out );
	}

	public function test_a_blocks_class_name_is_trimmed_before_it_is_applied(): void {
		$out = S\apply_block_class( self::FORM, $this->block( 'advanced-woo-search/search-block', "  is-style-kwv-search \n" ) );

		$this->assertStringContainsString( 'class="aws-container bv-hidden-desktop is-style-kwv-search"', $out );
	}

	public function test_the_style_labels_are_set(): void {
		S\register_block_styles();

		$styles = \WP_Block_Styles_Registry::get_instance()->get_registered_styles_for_block( 'advanced-woo-search/search-block' );

		$this->assertSame( 'KWV Search Bar', $styles['kwv-search']['label'] );
		$this->assertSame( 'KWV Header Search (Icon)', $styles['kwv-header-search']['label'] );
	}

	public function test_the_block_list_is_exactly_the_two_aws_blocks(): void {
		$this->assertSame( array( 'advanced-woo-search/search-block', 'advanced-woo-search/nav-search-block' ), S\block_names() );
	}

	public function test_the_assets_load_when_the_plugin_style_is_only_enqueued(): void {
		wp_enqueue_style( 'aws-style', 'https://example.test/aws.css' );

		S\enqueue_assets();

		$this->assertTrue( $this->isQueued( 'style', S\STYLE_HANDLE ) );
	}

	public function test_the_script_is_deferred_in_the_footer_with_exact_labels_and_versions(): void {
		wp_register_style( 'aws-style', 'https://example.test/aws.css' );

		S\enqueue_assets();

		$this->assertSame( 'defer', wp_scripts()->get_data( S\SCRIPT_HANDLE, 'strategy' ) );
		$this->assertSame( 1, wp_scripts()->get_data( S\SCRIPT_HANDLE, 'group' ) );
		$this->assertSame( \Kwv\asset_version( 'assets/js/aws-search.js' ), wp_scripts()->registered[ S\SCRIPT_HANDLE ]->ver );
		$this->assertSame( \Kwv\asset_version( 'assets/styles/aws-search.css' ), wp_styles()->registered[ S\STYLE_HANDLE ]->ver );
		$data = (string) wp_scripts()->get_data( S\SCRIPT_HANDLE, 'data' );
		$this->assertStringContainsString( '"search":"Search"', $data );
		$this->assertStringContainsString( '"close":"Close search"', $data );
	}
}
