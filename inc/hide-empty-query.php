<?php
/**
 * Hide a section whose query loop found nothing.
 *
 * A Query Loop with no results still renders everything around it — the
 * section, its heading, and the "no results" block — so an empty "Upcoming
 * Events" strip would show a heading over nothing.
 *
 * Opt in by adding the `kwv-hide-if-empty` class to the Group that wraps the
 * query (and its heading). At render time, if that group contains no Post
 * Template list — core only outputs `.wp-block-post-template` when the query
 * returned posts — the whole group renders as an empty string.
 *
 * Front end only: the editor doesn't run render_block for static blocks, so
 * the section stays visible and editable there.
 *
 * @package kwv
 */

namespace Kwv\HideEmptyQuery;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const CLASS_NAME = 'kwv-hide-if-empty';

/**
 * Drop opted-in groups whose query loop rendered no posts.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block (name, attrs, …).
 * @return string Block HTML, or '' when the query inside it is empty.
 */
function hide_empty_query_group( $block_content, $block ) {

	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';

	if ( ! in_array( CLASS_NAME, preg_split( '/\s+/', $class_name ), true ) ) {
		return $block_content;
	}

	$tags = new \WP_HTML_Tag_Processor( (string) $block_content );

	if ( $tags->next_tag( array( 'class_name' => 'wp-block-post-template' ) ) ) {
		return $block_content;
	}

	return '';
}
add_filter( 'render_block_core/group', __NAMESPACE__ . '\hide_empty_query_group', 10, 2 );
