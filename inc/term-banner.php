<?php
/**
 * Per-term banner images for product categories and brands.
 *
 * Gives every `product_cat` and `product_brand` term an optional wide "Banner
 * image", stored as the attachment ID in the `kwv_banner_image_id` term meta and
 * editable via a media uploader on the term add/edit screens. The
 * `kwv/term-banner-hero` pattern reads the current archive's term banner through
 * {@see get_queried_term_banner()} so a category or brand archive can show its own
 * hero visual, falling back to a shared default when none is set.
 *
 * This is separate from WooCommerce's small category/brand thumbnail: the banner
 * is a wide hero image with a different purpose and aspect ratio.
 *
 * @package kwv
 * @author  LightSpeed
 * @license GNU General Public License v2 or later
 * @link    https://kwv.co.za
 */

namespace Kwv;

/**
 * Term meta key holding the banner attachment ID.
 */
const BANNER_META_KEY = 'kwv_banner_image_id';

/**
 * Taxonomies that get a banner image field.
 *
 * @return string[]
 */
function banner_taxonomies() {
	return array( 'product_cat', 'product_brand' );
}

/**
 * Whether the current user may manage the banner meta on a term.
 *
 * Uses the WooCommerce product-terms capability, falling back to the core
 * taxonomy capability on installs where it is not present.
 *
 * @return bool
 */
function can_manage_banner() {
	return current_user_can( 'manage_product_terms' ) || current_user_can( 'manage_categories' );
}

/**
 * Register the banner term meta for each supported taxonomy.
 *
 * Exposed to the REST API so the value is available to the block editor and the
 * front end.
 */
