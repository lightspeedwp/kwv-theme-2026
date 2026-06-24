<?php
/**
 * Title: Post Loop Grid
 * Slug: kwv/post-loop-grid
 * Description: This post loop grid is best used on custom pages where there is not a default post loop.
 * Categories: kwv/posts
 * Keywords: blog, posts, query, loop
 * Viewport Width: 1280
 * Block Types: core/query
 * Post Types: 
 * Inserter: false
 */
?>
<!-- wp:query {"queryId":1,"query":{"perPage":"6","pages":"0","offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"exclude","inherit":false},"align":"full"} -->
	<div class="wp-block-query alignfull">
	<!-- wp:group {"metadata":{"name":"Post Grid"},"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|50"}},"backgroundColor":"neutral-200","layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide has-neutral-200-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
		<!-- wp:post-template {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":2}} -->
			<!-- wp:group {"metadata":{"name":"Post"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}},"border":{"radius":"5px"}},"backgroundColor":"base","layout":{"type":"flex","orientation":"vertical","verticalAlignment":"space-between"}} -->
				<div class="wp-block-group has-base-background-color has-background" style="border-radius:5px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
				<!-- wp:group {"metadata":{"name":"Post Content"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">
					<!-- wp:post-featured-image {"isLink":true,"height":"300px","style":{"border":{"radius":"5px"}}} /-->
					<!-- wp:group {"style":{"spacing":{"blockGap":"5px","margin":{"top":"1.5rem"}},"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}},"textColor":"neutral-700","fontSize":"100","layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left","verticalAlignment":"center"}} -->
						<div class="wp-block-group has-neutral-700-color has-text-color has-link-color has-100-font-size" style="margin-top:1.5rem">
						<!-- wp:post-author {"showAvatar":false} /-->
						<!-- wp:paragraph -->
							<p><?php esc_html_e( '·', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:post-date /-->
						</div>
					<!-- /wp:group -->
					<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
						<div class="wp-block-group">
						<!-- wp:post-title {"isLink":true,"fontSize":"400"} /-->
						<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"className":"is-style-excerpt-truncate-3","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"fontSize":"300"} /-->
						</div>
					<!-- /wp:group -->
					</div>
				<!-- /wp:group -->
				<!-- wp:group {"metadata":{"name":"Post Meta"},"style":{"spacing":{"blockGap":"1px"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
					<div class="wp-block-group">
					<!-- wp:post-terms {"term":"category","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}}},"textColor":"brand-500","fontSize":"100"} /-->
					</div>
				<!-- /wp:group -->
				</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
		<!-- wp:group {"metadata":{"name":"Pagination"},"align":"wide","layout":{"type":"constrained"}} -->
			<div class="wp-block-group alignwide">
			<!-- wp:query-pagination {"align":"wide","layout":{"type":"flex","justifyContent":"space-between"}} -->
				<!-- wp:query-pagination-previous {"className":"is-style-default"} /-->
				<!-- wp:query-pagination-next {"className":"is-style-default"} /-->
			<!-- /wp:query-pagination -->
			</div>
		<!-- /wp:group -->
		</div>
	<!-- /wp:group -->
	</div>
<!-- /wp:query -->
