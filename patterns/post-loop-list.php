<?php
/**
 * Title: Post Loop List
 * Slug: kwv/post-loop-list
 * Description:
 * Categories: kwv/posts
 * Keywords: blog, posts, query, loop
 * Viewport Width: 1280
 * Block Types: core/query
 * Post Types:
 * Inserter: true
 */
?>
<!-- wp:query {"query":{"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"layout":{"type":"default"}} -->
	<div class="wp-block-query">
	<!-- wp:group {"metadata":{"name":"Single Post"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
		<!-- wp:post-template {"layout":{"type":"default"}} -->
			<!-- wp:group {"metadata":{"name":"Post Content"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
				<div class="wp-block-group">
				<!-- wp:group {"metadata":{"name":"Post Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
					<div class="wp-block-group">
					<!-- wp:post-terms {"term":"category","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}},"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"300"} /-->
					<!-- wp:post-title {"isLink":true,"fontSize":"600"} /-->
					<!-- wp:group {"style":{"spacing":{"blockGap":"10px"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"neutral-700","fontSize":"300","layout":{"type":"flex","flexWrap":"wrap"}} -->
						<div class="wp-block-group has-neutral-700-color has-text-color has-300-font-size" style="font-style:normal;font-weight:500">
						<!-- wp:post-author {"showBio":false} /-->
						<!-- wp:paragraph {"textColor":"neutral-700"} -->
							<p class="has-neutral-700-color has-text-color"><?php esc_html_e( '·', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
						<!-- wp:post-date /-->
						</div>
					<!-- /wp:group -->
					</div>
				<!-- /wp:group -->
				<!-- wp:post-featured-image {"isLink":true,"style":{"border":{"radius":"5px"},"spacing":{"margin":{"top":"var:preset|spacing|40"}}}} /-->
				<!-- wp:post-content /-->
				<!-- wp:separator {"className":"is-style-separator-thin","style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"backgroundColor":"neutral-300"} -->
					<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50)"/>
				<!-- /wp:separator -->
				</div>
			<!-- /wp:group -->
		<!-- /wp:post-template -->
		<!-- wp:group {"metadata":{"name":"Pagination"},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
			<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"space-between"}} -->
				<!-- wp:query-pagination-previous {"className":"is-style-wp-block-button__link"} /-->
				<!-- wp:query-pagination-next {"className":"is-style-wp-block-button__link"} /-->
			<!-- /wp:query-pagination -->
			</div>
		<!-- /wp:group -->
		</div>
	<!-- /wp:group -->
	</div>
<!-- /wp:query -->
