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
 */
?>
<!-- wp:group {"metadata":{"name":"Header — Dark"},"align":"full","className":"is-style-header-dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-header-dark">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:site-logo {"width":96} /-->

		<!-- wp:navigation {"openSubmenusOnClick":true,"className":"is-style-main-navigation","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"}} /-->

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"textColor":"brand-500","layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group has-brand-500-color has-text-color">
			<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line"} /-->

			<!-- wp:woocommerce/mini-cart {"className":"is-style-mini-cart-dark"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
