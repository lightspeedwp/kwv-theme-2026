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

		$this->assertStringContainsString( '<label>Banner image</label>', $html );
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

	/**
	 * @return array<string,array{0:string,1:bool}>
	 */
	public function rolesAndBannerAccess(): array {
		return array(
			'administrator' => array( 'administrator', true ),
			'shop manager'  => array( 'shop_manager', true ),
			'editor'        => array( 'editor', true ),
			'author'        => array( 'author', false ),
			'contributor'   => array( 'contributor', false ),
			'subscriber'    => array( 'subscriber', false ),
			'customer'      => array( 'customer', false ),
		);
	}

	/**
	 * @dataProvider rolesAndBannerAccess
	 */
	public function test_only_term_managing_roles_may_manage_banners( string $role, bool $allowed ): void {
		$this->actAs( $role );

		$this->assertSame( $allowed, \Kwv\can_manage_banner() );
	}

	public function test_nobody_logged_in_may_not_manage_banners(): void {
		wp_set_current_user( 0 );

		$this->assertFalse( \Kwv\can_manage_banner() );
	}

	public function test_a_nonce_for_a_different_action_is_rejected(): void {
		$this->actAs( 'administrator' );
		$term                         = $this->makeTerm( 'product_cat' );
		$_POST['kwv_banner_nonce']    = wp_create_nonce( 'some_other_action' );
		$_POST['kwv_banner_image_id'] = '55';

		\Kwv\save_banner_field( $term->term_id );

		$this->assertFalse( metadata_exists( 'term', $term->term_id, 'kwv_banner_image_id' ) );
	}

	public function test_a_nonce_from_another_user_is_rejected(): void {
		$other = self::factory()->user->create( array( 'role' => 'administrator' ) );
		wp_set_current_user( $other );
		$nonce = wp_create_nonce( 'kwv_banner_save' );
		$this->actAs( 'administrator' );
		$term                         = $this->makeTerm( 'product_cat' );
		$_POST['kwv_banner_nonce']    = $nonce;
		$_POST['kwv_banner_image_id'] = '55';

		\Kwv\save_banner_field( $term->term_id );

		$this->assertFalse( metadata_exists( 'term', $term->term_id, 'kwv_banner_image_id' ) );
	}

	/**
	 * @return array<string,array{0:string,1:int|null}> Posted value => expected stored ID (null = meta removed).
	 */
	public function postedBannerValues(): array {
		return array(
			'plain id'       => array( '55', 55 ),
			'padded id'      => array( ' 12 ', 12 ),
			'negative id'    => array( '-7', 7 ),
			'id with suffix' => array( '9abc', 9 ),
			'zero'           => array( '0', null ),
			'empty string'   => array( '', null ),
			'not a number'   => array( 'abc', null ),
			'script payload' => array( '"><script>alert(1)</script>', null ),
		);
	}

	/**
	 * @dataProvider postedBannerValues
	 */
	public function test_the_posted_value_is_reduced_to_a_positive_integer_or_removes_the_banner( string $posted, ?int $expected ): void {
		$this->actAs( 'administrator' );
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', 999 );
		$_POST['kwv_banner_nonce']    = wp_create_nonce( 'kwv_banner_save' );
		$_POST['kwv_banner_image_id'] = $posted;

		\Kwv\save_banner_field( $term->term_id );

		if ( null === $expected ) {
			$this->assertFalse( metadata_exists( 'term', $term->term_id, 'kwv_banner_image_id' ) );
		} else {
			$this->assertSame( $expected, (int) get_term_meta( $term->term_id, 'kwv_banner_image_id', true ) );
		}
	}

	public function test_a_request_without_the_image_field_keeps_the_existing_banner(): void {
		$this->actAs( 'administrator' );
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', 77 );
		$_POST['kwv_banner_nonce'] = wp_create_nonce( 'kwv_banner_save' );

		\Kwv\save_banner_field( $term->term_id );

		$this->assertSame( 77, (int) get_term_meta( $term->term_id, 'kwv_banner_image_id', true ) );
	}

	public function test_a_rejected_save_keeps_the_existing_banner(): void {
		$this->actAs( 'subscriber' );
		$term = $this->makeTerm( 'product_brand' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', 77 );
		$_POST['kwv_banner_nonce']    = wp_create_nonce( 'kwv_banner_save' );
		$_POST['kwv_banner_image_id'] = '0';

		\Kwv\save_banner_field( $term->term_id );

		$this->assertSame( 77, (int) get_term_meta( $term->term_id, 'kwv_banner_image_id', true ), 'A low-privilege user cannot remove a banner.' );
	}

	public function test_creating_a_brand_through_the_real_hook_stores_the_banner(): void {
		\Kwv\register_banner_admin_hooks();
		$this->actAs( 'administrator' );
		$_POST['kwv_banner_nonce']    = wp_create_nonce( 'kwv_banner_save' );
		$_POST['kwv_banner_image_id'] = '31';

		$term = $this->makeTerm( 'product_brand', 'Fresh brand' );

		$this->assertSame( 31, (int) get_term_meta( $term->term_id, 'kwv_banner_image_id', true ) );
	}

	public function test_the_edit_field_without_a_banner_hides_the_remove_button_and_shows_no_preview(): void {
		$term = $this->makeTerm( 'product_cat' );

		ob_start();
		\Kwv\render_banner_edit_field( $term );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'value="0"', $html );
		$this->assertStringContainsString( 'display:none', $html );
		$this->assertStringNotContainsString( '<img', $html );
	}

	public function test_the_edit_field_shows_no_preview_when_the_attachment_is_gone(): void {
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', 987654 );

		ob_start();
		\Kwv\render_banner_edit_field( $term );
		$html = ob_get_clean();

		$this->assertStringNotContainsString( '<img', $html );
		$this->assertStringContainsString( 'value="987654"', $html );
	}

	public function test_a_hostile_image_url_cannot_break_out_of_the_preview(): void {
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', $this->makeImageAttachment() );
		add_filter(
			'wp_get_attachment_image_src',
			static fn() => array( 'https://example.test/a.jpg"><script>alert(1)</script>', 10, 10, false )
		);

		ob_start();
		\Kwv\render_banner_edit_field( $term );
		$html = ob_get_clean();

		$this->assertStringNotContainsString( '<script>', $html );
		$this->assertSame( 1, preg_match( '/<img src="([^"]*)"/', $html, $m ) );
		$this->assertStringNotContainsString( '<', $m[1] );
		$this->assertStringNotContainsString( '>', $m[1] );
	}

	public function test_the_picker_nonce_field_is_verifiable_by_the_save_handler(): void {
		$this->actAs( 'administrator' );
		ob_start();
		\Kwv\render_banner_add_field();
		$html = ob_get_clean();

		$this->assertSame( 1, preg_match( '/name="kwv_banner_nonce" value="([^"]+)"/', $html, $m ) );
		$this->assertNotFalse( wp_verify_nonce( $m[1], 'kwv_banner_save' ) );
	}

	public function test_the_admin_script_is_localised_and_depends_on_jquery(): void {
		$this->resetAssets();
		set_current_screen( 'edit-product_brand' );

		\Kwv\enqueue_banner_admin_assets( 'term.php' );

		$script = wp_scripts()->registered['kwv-term-banner-admin'];
		$this->assertSame( array( 'jquery' ), $script->deps );
		$this->assertSame( 1, $script->extra['group'], 'Loads in the footer.' );
		$this->assertStringContainsString( 'kwvTermBanner', (string) wp_scripts()->get_data( 'kwv-term-banner-admin', 'data' ) );
		$this->assertStringContainsString( 'Select banner image', (string) wp_scripts()->get_data( 'kwv-term-banner-admin', 'data' ) );
		$this->assertStringContainsString( 'term-banner-admin.js', $script->src );
		$this->assertTrue( wp_script_is( 'media-editor', 'registered' ) );
	}

	/**
	 * @return array<string,array{0:string}>
	 */
	public function otherAdminPages(): array {
		return array(
			'post editor'     => array( 'post.php' ),
			'post list'       => array( 'edit.php' ),
			'dashboard'       => array( 'index.php' ),
			'empty hook'      => array( '' ),
			'look-alike hook' => array( 'edit-tags.php.bak' ),
		);
	}

	/**
	 * @dataProvider otherAdminPages
	 */
	public function test_the_admin_script_ignores_other_admin_pages_even_on_a_banner_taxonomy_screen( string $hook ): void {
		$this->resetAssets();
		set_current_screen( 'edit-product_cat' );

		\Kwv\enqueue_banner_admin_assets( $hook );

		$this->assertFalse( wp_script_is( 'kwv-term-banner-admin', 'enqueued' ) );
	}

	public function test_the_admin_script_needs_a_current_screen(): void {
		$this->resetAssets();
		$GLOBALS['current_screen'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		\Kwv\enqueue_banner_admin_assets( 'edit-tags.php' );

		$this->assertFalse( wp_script_is( 'kwv-term-banner-admin', 'enqueued' ) );
	}

	public function test_the_queried_banner_returns_the_id_and_url_for_a_term_with_a_banner(): void {
		$term = $this->makeTerm( 'product_cat' );
		$id   = $this->makeImageAttachment();
		update_term_meta( $term->term_id, 'kwv_banner_image_id', $id );
		$this->go_to( get_term_link( $term ) );

		$banner = \Kwv\get_queried_term_banner( 'thumbnail' );

		$this->assertSame( $id, $banner['id'] );
		$this->assertStringContainsString( '2026/01/banner.jpg', $banner['url'] );
		$this->assertIsString( $banner['url'] );
	}

	public function test_the_queried_banner_asks_for_the_requested_size(): void {
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', $this->makeImageAttachment() );
		$this->go_to( get_term_link( $term ) );
		$sizes = array();
		add_filter(
			'wp_get_attachment_image_src',
			static function ( $image, $attachment_id, $size ) use ( &$sizes ) {
				$sizes[] = $size;

				return $image;
			},
			10,
			3
		);

		\Kwv\get_queried_term_banner( 'medium_large' );
		\Kwv\get_queried_term_banner();

		$this->assertSame( array( 'medium_large', 'large' ), $sizes );
	}

	public function test_a_banner_stored_on_an_unsupported_taxonomy_is_ignored(): void {
		$term_id = self::factory()->category->create();
		update_term_meta( $term_id, 'kwv_banner_image_id', $this->makeImageAttachment() );
		$this->go_to( get_category_link( $term_id ) );

		$this->assertSame(
			array(
				'id'  => 0,
				'url' => '',
			),
			\Kwv\get_queried_term_banner()
		);
	}

	public function test_the_binding_returns_the_url_for_the_url_attribute_only(): void {
		$term = $this->makeTerm( 'product_cat' );
		update_term_meta( $term->term_id, 'kwv_banner_image_id', $this->makeImageAttachment() );
		$this->go_to( get_term_link( $term ) );

		$this->assertStringContainsString( 'banner.jpg', (string) \Kwv\get_term_banner_binding( array(), new \stdClass(), 'url' ) );
		foreach ( array( 'alt', 'id', 'URL', '', 'src' ) as $attribute ) {
			$this->assertNull( \Kwv\get_term_banner_binding( array(), new \stdClass(), $attribute ), "'{$attribute}'" );
		}
	}

	public function test_the_binding_source_has_a_label(): void {
		$source = \WP_Block_Bindings_Registry::get_instance()->get_registered( 'kwv/term-banner' );

		$this->assertSame( 'Term Banner Image', $source->label );
	}

	public function test_the_binding_source_can_be_re_registered_with_its_callback(): void {
		unregister_block_bindings_source( 'kwv/term-banner' );
		$this->assertNull( \WP_Block_Bindings_Registry::get_instance()->get_registered( 'kwv/term-banner' ) );

		\Kwv\register_term_banner_binding();

		$source = \WP_Block_Bindings_Registry::get_instance()->get_registered( 'kwv/term-banner' );
		$this->assertSame( 'Term Banner Image', $source->label );
		$this->assertSame( 'Kwv\get_term_banner_binding', ( new \ReflectionProperty( $source, 'get_value_callback' ) )->getValue( $source ) );
	}
}
