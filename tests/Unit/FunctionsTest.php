<?php
/**
 * Unit tests for the helpers in functions.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * @covers ::Kwv\asset_version
 * @covers ::Kwv\setup
 * @covers ::Kwv\pattern_categories
 */
final class FunctionsTest extends TestCase {

	protected function set_up(): void {
		parent::set_up();
		// functions.php require_once's every inc/ file; they only add hooks.
		$this->loadThemeFile( 'functions.php' );
	}

	public function test_asset_version_is_the_file_mtime(): void {
		$file = tempnam( sys_get_temp_dir(), 'kwv' );
		Functions\when( 'get_theme_file_path' )->justReturn( $file );

		$this->assertSame( (string) filemtime( $file ), \Kwv\asset_version( 'style.css' ) );

		unlink( $file );
	}

	public function test_asset_version_falls_back_to_the_theme_version(): void {
		Functions\when( 'get_theme_file_path' )->justReturn( '/does/not/exist.css' );
		$theme = \Mockery::mock();
		$theme->shouldReceive( 'get' )->with( 'Version' )->andReturn( '9.9.9' );
		Functions\when( 'wp_get_theme' )->justReturn( $theme );

		$this->assertSame( '9.9.9', \Kwv\asset_version( 'nope.css' ) );
	}

	public function test_setup_adds_editor_style_and_removes_core_patterns(): void {
		Functions\expect( 'add_editor_style' )->once()->with( 'style.css' );
		Functions\expect( 'remove_theme_support' )->once()->with( 'core-block-patterns' );

		\Kwv\setup();
	}

	public function test_pattern_categories_are_all_namespaced(): void {
		$registered = array();
		Functions\when( 'register_block_pattern_category' )->alias(
			static function ( $name, $properties ) use ( &$registered ) {
				$registered[ $name ] = $properties;
			}
		);

		\Kwv\pattern_categories();

		$this->assertNotEmpty( $registered );
		foreach ( $registered as $name => $properties ) {
			$this->assertStringStartsWith( 'kwv/', $name );
			$this->assertNotEmpty( $properties['label'] );
		}
	}
}
