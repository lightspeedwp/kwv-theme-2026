<?php
/**
 * Integration tests for inc/age-gate.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\AgeGate as A;

/**
 * @covers ::Kwv\AgeGate\enqueue_style
 */
final class AgeGateTest extends TestCase {

	public function set_up(): void {
		parent::set_up();
		$this->resetAssets();
		set_current_screen( 'front' );
	}

	public function tear_down(): void {
		set_current_screen( 'front' );
		parent::tear_down();
	}

	public function test_hooked_after_the_plugin_enqueues_its_own_style(): void {
		$this->assertSame( 20, has_action( 'wp_enqueue_scripts', 'Kwv\AgeGate\enqueue_style' ) );
	}

	public function test_nothing_loads_when_the_plugin_is_inactive(): void {
		A\enqueue_style();

		$this->assertFalse( $this->isQueued( 'style', A\STYLE_HANDLE ) );
	}

	public function test_the_skin_loads_after_the_plugin_style_when_it_is_registered(): void {
		wp_register_style( 'age-gate', 'https://example.test/age-gate.css' );

		A\enqueue_style();

		$this->assertTrue( $this->isQueued( 'style', A\STYLE_HANDLE ) );
		$this->assertSame( array( 'age-gate' ), wp_styles()->registered[ A\STYLE_HANDLE ]->deps );
	}

	public function test_the_skin_loads_when_the_plugin_style_is_enqueued(): void {
		wp_enqueue_style( 'age-gate', 'https://example.test/age-gate.css' );

		A\enqueue_style();

		$this->assertTrue( $this->isQueued( 'style', A\STYLE_HANDLE ) );
	}

	public function test_nothing_loads_in_the_admin(): void {
		wp_register_style( 'age-gate', 'https://example.test/age-gate.css' );
		set_current_screen( 'dashboard' );

		A\enqueue_style();

		$this->assertFalse( $this->isQueued( 'style', A\STYLE_HANDLE ) );
	}

	public function test_the_skin_is_the_age_gate_stylesheet_with_a_file_version(): void {
		wp_register_style( 'age-gate', 'https://example.test/age-gate.css' );

		A\enqueue_style();

		$style = wp_styles()->registered[ A\STYLE_HANDLE ];
		$this->assertStringContainsString( 'assets/styles/age-gate.css', $style->src );
		$this->assertSame( \Kwv\asset_version( 'assets/styles/age-gate.css' ), $style->ver );
	}

	public function test_a_look_alike_handle_does_not_trigger_the_skin(): void {
		wp_register_style( 'age-gate-extra', 'https://example.test/other.css' );

		A\enqueue_style();

		$this->assertFalse( $this->isQueued( 'style', A\STYLE_HANDLE ) );
	}

	public function test_nothing_loads_in_the_admin_even_when_the_plugin_style_is_enqueued(): void {
		wp_enqueue_style( 'age-gate', 'https://example.test/age-gate.css' );
		set_current_screen( 'dashboard' );

		A\enqueue_style();

		$this->assertFalse( $this->isQueued( 'style', A\STYLE_HANDLE ) );
	}

	public function test_the_skin_loads_through_the_real_enqueue_hook(): void {
		wp_register_style( 'age-gate', 'https://example.test/age-gate.css' );

		do_action( 'wp_enqueue_scripts' );

		$this->assertTrue( $this->isQueued( 'style', A\STYLE_HANDLE ) );
	}
}
