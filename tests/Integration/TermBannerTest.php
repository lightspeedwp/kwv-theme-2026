<?php
/**
 * Integration tests for inc/term-banner.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @covers ::Kwv\register_banner_meta
 * @covers ::Kwv\register_banner_admin_hooks
 * @covers ::Kwv\enqueue_banner_admin_assets
 * @covers ::Kwv\render_banner_controls
 * @covers ::Kwv\render_banner_add_field
 * @covers ::Kwv\render_banner_edit_field
 * @covers ::Kwv\save_banner_field
 * @covers ::Kwv\register_term_banner_binding
 * @covers ::Kwv\get_term_banner_binding
 * @covers ::Kwv\get_queried_term_banner
 * @covers ::Kwv\can_manage_banner
 */
final class TermBannerTest extends TestCase {

	private const IMAGE_BLOCK = '<!-- wp:image {"metadata":{"bindings":{"url":{"source":"kwv/term-banner"}}}} --><figure class="wp-block-image"><img src="https://example.test/fallback.jpg" alt=""/></figure><!-- /wp:image -->';

	public function set_up(): void {
		parent::set_up();
		// The test library unregisters every meta key after each test.
		\Kwv\register_banner_meta();
		$_POST = array();
	}

	public function tear_down(): void {
		$_POST = array();
		parent::tear_down();
	}

	/**
	 * @return array<string,array{0:string}>
	 */
	public function bannerTaxonomies(): array {
		return array(
			'categories' => array( 'product_cat' ),
			'brands'     => array( 'product_brand' ),
		);
	}

	/**
	 * @dataProvider bannerTaxonomies
	 */
	public function test_meta_is_registered_for_rest_with_an_integer_default( string $taxonomy ): void {
		$keys = get_registered_meta_keys( 'term', $taxonomy );

		$this->assertArrayHasKey( 'kwv_banner_image_id', $keys );
		$this->assertSame( 'integer', $keys['kwv_banner_image_id']['type'] );
		$this->assertTrue( $keys['kwv_banner_image_id']['single'] );
		$this->assertNotEmpty( $keys['kwv_banner_image_id']['show_in_rest'] );
		$this->assertSame( 0, $keys['kwv_banner_image_id']['default'] );
	}

	public function test_meta_is_not_registered_for_other_taxonomies(): void {
		$this->assertArrayNotHasKey( 'kwv_banner_image_id', get_registered_meta_keys( 'term', 'category' ) );
	}

	public function test_meta_is_sanitised_to_a_positive_integer(): void {
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', '-12abc' );

		$this->assertSame( 12, (int) get_term_meta( $term->term_id, 'kwv_banner_image_id', true ) );
	}

	public function test_meta_auth_follows_the_term_capability(): void {
		$callback = get_registered_meta_keys( 'term', 'product_cat' )['kwv_banner_image_id']['auth_callback'];

		$this->actAs( 'administrator' );
		$this->assertTrue( $callback() );

		$this->actAs( 'subscriber' );
		$this->assertFalse( $callback() );
	}

	public function test_admin_hooks_register_fields_and_save_handlers_for_both_taxonomies(): void {
		\Kwv\register_banner_admin_hooks();

		foreach ( array( 'product_cat', 'product_brand' ) as $taxonomy ) {
			$this->assertNotFalse( has_action( "{$taxonomy}_add_form_fields", 'Kwv\render_banner_add_field' ) );
			$this->assertNotFalse( has_action( "{$taxonomy}_edit_form_fields", 'Kwv\render_banner_edit_field' ) );
			$this->assertNotFalse( has_action( "created_{$taxonomy}", 'Kwv\save_banner_field' ) );
			$this->assertNotFalse( has_action( "edited_{$taxonomy}", 'Kwv\save_banner_field' ) );
		}
	}

	public function test_saving_a_term_stores_the_banner_when_nonce_and_capability_pass(): void {
		\Kwv\register_banner_admin_hooks();
		$this->actAs( 'administrator' );
		$term                         = $this->makeTerm( 'product_cat' );
		$_POST['kwv_banner_nonce']    = wp_create_nonce( 'kwv_banner_save' );
		$_POST['kwv_banner_image_id'] = '55';

		do_action( 'edited_product_cat', $term->term_id );

		$this->assertSame( 55, (int) get_term_meta( $term->term_id, 'kwv_banner_image_id', true ) );
	}

