<?php
/**
 * Title: Product Card 3
 * Slug: kwv/woo-product-card-3
 * Description: Centered product card with star rating and add to cart button
 * Categories: kwv/card, kwv/product-card
 * Keywords: product,  card,  image,  text,  button,  woocommerce
 * Block Types:
 * Inserter: false
 * Viewport Width: 600
 */
?>
<!-- wp:group {"metadata":{"name":"Product Card"},"className":"","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group">
	<!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"style":{"border":{"radius":{"topLeft":"10px","topRight":"10px","bottomLeft":"10px","bottomRight":"10px"}}}} -->
		<!-- wp:group {"style":{"dimensions":{"minHeight":"100%"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"space-between"}} -->
			<div class="wp-block-group" style="min-height:100%">
			<!-- wp:woocommerce/product-sale-badge {"isDescendentOfQueryLoop":true,"align":"right","backgroundColor":"contrast","textColor":"base","style":{"border":{"radius":{"topLeft":"100px","topRight":"100px","bottomLeft":"100px","bottomRight":"100px"},"width":"0px","style":"none"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"typography":{"fontSize":"0.75rem"}}} /-->
			</div>
		<!-- /wp:group -->
	<!-- /wp:woocommerce/product-image -->
	<!-- wp:group {"metadata":{"name":"Product Details"},"style":{"spacing":{"blockGap":"4px"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
		<!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"fontSize":"300","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->
		<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"center","textColor":"neutral-700","fontSize":"300","style":{"typography":{"fontStyle":"normal"}}} /-->
		<!-- wp:woocommerce/product-rating {"isDescendentOfQueryLoop":true,"textAlign":"center","fontSize":"100"} /-->
		</div>
	<!-- /wp:group -->
	<!-- wp:woocommerce/product-button {"isDescendentOfQueryLoop":true,"className":"is-style-fill","backgroundColor":"neutral-200","textColor":"contrast","style":{"elements":{"link":{"color":{"text":"var:preset|color|contrast"}}}}} /-->
	</div>
<!-- /wp:group -->
