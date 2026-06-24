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
<!-- wp:group {"metadata":{"name":"Header — Transparent"},"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","right":"var:preset|spacing|20","left":"var:preset|spacing|20"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-base-color has-text-color has-link-color" style="padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
	<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
	<div class="wp-block-group alignwide">
		<!-- wp:site-logo {"width":96} /-->

		<!-- wp:navigation {"openSubmenusOnClick":true,"icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"}} /-->

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
		<div class="wp-block-group">
			<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line"} /-->

			<!-- wp:woocommerce/mini-cart /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
