<?php
/**
 * Lazy-loaded cover background video.
 *
 * `core/cover` with a video background renders a bare
 * `<video autoplay muted loop playsinline src="…">`, so the browser starts
 * downloading the file immediately. For the homepage "Our Legacy" band (a
 * multi-megabyte ambient clip well below the fold) that is wasted bandwidth.
 *
 * Opt in by adding the `kwv-lazy-video` class to the Cover block. At render
 * time this module then:
 *   - swaps `src` for `data-src` and sets `preload="none"`, so nothing is
 *     fetched and the Cover's `poster` image shows instead;
 *   - drops `autoplay`, and marks the video decorative (`aria-hidden`);
 *   - enqueues assets/js/lazy-video.js, which loads + plays the video when the
 *     section nears the viewport and never plays it under
 *     `prefers-reduced-motion: reduce` (poster only).
 *
 * Without this module (or without JS) the saved markup is still a valid plain
 * Cover video that autoplays — it degrades, it does not break.
 *
 * Block validation is untouched: all changes happen on the rendered output, so
 * the markup stored in templates/DB stays exactly what the Cover block saves.
 *
 * @package kwv
 */

namespace Kwv\LazyVideo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SCRIPT_HANDLE = 'kwv-lazy-video';
const CLASS_NAME    = 'kwv-lazy-video';

/**
 * Defer the background video of opted-in Cover blocks.
 *
 * @param string $block_content Rendered block HTML.
 * @param array  $block         Parsed block (name, attrs, …).
 * @return string Block HTML with the video made lazy.
 */
function defer_cover_video( $block_content, $block ) {

	$class_name = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';

	if ( ! in_array( CLASS_NAME, preg_split( '/\s+/', $class_name ), true ) ) {
		return $block_content;
	}

	$tags = new \WP_HTML_Tag_Processor( (string) $block_content );

	if ( ! $tags->next_tag(
		array(
			'tag_name'   => 'video',
			'class_name' => 'wp-block-cover__video-background',
		)
	) ) {
		return $block_content;
	}

	$src = $tags->get_attribute( 'src' );

	if ( ! is_string( $src ) || '' === $src ) {
		return $block_content;
	}

	$tags->set_attribute( 'data-src', esc_url( $src ) );
	$tags->remove_attribute( 'src' );
	$tags->remove_attribute( 'autoplay' );
	$tags->set_attribute( 'preload', 'none' );
	$tags->set_attribute( 'aria-hidden', 'true' );
	$tags->set_attribute( 'tabindex', '-1' );
	$tags->set_attribute( 'data-kwv-lazy-video', '' );

	wp_enqueue_script(
		SCRIPT_HANDLE,
		get_theme_file_uri( 'assets/js/lazy-video.js' ),
		array(),
		\Kwv\asset_version( 'assets/js/lazy-video.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);

	return $tags->get_updated_html();
}
add_filter( 'render_block_core/cover', __NAMESPACE__ . '\defer_cover_video', 10, 2 );
