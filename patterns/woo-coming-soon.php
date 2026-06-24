<?php
/**
 * Title: WooCommerce Coming Soon
 * Slug: kwv/woo-coming-soon
 * Description: The coming soon page for WooCommerce.
 * Categories: kwv/woocommerce
 * Keywords: coming soon, woocommerce
 * Template Types: coming-soon
 * Inserter: false
 * Viewport Width: 1500
 */
?>
<!-- wp:woocommerce/coming-soon {"storeOnly":true,"className":"woocommerce-coming-soon-store-only"} -->
<div class="wp-block-woocommerce-coming-soon woocommerce-coming-soon-store-only">
	<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->
	<!-- wp:group {"metadata":{"name":"Coming Soon Container"},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group">
		<!-- wp:group {"metadata":{"name":"Coming Soon Content"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|40","right":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|20"},"border":{"radius":{"topLeft":"var:preset|border-radius|md","topRight":"var:preset|border-radius|md","bottomLeft":"var:preset|border-radius|md","bottomRight":"var:preset|border-radius|md"}}},"backgroundColor":"tertiary","layout":{"type":"constrained"}} -->
			<div class="wp-block-group has-neutral-200-background-color has-background" style="border-top-left-radius:var(--wp--preset--border-radius--md);border-top-right-radius:var(--wp--preset--border-radius--md);border-bottom-left-radius:var(--wp--preset--border-radius--md);border-bottom-right-radius:var(--wp--preset--border-radius--md);margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)">
			<!-- wp:heading {"textAlign":"center","level":1,"fontSize":"large","fontFamily":"cardo"} -->
				<h1 class="wp-block-heading has-text-align-center has-cardo-font-family has-500-font-size">Great things are on the horizon</h1>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}}},"textColor":"secondary","fontFamily":"inter"} -->
				<p class="has-text-align-center has-neutral-700-color has-text-color has-link-color has-inter-font-family">Something big is brewing! Our store is in the works and will be launching soon!</p>
			<!-- /wp:paragraph -->
			</div>
		<!-- /wp:group -->
		</div>
	<!-- /wp:group -->
	<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->
	</div>
<!-- /wp:woocommerce/coming-soon -->
