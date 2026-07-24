<?php
/**
 * Title: Header Light
 * Slug: kwv/header-light
 * Description: Light header with logo, navigation, account and cart — for shop, category and product pages. The promo bar is a separate `promo-bar` part composed above this header in the templates.
 * Categories: header
 * Keywords: header, nav, promo, shop, cart, account
 * Viewport Width: 1500
 * Block Types: core/template-part/header
 * Post Types: wp_template
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Header — Light"},"align":"full","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">

	<!-- wp:group {"metadata":{"name":"Header Row"},"align":"full","className":"is-style-header-row","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull is-style-header-row">
		<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:site-logo {"width":96} /-->

			<!-- wp:navigation {"openSubmenusOnClick":true,"className":"is-style-main-navigation","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"}} /-->

			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
			<div class="wp-block-group">
				<!-- wp:advanced-woo-search/nav-search-block {"className":"is-style-kwv-header-search"} /-->

				<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line"} /-->

				<!-- wp:woocommerce/mini-cart {"className":"is-style-mini-cart-light"} /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
