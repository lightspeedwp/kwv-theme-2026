<?php
/**
 * Title: Blog Card Large
 * Slug: kwv/blog-card-large
 * Description: A large two-column news card for the blog landing query loop — a square featured image above the author's name and job title on the left; post title, a gold rule and the excerpt on the right, with a gold rule beneath the card.
 * Categories: kwv/card, kwv/posts
 * Keywords: blog, post, card, news, large, author, winemaker, excerpt
 * Viewport Width: 1280
 * Block Types: core/post-template
 * Post Types: post
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Blog Card Large"},"className":"is-style-blog-card-large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"},"padding":{"bottom":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|brand-500","width":"1px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-blog-card-large" style="border-bottom-color:var(--wp--preset--color--brand-500);border-bottom-width:1px;margin-bottom:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)">
	<!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns are-vertically-aligned-top">
		<!-- wp:column {"verticalAlignment":"top","width":"40%"} -->
		<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:40%">
			<!-- wp:group {"metadata":{"name":"Media"},"className":"blog-card-large__media","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
			<div class="wp-block-group blog-card-large__media" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1","className":"is-style-default"} /-->
				<!-- wp:group {"metadata":{"name":"Author"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
					<!-- wp:post-author-name {"isLink":false,"fontFamily":"heading","fontSize":"300","style":{"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}}} /-->
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
			<!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group" style="padding-left:var(--wp--preset--spacing--30)">
				<!-- wp:post-title {"isLink":true,"style":{"spacing":{"margin":{"top":"0"}}}} /-->
			</div>
			<!-- /wp:group -->
			<!-- wp:separator {"className":"kwv-rule-brand","backgroundColor":"brand-500","style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}}} -->
			<hr class="wp-block-separator has-text-color has-brand-500-color has-alpha-channel-opacity has-brand-500-background-color has-background kwv-rule-brand" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30)"/>
			<!-- /wp:separator -->
			<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":100,"fontFamily":"body","fontSize":"300","style":{"typography":{"lineHeight":"var:custom|line-height|body"},"spacing":{"padding":{"left":"var:preset|spacing|30"}}}} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
