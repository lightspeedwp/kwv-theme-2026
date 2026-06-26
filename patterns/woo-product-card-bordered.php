<?php
/**
 * Title: Product Card — Bordered
 * Slug: kwv/woo-product-card-bordered
 * Description: Gold-bordered product card with packshot, divider, title, bottle-size attribute, price and an always-visible Add to Cart button. Built for a WooCommerce product collection / query loop.
 * Categories: kwv/card, kwv/product-card
 * Keywords: product, card, woocommerce, bordered, add to cart, bottle size, brandy
 * Block Types: core/query/woocommerce/product-query
 * Inserter: false
 * Viewport Width: 600
 */
?>
<!-- wp:group {"metadata":{"name":"Product Card — Bordered"},"className":"is-style-product-card","layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"space-between"}} -->
<div class="wp-block-group is-style-product-card">
	<!-- wp:group {"metadata":{"name":"Media"},"className":"product-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group product-card__media">
		<!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true} /-->
	</div>
	<!-- /wp:group -->

	<!-- wp:separator {"className":"product-card__divider is-style-wide"} -->
	<hr class="wp-block-separator has-alpha-channel-opacity product-card__divider is-style-wide"/>
	<!-- /wp:separator -->

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
