<?php
/**
 * Integration tests for inc/gravity-forms.php.
 *
 * The module only attaches a stylesheet to the `gravityforms/form` block, so it
 * does not need Gravity Forms itself and runs in CI without it. The extra test
 * that renders the real block skips when Gravity Forms is missing.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\GravityForms as G;

/**
 * @covers ::Kwv\GravityForms\enqueue_block_style
 */
final class GravityFormsTest extends TestCase {

	public function test_the_hook_is_registered_on_init(): void {
		$this->assertSame( 20, has_action( 'init', 'Kwv\GravityForms\enqueue_block_style' ) );
	}

	public function test_the_stylesheet_loads_when_the_form_block_renders(): void {
		$this->resetAssets();
		G\enqueue_block_style();

		$this->assertFalse( $this->isQueued( 'style', G\STYLE_HANDLE ) );

		$this->simulateBlockRender( G\BLOCK_NAME );

		$this->assertTrue( $this->isQueued( 'style', G\STYLE_HANDLE ) );
		$this->assertStringContainsString( 'assets/styles/gravity-forms.css', (string) wp_styles()->get_data( G\STYLE_HANDLE, 'path' ) );
	}

	public function test_it_does_not_load_for_other_blocks(): void {
		$this->resetAssets();
		G\enqueue_block_style();
		$this->applyRenderBlock( '', array( 'blockName' => 'core/group' ) );

		$this->assertFalse( $this->isQueued( 'style', G\STYLE_HANDLE ) );
	}

	public function test_the_form_block_is_registered_by_gravity_forms(): void {
		$this->requireGravityForms();

		$this->assertTrue( \WP_Block_Type_Registry::get_instance()->is_registered( G\BLOCK_NAME ) );
	}
}
