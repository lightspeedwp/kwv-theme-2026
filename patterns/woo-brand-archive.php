<?php
/**
 * Title: Brand Archive
 * Slug: kwv/woo-brand-archive
 * Description: Brand page (/brand/…) — the per-brand banner hero, WooCommerce breadcrumbs, the brand logo, introduction and awards row from the Brand's fields, a "Back to Home" link, a one-product-at-a-time slider of the brand's products (each slide is the editable "Brand Product Slide" template part), thumbnails, the brand's social icons and website link, and a "Continue to Shop" button. Fields left blank on the Brand are not shown.
 * Categories: kwv/woocommerce
 * Keywords: brand, archive, slider, products, awards, woocommerce
 * Template Types: taxonomy-product_brand
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package kwv
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Main Content"},"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group" style="margin-top:0;margin-bottom:0"><?php require __DIR__ . '/term-banner-hero.php'; ?>

<!-- wp:group {"metadata":{"name":"Woo Breadcrumbs"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:0"><!-- wp:woocommerce/breadcrumbs {"align":"wide","fontSize":"200","textColor":"neutral-800","style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-800"}}},"spacing":{"margin":{"bottom":"0"}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"var:custom|font-weight|regular"}}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Brand Introduction"},"align":"full","className":"is-style-light-page-section kwv-brand-intro","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|40"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section kwv-brand-intro" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:image {"metadata":{"name":"Brand Logo","bindings":{"url":{"source":"kwv/brand","args":{"key":"logo"}},"alt":{"source":"kwv/brand","args":{"key":"logo"}}}},"align":"center","className":"kwv-brand-intro__logo"} -->
<figure class="wp-block-image aligncenter kwv-brand-intro__logo"><img alt=""/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"metadata":{"name":"Brand Introduction","bindings":{"content":{"source":"kwv/brand","args":{"key":"intro"}}}},"fontSize":"200"} -->
<p class="has-200-font-size"><?php esc_html_e( 'The brand introduction appears here. It is entered on the Brand under Products → Brands.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:kwv/brand-awards /-->

<!-- wp:separator {"align":"wide","className":"is-style-separator-thin kwv-rule-brand"} -->
<hr class="wp-block-separator alignwide has-alpha-channel-opacity is-style-separator-thin kwv-rule-brand"/>
<!-- /wp:separator --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Brand Products"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"0","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section" style="padding-top:0;padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:buttons {"metadata":{"name":"Back To Home"},"align":"wide","layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons alignwide"><!-- wp:button {"className":"is-style-text-link"} -->
<div class="wp-block-button is-style-text-link"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/' ); ?>">&lt; <?php esc_html_e( 'Back to Home', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"metadata":{"name":"Store Notices Container"},"align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:0;margin-bottom:0"><!-- wp:woocommerce/store-notices /--></div>
<!-- /wp:group -->

<!-- wp:woocommerce/product-collection {"queryId":0,"query":{"woocommerceAttributes":[],"woocommerceStockStatus":["instock","outofstock","onbackorder"],"taxQuery":[],"isProductCollectionBlock":true,"perPage":100,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":true,"featured":false,"woocommerceOnSale":false,"woocommerceHandPickedProducts":[],"filterable":false,"relatedBy":{"categories":true,"tags":true}},"tagName":"div","displayLayout":{"type":"list","columns":1,"shrinkColumns":false},"dimensions":{"widthType":"fill","fixedWidth":""},"queryContextIncludes":["collection"],"forcePageReload":true,"__privatePreviewState":{"isPreview":false,"previewMessage":"Actual products will vary depending on the brand being viewed."},"metadata":{"name":"Brand Product Slider"},"align":"wide","className":"kwv-brand-slider"} -->
<div class="wp-block-woocommerce-product-collection alignwide kwv-brand-slider"><!-- wp:woocommerce/product-template -->
<!-- wp:template-part {"slug":"brand-product-slide","theme":"kwv-theme-2026"} /-->
<!-- /wp:woocommerce/product-template --></div>
<!-- /wp:woocommerce/product-collection -->

<!-- wp:columns {"metadata":{"name":"Thumbnails And Brand Links"},"align":"wide","className":"kwv-brand-slider-footer","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|80"}}}} -->
<div class="wp-block-columns alignwide kwv-brand-slider-footer"><!-- wp:column {"width":"50%","metadata":{"name":"Product Thumbnails"}} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"metadata":{"name":"Product Thumbnails (filled automatically)"},"className":"kwv-brand-slider-thumbs","layout":{"type":"default"}} -->
<div class="wp-block-group kwv-brand-slider-thumbs"></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%","metadata":{"name":"Brand Links"},"className":"kwv-brand-links","style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-column kwv-brand-links" style="flex-basis:50%"><!-- wp:separator {"className":"is-style-separator-thin kwv-rule-brand"} -->
<hr class="wp-block-separator has-alpha-channel-opacity is-style-separator-thin kwv-rule-brand"/>
<!-- /wp:separator -->

<!-- wp:social-links {"iconColor":"brand-600","iconColorValue":"var(--wp--preset--color--brand-600)","openInNewTab":true,"size":"has-normal-icon-size","className":"is-style-logos-only","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|10","left":"var:preset|spacing|10"},"margin":{"top":"var:preset|spacing|30"}}}} -->
<ul class="wp-block-social-links has-normal-icon-size has-icon-color is-style-logos-only" style="margin-top:var(--wp--preset--spacing--30)"><!-- wp:social-link {"url":"#","service":"facebook","metadata":{"bindings":{"url":{"source":"kwv/brand","args":{"key":"facebook"}}}}} /-->

<!-- wp:social-link {"url":"#","service":"instagram","metadata":{"bindings":{"url":{"source":"kwv/brand","args":{"key":"instagram"}}}}} /-->

<!-- wp:social-link {"url":"#","service":"x","metadata":{"bindings":{"url":{"source":"kwv/brand","args":{"key":"x"}}}}} /-->

<!-- wp:social-link {"url":"#","service":"youtube","metadata":{"bindings":{"url":{"source":"kwv/brand","args":{"key":"youtube"}}}}} /-->

<!-- wp:social-link {"url":"#","service":"tiktok","metadata":{"bindings":{"url":{"source":"kwv/brand","args":{"key":"tiktok"}}}}} /-->

<!-- wp:social-link {"url":"#","service":"linkedin","metadata":{"bindings":{"url":{"source":"kwv/brand","args":{"key":"linkedin"}}}}} /--></ul>
<!-- /wp:social-links -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"metadata":{"name":"Brand Website Link","bindings":{"url":{"source":"kwv/brand","args":{"key":"website"}}}},"linkTarget":"_blank","rel":"noreferrer noopener","className":"is-style-text-link"} -->
<div class="wp-block-button is-style-text-link"><a class="wp-block-button__link wp-element-button" href="#" target="_blank" rel="noreferrer noopener"><?php esc_html_e( 'Visit brand website', 'kwv' ); ?> &gt;</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:buttons {"metadata":{"name":"Continue To Shop"},"align":"wide","layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons alignwide"><!-- wp:button {"className":"is-style-outline-dark"} -->
<div class="wp-block-button is-style-outline-dark"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/shop/' ); ?>"><?php esc_html_e( 'Continue to Shop', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->
