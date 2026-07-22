<?php
/**
 * Title: Template: Page (With Sidebar)
 * Slug: kwv/template-page-right-sidebar
 * Description: Page template body — light header, a page title above a two-column layout of post content with a right-hand sidebar template part, then the dark footer. Used by the "Page (With Sidebar)" template.
 * Categories: hidden
 * Keywords: template, page, sidebar, right
 * Block Types: core/post-content
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 */
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Page With Sidebar"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

	<!-- wp:post-title {"level":1,"fontFamily":"heading","fontSize":"700","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|black","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}}} /-->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"var:preset|spacing|70"}}}} -->
	<div class="wp-block-columns">
		<!-- wp:column {"width":"66.66%"} -->
		<div class="wp-block-column" style="flex-basis:66.66%">
			<!-- wp:post-content {"layout":{"type":"constrained"}} /-->
		</div>
		<!-- /wp:column -->
		<!-- wp:column {"width":"33.33%","style":{"border":{"left":{"color":"var:preset|color|neutral-300","width":"1px"}},"spacing":{"padding":{"left":"var:preset|spacing|40"}}}} -->
		<div class="wp-block-column" style="border-left-color:var(--wp--preset--color--neutral-300);border-left-width:1px;padding-left:var(--wp--preset--spacing--40);flex-basis:33.33%">
			<!-- wp:template-part {"slug":"sidebar","tagName":"aside"} /-->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->

</main>
<!-- /wp:group -->

