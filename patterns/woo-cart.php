<?php
/**
 * Title: WooCommerce Cart
 * Slug: kwv/woo-cart
 * Description: The cart page for WooCommerce.
 * Categories: kwv/woocommerce
 * Keywords: cart, woocommerce
 * Template Types: page-cart
 * Inserter: false
 * Viewport Width: 1500
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
<!-- wp:group {"metadata":{"name":"Page Wrapper"},"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"type":"default"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0">
	<!-- wp:group {"metadata":{"name":"Page Header"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignfull has-neutral-200-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
		<!-- wp:columns {"verticalAlignment":"bottom","isStackedOnMobile":false,"metadata":{"name":"Header Columns"},"align":"wide"} -->
			<div class="wp-block-columns alignwide are-vertically-aligned-bottom is-not-stacked-on-mobile">
			<!-- wp:column {"verticalAlignment":"bottom","metadata":{"name":"Title Column"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
				<div class="wp-block-column is-vertically-aligned-bottom">
				<!-- wp:woocommerce/breadcrumbs {"fontSize":"x-small","textColor":"secondary","style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}}} /-->
				<!-- wp:post-title /-->
				</div>
			<!-- /wp:column -->
			<!-- wp:column {"verticalAlignment":"bottom","metadata":{"name":"Search Column"}} -->
				<div class="wp-block-column is-vertically-aligned-bottom">
				<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right"}} -->
					<div class="wp-block-buttons">
					<!-- wp:button {"className":"is-style-button-light","style":{"border":{"bottom":{"color":"var:preset|color|neutral-300","width":"1px"},"top":{},"right":{},"left":{}}},"fontSize":"x-small","icon":"arrow-left","iconPositionLeft":true} -->
						<div class="wp-block-button is-style-button-light"><a class="wp-block-button__link has-100-font-size has-custom-font-size wp-element-button" href="/shop" style="border-bottom-color:var(--wp--preset--color--neutral-300);border-bottom-width:1px">Continue Shopping</a></div>
					<!-- /wp:button -->
					</div>
				<!-- /wp:buttons -->
				</div>
			<!-- /wp:column -->
			</div>
		<!-- /wp:columns -->
		</div>
	<!-- /wp:group -->
	<!-- wp:woocommerce/page-content-wrapper {"page":"cart"} -->
		<!-- wp:group {"tagName":"main","metadata":{"name":"Cart Main Content"},"align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
			<main class="wp-block-group alignwide" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
			<!-- wp:woocommerce/store-notices /-->
			<!-- wp:post-content {"align":"wide"} /-->
			</main>
		<!-- /wp:group -->
	<!-- /wp:woocommerce/page-content-wrapper -->
	</div>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->
