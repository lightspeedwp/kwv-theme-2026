<?php
/**
 * Title: Header Transparent
 * Slug: kwv/header-transparent
 * Description: Transparent header with logo, navigation, account and cart — sits over large hero sections.
 * Categories: header
 * Keywords: header, nav, transparent, hero, overlay, cart, account
 * Viewport Width: 1500
 * Block Types: core/template-part/header
 * Post Types: wp_template
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Header Transparent","patternName":"kwv/header-transparent","description":"Transparent header with logo, navigation, account and cart — sits over large hero sections.","categories":["header"]},"align":"full","className":"is-style-header-transparent","style":{"border":{"bottom":{"color":"var:preset|color|brand-200","width":"1px"},"top":{},"right":{},"left":{}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header-transparent" style="border-bottom-color:var(--wp--preset--color--brand-200);border-bottom-width:1px"><!-- wp:group {"align":"wide","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group alignwide has-base-color has-text-color has-link-color"><!-- wp:site-logo {"width":96,"shouldSyncIcon":true} /-->

<!-- wp:navigation {"ref":4,"submenuVisibility":"click","className":"is-style-main-navigation","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"}} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line","iconClass":"wc-block-customer-account__account-icon","textColor":"base","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} /-->

<!-- wp:woocommerce/mini-cart /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
