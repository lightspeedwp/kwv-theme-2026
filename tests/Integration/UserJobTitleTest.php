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

	public function test_the_profile_field_shows_every_label_and_hint(): void {
		ob_start();
		\Kwv\render_job_title_field( get_userdata( self::factory()->user->create() ) );
		$html = ob_get_clean();

		$this->assertStringContainsString( '<h2>KWV</h2>', $html );
		$this->assertStringContainsString( '>Job title</label>', $html );
		$this->assertStringContainsString( 'placeholder="e.g. Cellar Master"', $html );
		$this->assertStringContainsString( 'Shown in the byline of posts this user authors.', $html );
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

	public function test_a_user_may_save_their_own_title(): void {
		$me                           = $this->actAs( 'subscriber' );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'kwv_job_title_save' );
		$_POST['kwv_job_title_field'] = 'Tasting Host';

		\Kwv\save_job_title_field( $me );

		$this->assertSame( 'Tasting Host', get_user_meta( $me, 'job_title', true ) );
	}

	/**
	 * @return array<string,array{0:string}>
	 */
	public function rolesWhoCannotEditOthers(): array {
		return array(
			'editor'       => array( 'editor' ),
			'author'       => array( 'author' ),
			'shop manager' => array( 'shop_manager' ),
			'subscriber'   => array( 'subscriber' ),
		);
	}

	/**
	 * @dataProvider rolesWhoCannotEditOthers
	 */
	public function test_only_administrators_change_someone_elses_title( string $role ): void {
		$target = self::factory()->user->create();
		update_user_meta( $target, 'job_title', 'Original' );
		$this->actAs( $role );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'kwv_job_title_save' );
		$_POST['kwv_job_title_field'] = 'Changed';

		do_action( 'edit_user_profile_update', $target );

		$this->assertSame( 'Original', get_user_meta( $target, 'job_title', true ) );
	}

	public function test_a_nonce_for_another_action_is_rejected(): void {
		$target = self::factory()->user->create();
		$this->actAs( 'administrator' );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'update-user_' . $target );
		$_POST['kwv_job_title_field'] = 'Hacker';

		\Kwv\save_job_title_field( $target );

		$this->assertSame( '', get_user_meta( $target, 'job_title', true ) );
	}

	public function test_a_request_without_the_nonce_is_rejected(): void {
		$target = self::factory()->user->create();
		$this->actAs( 'administrator' );
		$_POST['kwv_job_title_field'] = 'Hacker';

		\Kwv\save_job_title_field( $target );

		$this->assertSame( '', get_user_meta( $target, 'job_title', true ) );
	}

	public function test_a_request_without_the_field_keeps_the_existing_title(): void {
		$target = self::factory()->user->create();
		update_user_meta( $target, 'job_title', 'Keep me' );
		$this->actAs( 'administrator' );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'kwv_job_title_save' );

		\Kwv\save_job_title_field( $target );

		$this->assertSame( 'Keep me', get_user_meta( $target, 'job_title', true ) );
	}

	public function test_an_empty_field_clears_the_title(): void {
		$target = self::factory()->user->create();
		update_user_meta( $target, 'job_title', 'Old title' );
		$this->actAs( 'administrator' );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'kwv_job_title_save' );
		$_POST['kwv_job_title_field'] = '';

		\Kwv\save_job_title_field( $target );

		$this->assertSame( '', get_user_meta( $target, 'job_title', true ) );
	}

	/**
	 * @return array<string,array{0:string,1:string}> Posted value => stored value.
	 */
	public function hostileTitles(): array {
		return array(
			'script tag'          => array( '"><script>alert(1)</script>Chief', '">Chief' ),
			'inline event'        => array( '<img src=x onerror=alert(1)>Boss', 'Boss' ),
			'javascript url text' => array( 'javascript:alert(1)', 'javascript:alert(1)' ),
			'newlines collapse'   => array( "Line one\nLine two", 'Line one Line two' ),
			'slashes removed'     => array( 'O\\\\Brien', 'O\\Brien' ),
		);
	}

	/**
	 * @dataProvider hostileTitles
	 */
	public function test_a_posted_title_is_stored_as_plain_text( string $posted, string $expected ): void {
		$target = self::factory()->user->create();
		$this->actAs( 'administrator' );
		$_POST['kwv_job_title_nonce'] = wp_create_nonce( 'kwv_job_title_save' );
		$_POST['kwv_job_title_field'] = wp_slash( $posted );

		\Kwv\save_job_title_field( $target );

		$stored = get_user_meta( $target, 'job_title', true );
		$this->assertStringNotContainsString( '<', $stored );
		$this->assertSame( $expected, $stored );
	}

	public function test_a_hostile_stored_title_is_escaped_in_the_profile_field(): void {
		global $wpdb;
		$user = self::factory()->user->create();
		// Written straight to the table to bypass the meta sanitiser.
		$wpdb->insert( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
			$wpdb->usermeta,
			array(
				'user_id'    => $user,
				'meta_key'   => 'job_title', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_value' => '"><script>alert(1)</script>', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
			)
		);
		wp_cache_delete( $user, 'user_meta' );

		ob_start();
		\Kwv\render_job_title_field( get_userdata( $user ) );
		$html = ob_get_clean();

		$this->assertStringNotContainsString( '<script>', $html );
		$this->assertStringContainsString( 'value="&quot;&gt;&lt;script&gt;', $html );
	}

	public function test_the_profile_field_is_empty_for_a_user_without_a_title(): void {
		$user = self::factory()->user->create();

		ob_start();
		\Kwv\render_job_title_field( get_userdata( $user ) );
		$html = ob_get_clean();

		$this->assertStringContainsString( 'value=""', $html );
		$this->assertStringContainsString( 'id="kwv_job_title_field"', $html );
		$this->assertStringContainsString( 'name="kwv_job_title_field"', $html );
	}

	public function test_a_context_post_id_of_zero_does_not_fall_back_to_the_loop(): void {
		$author = self::factory()->user->create();
		update_user_meta( $author, 'job_title', 'CEO' );
		$post = self::factory()->post->create( array( 'post_author' => $author ) );
		$this->go_to( get_permalink( $post ) );

		$this->assertSame( '', \Kwv\get_author_role_binding( array(), (object) array( 'context' => array( 'postId' => 0 ) ) ) );
	}

	public function test_a_context_post_that_does_not_exist_resolves_to_nothing(): void {
		$block = (object) array( 'context' => array( 'postId' => 987654 ) );

		$this->assertSame( '', \Kwv\get_author_role_binding( array(), $block ) );
	}

	public function test_an_author_without_a_title_resolves_to_an_empty_string(): void {
		$author = self::factory()->user->create();
		$post   = self::factory()->post->create( array( 'post_author' => $author ) );

		$result = \Kwv\get_author_role_binding( array(), (object) array( 'context' => array( 'postId' => $post ) ) );

		$this->assertSame( '', $result );
	}

	public function test_the_binding_reads_the_authors_title_not_the_current_users(): void {
		$author = self::factory()->user->create();
		update_user_meta( $author, 'job_title', 'Author title' );
		$viewer = $this->actAs( 'administrator' );
		update_user_meta( $viewer, 'job_title', 'Viewer title' );
		$post = self::factory()->post->create( array( 'post_author' => $author ) );

		$result = \Kwv\get_author_role_binding( array(), (object) array( 'context' => array( 'postId' => $post ) ) );

		$this->assertSame( 'Author title', $result );
	}

	public function test_the_meta_is_not_registered_on_posts_or_terms(): void {
		$this->assertArrayNotHasKey( 'job_title', get_registered_meta_keys( 'post' ) );
		$this->assertArrayNotHasKey( 'job_title', get_registered_meta_keys( 'term' ) );
	}

	public function test_a_non_string_value_is_stored_as_text(): void {
		$user = self::factory()->user->create();
		update_user_meta( $user, 'job_title', 42 );

		$this->assertSame( '42', get_user_meta( $user, 'job_title', true ) );
	}

	public function test_the_meta_is_readable_through_the_rest_api(): void {
		$author = self::factory()->user->create( array( 'role' => 'administrator' ) );
		update_user_meta( $author, 'job_title', 'Cellar Master' );
		wp_set_current_user( $author );
		register_rest_field( 'user', '__noop', array( 'get_callback' => '__return_null' ) );

		$request = new \WP_REST_Request( 'GET', '/wp/v2/users/' . $author );
		$request->set_param( 'context', 'edit' );
		$response = rest_do_request( $request );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame( 'Cellar Master', $response->get_data()['meta']['job_title'] );
	}

	public function test_the_binding_label_is_set(): void {
		$source = \WP_Block_Bindings_Registry::get_instance()->get_registered( 'kwv/author-role' );

		$this->assertSame( 'Author Job Title', $source->label );
	}

	public function test_the_binding_source_can_be_re_registered_with_its_callback_and_context(): void {
		unregister_block_bindings_source( 'kwv/author-role' );

		\Kwv\register_author_role_binding();

		$source = \WP_Block_Bindings_Registry::get_instance()->get_registered( 'kwv/author-role' );
		$this->assertSame( array( 'postId' ), $source->uses_context );
		$this->assertSame( 'Kwv\get_author_role_binding', ( new \ReflectionProperty( $source, 'get_value_callback' ) )->getValue( $source ) );
	}
}
