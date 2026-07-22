<?php
/**
 * Title: Single Product
 * Slug: kwv/woo-single-product
 * Description: Single product — WooCommerce breadcrumbs above a 45% product gallery beside a sticky details column (title, brand subtitle, price, add to cart, description, and SKU / category / tag links), followed by a "Shop the range" product collection.
 * Categories: kwv/woocommerce
 * Keywords: product, single, woocommerce, grid
 * Template Types: single-product
 * Inserter: false
 * Viewport Width: 1500
 */
?>
<!-- wp:template-part {"slug":"header","tagName":"header","className":"site-header"} /-->

<!-- wp:group {"tagName":"main","metadata":{"name":"Main Content"},"className":"is-style-light-page-section","layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group is-style-light-page-section"><!-- wp:group {"metadata":{"name":"Product Layout"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0"><!-- wp:group {"metadata":{"name":"Store Notice"},"align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--40)"><!-- wp:woocommerce/store-notices /--></div>
<!-- /wp:group -->

<!-- wp:columns {"metadata":{"name":"Product Info Columns"},"align":"wide","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"0","bottom":"0"}}}} -->
<div class="wp-block-columns alignwide" style="margin-top:0;margin-bottom:0"><!-- wp:column {"width":"45%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column" style="flex-basis:45%"><!-- wp:woocommerce/breadcrumbs {"fontSize":"200","textColor":"neutral-800","style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-800"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"400"}}} /-->

<!-- wp:woocommerce/product-gallery {"hoverZoom":false,"layout":{"type":"flex","flexWrap":"nowrap","orientation":"vertical"}} -->
<div class="wp-block-woocommerce-product-gallery wc-block-product-gallery"><!-- wp:woocommerce/product-gallery-large-image -->
<div class="wp-block-woocommerce-product-gallery-large-image wc-block-product-gallery-large-image__inner-blocks"><!-- wp:woocommerce/product-image {"showProductLink":false,"showSaleBadge":false,"style":{"border":{"radius":{"topLeft":"10px","topRight":"10px","bottomLeft":"10px","bottomRight":"10px"}}}} -->
<div class="is-loading"></div>
<!-- /wp:woocommerce/product-image -->

<!-- wp:woocommerce/product-sale-badge {"backgroundColor":"contrast","textColor":"base","align":"right","fontSize":"100","style":{"border":{"width":"0px","style":"none","radius":{"topLeft":"100px","topRight":"100px","bottomLeft":"100px","bottomRight":"100px"}},"elements":{"link":{"color":{"text":"var:preset|color|base"}}},"spacing":{"margin":{"top":"5px","right":"5px"}}}} /-->

<!-- wp:woocommerce/product-gallery-large-image-next-previous -->
<div class="wp-block-woocommerce-product-gallery-large-image-next-previous"></div>
<!-- /wp:woocommerce/product-gallery-large-image-next-previous --></div>
<!-- /wp:woocommerce/product-gallery-large-image -->

<!-- wp:woocommerce/product-gallery-thumbnails {"style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} /--></div>
<!-- /wp:woocommerce/product-gallery --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"metadata":{"name":"Product Details Wrapper"},"style":{"dimensions":{"minHeight":"100%"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="min-height:100%"><!-- wp:group {"metadata":{"name":"Product Info Section"},"style":{"position":{"type":"sticky","top":"0px"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Product Details (Desktop)"},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"medium":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":1,"fontSize":"500","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

<!-- wp:post-excerpt {"style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"600"}},"textColor":"brand-500","fontSize":"200"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"120px"} -->
<div class="wp-block-column" style="flex-basis:120px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained","justifyContent":"right"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-price {"textAlign":"right","isDescendentOfSingleProductTemplate":true,"fontSize":"400","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}}} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:woocommerce/add-to-cart-with-options /-->

<!-- wp:woocommerce/product-description /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Product Details (Mobile)"},"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"},"blockVisibility":{"controlSets":[{"id":1,"enable":true,"controls":{"screenSize":{"hideOnScreenSize":{"large":true,"small":true}}}}]}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|0"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":1,"fontSize":"500","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

<!-- wp:post-excerpt {"style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"600"}},"textColor":"brand-500","fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained","justifyContent":"right"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-price {"textAlign":"right","isDescendentOfSingleProductTemplate":true,"fontSize":"400","style":{"typography":{"fontStyle":"normal","fontWeight":"700"}}} /-->

<!-- wp:woocommerce/add-to-cart-with-options /--></div>
<!-- /wp:group -->

<!-- wp:woocommerce/product-description /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Product Taxonomy Links"},"style":{"spacing":{"blockGap":"var:preset|spacing|20"},"elements":{"link":{"color":{"text":"var:preset|color|neutral-700"}}},"position":{"type":""}},"textColor":"neutral-700","fontSize":"100","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group has-neutral-700-color has-text-color has-link-color has-100-font-size"><!-- wp:woocommerce/product-sku /-->

<!-- wp:post-terms {"term":"product_cat","prefix":"Category: "} /-->

<!-- wp:post-terms {"term":"product_tag","prefix":"Tags: "} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:woocommerce/product-collection {"queryId":0,"query":{"perPage":4,"pages":1,"offset":0,"postType":"product","order":"asc","orderBy":"title","search":"","exclude":[],"inherit":false,"taxQuery":[],"isProductCollectionBlock":true,"featured":false,"woocommerceOnSale":false,"woocommerceStockStatus":["instock","outofstock","onbackorder"],"woocommerceAttributes":[],"woocommerceHandPickedProducts":[],"filterable":true,"relatedBy":{"categories":true,"tags":false}},"tagName":"div","displayLayout":{"type":"flex","columns":4,"shrinkColumns":false},"dimensions":{"widthType":"fill"},"collection":"woocommerce/product-collection/related","hideControls":["inherit"],"queryContextIncludes":["collection"],"__privatePreviewState":{"isPreview":true,"previewMessage":"Actual products will vary depending on the product being viewed."},"align":"wide"} -->
<div class="wp-block-woocommerce-product-collection alignwide"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"align":"wide","fontSize":"500"} -->
<h2 class="wp-block-heading alignwide has-500-font-size">Shop the range</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:woocommerce/product-template -->
<!-- wp:template-part {"slug":"product-card"} /-->
<!-- /wp:woocommerce/product-template --></div>
<!-- /wp:woocommerce/product-collection --></main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer","className":"site-footer"} /-->
