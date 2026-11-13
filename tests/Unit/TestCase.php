<?php
/**
 * Base class for unit tests: Brain Monkey set-up/tear-down plus helpers.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Yoast\PHPUnitPolyfills\TestCases\TestCase as PolyfillTestCase;

/**
 * Unit test case with WordPress functions mocked by Brain Monkey.
 */
abstract class TestCase extends PolyfillTestCase {

	use MockeryPHPUnitIntegration;

	protected function set_up(): void {
		parent::set_up();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\stubEscapeFunctions();
		$_POST = array();
		$_GET  = array();
	}

	protected function tear_down(): void {
		Monkey\tearDown();
		$_POST = array();
		$_GET  = array();
		parent::tear_down();
	}

	/**
	 * Absolute path to the theme root.
	 *
	 * @return string
	 */
	protected function themeDir(): string {
		return dirname( __DIR__, 2 );
	}

	/**
	 * Load a theme file so its functions exist (hooks it adds go to Brain Monkey).
	 *
	 * @param string $relative Path relative to the theme root, e.g. 'inc/woocommerce.php'.
	 */
	protected function loadThemeFile( string $relative ): void {
		Functions\when( 'get_template_directory' )->justReturn( $this->themeDir() );
		require_once $this->themeDir() . '/' . $relative;
	}
}
