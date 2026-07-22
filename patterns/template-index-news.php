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

$kwv_hero_image = esc_url( get_theme_file_uri( 'assets/images/wine-hero.png' ) );
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"News"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0;margin-bottom:0"><!-- wp:cover {"url":"<?php echo $kwv_hero_image; ?>","dimRatio":30,"overlayColor":"neutral-700","isUserOverlayColor":true,"minHeight":400,"minHeightUnit":"px","contentPosition":"center center","sizeSlug":"full","align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull" style="min-height:400px"><img class="wp-block-cover__image-background size-full" alt="" src="<?php echo $kwv_hero_image; ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-neutral-700-background-color has-background-dim-30 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:heading {"level":1,"align":"wide","style":{"typography":{"fontStyle":"normal","fontWeight":"600","letterSpacing":"0.03em"}},"textColor":"base","fontSize":"800"} -->
<h1 class="wp-block-heading alignwide has-base-color has-text-color has-800-font-size" style="font-style:normal;font-weight:600;letter-spacing:0.03em"><?php esc_html_e( 'News', 'kwv' ); ?></h1>
<!-- /wp:heading --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|80"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"90%"} -->
<div class="wp-block-column" style="flex-basis:90%"><!-- wp:query {"queryId":0,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":true,"taxQuery":null,"parents":[]},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default"}} -->
<?php require __DIR__ . '/blog-card-large.php'; ?>
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
<p><?php esc_html_e( 'No posts found.', 'kwv' ); ?></p>
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

