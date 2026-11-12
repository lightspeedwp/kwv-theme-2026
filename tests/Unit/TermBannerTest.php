<?php
/**
 * Unit tests for the save handler and capability logic in inc/term-banner.php.
 * Meta registration and the block binding run against real WordPress in
 * tests/Integration/TermBannerTest.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * @covers ::Kwv\banner_taxonomies
 * @covers ::Kwv\can_manage_banner
 * @covers ::Kwv\save_banner_field
 * @covers ::Kwv\get_term_banner_binding
 */
final class TermBannerTest extends TestCase {

	protected function set_up(): void {
		parent::set_up();
		$this->loadThemeFile( 'inc/term-banner.php' );
		Functions\stubs(
			array(
				'sanitize_text_field' => null,
				'wp_unslash'          => null,
				'absint'              => static fn( $v ) => abs( (int) $v ),
			)
		);
	}

	private function postValidRequest( $value ): void {
		$_POST['kwv_banner_nonce']    = 'nonce';
		$_POST['kwv_banner_image_id'] = $value;
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
	}

	public function test_only_categories_and_brands_get_a_banner(): void {
		$this->assertSame( array( 'product_cat', 'product_brand' ), \Kwv\banner_taxonomies() );
	}

	public function test_either_capability_is_enough_to_manage_banners(): void {
		Functions\when( 'current_user_can' )->alias(
			static fn( $cap ) => 'manage_categories' === $cap
		);
		$this->assertTrue( \Kwv\can_manage_banner() );

		Functions\when( 'current_user_can' )->justReturn( false );
		$this->assertFalse( \Kwv\can_manage_banner() );
	}

	public function test_save_stores_the_attachment_id(): void {
		$this->postValidRequest( '42' );
		Functions\expect( 'update_term_meta' )->once()->with( 7, 'kwv_banner_image_id', 42 );
		Functions\expect( 'delete_term_meta' )->never();

		\Kwv\save_banner_field( 7 );
	}

	public function test_save_deletes_the_meta_when_the_field_is_emptied(): void {
		$this->postValidRequest( '0' );
		Functions\expect( 'delete_term_meta' )->once()->with( 7, 'kwv_banner_image_id' );
		Functions\expect( 'update_term_meta' )->never();

		\Kwv\save_banner_field( 7 );
	}

	public function test_save_ignores_requests_without_a_nonce(): void {
		$_POST['kwv_banner_image_id'] = '42';
		Functions\expect( 'update_term_meta' )->never();
		Functions\expect( 'delete_term_meta' )->never();

		\Kwv\save_banner_field( 7 );
	}

	public function test_save_ignores_an_invalid_nonce(): void {
		$this->postValidRequest( '42' );
		Functions\when( 'wp_verify_nonce' )->justReturn( false );
		Functions\expect( 'update_term_meta' )->never();

		\Kwv\save_banner_field( 7 );
	}

	public function test_save_ignores_users_without_the_capability(): void {
		$this->postValidRequest( '42' );
		Functions\when( 'current_user_can' )->justReturn( false );
		Functions\expect( 'update_term_meta' )->never();
		Functions\expect( 'delete_term_meta' )->never();

		\Kwv\save_banner_field( 7 );
	}

	public function test_save_ignores_a_request_without_the_image_field(): void {
		$this->postValidRequest( '42' );
		unset( $_POST['kwv_banner_image_id'] );
		Functions\expect( 'update_term_meta' )->never();
		Functions\expect( 'delete_term_meta' )->never();

		\Kwv\save_banner_field( 7 );
	}

	public function test_binding_only_answers_for_the_url_attribute(): void {
		$this->assertNull( \Kwv\get_term_banner_binding( array(), new \stdClass(), 'alt' ) );
	}
}
