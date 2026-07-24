<?php
/**
 * Title: Woo Header
 * Slug: kwv/woo-header
 * Description: WooCommerce header with CTA bar, navigation, cart, and search
 * Categories: header
 * Keywords: header, nav, woocommerce, shop, cart
 * Viewport Width: 1500
 * Block Types: core/template-part/header
 * Post Types: wp_template
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Header"},"align":"full","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">
	<!-- wp:group {"metadata":{"name":"CTA Bar"},"align":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20","top":"1rem","bottom":"1rem"}}},"backgroundColor":"contrast","textColor":"neutral-300","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-neutral-300-color has-contrast-background-color has-text-color has-background" style="padding-top:1rem;padding-right:var(--wp--preset--spacing--20);padding-bottom:1rem;padding-left:var(--wp--preset--spacing--20)">
		<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"fontSize":"100","layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-group alignwide has-100-font-size">
			<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"var:custom|font-weight|medium"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base"} -->
			<p class="has-base-color has-text-color has-link-color" style="font-style:normal;font-weight:var(--wp--custom--font-weight--medium)"><?php esc_html_e( 'Save 25% off during our Winter Sale! Use code SELLWISE25', 'kwv' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Header Row 1"},"align":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20","top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"border":{"bottom":{"color":"var:preset|color|neutral-300","width":"1px"},"top":[],"right":[],"left":[]}},"backgroundColor":"base","layout":{"inherit":true,"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-base-background-color has-background has-link-color" style="border-bottom-color:var(--wp--preset--color--neutral-300);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--20)">
		<!-- wp:group {"align":"wide","layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:site-title {"level":0} /-->

				<!-- wp:navigation {"openSubmenusOnClick":true,"metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"spacing":{"blockGap":"var:preset|spacing|20"},"layout":{"selfStretch":"fit","flexSize":null}},"fontSize":"300"} /-->
			</div>
			<!-- /wp:group -->

			<!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
			<div class="wp-block-group">
				<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line","iconClass":"wc-block-customer-account__account-icon"} /-->

				<!-- wp:woocommerce/mini-cart /-->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Header Row 2"},"align":"full","style":{"spacing":{"padding":{"right":"var:preset|spacing|20","left":"var:preset|spacing|20","top":"12px","bottom":"12px"}},"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}},"border":{"bottom":{"color":"var:preset|color|neutral-300","width":"1px"},"top":[],"right":[],"left":[]}},"backgroundColor":"base","layout":{"inherit":true,"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-base-background-color has-background has-link-color" style="border-bottom-color:var(--wp--preset--color--neutral-300);border-bottom-width:1px;padding-top:12px;padding-right:var(--wp--preset--spacing--20);padding-bottom:12px;padding-left:var(--wp--preset--spacing--20)">
		<!-- wp:group {"align":"wide","layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group alignwide">
			<!-- wp:navigation {"openSubmenusOnClick":true,"metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"spacing":{"blockGap":"var:preset|spacing|20"},"layout":{"selfStretch":"fit","flexSize":null}},"fontSize":"100"} /-->

			<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search products…","width":100,"widthUnit":"%","buttonText":"Search","buttonUseIcon":true,"query":{"post_type":"product"},"fontSize":"100","namespace":"woocommerce/product-search"} /-->
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
