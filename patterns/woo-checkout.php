<?php
/**
 * Title: WooCommerce Checkout
 * Slug: kwv/woo-checkout
 * Description: The checkout page for WooCommerce.
 * Categories: kwv/woocommerce
 * Keywords: checkout, woocommerce
 * Template Types: page-checkout
 * Inserter: false
 * Viewport Width: 1500
 */
?>
<!-- wp:group {"tagName":"header","metadata":{"name":"Checkout Header"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}},"border":{"top":{"width":"0px","style":"none"},"right":{"width":"0px","style":"none"},"bottom":{"color":"var:preset|color|neutral-300","width":"1px"},"left":{"width":"0px","style":"none"}}},"layout":{"type":"constrained"}} -->
<header class="wp-block-group" style="border-top-style:none;border-top-width:0px;border-right-style:none;border-right-width:0px;border-bottom-color:var(--wp--preset--color--neutral-300);border-bottom-width:1px;border-left-style:none;border-left-width:0px;padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)">
	<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|20","top":"var:preset|spacing|20"}}},"layout":{"type":"flex","justifyContent":"space-between"}} -->
		<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)">
		<!-- wp:site-title {"level":0} /-->
		<!-- wp:woocommerce/cart-link {"cartIcon":"bag","content":"Return to Cart","fontSize":"x-small","style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}}} /-->
		</div>
	<!-- /wp:group -->
	</header>
<!-- /wp:group -->
<!-- wp:group {"metadata":{"name":"Page Header"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-neutral-200-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
	<!-- wp:group {"align":"wide","layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignwide">
		<!-- wp:post-title {"level":1,"align":"wide","fontSize":"medium"} /-->
		</div>
	<!-- /wp:group -->
	</div>
<!-- /wp:group -->
<!-- wp:woocommerce/page-content-wrapper {"page":"checkout"} -->
	<!-- wp:group {"tagName":"main","metadata":{"name":"Checkout Main Content"},"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
		<main class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
		<!-- wp:group {"metadata":{"name":"Store Notices Container"},"align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
			<div class="wp-block-group alignwide" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40)">
			<!-- wp:woocommerce/store-notices /-->
			</div>
		<!-- /wp:group -->
		<!-- wp:post-content {"align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"0","bottom":"0"}}}} /-->
		</main>
	<!-- /wp:group -->
<!-- /wp:woocommerce/page-content-wrapper -->
