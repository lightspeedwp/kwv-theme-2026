<?php
/**
 * Title: KWV Product Card
 * Slug: kwv/woo-product-card-bordered
 * Description: Gold-bordered product card with packshot, divider, title, bottle-size attribute, price and an always-visible Add to Cart button. Built for a WooCommerce product collection / query loop.
 * Categories: kwv/card, kwv/product-card
 * Keywords: product, card, woocommerce, bordered, add to cart, bottle size, brandy
 * Block Types: core/query/woocommerce/product-query
 * Inserter: false
 * Viewport Width: 600
 */
?>
<!-- wp:group {"metadata":{"name":"KWV Product Card"},"className":"is-style-product-card","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"space-between"}} -->
<div class="wp-block-group is-style-product-card">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"product-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group product-card__media">
		<!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"metadata":{"name":"Divider"},"className":"product-card__divider-wrap","style":{"spacing":{"padding":{"top":"0","right":"var:preset|spacing|20","bottom":"0","left":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group product-card__divider-wrap" style="padding-top:0;padding-right:var(--wp--preset--spacing--20);padding-bottom:0;padding-left:var(--wp--preset--spacing--20)">
		<!-- wp:separator {"className":"product-card__divider is-style-separator-thin kwv-rule-brand"} -->
		<hr class="wp-block-separator has-alpha-channel-opacity product-card__divider is-style-separator-thin kwv-rule-brand"/>
		<!-- /wp:separator -->
	</div>
	<!-- /wp:group -->

	<!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"className":"product-card__title","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->

	<!-- wp:group {"metadata":{"name":"Meta"},"className":"product-card__meta","style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"bottom"}} -->
	<div class="wp-block-group product-card__meta">
		<!-- wp:post-terms {"term":"pa_bottlesize","className":"product-card__attribute"} /-->
		<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"right","className":"product-card__price"} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:woocommerce/product-button {"textAlign":"center","isDescendentOfQueryLoop":true,"className":"is-style-add-to-cart"} /-->
</div>
<!-- /wp:group -->