	public function test_saving_with_an_empty_field_deletes_the_banner(): void {
		$this->actAs( 'administrator' );
		$term = $this->makeTerm( 'product_brand' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', 55 );
		$_POST['kwv_banner_nonce']    = wp_create_nonce( 'kwv_banner_save' );
		$_POST['kwv_banner_image_id'] = '0';

		\Kwv\save_banner_field( $term->term_id );

		$this->assertFalse( metadata_exists( 'term', $term->term_id, 'kwv_banner_image_id' ) );
	}

	public function test_saving_with_a_bad_nonce_changes_nothing(): void {
		$this->actAs( 'administrator' );
		$term                         = $this->makeTerm( 'product_cat' );
		$_POST['kwv_banner_nonce']    = 'not-a-nonce';
		$_POST['kwv_banner_image_id'] = '55';

		\Kwv\save_banner_field( $term->term_id );

		$this->assertFalse( metadata_exists( 'term', $term->term_id, 'kwv_banner_image_id' ) );
	}

	public function test_saving_without_the_capability_changes_nothing(): void {
		$term = $this->makeTerm( 'product_cat' );
		$this->actAs( 'subscriber' );
		$_POST['kwv_banner_nonce']    = wp_create_nonce( 'kwv_banner_save' );
		$_POST['kwv_banner_image_id'] = '55';

		\Kwv\save_banner_field( $term->term_id );

		$this->assertFalse( metadata_exists( 'term', $term->term_id, 'kwv_banner_image_id' ) );
	}

	public function test_edit_field_shows_the_current_banner_and_a_nonce(): void {
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', $this->makeImageAttachment() );

		ob_start();
		\Kwv\render_banner_edit_field( $term );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'name="kwv_banner_nonce"', $html );
		$this->assertStringContainsString( 'name="kwv_banner_image_id"', $html );
		$this->assertStringContainsString( 'banner.jpg', $html );
		$this->assertStringNotContainsString( 'display:none', $html, 'Remove button is visible when a banner is set.' );
	}

	public function test_add_field_renders_empty_controls(): void {
		ob_start();
		\Kwv\render_banner_add_field();
		$html = ob_get_clean();

		$this->assertStringContainsString( 'value="0"', $html );
		$this->assertStringContainsString( 'kwv-banner-remove', $html );
		$this->assertStringContainsString( 'display:none', $html );
	}

	public function test_admin_assets_load_only_on_banner_term_screens(): void {
		$this->resetAssets();

		set_current_screen( 'edit-product_cat' );
		\Kwv\enqueue_banner_admin_assets( 'edit-tags.php' );
		$this->assertTrue( wp_script_is( 'kwv-term-banner-admin', 'enqueued' ) );

		$this->resetAssets();
		\Kwv\enqueue_banner_admin_assets( 'post.php' );
		$this->assertFalse( wp_script_is( 'kwv-term-banner-admin', 'enqueued' ) );

		$this->resetAssets();
		set_current_screen( 'edit-category' );
		\Kwv\enqueue_banner_admin_assets( 'term.php' );
		$this->assertFalse( wp_script_is( 'kwv-term-banner-admin', 'enqueued' ), 'Core categories get no banner field.' );
	}

	public function test_binding_source_is_registered(): void {
		$source = \WP_Block_Bindings_Registry::get_instance()->get_registered( 'kwv/term-banner' );

		$this->assertNotNull( $source );
	}

	public function test_binding_leaves_the_fallback_image_off_a_term_archive(): void {
		$this->go_to( home_url( '/' ) );

		$html = $this->render( self::IMAGE_BLOCK );

		$this->assertStringContainsString( 'fallback.jpg', $html );
	}

	public function test_binding_leaves_the_fallback_when_the_term_has_no_banner(): void {
		$term = $this->makeTerm( 'product_cat' );
		$this->go_to( get_term_link( $term ) );

		$this->assertStringContainsString( 'fallback.jpg', $this->render( self::IMAGE_BLOCK ) );
	}

	public function test_binding_swaps_in_the_banner_url_on_a_term_with_a_banner(): void {
		$term = $this->makeTerm( 'product_brand' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', $this->makeImageAttachment() );
		$this->go_to( get_term_link( $term ) );

		$html = $this->render( self::IMAGE_BLOCK );

		$this->assertStringContainsString( '2026/01/banner.jpg', $html );
		$this->assertStringNotContainsString( 'fallback.jpg', $html );
	}

	public function test_binding_ignores_other_attributes(): void {
		$this->assertNull( \Kwv\get_term_banner_binding( array(), new \stdClass(), 'alt' ) );
	}

	public function test_queried_banner_is_empty_when_the_attachment_is_gone(): void {
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', 999999 );
		$this->go_to( get_term_link( $term ) );

		$this->assertSame(
			array(
				'id'  => 0,
				'url' => '',
			),
			\Kwv\get_queried_term_banner()
		);
	}

	public function test_queried_banner_is_empty_on_a_non_product_term(): void {
		$term_id = self::factory()->category->create();
		$this->go_to( get_category_link( $term_id ) );

		$this->assertSame( 0, \Kwv\get_queried_term_banner()['id'] );
	}
}
