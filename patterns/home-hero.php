<?php
/**
 * Title: Home Hero
 * Slug: kwv/home-hero
 * Description: Full-bleed hero — large background image with the transparent header over it, a heading, call-to-action, and a bottom-right scroll-down chevron that jumps to the page content (#content). Reusable across templates, not just the homepage.
 * Categories: kwv/hero
 * Keywords: hero, cover, banner, header, transparent, scroll, landing
 * Viewport Width: 1500
 * Inserter: true
 */
?>
<!-- wp:cover {"url":"/wp-content/uploads/2026/07/Wine-Hero-Image.png","dimRatio":20,"overlayColor":"contrast","isUserOverlayColor":true,"focalPoint":{"x":0.25,"y":0.5},"minHeight":720,"minHeightUnit":"px","contentPosition":"top center","sizeSlug":"full","align":"full","className":"is-style-default","style":{"spacing":{"padding":{"top":"var:preset|spacing|0","right":"var:preset|spacing|0","bottom":"var:preset|spacing|0","left":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-top-center is-style-default" style="padding-top:var(--wp--preset--spacing--0);padding-right:var(--wp--preset--spacing--0);padding-bottom:var(--wp--preset--spacing--0);padding-left:var(--wp--preset--spacing--0);min-height:720px"><img class="wp-block-cover__image-background size-full" alt="" src="/wp-content/uploads/2026/07/Wine-Hero-Image.png" style="object-position:25% 50%" data-object-fit="cover" data-object-position="25% 50%"/><span aria-hidden="true" class="wp-block-cover__background has-contrast-background-color has-background-dim-20 has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"metadata":{"name":"Header Transparent","patternName":"kwv/header-transparent","description":"Transparent header with logo, navigation, account and cart — sits over large hero sections.","categories":["header"]},"align":"full","className":"is-style-header-transparent","style":{"border":{"bottom":{"color":"var:preset|color|brand-200","width":"1px"},"top":[],"right":[],"left":[]},"spacing":{"padding":{"right":"var:preset|spacing|0","left":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
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
<h1 class="wp-block-heading has-base-color has-text-color has-heading-font-family has-700-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">OVER 100 YEARS <br>OF WINEMAKING</h1>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="#content">Learn more</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:buttons {"className":"kwv-scroll-indicator","layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons kwv-scroll-indicator"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#content">Scroll to content</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
