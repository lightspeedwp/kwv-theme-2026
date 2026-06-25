<?php
/**
 * Title: Blog Card
 * Slug: kwv/blog-card
 * Description: A single post card — featured image with hover zoom above the post title. Built for the homepage "Latest News" query loop.
 * Categories: kwv/card, kwv/posts
 * Keywords: blog, post, card, news, latest, featured image
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Blog Card"},"className":"is-style-blog-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group is-style-blog-card">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"blog-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group blog-card__media">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/5","className":"is-style-image-hover-zoom"} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:post-title {"isLink":true,"fontFamily":"heading","fontSize":"300","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|medium","lineHeight":"var:custom|line-height|heading"}}} /-->
</div>
<!-- /wp:group -->
