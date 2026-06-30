<?php
/**
 * Title: Blog Card Large
 * Slug: kwv/blog-card-large
 * Description: A large two-column news card for the blog landing query loop — featured image with the author's name and role on the left; post title, a gold rule and the excerpt on the right.
 * Categories: kwv/card, kwv/posts
 * Keywords: blog, post, card, news, large, author, winemaker, excerpt
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Blog Card Large"},"className":"is-style-blog-card-large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-blog-card-large" style="margin-bottom:var(--wp--preset--spacing--60)">
	<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-top">
		<!-- wp:column {"verticalAlignment":"top","width":"40%"} -->
		<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:40%">
			<!-- wp:group {"metadata":{"name":"Media"},"className":"blog-card-large__media","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group blog-card-large__media">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/5","className":"is-style-image-hover-zoom"} /-->
				<!-- wp:group {"metadata":{"name":"Author"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:post-author-name {"isLink":false,"fontFamily":"heading","fontSize":"300","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|black","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}}} /-->
					<!-- wp:paragraph {"metadata":{"name":"Author Role","bindings":{"content":{"source":"kwv/author-role"}}},"textColor":"neutral-700","fontSize":"300","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
					<p class="has-neutral-700-color has-text-color has-300-font-size" style="margin-top:0;margin-bottom:0"><?php esc_html_e( 'Role', 'kwv' ); ?></p>
					<!-- /wp:paragraph -->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"verticalAlignment":"top","width":"60%"} -->
		<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:60%">
			<!-- wp:post-title {"isLink":true,"fontFamily":"heading","fontSize":"400","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|black","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"},"spacing":{"margin":{"top":"0"}}}} /-->
			<!-- wp:separator {"className":"kwv-rule-brand","style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}}} -->
			<hr class="wp-block-separator has-alpha-channel-opacity kwv-rule-brand" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30)"/>
			<!-- /wp:separator -->
			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"fontFamily":"body","fontSize":"300","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
