<?php
/**
 * Team post type for the KWV theme.
 *
 * Registers the `kwv_team` post type and its `role` field. A team member uses:
 * - the post title for the member's name,
 * - the featured image for their photo,
 * - the post content for their short bio,
 * - the `kwv_team_role` meta field for their role.
 *
 * @package kwv
 * @author  LightSpeed
 * @license GNU General Public License v2 or later
 * @link    https://kwv.co.za
 */

namespace Kwv;

/**
 * Register the Team post type.
 */
function register_team_post_type() {

	$labels = array(
		'name'                  => _x( 'Team', 'Post type general name', 'kwv' ),
		'singular_name'         => _x( 'Team Member', 'Post type singular name', 'kwv' ),
		'menu_name'             => _x( 'Team', 'Admin Menu text', 'kwv' ),
		'name_admin_bar'        => _x( 'Team Member', 'Add New on Toolbar', 'kwv' ),
		'add_new'               => __( 'Add New', 'kwv' ),
		'add_new_item'          => __( 'Add New Team Member', 'kwv' ),
		'new_item'              => __( 'New Team Member', 'kwv' ),
		'edit_item'             => __( 'Edit Team Member', 'kwv' ),
		'view_item'             => __( 'View Team Member', 'kwv' ),
		'all_items'             => __( 'All Team Members', 'kwv' ),
		'search_items'          => __( 'Search Team Members', 'kwv' ),
		'not_found'             => __( 'No team members found.', 'kwv' ),
		'not_found_in_trash'    => __( 'No team members found in Trash.', 'kwv' ),
		'featured_image'        => __( 'Photo', 'kwv' ),
		'set_featured_image'    => __( 'Set photo', 'kwv' ),
		'remove_featured_image' => __( 'Remove photo', 'kwv' ),
		'use_featured_image'    => __( 'Use as photo', 'kwv' ),
		'archives'              => __( 'Team Archives', 'kwv' ),
		'item_published'        => __( 'Team member published.', 'kwv' ),
		'item_updated'          => __( 'Team member updated.', 'kwv' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true,
		'menu_icon'          => 'dashicons-groups',
		'menu_position'      => 20,
		'has_archive'        => true,
		'hierarchical'       => false,
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
		'rewrite'            => array( 'slug' => 'team' ),
		'capability_type'    => 'post',
	);

	register_post_type( 'kwv_team', $args );
}
add_action( 'init', __NAMESPACE__ . '\register_team_post_type' );


/**
 * Register the team member role meta field.
 *
 * Exposed to the REST API so it is available to the block editor and the
 * front end.
 */
function register_team_meta() {
	register_post_meta(
		'kwv_team',
		'kwv_team_role',
		array(
			'type'              => 'string',
			'description'       => __( 'The team member\'s role.', 'kwv' ),
			'single'            => true,
			'default'           => '',
			'show_in_rest'      => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function () {
				return current_user_can( 'edit_posts' );
			},
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_team_meta' );


/**
 * Add the Role meta box to the Team editor.
 */
function add_team_role_meta_box() {
	add_meta_box(
		'kwv_team_role',
		__( 'Role', 'kwv' ),
		__NAMESPACE__ . '\render_team_role_meta_box',
		'kwv_team',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', __NAMESPACE__ . '\add_team_role_meta_box' );


/**
 * Render the Role meta box.
 *
 * @param \WP_Post $post The current post object.
 */
function render_team_role_meta_box( $post ) {
	$role = get_post_meta( $post->ID, 'kwv_team_role', true );

	wp_nonce_field( 'kwv_team_role_save', 'kwv_team_role_nonce' );
	?>
	<p>
		<label class="screen-reader-text" for="kwv_team_role_field">
			<?php esc_html_e( 'Role', 'kwv' ); ?>
		</label>
		<input
			type="text"
			id="kwv_team_role_field"
			name="kwv_team_role_field"
			class="widefat"
			value="<?php echo esc_attr( $role ); ?>"
			placeholder="<?php esc_attr_e( 'e.g. Cellar Master', 'kwv' ); ?>"
		/>
	</p>
	<?php
}


/**
 * Save the Role meta box value.
 *
 * @param int $post_id The ID of the post being saved.
 */
function save_team_role_meta_box( $post_id ) {

	// Verify the nonce.
	if ( ! isset( $_POST['kwv_team_role_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kwv_team_role_nonce'] ) ), 'kwv_team_role_save' ) ) {
		return;
	}

	// Bail on autosave and capability failure.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['kwv_team_role_field'] ) ) {
		update_post_meta(
			$post_id,
			'kwv_team_role',
			sanitize_text_field( wp_unslash( $_POST['kwv_team_role_field'] ) )
		);
	}
}
add_action( 'save_post_kwv_team', __NAMESPACE__ . '\save_team_role_meta_box' );
