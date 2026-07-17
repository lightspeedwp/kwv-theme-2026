<?php
/**
 * Title: Template: Category
 * Slug: kwv/template-category
 * Description: Blog category archive body — the News landing layout (cover hero, a list of large blog cards with an Archive categories sidebar and pagination) titled with the current category and with the active category highlighted in the sidebar. Used by the category template.
 * Categories: hidden
 * Keywords: template, category, archive, blog, news, query
 * Block Types: core/query
 * Template Types: category
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 */
?>
<!-- wp:template-part {"slug":"header","theme":"kwv-theme-2026","tagName":"header"} /-->

<!-- wp:group {"tagName":"main","metadata":{"name":"News"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0"><!-- wp:cover {"url":"https://kwv.lightspeedwp.dev/wp-content/uploads/2026/07/Wine-Hero-Image.png","id":182457,"dimRatio":30,"overlayColor":"neutral-700","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","contentPosition":"center center","sizeSlug":"full","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull" style="min-height:400px"><img class="wp-block-cover__image-background wp-image-182457 size-full" alt="" src="https://kwv.lightspeedwp.dev/wp-content/uploads/2026/07/Wine-Hero-Image.png" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-neutral-700-background-color has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:query-title {"type":"archive","showPrefix":false,"level":1,"align":"wide","textColor":"base","fontSize":"800","style":{"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"0.03em"}}} /--></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|80"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"90%"} -->
<div class="wp-block-column" style="flex-basis:90%"><!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default"}} -->
<!-- wp:group {"metadata":{"name":"Blog Card Large"},"className":"is-style-blog-card-large","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"},"padding":{"bottom":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|brand-500","width":"1px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group is-style-blog-card-large" style="border-bottom-color:var(--wp--preset--color--brand-500);border-bottom-width:1px;margin-bottom:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns are-vertically-aligned-top"><!-- wp:column {"verticalAlignment":"top","width":"33.33%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:33.33%"><!-- wp:group {"metadata":{"name":"Media"},"className":"blog-card-large__media","style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}}} -->
<div class="wp-block-group blog-card-large__media" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"1","className":"is-style-default"} /-->

<!-- wp:group {"metadata":{"name":"Author"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-author-name {"style":{"typography":{"fontWeight":"600","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase","fontStyle":"normal"}},"fontSize":"300","fontFamily":"heading"} /-->

<!-- wp:paragraph {"metadata":{"name":"Author Role","bindings":{"content":{"source":"kwv/author-role"}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"neutral-700","fontSize":"300"} -->
<p class="has-neutral-700-color has-text-color has-300-font-size" style="margin-top:0;margin-bottom:0">Role</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":""} -->
<div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-left:var(--wp--preset--spacing--30)"><!-- wp:post-title {"isLink":true,"style":{"spacing":{"margin":{"top":"0"}}}} /--></div>
<!-- /wp:group -->

<!-- wp:separator {"className":"kwv-rule-brand","style":{"spacing":{"margin":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|30"}}},"backgroundColor":"brand-500"} -->
<hr class="wp-block-separator has-text-color has-brand-500-color has-alpha-channel-opacity has-brand-500-background-color has-background kwv-rule-brand" style="margin-top:var(--wp--preset--spacing--20);margin-bottom:var(--wp--preset--spacing--30)"/>
<!-- /wp:separator -->

<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false,"excerptLength":100,"style":{"typography":{"lineHeight":"var:custom|line-height|body"},"spacing":{"padding":{"left":"var:preset|spacing|30"}}},"fontSize":"300","fontFamily":"body"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:query-pagination {"style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:group -->

<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>No posts found.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"20%","className":"kwv-archive-sidebar","style":{"border":{"left":{"width":"0px","style":"none"},"top":[],"right":[],"bottom":[]},"spacing":{"padding":{"left":"var:preset|spacing|0"}}}} -->
<div class="wp-block-column kwv-archive-sidebar" style="border-left-style:none;border-left-width:0px;padding-left:var(--wp--preset--spacing--0);flex-basis:20%"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"margin":{"top":"-30px"}},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:-30px;padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"style":{"spacing":{"padding":{"left":"var:preset|spacing|40","top":"var:preset|spacing|0"}},"border":{"left":{"color":"var:preset|color|brand-500","width":"2px"},"top":[],"right":[],"bottom":[]},"position":{"type":""}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-left-color:var(--wp--preset--color--brand-500);border-left-width:2px;padding-top:var(--wp--preset--spacing--0);padding-left:var(--wp--preset--spacing--40)"><!-- wp:heading {"style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|30"}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"textColor":"brand-600"} -->
<h2 class="wp-block-heading has-brand-600-color has-text-color" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--30);font-style:normal;font-weight:600">Archive</h2>
<!-- /wp:heading -->

<!-- wp:categories {"showOnlyTopLevel":true,"className":"is-style-categories-chevron","style":{"spacing":{"blockGap":"var:preset|spacing|10","padding":{"left":"var:preset|spacing|30"}},"elements":{"link":{"color":{"text":"var:preset|color|contrast"},":hover":{"color":{"text":"var:preset|color|brand-500"}}}}},"fontSize":"300"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","theme":"kwv-theme-2026","tagName":"footer"} /-->