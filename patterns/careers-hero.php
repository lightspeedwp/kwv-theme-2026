<?php
/**
 * Title: Careers — Hero
 * Slug: kwv/careers-hero
 * Description: Full-bleed Careers hero — the Home Hero pattern re-used with a "Careers" heading: large background image, transparent header over it and a bottom scroll-down chevron that jumps to the page content (#content).
 * Categories: kwv/hero, kwv/pages
 * Keywords: careers, hero, cover, banner, header, transparent, scroll
 * Viewport Width: 1500
 * Inserter: true
 */
?>
<!-- wp:cover {"url":"http://localhost:8901/wp-content/uploads/2026/06/home-hero-image-full.png","id":145,"dimRatio":20,"overlayColor":"contrast","isUserOverlayColor":true,"focalPoint":{"x":0.3,"y":0.5},"minHeight":600,"minHeightUnit":"px","contentPosition":"top center","sizeSlug":"full","align":"full","className":"is-style-default","style":{"spacing":{"padding":{"top":"var:preset|spacing|0","right":"var:preset|spacing|0","bottom":"var:preset|spacing|0","left":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull has-custom-content-position is-position-top-center is-style-default"
	style="padding-top:var(--wp--preset--spacing--0);padding-right:var(--wp--preset--spacing--0);padding-bottom:var(--wp--preset--spacing--0);padding-left:var(--wp--preset--spacing--0);min-height:600px">
	<img class="wp-block-cover__image-background wp-image-145 size-full" alt=""
		src="http://localhost:8901/wp-content/uploads/2026/06/home-hero-image-full.png" style="object-position:30% 50%"
		data-object-fit="cover" data-object-position="30% 50%" /><span aria-hidden="true"
		class="wp-block-cover__background has-contrast-background-color has-background-dim-20 has-background-dim"></span>
	<div class="wp-block-cover__inner-container">
		<!-- wp:group {"metadata":{"name":"Header Transparent","patternName":"kwv/header-transparent","description":"Transparent header with logo, navigation, account and cart — sits over large hero sections.","categories":["header"]},"align":"full","className":"is-style-header-transparent","style":{"border":{"bottom":{"color":"var:preset|color|brand-200","width":"1px"},"top":{},"right":{},"left":{}}},"layout":{"type":"constrained"}} -->
		<div class="wp-block-group alignfull is-style-header-transparent"
			style="border-bottom-color:var(--wp--preset--color--brand-200);border-bottom-width:1px">
			<!-- wp:group {"align":"wide","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}},"textColor":"base","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
			<div class="wp-block-group alignwide has-base-color has-text-color has-link-color">
				<!-- wp:site-logo {"width":96,"shouldSyncIcon":true} /-->

				<!-- wp:navigation {"ref":4,"submenuVisibility":"click","icon":"menu","metadata":{"ignoredHookedBlocks":["woocommerce/customer-account"]},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"fontSize":"300","layout":{"type":"flex","justifyContent":"center"}} /-->

				<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
				<div class="wp-block-group">
					<!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"line","iconClass":"wc-block-customer-account__account-icon","textColor":"base","style":{"elements":{"link":{"color":{"text":"var:preset|color|base"}}}}} /-->

					<!-- wp:woocommerce/mini-cart /-->
				</div>
				<!-- /wp:group -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:group -->

		<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|70","padding":{"top":"16em"}}},"layout":{"type":"constrained","justifyContent":"left"}} -->
		<div class="wp-block-group alignwide" style="padding-top:16em">
			<!-- wp:heading {"level":1,"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"textColor":"base","fontSize":"700","fontFamily":"heading"} -->
			<h1 class="wp-block-heading has-base-color has-text-color has-heading-font-family has-700-font-size"
				style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">
				Careers</h1>
			<!-- /wp:heading -->
		</div>
		<!-- /wp:group -->

		<!-- wp:columns {"verticalAlignment":"bottom","align":"wide"} -->
		<div class="wp-block-columns alignwide are-vertically-aligned-bottom">
			<!-- wp:column {"verticalAlignment":"bottom","width":"50%"} -->
			<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:50%"></div>
			<!-- /wp:column -->

			<!-- wp:column {"verticalAlignment":"bottom","width":"50%"} -->
			<div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:50%"></div>
			<!-- /wp:column -->
		</div>
		<!-- /wp:columns -->

		<!-- wp:buttons {"className":"kwv-scroll-indicator","layout":{"type":"flex","justifyContent":"center"}} -->
		<div class="wp-block-buttons kwv-scroll-indicator"><!-- wp:button {"text":"Scroll to content","url":"#content"} -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="#content">Scroll to content</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
</div>
<!-- /wp:cover -->
