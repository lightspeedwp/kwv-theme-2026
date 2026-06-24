<?php
/**
 * Title: Product Card 5
 * Slug: kwv/woo-product-card-5
 * Description: Product card with hover add to cart button overlay
 * Categories: kwv/card, kwv/product-card
 * Keywords: product,  card,  image,  text,  button,  woocommerce
 * Block Types:
 * Inserter: false
 * Viewport Width: 600
 */
?>
<!-- wp:group {"metadata":{"name":"Product Card"},"className":"kwv-product-button-hover","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group kwv-product-button-hover">
	<!-- wp:group {"metadata":{"name":"Product Image"},"className":"kwv-product-button-hover-image","layout":{"type":"constrained"}} -->
		<div class="wp-block-group kwv-product-button-hover-image">
		<!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"style":{"border":{"radius":{"topLeft":"10px","topRight":"10px","bottomLeft":"10px","bottomRight":"10px"}}}} -->
			<!-- wp:group {"style":{"dimensions":{"minHeight":"100%"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch","verticalAlignment":"space-between"}} -->
				<div class="wp-block-group" style="min-height:100%">
				<!-- wp:woocommerce/product-sale-badge {"isDescendentOfQueryLoop":true,"align":"right","backgroundColor":"contrast","textColor":"base","style":{"border":{"radius":{"topLeft":"100px","topRight":"100px","bottomLeft":"100px","bottomRight":"100px"},"width":"0px","style":"none"},"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"typography":{"fontSize":"0.75rem"}}} /-->
				</div>
			<!-- /wp:group -->
		<!-- /wp:woocommerce/product-image -->
		<!-- wp:group {"metadata":{"name":"Add to Cart Button"},"className":"is-style-background-blur kwv-product-button-hover-group kwv-product-button-full-width","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"border":{"radius":{"bottomLeft":"10px","bottomRight":"10px"}},"color":{"background":"#14100e59"}},"textColor":"base","layout":{"type":"constrained"}} -->
			<div class="wp-block-group is-style-background-blur kwv-product-button-hover-group kwv-product-button-full-width has-base-color has-text-color has-background has-link-color" style="border-bottom-left-radius:10px;border-bottom-right-radius:10px;background-color:#14100e59">
			<!-- wp:woocommerce/product-button {"textAlign":"center","width":100,"isDescendentOfQueryLoop":true,"className":"is-style-fill","textColor":"base","fontSize":"100","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"spacing":{"padding":{"left":"var:preset|spacing|20","right":"var:preset|spacing|20","top":"0.8rem","bottom":"0.8rem"}},"color":{"background":"#ffffff00"}}} /-->
			</div>
		<!-- /wp:group -->
		</div>
	<!-- /wp:group -->
	<!-- wp:group {"metadata":{"name":"Product Details"},"style":{"spacing":{"blockGap":"4px"}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
		<!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"fontSize":"300","__woocommerceNamespace":"woocommerce/product-collection/product-title"} /-->
		<!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"left","textColor":"neutral-700","fontSize":"300","style":{"typography":{"fontStyle":"normal"}}} /-->
		</div>
	<!-- /wp:group -->
	</div>
<!-- /wp:group -->
