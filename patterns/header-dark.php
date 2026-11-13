<?php
/**
 * Title: Header Dark
 * Slug: kwv/header-dark
 * Description: Contrast (dark) header with logo, navigation, account and cart — for standard content templates.
 * Categories: header
 * Keywords: header, nav, dark, contrast, cart, account
 * Viewport Width: 1500
 * Block Types: core/template-part/header
 * Post Types: wp_template
 * Inserter: true
 *
 * @package kwv
 */

?>

<!-- wp:group {"metadata":{"name":"Header Dark","description":"Contrast (dark) header with logo, navigation, account and cart — for standard content templates.","categories":["header"]},"align":"full","className":"is-style-header-dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header-dark"><!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"small":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:site-logo {"width":100} /--></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"large":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:site-logo {"width":72} /--></div>
<!-- /wp:group -->

<!-- wp:navigation {"ref":182338,"submenuVisibility":"click","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"className":"is-style-main-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|90"}},"layout":{"type":"flex","justifyContent":"center"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:advanced-woo-search/search-block {"className":"is-style-kwv-header-search","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true,"small":true}}}}]}} /-->

<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line","iconClass":"wc-block-customer-account__account-icon"} /-->

<!-- wp:woocommerce/mini-cart {"className":"is-style-mini-cart-dark"} /-->

<!-- wp:navigation {"ref":182351,"submenuVisibility":"click","overlayMenu":"always","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"className":"is-style-mobile-navigation","style":{"spacing":{"blockGap":"var:preset|spacing|80"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"},"mobileMenuSlug":"mobile-menu","blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true,"medium":false}}}}]}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
