<?php
/**
 * KWV mega menu renderer.
 *
 * Renders the product-category fold-out menu used inside the Ollie Menu Designer
 * dropdowns ("Wines" / "Spirits" in the main navigation). The menu is output by a
 * shortcode so it can live inside a `menu`-area template part — Ollie's mega-menu
 * block runs `do_shortcode()` over the template-part output (see the plugin's
 * render.php), which is our injection point.
 *
 * Data source: the `product_cat` taxonomy. On dev these are dummy categories; on
 * the live site the same query pulls the real ones, so no markup changes are needed.
 *
 * Interaction: the three columns (parents -> children -> grandchildren) fold out to
 * the side via the WordPress Interactivity API. The first parent and its first
 * child that has grandchildren are open by default. The store lives in
 * assets/js/mega-menu.js.
 *
 * @package kwv
 */

namespace Kwv\MegaMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const STORE       = 'kwv/megamenu';
const MODULE_ID   = 'kwv-mega-menu';
const STYLE_HANDLE = 'kwv-mega-menu';

/**
 * Register the Interactivity API view module and the mega-menu stylesheet.
 *
 * The stylesheet is attached to the Ollie mega-menu block so it only loads on
 * pages where the dropdown is present. The script module is registered here and
 * enqueued on demand by the shortcode.
 */
