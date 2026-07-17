<?php
/**
 * Advanced Woo Search integration: KWV block styles + assets.
 *
 * The Advanced Woo Search plugin exposes two blocks — `advanced-woo-search/search-block`
 * and `advanced-woo-search/nav-search-block` — that both render the identical search
 * form markup (`aws_get_search_form()`), a `.aws-container` wrapper with no standard
 * block wrapper attributes. We layer two KWV brand looks on top as *block styles* so
 * they can be toggled from the editor:
 *
 *   - `is-style-kwv-search`        — a brand-styled inline search bar (used in the mobile menu).
 *   - `is-style-kwv-header-search` — collapses to a single icon in the header icon cluster
 *                                    and folds out over the nav when clicked.
 *
 * The plugin's render callback returns raw markup without `get_block_wrapper_attributes()`,
 * so WordPress never prints the block's `className` (the `is-style-*` class) onto the
 * output. We reattach it with a `render_block` filter (see apply_block_class()) using the
 * HTML Tag Processor — the Block Visibility plugin adds its own classes to `.aws-container`
 * on the same hook, so we must merge into whatever class list already exists rather than
 * matching an exact attribute string.
 *
 * The styling and fold-out behaviour live in assets/styles/aws-search.css and
 * assets/js/aws-search.js. They can't live in a block-style JSON `css` field: the
 * fold-out needs `:hover`/`:focus`, `content:""` icon pseudo-elements, transitions and
 * body-level result-dropdown positioning, all of which the `:where()`-wrapped css field
 * strips or mangles (see the theme AGENTS.md "Styling lives in JSON" note). AWS is a
 * front-end-only plugin, so its form never renders in the editor anyway.
 *
 * @package kwv
 */

namespace Kwv\Search;

const STYLE_HANDLE  = 'kwv-aws-search';
const SCRIPT_HANDLE = 'kwv-aws-search';

/**
 * The AWS blocks these styles attach to.
 *
 * @return string[]
 */
function block_names() {
	return array(
		'advanced-woo-search/search-block',
		'advanced-woo-search/nav-search-block',
	);
}

/**
 * Register the KWV block styles on the AWS search blocks.
 *
 * Registered late (priority 20) so the plugin has registered its blocks first;
 * register_block_style() only takes effect once the target block type exists.
 */
function register_block_styles() {

	if ( ! function_exists( 'register_block_style' ) ) {
		return;
	}

	$styles = array(
		'kwv-search'        => __( 'KWV Search Bar', 'kwv' ),
		'kwv-header-search' => __( 'KWV Header Search (Icon)', 'kwv' ),
	);

	foreach ( block_names() as $block ) {
		foreach ( $styles as $name => $label ) {
			register_block_style(
				$block,
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\register_block_styles', 20 );

/**
 * Reattach the block's className to the AWS form wrapper.
 *
 * The plugin renders `<div class="aws-container">` with no block wrapper attributes,
 * so the `is-style-*` class chosen in the editor would otherwise be lost. We splice
 * it back onto the container so our CSS/JS can key off it.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block.
 * @return string
 */
function apply_block_class( $block_content, $block ) {

	if ( empty( $block['blockName'] ) || 0 !== strpos( $block['blockName'], 'advanced-woo-search/' ) ) {
		return $block_content;
	}

	$classes = isset( $block['attrs']['className'] ) ? trim( $block['attrs']['className'] ) : '';

	if ( '' === $classes || ! class_exists( 'WP_HTML_Tag_Processor' ) ) {
		return $block_content;
	}

	// Merge the class into the container's existing class list. Using the Tag
	// Processor (rather than a string match) is essential: the Block Visibility
	// plugin adds its own classes to .aws-container on the same render_block
	// hook, so the attribute is not a predictable literal.
	$tags = new \WP_HTML_Tag_Processor( $block_content );

	if ( $tags->next_tag( array( 'class_name' => 'aws-container' ) ) ) {
		foreach ( preg_split( '/\s+/', $classes ) as $class ) {
			if ( '' !== $class ) {
				$tags->add_class( $class );
			}
		}
		return $tags->get_updated_html();
	}

	return $block_content;
}
add_filter( 'render_block', __NAMESPACE__ . '\apply_block_class', 20, 2 );

/**
 * Enqueue the KWV search styling and fold-out script on the front end.
 *
 * The stylesheet depends on the plugin's `aws-style` handle so it always cascades
 * after the plugin defaults (letting equal-specificity rules win by source order).
 * Both only load when the plugin is active.
 */
function enqueue_assets() {

	if ( is_admin() ) {
		return;
	}

	// AWS enqueues `aws-style` on the front end; bail if the plugin is inactive.
	if ( ! wp_style_is( 'aws-style', 'registered' ) && ! wp_style_is( 'aws-style', 'enqueued' ) ) {
		return;
	}

	wp_enqueue_style(
		STYLE_HANDLE,
		get_theme_file_uri( 'assets/styles/aws-search.css' ),
		array( 'aws-style' ),
		\Kwv\asset_version( 'assets/styles/aws-search.css' )
	);

	wp_enqueue_script(
		SCRIPT_HANDLE,
		get_theme_file_uri( 'assets/js/aws-search.js' ),
		array(),
		\Kwv\asset_version( 'assets/js/aws-search.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	wp_localize_script(
		SCRIPT_HANDLE,
		'kwvSearchL10n',
		array(
			'search' => __( 'Search', 'kwv' ),
			'close'  => __( 'Close search', 'kwv' ),
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets', 20 );
