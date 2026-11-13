<?php
/**
 * Unit tests: loading each theme file registers exactly its documented hooks.
 *
 * Each case runs in its own process because the files declare functions and
 * run their registration code once, at load. There is deliberately no @covers
 * tag: the registration calls sit outside any function, so a @covers filter
 * would discard them from the coverage report.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Kwv\Tests\Support\ThemeHooks;

/**
 * Loads the theme under Brain Monkey and checks its hooks.
 */
final class HookRegistrationTest extends TestCase {

	/**
	 * functions.php requires every inc/ file, so loading it once exercises them all.
	 *
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_loading_the_theme_registers_every_documented_hook(): void {
		$this->assertFalse( class_exists( 'WooCommerce', false ), 'Precondition: WooCommerce is absent.' );

		$this->loadThemeFile( 'functions.php' );

		foreach ( ThemeHooks::byFile() as $file => $rows ) {
			foreach ( $rows as $row ) {
				list( $type, $hook, $callback, $priority ) = $row;
				$found                                     = 'action' === $type ? has_action( $hook, $callback, $priority ) : has_filter( $hook, $callback, $priority );

				$this->assertTrue( (bool) $found, "{$file}: {$callback} on {$hook} at {$priority}" );
			}
		}
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_woocommerce_hooks_are_registered_only_when_woocommerce_is_active(): void {
		class_alias( \stdClass::class, 'WooCommerce' );

		$this->loadThemeFile( 'inc/woocommerce.php' );

		foreach ( ThemeHooks::whenWooCommerceActive() as $row ) {
			list( $type, $hook, $callback, $priority ) = $row;
			$this->assertTrue( (bool) ( 'action' === $type ? has_action( $hook, $callback, $priority ) : has_filter( $hook, $callback, $priority ) ), $callback );
		}
		foreach ( ThemeHooks::whenWooCommerceInactive() as $row ) {
			list( $type, $hook, $callback, $priority ) = $row;
			$this->assertFalse( (bool) ( 'action' === $type ? has_action( $hook, $callback, $priority ) : has_filter( $hook, $callback, $priority ) ), $callback );
		}
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_the_inactive_woocommerce_hooks_are_registered_without_woocommerce(): void {
		$this->assertFalse( class_exists( 'WooCommerce', false ), 'Precondition: WooCommerce is absent.' );

		$this->loadThemeFile( 'inc/woocommerce.php' );

		foreach ( ThemeHooks::whenWooCommerceInactive() as $row ) {
			list( $type, $hook, $callback, $priority ) = $row;
			$this->assertTrue( (bool) ( 'action' === $type ? has_action( $hook, $callback, $priority ) : has_filter( $hook, $callback, $priority ) ), $callback );
		}
		foreach ( ThemeHooks::whenWooCommerceActive() as $row ) {
			list( $type, $hook, $callback, $priority ) = $row;
			$this->assertFalse( (bool) ( 'action' === $type ? has_action( $hook, $callback, $priority ) : has_filter( $hook, $callback, $priority ) ), $callback );
		}
	}
}
