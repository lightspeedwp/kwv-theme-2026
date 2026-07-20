<?php
/**
 * Title: Event Card
 * Slug: kwv/event-card
 * Description: A single event card — featured image with hover zoom above the event title and an outlined "Learn More" link to the event. Carries the "Event Card" section style (blog-card hover colours: link turns gold on hover, image zooms). Designed to sit inside a Query Loop post-template over kwv_event.
 * Categories: kwv/card, kwv/visit
 * Keywords: event, visit, upcoming, card, tour, query, loop, featured image
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: kwv_event
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Event Card"},"className":"is-style-event-card","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group is-style-event-card">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"event-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group event-card__media">
		<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/5","className":"is-style-image-hover-zoom"} /-->
	</div>
	<!-- /wp:group -->
	<!-- wp:post-title {"isLink":true,"level":3,"fontFamily":"heading","fontSize":"200","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|regular","lineHeight":"var:custom|line-height|heading"}}} /-->
	<!-- wp:read-more {"content":"Learn More"} /-->
</div>
<!-- /wp:group -->
