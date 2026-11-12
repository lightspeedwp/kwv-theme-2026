<?php
/**
 * Integration tests for the hooks in functions.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @covers ::Kwv\asset_version
 * @covers ::Kwv\setup
 * @covers ::Kwv\enqueue_style_sheet
 * @covers ::Kwv\register_block_styles
 * @covers ::Kwv\enqueue_custom_block_styles
 * @covers ::Kwv\pattern_categories
 * @covers ::Kwv\is_paginated
 * @covers ::Kwv\template_part_areas
 */
final class FunctionsTest extends TestCase {

	public function test_every_inc_module_is_loaded(): void {
		foreach ( glob( $this->themeDir() . '/inc/*.php' ) as $file ) {
			$this->assertContains( realpath( $file ), array_map( 'realpath', get_included_files() ), basename( $file ) . ' is not loaded by functions.php.' );
		}
	}

	public function test_asset_version_changes_with_the_file(): void {
		$this->assertSame( (string) filemtime( $this->themeDir() . '/style.css' ), \Kwv\asset_version( 'style.css' ) );
	}

	public function test_asset_version_falls_back_to_the_theme_version_for_a_missing_file(): void {
		$this->assertSame( wp_get_theme()->get( 'Version' ), \Kwv\asset_version( 'assets/does-not-exist.css' ) );
	}

	public function test_the_theme_stylesheet_is_enqueued_with_a_file_based_version(): void {
		$this->runEnqueue();

		$this->assertTrue( $this->isQueued( 'style', 'kwv' ) );
		$this->assertSame( \Kwv\asset_version( 'style.css' ), wp_styles()->registered['kwv']->ver );
	}

	public function test_setup_removes_core_block_patterns_support(): void {
		add_theme_support( 'core-block-patterns' );

		\Kwv\setup();

		$this->assertFalse( current_theme_supports( 'core-block-patterns' ) );
	}

	public function test_custom_block_styles_are_registered(): void {
		\Kwv\register_block_styles();

		$registry = \WP_Block_Styles_Registry::get_instance();
		$this->assertArrayHasKey( 'list-check', $registry->get_registered_styles_for_block( 'core/list' ) );
		$this->assertArrayHasKey( 'background-blur', $registry->get_registered_styles_for_block( 'core/group' ) );
		$this->assertArrayHasKey( 'separator-thin', $registry->get_registered_styles_for_block( 'core/separator' ) );
	}

	public function test_each_core_block_stylesheet_loads_when_its_block_renders(): void {
		$files = glob( $this->themeDir() . '/assets/styles/core-*.css' );
		$this->assertNotEmpty( $files );

		$this->resetAssets();
		\Kwv\enqueue_custom_block_styles();

		foreach ( $files as $file ) {
			$name  = basename( $file, '.css' );
			$block = str_replace( 'core-', 'core/', $name );
			$this->simulateBlockRender( $block );
			$this->assertTrue( $this->isQueued( 'style', "kwv-block-{$name}" ), "{$name} did not load with {$block}." );
		}
	}

	public function test_pattern_categories_are_registered(): void {
		\Kwv\pattern_categories();

		$registry = \WP_Block_Pattern_Categories_Registry::get_instance();
		foreach ( array( 'kwv/card', 'kwv/hero', 'kwv/woocommerce', 'kwv/winemakers-club' ) as $name ) {
			$this->assertTrue( $registry->is_registered( $name ), $name );
		}
	}

	public function test_the_last_separator_is_hidden_when_nothing_paginates(): void {
		$this->go_to( home_url( '/' ) );
		$GLOBALS['wp_query']->max_num_pages = 1;

		ob_start();
		\Kwv\is_paginated();
		$this->assertStringContainsString( '<style>', ob_get_clean() );
	}

	public function test_no_separator_rule_is_printed_when_there_are_pages(): void {
		$this->go_to( home_url( '/' ) );
		$GLOBALS['wp_query']->max_num_pages = 3;

		ob_start();
		\Kwv\is_paginated();
		$this->assertSame( '', ob_get_clean() );
	}

	public function test_the_sidebar_template_part_area_is_registered(): void {
		$areas = apply_filters( 'default_wp_template_part_areas', array() );

		$this->assertSame( 'sidebar', $areas[0]['area'] );
	}
}
