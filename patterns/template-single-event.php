<?php
/**
 * Title: Single Event
 * Slug: kwv/template-single-event
 * Template Types: single
 * Description: The KWV single event layout — dark header, a two-column article header (a "back to visit" link, event title and the event date/time beside a square featured image), the event body, an "Accommodation nearby" card grid (the KWV Enhancements kwv/event-accommodation block — hidden when the event lists no accommodation) and previous/next navigation. Mirrors the Single Post template but shows the event date and time in place of the author/date byline.
 * Categories: kwv/visit
 * Keywords: event, single, visit, tour, upcoming
 * Viewport Width: 1500
 * Inserter: true
 *
 * @package kwv
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Single Event","description":"The KWV single event layout — dark header, a two-column article header (a \u0022back to visit\u0022 link, event title and the event date/time beside a square featured image), the event body, an \u0022Accommodation nearby\u0022 card grid (the KWV Enhancements kwv/event-accommodation block — hidden when the event lists no accommodation) and previous/next navigation. Mirrors the Single Post template but shows the event date and time in place of the author/date byline.","categories":["kwv/visit"]},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"var:preset|spacing|70"}},"layout":{"type":"constrained","contentSize":"1100px","wideSize":"1520px"}} -->
<main class="wp-block-group alignfull is-style-light-page-section" style="margin-top:0;margin-bottom:0"><!-- wp:group {"metadata":{"name":"Article Header"},"align":"wide","layout":{"type":"constrained","contentSize":"1100px"}} -->
<div class="wp-block-group alignwide"><!-- wp:columns {"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|60"}},"border":{"bottom":{"color":"var:preset|color|neutral-300","width":"1px"}}}} -->
<div class="wp-block-columns alignwide" style="border-bottom-color:var(--wp--preset--color--neutral-300);border-bottom-width:1px;padding-bottom:var(--wp--preset--spacing--60)"><!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:paragraph {"align":"wide","className":"kwv-back-link","style":{"typography":{"fontStyle":"normal","fontWeight":"700","textTransform":"uppercase"},"elements":{"link":{"color":{"text":"var:preset|color|brand-500"},":hover":{"color":{"text":"var:preset|color|brand-600"}}}}},"textColor":"brand-500","fontSize":"200"} -->
<p class="alignwide kwv-back-link has-brand-500-color has-text-color has-link-color has-200-font-size" style="font-style:normal;font-weight:700;text-transform:uppercase"><a href="/visit/">&larr; <?php esc_html_e( 'Back to Visit', 'kwv' ); ?></a></p>
<!-- /wp:paragraph -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group alignwide"><!-- wp:post-title {"level":1,"align":"wide","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase","letterSpacing":"1px"}},"textColor":"contrast","fontSize":"600","fontFamily":"heading"} /-->

<!-- wp:group {"metadata":{"name":"Event Meta"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:outermost/icon-block {"iconName":"","width":"20px"} -->
<div class="wp-block-outermost-icon-block"><div class="icon-container" style="width:20px;transform:rotate(0deg) scaleX(1) scaleY(1)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM72,48v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24V80H48V48ZM208,208H48V96H208V208Z"></path></svg></div></div>
<!-- /wp:outermost/icon-block -->

<!-- wp:paragraph {"metadata":{"name":"Event Date","bindings":{"content":{"source":"kwv/meta-date","args":{"key":"kwv_event_date","format":"j F Y"}}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase"}},"textColor":"neutral-700","fontSize":"200"} -->
<p class="has-neutral-700-color has-text-color has-200-font-size" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:400;text-transform:uppercase"><?php esc_html_e( 'Event date', 'kwv' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:outermost/icon-block {"iconName":"","width":"20px"} -->
<div class="wp-block-outermost-icon-block"><div class="icon-container" style="width:20px;transform:rotate(0deg) scaleX(1) scaleY(1)"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#000000" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm64-88a8,8,0,0,1-8,8H128a8,8,0,0,1-8-8V72a8,8,0,0,1,16,0v48h48A8,8,0,0,1,192,128Z"></path></svg></div></div>
<!-- /wp:outermost/icon-block -->

<!-- wp:paragraph {"metadata":{"name":"Event Time","bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_event_time"}}}},"style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"fontStyle":"normal","fontWeight":"400","textTransform":"uppercase"}},"textColor":"neutral-700","fontSize":"200"} -->
<p class="has-neutral-700-color has-text-color has-200-font-size" style="margin-top:0;margin-bottom:0;font-style:normal;font-weight:400;text-transform:uppercase"><?php esc_html_e( 'Time', 'kwv' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:post-content {"layout":{"type":"constrained"}} /-->

<!-- wp:buttons {"metadata":{"name":"Book now"}} -->
<div class="wp-block-buttons"><!-- wp:button {"metadata":{"name":"Book now — KWV Emporium","bindings":{"url":{"source":"kwv/event-booking-url","args":{"path":"/visit/kwv/"}}}},"className":"is-style-cta","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postTaxonomy","subField":"kwv_brand","operator":"atLeastOne","value":["kwv-emporium"]}]}],"hideOnRuleSets":false}}}]}} -->
<div class="wp-block-button is-style-cta"><a class="wp-block-button__link wp-element-button" href="/visit/kwv/"><?php esc_html_e( 'Book now', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"metadata":{"name":"Book now — Cathedral Cellar","bindings":{"url":{"source":"kwv/event-booking-url","args":{"path":"/visit/cellar/"}}}},"className":"is-style-cta","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postTaxonomy","subField":"kwv_brand","operator":"atLeastOne","value":["cathedral-cellar"]}]}],"hideOnRuleSets":false}}}]}} -->
<div class="wp-block-button is-style-cta"><a class="wp-block-button__link wp-element-button" href="/visit/cellar/"><?php esc_html_e( 'Book now', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"metadata":{"name":"Book now — House of Fire","bindings":{"url":{"source":"kwv/event-booking-url","args":{"path":"/visit/fire/"}}}},"className":"is-style-cta","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postTaxonomy","subField":"kwv_brand","operator":"atLeastOne","value":["house-of-fire"]}]}],"hideOnRuleSets":false}}}]}} -->
<div class="wp-block-button is-style-cta"><a class="wp-block-button__link wp-element-button" href="/visit/fire/"><?php esc_html_e( 'Book now', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"metadata":{"name":"Book now — Event enquiry (no venue)","bindings":{"url":{"source":"kwv/event-booking-url","args":{"path":"/visit/event-enquiry/"}}}},"className":"is-style-cta","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"location":{"ruleSets":[{"enable":true,"rules":[{"field":"postTaxonomy","subField":"kwv_brand","operator":"noTerms"}]}],"hideOnRuleSets":false}}}]}} -->
<div class="wp-block-button is-style-cta"><a class="wp-block-button__link wp-element-button" href="/visit/event-enquiry/"><?php esc_html_e( 'Book now', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":""} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:post-featured-image {"aspectRatio":"3/4","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:kwv/event-accommodation {"metadata":{"name":"Accommodation Nearby"}} /-->

<!-- wp:separator {"className":"is-style-separator-thin","style":{"spacing":{"margin":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"neutral-300"} -->
<hr class="wp-block-separator has-text-color has-neutral-300-color has-alpha-channel-opacity has-neutral-300-background-color has-background is-style-separator-thin" style="margin-top:var(--wp--preset--spacing--30);margin-bottom:var(--wp--preset--spacing--30)"/>
<!-- /wp:separator -->

<!-- wp:group {"metadata":{"name":"Event Navigation"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Previous"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
<div class="wp-block-group"><!-- wp:post-navigation-link {"type":"previous","showTitle":true,"arrow":"chevron","className":"kwv-post-nav","style":{"typography":{"textAlign":"left"}},"fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Next"},"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained","contentSize":"400px"}} -->
<div class="wp-block-group"><!-- wp:post-navigation-link {"showTitle":true,"arrow":"chevron","className":"kwv-post-nav","style":{"typography":{"textAlign":"right"}},"fontSize":"200"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->
