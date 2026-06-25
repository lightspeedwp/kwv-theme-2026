<?php
/**
 * Title: Team Member Card
 * Slug: kwv/team-member-card
 * Description: A grid of Team members. Each card shows the photo, name and role; on hover an 80% contrast overlay dissolves in to reveal the member's bio.
 * Categories: kwv/card
 * Keywords: team, member, people, staff, card, profile
 * Viewport Width: 1280
 * Block Types: core/query
 * Post Types: kwv_team
 * Inserter: true
 */
?>
<!-- wp:query {"queryId":0,"query":{"perPage":"6","pages":0,"offset":0,"postType":"kwv_team","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide"} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","columnCount":3,"minimumColumnWidth":null}} -->
		<!-- wp:group {"metadata":{"name":"Team Member Card"},"className":"is-style-team-member-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
		<div class="wp-block-group is-style-team-member-card">
			<!-- wp:group {"metadata":{"name":"Media"},"className":"team-member-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group team-member-card__media">
				<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"3/4","style":{"border":{"radius":"0px"}}} /-->
				<!-- wp:group {"metadata":{"name":"Overlay"},"className":"team-member-card__overlay","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
				<div class="wp-block-group team-member-card__overlay" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)">
					<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":55,"textColor":"base","fontSize":"300","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
			<!-- wp:group {"metadata":{"name":"Caption"},"className":"team-member-card__footer","backgroundColor":"base","style":{"spacing":{"blockGap":"var:preset|spacing|5","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
			<div class="wp-block-group team-member-card__footer has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)">
				<!-- wp:post-title {"isLink":false,"fontFamily":"heading","fontSize":"400","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}}} /-->
				<!-- wp:paragraph {"metadata":{"name":"Role","bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_team_role"}}}},"textColor":"neutral-700","fontSize":"200","style":{"typography":{"lineHeight":"var:custom|line-height|snug"}}} -->
				<p class="has-neutral-700-color has-text-color has-200-font-size"><?php esc_html_e( 'Role', 'kwv' ); ?></p>
				<!-- /wp:paragraph -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	<!-- /wp:post-template -->
</div>
<!-- /wp:query -->
