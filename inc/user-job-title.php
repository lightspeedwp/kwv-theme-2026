<?php
/**
 * User job titles for the KWV theme.
 *
 * A person's job title (e.g. "Cellar Master", "CEO") is stored as the
 * `job_title` meta on the WordPress user, editable on every user's profile
 * screen and available to all roles. The `kwv/author-role` block binding reads
 * this value so a post's byline can show the author's title dynamically.
 *
 * This replaces the former `kwv_team` custom post type: the only field it
 * carried was the role, and its one remaining dynamic consumer — the author
 * byline — maps a person to a WordPress user, so the value belongs on the user.
 *
 * @package kwv
 * @author  LightSpeed
 * @license GNU General Public License v2 or later
 * @link    https://kwv.co.za
 */

namespace Kwv;

const JOB_TITLE_META_KEY = 'job_title';

/**
 * Register the `job_title` user meta.
 *
 * Exposed to the REST API so it is available to the block editor and the
 * front end. Available on every user regardless of role.
 */
function register_job_title_meta() {
	register_meta(
		'user',
		JOB_TITLE_META_KEY,
		array(
			'type'              => 'string',
			'description'       => __( 'The user\'s job title.', 'kwv' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function ( $allowed, $meta_key, $object_id ) {
				return current_user_can( 'edit_user', (int) $object_id );
			},
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_job_title_meta' );


/**
 * Render the Job Title field on the user profile screen.
 *
 * Fires for both the current user's own profile (`show_user_profile`) and when
 * editing another user (`edit_user_profile`), so it is available to every role.
 *
 * @param \WP_User $user The user being edited.
 */
function render_job_title_field( $user ) {
	$job_title = get_user_meta( $user->ID, JOB_TITLE_META_KEY, true );

	wp_nonce_field( 'kwv_job_title_save', 'kwv_job_title_nonce' );
	?>
	<h2><?php esc_html_e( 'KWV', 'kwv' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th>
				<label for="kwv_job_title_field"><?php esc_html_e( 'Job title', 'kwv' ); ?></label>
			</th>
			<td>
				<input
					type="text"
					id="kwv_job_title_field"
					name="kwv_job_title_field"
					class="regular-text"
					value="<?php echo esc_attr( $job_title ); ?>"
					placeholder="<?php esc_attr_e( 'e.g. Cellar Master', 'kwv' ); ?>"
				/>
				<p class="description">
					<?php esc_html_e( 'Shown in the byline of posts this user authors.', 'kwv' ); ?>
				</p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', __NAMESPACE__ . '\render_job_title_field' );
add_action( 'edit_user_profile', __NAMESPACE__ . '\render_job_title_field' );


/**
 * Save the Job Title field from the user profile screen.
 *
 * @param int $user_id The ID of the user being saved.
 */
function save_job_title_field( $user_id ) {

	// Verify the nonce.
	if ( ! isset( $_POST['kwv_job_title_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kwv_job_title_nonce'] ) ), 'kwv_job_title_save' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return;
	}

	if ( isset( $_POST['kwv_job_title_field'] ) ) {
		update_user_meta(
			$user_id,
			JOB_TITLE_META_KEY,
			sanitize_text_field( wp_unslash( $_POST['kwv_job_title_field'] ) )
		);
	}
}
add_action( 'personal_options_update', __NAMESPACE__ . '\save_job_title_field' );
add_action( 'edit_user_profile_update', __NAMESPACE__ . '\save_job_title_field' );


/**
 * Register a block binding source that resolves a post author's job title.
 *
 * Reads the `job_title` meta from the current post's author so a byline can
 * show the author's title dynamically without duplicating the value on every
 * post. Bind it to a paragraph's `content`:
 * `{"metadata":{"bindings":{"content":{"source":"kwv/author-role"}}}}`.
 */
function register_author_role_binding() {
	if ( ! function_exists( 'register_block_bindings_source' ) ) {
		return;
	}

	register_block_bindings_source(
		'kwv/author-role',
		array(
			'label'              => __( 'Author Job Title', 'kwv' ),
			'get_value_callback' => __NAMESPACE__ . '\get_author_role_binding',
			'uses_context'       => array( 'postId' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_author_role_binding' );


/**
 * Resolve the job title for a post's author.
 *
 * @param array     $source_args    Arguments passed to the binding (unused).
 * @param \WP_Block $block_instance The bound block instance.
 * @return string The job title, or an empty string when none is set.
 */
function get_author_role_binding( $source_args, $block_instance ) {
	$post_id = isset( $block_instance->context['postId'] )
		? (int) $block_instance->context['postId']
		: (int) get_the_ID();

	if ( ! $post_id ) {
		return '';
	}

	$author_id = (int) get_post_field( 'post_author', $post_id );

	if ( ! $author_id ) {
		return '';
	}

	return (string) get_user_meta( $author_id, JOB_TITLE_META_KEY, true );
}
