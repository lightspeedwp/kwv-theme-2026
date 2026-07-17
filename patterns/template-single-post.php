<?php
/**
 * Title: Single Post
 * Slug: kwv/template-single-post
 * Template Types: single
 * Description: The KWV single blog post layout — dark header, a two-column article header (a "back to news" link, post title and date beside a 4:3 featured image), the post body and previous/next post navigation.
 * Categories: kwv/posts
 * Keywords: post, single, blog, news, article
 * Viewport Width: 1500
 * Inserter: true
 */

$kwv_news_url = get_option( 'page_for_posts' )
	? get_permalink( (int) get_option( 'page_for_posts' ) )
	: home_url( '/news/' );
?>
<!-- wp:template-part {"slug":"header-dark","theme":"kwv-theme-2026","tagName":"header","className":"site-header"} /-->

<!-- wp:group {"tagName":"main","metadata":{"name":"Article"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|70"}},"layout":{"type":"constrained","contentSize":"1100px","wideSize":"1520px"}} -->
<main class="wp-block-group alignfull is-style-light-page-section" style="margin-top:0;margin-bottom:0"><!-- wp:group {"metadata":{"name":"Article Header"},"align":"wide","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignwide"><!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|60"}},"border":{"bottom":{"color":"var:preset|color|neutral-300","width":"1px"}}}} -->
<div class="wp-block-columns alignwide" style="border-bottom-color:var(--wp--preset--color--neutral-300);border-bottom-width:1px;padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%"><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph {"align":"wide","className":"kwv-back-link","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|brand-500"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"textColor":"brand-500","fontSize":"200"} -->
<p class="alignwide kwv-back-link has-brand-500-color has-text-color has-link-color has-200-font-size" style="font-style:normal;font-weight:700;text-transform:uppercase"><a href="<?php echo esc_url( $kwv_news_url ); ?>">&larr; <?php esc_html_e( 'Back to News', 'kwv' ); ?></a></p>
<!-- /wp:paragraph -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group alignwide"><!-- wp:post-title {"level":1,"align":"wide","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase","letterSpacing":"1px"}},"textColor":"contrast","fontSize":"600","fontFamily":"heading"} /-->

<!-- wp:post-date {"format":"F j, Y","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"style":{"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase"},"border":{"left":{"width":"0px","style":"none"}},"spacing":{"padding":{"top":"var:preset|spacing|10","bottom":"var:preset|spacing|10","left":"var:preset|spacing|5"}}},"textColor":"neutral-700","fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Post Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Byline"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:avatar {"size":60,"style":{"border":{"radius":"100px"}}} /-->

<!-- wp:group {"metadata":{"name":"Author"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-author-name {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|snug","textTransform":"uppercase"}},"textColor":"contrast","fontSize":"200","fontFamily":"heading"} /-->

<!-- wp:paragraph {"metadata":{"name":"Author Role","bindings":{"content":{"source":"kwv/author-role"}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"textColor":"neutral-700","fontSize":"200"} -->
<p class="has-neutral-700-color has-text-color has-200-font-size" style="margin-top:0;margin-bottom:0">Role</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":""} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:post-featured-image {"aspectRatio":"1","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

<!-- wp:separator {"className":"is-style-separator-thin","style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"neutral-300"} -->
<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"/>
<!-- /wp:separator -->

<!-- wp:group {"metadata":{"name":"Post Navigation"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Previous"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
<div class="wp-block-group"><!-- wp:post-navigation-link {"type":"previous","showTitle":true,"className":"kwv-post-nav","style":{"typography":{"textAlign":"left"}},"fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Next"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
<div class="wp-block-group"><!-- wp:post-navigation-link {"showTitle":true,"className":"kwv-post-nav","style":{"typography":{"textAlign":"right"}},"fontSize":"200"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","theme":"kwv-theme-2026","tagName":"footer","className":"site-footer"} /-->