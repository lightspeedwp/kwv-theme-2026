<?php
/**
 * Title: Template: Single Job
 * Slug: kwv/template-single-career
 * Description: Single Careers (kwv_career) template body — a single-job variant of the Careers hero (with its own transparent header and mobile menu, inlined here rather than shared with kwv/careers-hero) over a two-column layout: the role, its reference number, the job details (location, department, closing date) and description on the left, the apply panel (LinkedIn Easy Apply button when set, then the Gravity Forms application form) on the right, then the dark footer.
 * Categories: hidden
 * Keywords: template, careers, job, single, application, form
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 *
 * @package kwv
 */

// The hero embeds its own transparent header (like the home hero), so this
// template deliberately omits the `header` template part to avoid a double
// header. It is the single-job variant of the Careers hero, so it is inlined
// here rather than shared with kwv/careers-hero.

// The apply panel is embedded via the [kwv_career_form] shortcode (KWV
// Enhancements plugin): the job's "Easy Apply with LinkedIn" button when its
// LinkedIn Easy Apply field is set, then the application form — the per-job
// "Application Form" field (kwv_career_form_id), falling back to the shared
// installed form — or a "closed" notice once the closing date has passed. The
// form's hidden Job ID field defaults to {embed_post:ID}; the plugin fills the
// rest of the job's details (title, reference, link, …) server-side on submit.
// The same content opens in a modal from the Careers listing.
?>

<!-- wp:cover {"url":"/wp-content/uploads/2026/08/careers-hero.webp","id":183465,"alt":"Copper pot stills in the KWV distillery, glowing warm under interior lighting","dimRatio":20,"overlayColor":"contrast","isUserOverlayColor":true,"minHeight":600,"minHeightUnit":"px","contentPosition":"top center","sizeSlug":"full","metadata":{"name":"Hero","description":"Full-bleed hero — large background image with the transparent header over it, a heading, call-to-action, and a bottom-right scroll-down chevron that jumps to the page content (#content). Reusable across templates, not just the homepage.","categories":["kwv/hero"]},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|0","right":"var:preset|spacing|0","bottom":"var:preset|spacing|0","left":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-top-center" style="padding-top:var(--wp--preset--spacing--0);padding-right:var(--wp--preset--spacing--0);padding-bottom:var(--wp--preset--spacing--0);padding-left:var(--wp--preset--spacing--0);min-height:600px"><img class="wp-block-cover__image-background wp-image-183465 size-full" alt="Copper pot stills in the KWV distillery, glowing warm under interior lighting" src="/wp-content/uploads/2026/08/careers-hero.webp" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"metadata":{"name":"Header Transparent","patternName":"kwv/header-transparent","description":"Transparent header with logo, navigation, account and cart — sits over large hero sections.","categories":["header"]},"align":"full","className":"is-style-header-transparent","style":{"border":{"bottom":{"color":"var:preset|color|brand-200","width":"1px"},"top":[],"right":[],"left":[]},"spacing":{"padding":{"right":"var:preset|spacing|0","left":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header-transparent" style="border-bottom-color:var(--wp--preset--color--brand-200);border-bottom-width:1px;padding-right:var(--wp--preset--spacing--0);padding-left:var(--wp--preset--spacing--0)"><!-- wp:group {"align":"wide","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignwide has-base-color has-text-color has-link-color"><!-- wp:site-logo {"width":96,"shouldSyncIcon":false} /-->

<!-- wp:navigation {"ref":182338,"submenuVisibility":"click","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"className":"is-style-main-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|90"}},"layout":{"type":"flex","justifyContent":"center"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:advanced-woo-search/search-block {"className":"is-style-kwv-header-search","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} /-->

<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line","iconClass":"wc-block-customer-account__account-icon","textColor":"base","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} /-->

<!-- wp:woocommerce/mini-cart {"className":"is-style-mini-cart-dark"} /-->

<!-- wp:navigation {"ref":182351,"submenuVisibility":"click","overlayMenu":"always","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"className":"is-style-mobile-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"},"mobileMenuSlug":"mobile-menu","mobileIconColor":"base","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true,"medium":false}}}}]}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|70","padding":{"top":"16em"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
<div class="wp-block-group alignwide" style="padding-top:16em"><!-- wp:heading {"level":1,"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"textColor":"base","fontSize":"700","fontFamily":"heading"} -->
<h1 class="wp-block-heading has-base-color has-text-color has-heading-font-family has-700-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">Careers</h1>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:columns {"verticalAlignment":"bottom","align":"wide","blockVisibility":{"hideBlock":false}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:50%"></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"bottom","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:50%"></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:buttons {"className":"kwv-scroll-indicator","layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons kwv-scroll-indicator"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#content">Scroll to content</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->

<!-- wp:group {"tagName":"main","metadata":{"name":"Single Job"},"align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|0","margin":{"top":"0"}}},"layout":{"type":"constrained"},"anchor":"content"} -->
<main class="wp-block-group alignfull" id="content" style="margin-top:0"><!-- wp:group {"metadata":{"name":"Job Detail"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section"><!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-top"><!-- wp:column {"verticalAlignment":"top","width":"55%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:55%"><!-- wp:group {"metadata":{"name":"Role + Reference"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-title {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"textColor":"brand-500","fontSize":"400","fontFamily":"heading"} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"textColor":"brand-500","fontSize":"300","fontFamily":"heading"} -->
<p class="has-brand-500-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">Ref:</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_career_reference"}}},"name":"Reference"},"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"textColor":"brand-500","fontSize":"300","fontFamily":"heading"} -->
<p class="has-brand-500-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">BMMS0128</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Job Details"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold"}},"textColor":"contrast","fontSize":"200"} -->
<p class="has-contrast-color has-text-color has-200-font-size" style="font-weight:var(--wp--custom--font-weight--bold)">Location:</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_career_location"}}},"name":"Location"},"fontSize":"200"} -->
<p class="has-200-font-size">Paarl, Western Cape</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold"}},"textColor":"contrast","fontSize":"200"} -->
<p class="has-contrast-color has-text-color has-200-font-size" style="font-weight:var(--wp--custom--font-weight--bold)">Department:</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_career_department"}}},"name":"Department"},"fontSize":"200"} -->
<p class="has-200-font-size">Marketing</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold"}},"textColor":"contrast","fontSize":"200"} -->
<p class="has-contrast-color has-text-color has-200-font-size" style="font-weight:var(--wp--custom--font-weight--bold)">Closing date:</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"kwv/meta-date","args":{"key":"kwv_career_closing_date","format":"j F Y"}}},"name":"Closing Date"},"fontSize":"200"} -->
<p class="has-200-font-size">31 July 2026</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:post-content {"layout":{"type":"default"}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:45%"><!-- wp:group {"metadata":{"name":"Application Form"},"style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"top":"-40px"},"padding":{"top":"var:preset|spacing|60"}},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:-40px;padding-top:var(--wp--preset--spacing--60)"><!-- wp:heading {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"textColor":"contrast","fontSize":"300","fontFamily":"heading"} -->
<h2 class="wp-block-heading has-contrast-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">Apply for this position</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[kwv_career_form]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->
