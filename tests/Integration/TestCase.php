<?php
/**
 * Base class for integration tests.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * Adds KWV-specific helpers to the WordPress test case.
 */
abstract class TestCase extends \WP_UnitTestCase {

	/**
	 * Absolute path to the theme root.
	 *
	 * @return string
	 */
	protected function themeDir(): string {
		return dirname( __DIR__, 2 );
	}

	/**
	 * Skip the test when Gravity Forms is not installed (it is commercial, so
	 * CI does not have it).
	 */
	protected function requireGravityForms(): void {
		if ( ! class_exists( 'GFAPI' ) ) {
			$this->markTestSkipped( 'Gravity Forms is not available in this environment.' );
		}
	}

	/**
	 * Whether a script or style handle ends up in the queue after running the
	 * given enqueue hook.
	 *
	 * @param string $kind   'script' or 'style'.
	 * @param string $handle Handle to look for.
	 * @param string $status 'enqueued' (default) or 'registered'.
	 * @return bool
	 */
	protected function isQueued( string $kind, string $handle, string $status = 'enqueued' ): bool {
		return 'script' === $kind ? wp_script_is( $handle, $status ) : wp_style_is( $handle, $status );
	}

	/**
	 * Forget everything enqueued so far, so each test starts clean.
	 */
	protected function resetAssets(): void {
		$GLOBALS['wp_scripts'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$GLOBALS['wp_styles']  = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}

	/**
	 * Create a term in a product taxonomy.
	 *
	 * @param string $taxonomy 'product_cat' or 'product_brand'.
	 * @param string $name     Term name.
	 * @return \WP_Term
	 */
	protected function makeTerm( string $taxonomy, string $name = 'Term' ): \WP_Term {
		$id = self::factory()->term->create(
			array(
				'taxonomy' => $taxonomy,
				'name'     => $name,
			)
		);

		return get_term( $id, $taxonomy );
	}

	/**
	 * Create a published simple product.
	 *
	 * @param string $name Product name.
	 * @return int Product (post) ID.
	 */
	protected function makeProduct( string $name = 'Test product' ): int {
		$product = new \WC_Product_Simple();
		$product->set_name( $name );
		$product->set_regular_price( '10' );
		$product->set_status( 'publish' );

		return $product->save();
	}

	/**
	 * Create a fake image attachment (a row plus a file path; no real file).
	 *
	 * @return int Attachment ID.
	 */
	protected function makeImageAttachment(): int {
		$id = self::factory()->attachment->create(
			array(
				'post_mime_type' => 'image/jpeg',
				'post_title'     => 'banner',
			)
		);
		update_post_meta( $id, '_wp_attached_file', '2026/01/banner.jpg' );

		return $id;
	}

	/**
	 * Log in as a user with the given role.
	 *
	 * @param string $role Role slug.
	 * @return int User ID.
	 */
	protected function actAs( string $role ): int {
		$id = self::factory()->user->create( array( 'role' => $role ) );
		wp_set_current_user( $id );

		return $id;
	}

	/**
	 * Run the front-end enqueue hook with clean queues and return nothing; use
	 * isQueued() afterwards.
	 */
	protected function runEnqueue(): void {
		$this->resetAssets();
		do_action( 'wp_enqueue_scripts' );
	}

	/**
	 * Render a block-markup string the way the front end does.
	 *
	 * @param string $markup Serialised block markup.
	 * @return string
	 */
	protected function render( string $markup ): string {
		return do_blocks( $markup );
	}

	/**
	 * Simulate a block rendering, so on-demand block assets (wp_enqueue_block_style)
	 * load. Also fires the classic enqueue hooks, for sites not loading assets
	 * on demand.
	 *
	 * @param string $block_name Block name, e.g. 'cb/carousel-v2'.
	 */
	protected function simulateBlockRender( string $block_name ): void {
		$this->applyRenderBlock( '', array( 'blockName' => $block_name ) );
		do_action( 'wp_enqueue_scripts' );
		do_action( 'wp_footer' );
	}

	/**
	 * Run the generic render_block filter the way core does (three arguments).
	 *
	 * @param string $html  Rendered block HTML.
	 * @param array  $block Parsed block (blockName, attrs, ...).
	 * @return string
	 */
	protected function applyRenderBlock( string $html, array $block ): string {
		$block = array_merge(
			array(
				'attrs'        => array(),
				'innerBlocks'  => array(),
				'innerHTML'    => '',
				'innerContent' => array(),
			),
			$block
		);

		return apply_filters( 'render_block', $html, $block, new \WP_Block( $block ) );
	}
}
