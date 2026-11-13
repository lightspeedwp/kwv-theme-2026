<?php
/**
 * Unit tests for inc/user-job-title.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * @covers ::Kwv\save_job_title_field
 * @covers ::Kwv\get_author_role_binding
 * @covers ::Kwv\render_job_title_field
 */
final class UserJobTitleTest extends TestCase {

	protected function set_up(): void {
		parent::set_up();
		$this->loadThemeFile( 'inc/user-job-title.php' );
		Functions\stubs(
			array(
				'sanitize_text_field' => static fn( $v ) => trim( strip_tags( $v ) ),
				'wp_unslash'          => null,
			)
		);
	}

	private function validRequest( string $title ): void {
		$_POST['kwv_job_title_nonce'] = 'n';
		$_POST['kwv_job_title_field'] = $title;
		Functions\when( 'wp_verify_nonce' )->justReturn( 1 );
		Functions\when( 'current_user_can' )->justReturn( true );
	}

	public function test_save_sanitises_and_stores_the_title(): void {
		$this->validRequest( ' <b>Cellar Master</b> ' );
		Functions\expect( 'update_user_meta' )->once()->with( 5, 'job_title', 'Cellar Master' );

		\Kwv\save_job_title_field( 5 );
	}

	public function test_save_requires_a_nonce(): void {
		$_POST['kwv_job_title_field'] = 'CEO';
		Functions\expect( 'update_user_meta' )->never();

		\Kwv\save_job_title_field( 5 );
	}

	public function test_save_requires_edit_user_capability(): void {
		$this->validRequest( 'CEO' );
		Functions\when( 'current_user_can' )->justReturn( false );
		Functions\expect( 'update_user_meta' )->never();

		\Kwv\save_job_title_field( 5 );
	}

	public function test_save_does_nothing_without_the_field(): void {
		$this->validRequest( 'CEO' );
		unset( $_POST['kwv_job_title_field'] );
		Functions\expect( 'update_user_meta' )->never();

		\Kwv\save_job_title_field( 5 );
	}

	public function test_binding_reads_the_author_of_the_context_post(): void {
		$block          = new \stdClass();
		$block->context = array( 'postId' => 12 );
		Functions\when( 'get_post_field' )->justReturn( '3' );
		Functions\expect( 'get_user_meta' )->once()->with( 3, 'job_title', true )->andReturn( 'Winemaker' );

		$this->assertSame( 'Winemaker', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_binding_falls_back_to_the_loop_post(): void {
		$block          = new \stdClass();
		$block->context = array();
		Functions\when( 'get_the_ID' )->justReturn( 9 );
		Functions\when( 'get_post_field' )->justReturn( 4 );
		Functions\when( 'get_user_meta' )->justReturn( 'Chair' );

		$this->assertSame( 'Chair', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_binding_is_empty_without_a_post(): void {
		$block          = new \stdClass();
		$block->context = array();
		Functions\when( 'get_the_ID' )->justReturn( false );

		$this->assertSame( '', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_binding_is_empty_without_an_author(): void {
		$block          = new \stdClass();
		$block->context = array( 'postId' => 12 );
		Functions\when( 'get_post_field' )->justReturn( 0 );

		$this->assertSame( '', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_profile_field_renders_the_escaped_title_and_a_nonce(): void {
		$user     = new \stdClass();
		$user->ID = 5;
		Functions\when( 'get_user_meta' )->justReturn( 'Cellar "Master"' );
		Functions\expect( 'wp_nonce_field' )->once()->with( 'kwv_job_title_save', 'kwv_job_title_nonce' );

		ob_start();
		\Kwv\render_job_title_field( $user );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'name="kwv_job_title_field"', $html );
		$this->assertStringContainsString( 'value="Cellar &quot;Master&quot;"', $html );
		$this->assertStringContainsString( '<h2>KWV</h2>', $html );
		$this->assertStringContainsString( '>Job title</label>', $html );
		$this->assertStringContainsString( 'placeholder="e.g. Cellar Master"', $html );
		$this->assertStringContainsString( 'Shown in the byline of posts this user authors.', $html );
	}

	public function test_binding_passes_integer_ids_and_returns_a_string(): void {
		$block          = new \stdClass();
		$block->context = array( 'postId' => '12' );
		Functions\when( 'get_post_field' )->alias(
			function ( $field, $post_id ) {
				$this->assertSame( 'post_author', $field );
				$this->assertSame( 12, $post_id, 'The context post ID is cast to an integer.' );

				return '3';
			}
		);
		Functions\when( 'get_user_meta' )->alias(
			function ( $user_id, $key, $single ) {
				$this->assertSame( 3, $user_id, 'The author ID is cast to an integer.' );
				$this->assertSame( 'job_title', $key );
				$this->assertTrue( $single );

				return 42;
			}
		);

		$this->assertSame( '42', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_binding_casts_the_loop_post_id_to_an_integer(): void {
		$block          = new \stdClass();
		$block->context = array();
		Functions\when( 'get_the_ID' )->justReturn( '9' );
		Functions\when( 'get_post_field' )->alias(
			function ( $field, $post_id ) {
				$this->assertSame( 9, $post_id );

				return 0;
			}
		);

		$this->assertSame( '', \Kwv\get_author_role_binding( array(), $block ) );
	}
}
