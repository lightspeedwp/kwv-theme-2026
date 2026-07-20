<?php
/**
 * Title: Single Event
 * Slug: kwv/template-single-event
 * Template Types: single
 * Description: The KWV single event layout — dark header, a two-column article header (a "back to visit" link, event title and the event date/time beside a square featured image), the event body and previous/next navigation. Mirrors the Single Post template but shows the event date and time in place of the author/date byline.
 * Categories: kwv/visit
 * Keywords: event, single, visit, tour, upcoming
 * Viewport Width: 1500
 * Inserter: true
 */

$kwv_visit     = get_page_by_path( 'visit', OBJECT, 'page' );
$kwv_visit_url = $kwv_visit ? get_permalink( $kwv_visit ) : home_url( '/visit/' );
?>
<!-- wp:template-part {"slug":"header-dark","theme":"kwv-theme-2026","tagName":"header","className":"site-header"} /-->

<!-- wp:group {"tagName":"main","metadata":{"name":"Article"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|70"}},"layout":{"type":"constrained","contentSize":"1100px","wideSize":"1520px"}} -->
<main class="wp-block-group alignfull is-style-light-page-section" style="margin-top:0;margin-bottom:0"><!-- wp:group {"metadata":{"name":"Article Header"},"align":"wide","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignwide"><!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|60"}},"border":{"bottom":{"color":"var:preset|color|neutral-300","width":"1px"}}}} -->
<div class="wp-block-columns alignwide" style="border-bottom-color:var(--wp--preset--color--neutral-300);border-bottom-width:1px;padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%"><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph {"align":"wide","className":"kwv-back-link","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|brand-500"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"textColor":"brand-500","fontSize":"200"} -->
<p class="alignwide kwv-back-link has-brand-500-color has-text-color has-link-color has-200-font-size" style="font-style:normal;font-weight:700;text-transform:uppercase"><a href="<?php echo esc_url( $kwv_visit_url ); ?>">&larr; <?php esc_html_e( 'Back to Visit', 'kwv' ); ?></a></p>
<!-- /wp:paragraph -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group alignwide"><!-- wp:post-title {"level":1,"align":"wide","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase","letterSpacing":"1px"}},"textColor":"contrast","fontSize":"600","fontFamily":"heading"} /-->

<!-- wp:group {"metadata":{"name":"Event Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"metadata":{"name":"Event Date","bindings":{"content":{"source":"kwv/meta-date","args":{"key":"kwv_event_date","format":"j F Y"}}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase"}},"textColor":"neutral-700","fontSize":"200"} -->
<p class="has-neutral-700-color has-text-color has-200-font-size" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:400;text-transform:uppercase"><?php esc_html_e( 'Event date', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"name":"Event Time","bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_event_time"}}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase"}},"textColor":"neutral-700","fontSize":"200"} -->
<p class="has-neutral-700-color has-text-color has-200-font-size" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:400;text-transform:uppercase"><?php esc_html_e( 'Time', 'kwv' ); ?></p>
<!-- /wp:paragraph --></div>
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

<!-- wp:group {"metadata":{"name":"Event Navigation"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Previous"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
<div class="wp-block-group"><!-- wp:post-navigation-link {"type":"previous","showTitle":true,"className":"kwv-post-nav","style":{"typography":{"textAlign":"left"}},"fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Next"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
<div class="wp-block-group"><!-- wp:post-navigation-link {"showTitle":true,"className":"kwv-post-nav","style":{"typography":{"textAlign":"right"}},"fontSize":"200"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","theme":"kwv-theme-2026","tagName":"footer","className":"site-footer"} /-->