function register_banner_meta() {
	foreach ( banner_taxonomies() as $taxonomy ) {
		register_term_meta(
			$taxonomy,
			BANNER_META_KEY,
			array(
				'type'              => 'integer',
				'description'       => __( 'Attachment ID of the term banner image.', 'kwv' ),
				'single'            => true,
				'default'           => 0,
				'show_in_rest'      => true,
				'sanitize_callback' => 'absint',
				'auth_callback'     => function () {
					return can_manage_banner();
				},
			)
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\register_banner_meta' );


/**
 * Register the add/edit form fields and save handlers for each taxonomy.
 */
function register_banner_admin_hooks() {
	foreach ( banner_taxonomies() as $taxonomy ) {
		add_action( "{$taxonomy}_add_form_fields", __NAMESPACE__ . '\render_banner_add_field' );
		add_action( "{$taxonomy}_edit_form_fields", __NAMESPACE__ . '\render_banner_edit_field', 10, 2 );
		add_action( "created_{$taxonomy}", __NAMESPACE__ . '\save_banner_field' );
		add_action( "edited_{$taxonomy}", __NAMESPACE__ . '\save_banner_field' );
	}
}
add_action( 'admin_init', __NAMESPACE__ . '\register_banner_admin_hooks' );


/**
 * Enqueue the media uploader and admin script on the banner term screens only.
 *
 * @param string $hook_suffix The current admin page.
 */
function enqueue_banner_admin_assets( $hook_suffix ) {
	if ( 'edit-tags.php' !== $hook_suffix && 'term.php' !== $hook_suffix ) {
		return;
	}

	$screen = get_current_screen();
	if ( ! $screen || ! in_array( $screen->taxonomy, banner_taxonomies(), true ) ) {
		return;
	}

	wp_enqueue_media();

	wp_enqueue_script(
		'kwv-term-banner-admin',
		get_theme_file_uri( 'assets/js/term-banner-admin.js' ),
		array( 'jquery' ),
		asset_version( 'assets/js/term-banner-admin.js' ),
		array( 'in_footer' => true )
	);

	wp_localize_script(
		'kwv-term-banner-admin',
		'kwvTermBanner',
		array(
			'title'  => __( 'Select banner image', 'kwv' ),
			'button' => __( 'Use this image', 'kwv' ),
		)
	);
}
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_banner_admin_assets' );


/**
 * Render the banner picker controls (button, hidden input, preview).
 *
 * Shared by the add and edit screens.
 *
 * @param int $banner_id The currently-selected attachment ID (0 when none).
 */
function render_banner_controls( $banner_id ) {
	$banner_id  = (int) $banner_id;
	$banner_url = $banner_id ? wp_get_attachment_image_url( $banner_id, 'medium' ) : '';

	wp_nonce_field( 'kwv_banner_save', 'kwv_banner_nonce' );
	?>
	<div class="kwv-banner-field">
		<div class="kwv-banner-preview">
			<?php if ( $banner_url ) : ?>
				<img src="<?php echo esc_url( $banner_url ); ?>" alt="" style="max-width:300px;height:auto;display:block;" />
			<?php endif; ?>
		</div>
		<input type="hidden" name="kwv_banner_image_id" class="kwv-banner-input" value="<?php echo esc_attr( $banner_id ); ?>" />
		<p>
			<button type="button" class="button kwv-banner-select"><?php esc_html_e( 'Select image', 'kwv' ); ?></button>
			<button type="button" class="button kwv-banner-remove"<?php echo $banner_id ? '' : ' style="display:none;"'; ?>><?php esc_html_e( 'Remove image', 'kwv' ); ?></button>
		</p>
		<p class="description"><?php esc_html_e( 'Wide image shown in the hero of this term’s archive page.', 'kwv' ); ?></p>
	</div>
	<?php
}


/**
 * Render the banner field on the "Add new term" screen.
 */
function render_banner_add_field() {
	?>
	<div class="form-field term-banner-wrap">
		<label><?php esc_html_e( 'Banner image', 'kwv' ); ?></label>
		<?php render_banner_controls( 0 ); ?>
	</div>
	<?php
}


/**
 * Render the banner field on the "Edit term" screen.
 *
 * @param \WP_Term $term The term being edited.
 */
function render_banner_edit_field( $term ) {
	$banner_id = get_term_meta( $term->term_id, BANNER_META_KEY, true );
	?>
	<tr class="form-field term-banner-wrap">
		<th scope="row"><label><?php esc_html_e( 'Banner image', 'kwv' ); ?></label></th>
		<td><?php render_banner_controls( (int) $banner_id ); ?></td>
	</tr>
	<?php
}


/**
 * Save the banner field for a term.
 *
 * @param int $term_id The ID of the term being saved.
 */
function save_banner_field( $term_id ) {
	if ( ! isset( $_POST['kwv_banner_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['kwv_banner_nonce'] ) ), 'kwv_banner_save' ) ) {
		return;
	}

	if ( ! can_manage_banner() ) {
		return;
	}

	if ( ! isset( $_POST['kwv_banner_image_id'] ) ) {
		return;
	}

	$banner_id = absint( wp_unslash( $_POST['kwv_banner_image_id'] ) );

	if ( $banner_id ) {
		update_term_meta( $term_id, BANNER_META_KEY, $banner_id );
	} else {
		delete_term_meta( $term_id, BANNER_META_KEY );
	}
}


/**
 * Register a block binding source that resolves the current term's banner URL.
 *
 * Runs at render time (unlike raw PHP baked into a pattern file, which executes
 * once at pattern registration when there is no queried object), so
 * `get_queried_object()` is the real category/brand term. Bind it to an image's
 * `url`:
 * `{"metadata":{"bindings":{"url":{"source":"kwv/term-banner"}}}}`.
 *
 * Returns null when the term has no banner (or on non-term views), which tells
 * core to leave the image's baked `src` in place — that is the shared fallback
 * image, so no per-request fallback lookup is needed here.
 */
function register_term_banner_binding() {
	if ( ! function_exists( 'register_block_bindings_source' ) ) {
		return;
	}

	register_block_bindings_source(
		'kwv/term-banner',
		array(
			'label'              => __( 'Term Banner Image', 'kwv' ),
			'get_value_callback' => __NAMESPACE__ . '\get_term_banner_binding',
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_term_banner_binding' );


/**
 * Resolve the bound value for the `kwv/term-banner` source.
 *
 * @param array     $source_args    Arguments passed to the binding (unused).
 * @param \WP_Block $block_instance The bound block instance (unused).
 * @param string    $attribute_name The image attribute being bound.
 * @return string|null The banner URL for the `url` attribute, or null to leave
 *                     the block's own value in place.
 */
function get_term_banner_binding( $source_args, $block_instance, $attribute_name ) {
	if ( 'url' !== $attribute_name ) {
		return null;
	}

	$banner = get_queried_term_banner( 'large' );

	return $banner['url'] ? $banner['url'] : null;
}


/**
 * Resolve the banner image for the currently-queried term.
 *
 * Returns the banner attachment ID and URL when the current request is a
 * `product_cat` or `product_brand` archive whose term has a banner set;
 * otherwise returns empty values so callers can fall back to a default image.
 *
 * @param string $size Image size slug. Default 'large'.
 * @return array{id:int,url:string}
 */
function get_queried_term_banner( $size = 'large' ) {
	$empty = array(
		'id'  => 0,
		'url' => '',
	);

	$term = get_queried_object();

	if ( ! $term instanceof \WP_Term || ! in_array( $term->taxonomy, banner_taxonomies(), true ) ) {
		return $empty;
	}

	$banner_id = (int) get_term_meta( $term->term_id, BANNER_META_KEY, true );

	if ( ! $banner_id ) {
		return $empty;
	}

	$url = wp_get_attachment_image_url( $banner_id, $size );

	if ( ! $url ) {
		return $empty;
	}

	return array(
		'id'  => $banner_id,
		'url' => (string) $url,
	);
}
