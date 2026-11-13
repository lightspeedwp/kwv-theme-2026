<?php
/**
 * Title: Header Light
 * Slug: kwv/header-light
 * Description: Light header with logo, navigation, account and cart — for shop, category and product pages. The promo bar is a separate `promo-bar` part composed above this header in the templates; the hidden "Promo Bar" group inside this pattern is kept switched off.
 * Categories: header
 * Keywords: header, nav, promo, shop, cart, account
 * Viewport Width: 1500
 * Block Types: core/template-part/header
 * Post Types: wp_template
 * Inserter: true
 *
 * @package kwv
 */

?>

<!-- wp:group {"metadata":{"name":"Promo Bar","blockVisibility":false},"align":"full","className":"is-style-header-promo-bar","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header-promo-bar"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500","textAlign":"center","letterSpacing":"0.02em"}},"fontSize":"200"} -->
<p class="has-text-align-center has-200-font-size" style="font-style:normal;font-weight:500;letter-spacing:0.02em">15% Off All Wine Orders Of R500+ | Use Code: PINOTAGE100</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Header Row"},"align":"full","className":"is-style-header-row","style":{"position":{"type":""},"spacing":{"margin":{"top":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header-row" style="margin-top:var(--wp--preset--spacing--0)"><!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"small":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:image {"lightbox":{"enabled":false},"id":182329,"width":"100px","sizeSlug":"large","linkDestination":"custom","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"small":false}}}}]}} -->
<figure class="wp-block-image size-large is-resized"><a href="/"><img src="/wp-content/uploads/2026/07/Site-Logo-Dark.svg" alt="" class="wp-image-182329" style="width:100px"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":false,"large":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:image {"lightbox":{"enabled":false},"id":182329,"width":"72px","aspectRatio":"1.5252621544327931","scale":"cover","sizeSlug":"large","linkDestination":"custom","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":false,"small":false,"large":false}}}}]}} -->
<figure class="wp-block-image size-large is-resized"><a href="/"><img src="/wp-content/uploads/2026/07/Site-Logo-Dark.svg" alt="" class="wp-image-182329" style="aspect-ratio:1.5252621544327931;object-fit:cover;width:72px"/></a></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:navigation {"ref":182338,"submenuVisibility":"click","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"className":"is-style-main-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|90"}},"fontSize":"200","layout":{"type":"flex","justifyContent":"center"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:advanced-woo-search/search-block {"className":"is-style-kwv-header-search","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} /-->

<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line","iconClass":"wc-block-customer-account__account-icon"} /-->

<!-- wp:woocommerce/mini-cart {"iconColor":{"name":"Contrast","slug":"contrast","color":"#000000","class":"has-contrast-icon-color"},"className":"is-style-mini-cart-light"} /-->

<!-- wp:navigation {"ref":182351,"textColor":"contrast","submenuVisibility":"click","overlayMenu":"always","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"className":"is-style-mobile-navigation ","style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"},"mobileMenuSlug":"mobile-menu","mobileIconColor":"contrast","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true,"medium":false}}}}]}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
