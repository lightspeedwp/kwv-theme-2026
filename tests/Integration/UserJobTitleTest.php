<?php
/**
 * Integration tests for inc/user-job-title.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @covers ::Kwv\register_job_title_meta
 * @covers ::Kwv\render_job_title_field
 * @covers ::Kwv\save_job_title_field
 * @covers ::Kwv\register_author_role_binding
 * @covers ::Kwv\get_author_role_binding
 */
final class UserJobTitleTest extends TestCase {

	private const PARAGRAPH = '<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"kwv/author-role"}}}} --><p>placeholder</p><!-- /wp:paragraph -->';

	public function set_up(): void {
		parent::set_up();
		\Kwv\register_job_title_meta();
		$_POST = array();
	}

	public function tear_down(): void {
		$_POST = array();
		parent::tear_down();
	}

	public function test_the_meta_is_registered_for_users_and_exposed_to_rest(): void {
		$keys = get_registered_meta_keys( 'user' );

		$this->assertArrayHasKey( 'job_title', $keys );
		$this->assertSame( 'string', $keys['job_title']['type'] );
		$this->assertNotEmpty( $keys['job_title']['show_in_rest'] );
		$this->assertSame( '', $keys['job_title']['default'] );
	}

	public function test_the_meta_is_sanitised_as_text(): void {
		$user = self::factory()->user->create();
		update_user_meta( $user, 'job_title', "  <b>Chief</b>\nOfficer " );

		$this->assertSame( 'Chief Officer', get_user_meta( $user, 'job_title', true ) );
	}

	public function test_meta_auth_requires_permission_to_edit_that_user(): void {
		$callback = get_registered_meta_keys( 'user' )['job_title']['auth_callback'];
		$target   = self::factory()->user->create();

		$this->actAs( 'administrator' );
		$this->assertTrue( $callback( false, 'job_title', $target ) );

		$subscriber = $this->actAs( 'subscriber' );
		$this->assertFalse( $callback( false, 'job_title', $target ) );
		$this->assertTrue( $callback( false, 'job_title', $subscriber ), 'Users may edit their own title.' );
	}

	public function test_the_profile_field_is_hooked_for_own_and_other_profiles(): void {
		$this->assertNotFalse( has_action( 'show_user_profile', 'Kwv\render_job_title_field' ) );
		$this->assertNotFalse( has_action( 'edit_user_profile', 'Kwv\render_job_title_field' ) );
	}

	public function test_the_profile_field_renders_the_escaped_value_and_a_nonce(): void {
		$user = self::factory()->user->create();
		update_user_meta( $user, 'job_title', 'Cellar "Master"' );

		ob_start();
		\Kwv\render_job_title_field( get_userdata( $user ) );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'name="kwv_job_title_nonce"', $html );
		$this->assertStringContainsString( 'value="Cellar &quot;Master&quot;"', $html );
	}

	public function test_saving_stores_the_sanitised_title(): void {
		$target = self::factory()->user->create();
		$this->actAs( 'administrator' );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'kwv_job_title_save' );
		$_POST['kwv_job_title_field'] = '  <i>Winemaker</i> ';

		do_action( 'edit_user_profile_update', $target );

		$this->assertSame( 'Winemaker', get_user_meta( $target, 'job_title', true ) );
	}

	public function test_saving_without_a_valid_nonce_changes_nothing(): void {
		$target = self::factory()->user->create();
		$this->actAs( 'administrator' );
		$_POST['kwv_job_title_nonce'] = 'bad';
		$_POST['kwv_job_title_field'] = 'Hacker';

		do_action( 'personal_options_update', $target );

		$this->assertSame( '', get_user_meta( $target, 'job_title', true ) );
	}

	public function test_saving_for_another_user_without_permission_changes_nothing(): void {
		$target = self::factory()->user->create();
		$this->actAs( 'subscriber' );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'kwv_job_title_save' );
		$_POST['kwv_job_title_field'] = 'Hacker';

		\Kwv\save_job_title_field( $target );

		$this->assertSame( '', get_user_meta( $target, 'job_title', true ) );
	}

	public function test_the_binding_callback_resolves_the_author_of_the_context_post(): void {
		$author = self::factory()->user->create();
		update_user_meta( $author, 'job_title', 'Cellar Master' );
		$post  = self::factory()->post->create( array( 'post_author' => $author ) );
		$block = (object) array( 'context' => array( 'postId' => $post ) );

		$this->assertSame( 'Cellar Master', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_a_bound_paragraph_renders_the_authors_title(): void {
		$author = self::factory()->user->create();
		update_user_meta( $author, 'job_title', 'Head Distiller' );
		$post = self::factory()->post->create( array( 'post_author' => $author ) );

		$block = new \WP_Block(
			parse_blocks( self::PARAGRAPH )[0],
			array( 'postId' => $post )
		);

		$this->assertStringContainsString( 'Head Distiller', $block->render() );
	}

	public function test_the_binding_callback_falls_back_to_the_loop_post(): void {
		$author = self::factory()->user->create();
		update_user_meta( $author, 'job_title', 'CEO' );
		$post = self::factory()->post->create( array( 'post_author' => $author ) );
		$this->go_to( get_permalink( $post ) );
		$block = (object) array( 'context' => array() );

		$this->assertSame( 'CEO', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_the_binding_callback_is_empty_without_a_post(): void {
		$this->go_to( home_url( '/' ) );

		$this->assertSame( '', \Kwv\get_author_role_binding( array(), (object) array( 'context' => array() ) ) );
	}

	public function test_the_binding_callback_is_empty_for_an_authorless_post(): void {
		$post  = self::factory()->post->create( array( 'post_author' => 0 ) );
		$block = (object) array( 'context' => array( 'postId' => $post ) );

		$this->assertSame( '', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_the_binding_source_is_registered_and_asks_for_the_post_id_context(): void {
		$source = \WP_Block_Bindings_Registry::get_instance()->get_registered( 'kwv/author-role' );

		$this->assertNotNull( $source );
		$this->assertContains( 'postId', $source->uses_context );
	}
}