function register_assets() {
	wp_register_script_module(
		MODULE_ID,
		get_theme_file_uri( 'assets/js/mega-menu.js' ),
		array( '@wordpress/interactivity' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_block_style(
		'ollie/mega-menu',
		array(
			'handle' => STYLE_HANDLE,
			'src'    => get_theme_file_uri( 'assets/styles/ollie-mega-menu.css' ),
			'path'   => get_theme_file_path( 'assets/styles/ollie-mega-menu.css' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_assets' );

/**
 * Register the [kwv_mega_menu] shortcode.
 */
function register_shortcode() {
	add_shortcode( 'kwv_mega_menu', __NAMESPACE__ . '\render' );
}
add_action( 'init', __NAMESPACE__ . '\register_shortcode' );

/**
 * Register the search block style used by the mega-menu search field.
 */
function register_search_style() {
	register_block_style(
		'core/search',
		array(
			'name'  => 'kwv-megamenu-search',
			'label' => __( 'KWV Search', 'kwv' ),
		)
	);
}
add_action( 'init', __NAMESPACE__ . '\register_search_style' );

/**
 * Fetch direct child terms of a product category, ordered by the WooCommerce
 * category display order (term meta `order`), falling back to name.
 *
 * @param int $parent_id Parent term ID (0 for top level).
 * @return \WP_Term[]
 */
function get_children( $parent_id ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => (int) $parent_id,
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return array();
	}

	usort(
		$terms,
		static function ( $a, $b ) {
			$oa = (int) get_term_meta( $a->term_id, 'order', true );
			$ob = (int) get_term_meta( $b->term_id, 'order', true );
			if ( $oa === $ob ) {
				return strcasecmp( $a->name, $b->name );
			}
			return $oa <=> $ob;
		}
	);

	return $terms;
}

/**
 * Render the mega menu.
 *
 * @param array $atts Shortcode attributes. `root` selects which top parent is open
 *                    by default (slug, e.g. "all-wines"). Empty = first parent.
 * @return string Processed Interactivity API markup.
 */
function render( $atts ) {
	$atts = shortcode_atts( array( 'root' => '' ), $atts, 'kwv_mega_menu' );

	$parents = get_children( 0 );
	// Only treat the two shop roots as menu parents; ignore Uncategorized etc.
	$parents = array_values(
		array_filter(
			$parents,
			static function ( $term ) {
				return in_array( $term->slug, array( 'all-wines', 'all-spirits' ), true );
			}
		)
	);

	if ( empty( $parents ) ) {
		return '';
	}

	// Resolve the default-open parent.
	$default_parent = $parents[0]->slug;
	foreach ( $parents as $p ) {
		if ( $p->slug === $atts['root'] ) {
			$default_parent = $p->slug;
			break;
		}
	}

	// Resolve the default-open child: the first child of the default parent that
	// has grandchildren (matches the Figma states), else its first child.
	$default_child = '';
	foreach ( $parents as $p ) {
		if ( $p->slug !== $default_parent ) {
			continue;
		}
		$children = get_children( $p->term_id );
		foreach ( $children as $c ) {
			if ( '' === $default_child ) {
				$default_child = $c->slug; // first child fallback.
			}
			if ( get_children( $c->term_id ) ) {
				$default_child = $c->slug; // prefer first child with grandchildren.
				break;
			}
		}
		break;
	}

	wp_enqueue_script_module( MODULE_ID );

	$root_context = wp_json_encode(
		array(
			'activeParent' => $default_parent,
			'activeChild'  => $default_child,
		)
	);

	ob_start();
	?>
	<div
		class="kwv-mega-menu"
		data-wp-interactive="<?php echo esc_attr( STORE ); ?>"
		data-wp-context="<?php echo esc_attr( $root_context ); ?>"
	>
		<?php // Column 1 — parents. ?>
		<ul class="kwv-mega-menu__col kwv-mega-menu__parents" role="list">
			<?php foreach ( $parents as $parent ) : ?>
				<?php
				$children      = get_children( $parent->term_id );
				$first_child   = '';
				foreach ( $children as $c ) {
					if ( '' === $first_child ) {
						$first_child = $c->slug;
					}
					if ( get_children( $c->term_id ) ) {
						$first_child = $c->slug;
						break;
					}
				}
				$ctx = wp_json_encode(
					array(
						'parentSlug'     => $parent->slug,
						'firstChildSlug' => $first_child,
					)
				);
				?>
				<li
					class="kwv-mega-menu__row"
					data-wp-context="<?php echo esc_attr( $ctx ); ?>"
					data-wp-class--is-active="state.isParentActive"
					data-wp-on--mouseenter="actions.selectParent"
				>
					<a class="kwv-mega-menu__link" href="<?php echo esc_url( get_term_link( $parent ) ); ?>" data-wp-on--click="actions.selectParent">
						<span class="kwv-mega-menu__label"><?php echo esc_html( $parent->name ); ?></span>
						<?php if ( $children ) : ?>
							<span class="kwv-mega-menu__chevron" aria-hidden="true"></span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>

		<?php // Column 2 — children (one list per parent, only the active one shown). ?>
		<div class="kwv-mega-menu__col kwv-mega-menu__children">
			<?php foreach ( $parents as $parent ) : ?>
				<?php $children = get_children( $parent->term_id ); ?>
				<?php if ( ! $children ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<ul
					class="kwv-mega-menu__list"
					role="list"
					data-wp-context="<?php echo esc_attr( wp_json_encode( array( 'parentSlug' => $parent->slug ) ) ); ?>"
					data-wp-bind--hidden="!state.isParentActive"
				>
					<?php foreach ( $children as $child ) : ?>
						<?php $grandchildren = get_children( $child->term_id ); ?>
						<li
							class="kwv-mega-menu__row"
							data-wp-context="<?php echo esc_attr( wp_json_encode( array( 'childSlug' => $child->slug ) ) ); ?>"
							data-wp-class--is-active="state.isChildActive"
							data-wp-on--mouseenter="actions.selectChild"
						>
							<a class="kwv-mega-menu__link" href="<?php echo esc_url( get_term_link( $child ) ); ?>" data-wp-on--click="actions.selectChild">
								<span class="kwv-mega-menu__label"><?php echo esc_html( $child->name ); ?></span>
								<?php if ( $grandchildren ) : ?>
									<span class="kwv-mega-menu__chevron" aria-hidden="true"></span>
								<?php endif; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>
		</div>

		<?php // Column 3 — grandchildren (one list per child, only the active one shown). ?>
		<div class="kwv-mega-menu__col kwv-mega-menu__grandchildren">
			<?php foreach ( $parents as $parent ) : ?>
				<?php foreach ( get_children( $parent->term_id ) as $child ) : ?>
					<?php $grandchildren = get_children( $child->term_id ); ?>
					<?php if ( ! $grandchildren ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<ul
						class="kwv-mega-menu__list"
						role="list"
						data-wp-context="<?php echo esc_attr( wp_json_encode( array( 'parentSlug' => $parent->slug, 'childSlug' => $child->slug ) ) ); ?>"
						data-wp-bind--hidden="!state.isGrandchildVisible"
					>
						<?php foreach ( $grandchildren as $grandchild ) : ?>
							<li class="kwv-mega-menu__row">
								<a class="kwv-mega-menu__link" href="<?php echo esc_url( get_term_link( $grandchild ) ); ?>">
									<span class="kwv-mega-menu__label"><?php echo esc_html( $grandchild->name ); ?></span>
								</a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endforeach; ?>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	$html = ob_get_clean();

	// Resolve directives to their server-rendered initial state (e.g. hidden lists).
	if ( function_exists( 'wp_interactivity_process_directives' ) ) {
		$html = wp_interactivity_process_directives( $html );
	}

	return $html;
}
