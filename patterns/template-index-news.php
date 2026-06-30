<?php
/**
 * Title: Template: Blog Landing (News)
 * Slug: kwv/template-index-news
 * Description: Blog landing body — light header, a "News" cover hero, a list of large blog cards with an Archive (categories) sidebar and pagination, then the footer. Used by the index template.
 * Categories: hidden
 * Keywords: template, index, blog, news, landing, archive
 * Block Types: core/post-template
 * Template Types: index
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","metadata":{"name":"News"},"anchor":"content","align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<main id="content" class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0">

	<!-- wp:cover {"dimRatio":100,"overlayColor":"neutral-700","minHeight":475,"minHeightUnit":"px","contentPosition":"center left","align":"full","layout":{"type":"constrained"}} -->
	<div class="wp-block-cover alignfull has-custom-content-position is-position-center-left" style="min-height:475px"><span aria-hidden="true" class="wp-block-cover__background has-neutral-700-background-color has-background-dim-100 has-background-dim"></span><div class="wp-block-cover__inner-container">
		<!-- wp:heading {"level":1,"align":"wide","style":{"typography":{"fontWeight":"var:custom|font-weight|black","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"textColor":"base","fontSize":"800","fontFamily":"heading"} -->
		<h1 class="wp-block-heading alignwide has-base-color has-text-color has-heading-font-family has-800-font-size" style="font-weight:var(--wp--custom--font-weight--black);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'News', 'kwv' ); ?></h1>
		<!-- /wp:heading -->
	</div></div>
	<!-- /wp:cover -->

	<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--80)">
		<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
		<div class="wp-block-columns">
			<!-- wp:column {"width":"72%"} -->
			<div class="wp-block-column" style="flex-basis:72%">
				<!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"layout":{"type":"default"}} -->
				<div class="wp-block-query">
					<!-- wp:post-template {"layout":{"type":"default"}} -->
						<?php require __DIR__ . '/blog-card-large.php'; ?>
					<!-- /wp:post-template -->
					<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
						<!-- wp:query-pagination-previous /-->
						<!-- wp:query-pagination-numbers /-->
						<!-- wp:query-pagination-next /-->
					<!-- /wp:query-pagination -->
					<!-- wp:query-no-results -->
						<!-- wp:paragraph -->
						<p><?php esc_html_e( 'No posts found.', 'kwv' ); ?></p>
						<!-- /wp:paragraph -->
					<!-- /wp:query-no-results -->
				</div>
				<!-- /wp:query -->
			</div>
			<!-- /wp:column -->
			<!-- wp:column {"width":"28%","className":"kwv-archive-sidebar","style":{"border":{"left":{"color":"var:preset|color|neutral-300","width":"1px"}},"spacing":{"padding":{"left":"var:preset|spacing|40"}}}} -->
			<div class="wp-block-column kwv-archive-sidebar" style="border-left-color:var(--wp--preset--color--neutral-300);border-left-width:1px;padding-left:var(--wp--preset--spacing--40);flex-basis:28%">
				<!-- wp:heading {"level":2,"fontFamily":"heading","fontSize":"400","textColor":"brand-500","style":{"typography":{"fontWeight":"var:custom|font-weight|black","textTransform":"uppercase"},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}}}} -->
				<h2 class="wp-block-heading has-brand-500-color has-text-color has-heading-font-family has-400-font-size" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-weight:var(--wp--custom--font-weight--black);text-transform:uppercase"><?php esc_html_e( 'Archive', 'kwv' ); ?></h2>
				<!-- /wp:heading -->
				<!-- wp:categories {"showPostCounts":false,"showHierarchy":false,"fontSize":"300","style":{"spacing":{"blockGap":"var:preset|spacing|10"},"elements":{"link":{"color":{"text":"var:preset|color|contrast"},":hover":{"color":{"text":"var:preset|color|brand-500"}}}}}} /-->
			</div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->

</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
