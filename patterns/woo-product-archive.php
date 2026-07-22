<?php
/**
 * Title: Product Archive
 * Slug: kwv/woo-product-archive
 * Description: Shop / product archive — dynamic per-term hero, WooCommerce breadcrumbs, a filters sidebar (Brand, Price Range, Type, Volume), a three-column product grid using the Product Card, and pagination. Serves the shop, product category, and product brand archives.
 * Categories: kwv/woocommerce
 * Keywords: product, archive, shop, woocommerce, grid, filters
 * Template Types: archive-product, taxonomy-product_cat, taxonomy-product_brand
 * Inserter: false
 * Viewport Width: 1500
 */
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Main Content"},"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"blockGap":"0"}},"layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group" style="margin-top:0;margin-bottom:0"><?php require __DIR__ . '/term-banner-hero.php'; ?>

<!-- wp:group {"metadata":{"name":"Product Grid Section"},"align":"full","className":"is-style-light-page-section","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section" style="padding-top:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"metadata":{"name":"Woo Breadcrumbs"},"align":"wide","style":{"spacing":{"padding":{"bottom":"var:preset|spacing|5","top":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--5)"><!-- wp:woocommerce/breadcrumbs {"fontSize":"200","textColor":"neutral-800","style":{"elements":{"link":{"color":{"text":"var:preset|color|neutral-800"}}},"spacing":{"margin":{"bottom":"var:preset|spacing|20"}},"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"400"}}} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Store Notices Container"},"align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:woocommerce/store-notices /--></div>
<!-- /wp:group -->

<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"22%","metadata":{"name":"Filters Sidebar"}} -->
<div class="wp-block-column" style="flex-basis:22%"><!-- wp:woocommerce/product-filters {"className":"kwv-shop-filters","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-woocommerce-product-filters wc-block-product-filters kwv-shop-filters"><!-- wp:heading {"style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|20"}},"typography":{"fontStyle":"normal","fontWeight":"400","letterSpacing":"0.05em"}},"fontSize":"400"} -->
<h2 class="wp-block-heading has-400-font-size" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--20);font-style:normal;font-weight:400;letter-spacing:0.05em">Filters</h2>
<!-- /wp:heading -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:woocommerce/product-filter-active -->
<div class="wp-block-woocommerce-product-filter-active"><!-- wp:woocommerce/product-filter-removable-chips -->
<div class="wp-block-woocommerce-product-filter-removable-chips wc-block-product-filter-removable-chips"></div>
<!-- /wp:woocommerce/product-filter-removable-chips -->

<!-- wp:woocommerce/product-filter-clear-button -->
<!-- wp:buttons {"layout":{"type":"flex","verticalAlignment":"stretched"}} -->
<div class="wp-block-buttons"><!-- wp:button {"width":100,"className":"wc-block-product-filter-clear-button is-style-button-dark is-style-cta","style":{"typography":{"textDecoration":"none","textAlign":"center"},"outline":"none","fontSize":"100","spacing":{"padding":{"left":"0.6rem","right":"0.6rem","top":"0.6rem","bottom":"0.6rem"}},"dimensions":{"width":"var:preset|dimension|100"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 wc-block-product-filter-clear-button is-style-button-dark is-style-cta" style="width:var(--wp--preset--dimension--100)"><a class="wp-block-button__link has-text-align-center wp-element-button" style="padding-top:0.6rem;padding-right:0.6rem;padding-bottom:0.6rem;padding-left:0.6rem;text-decoration:none">Clear filters</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:woocommerce/product-filter-clear-button --></div>
<!-- /wp:woocommerce/product-filter-active -->

<!-- wp:woocommerce/product-filter-taxonomy {"taxonomy":"product_brand"} -->
<div class="wp-block-woocommerce-product-filter-taxonomy"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10","top":"0"}}},"fontSize":"100"} -->
<h3 class="wp-block-heading has-100-font-size" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--10)">Brand</h3>
<!-- /wp:heading -->

<!-- wp:woocommerce/product-filter-checkbox-list -->
<div class="wp-block-woocommerce-product-filter-checkbox-list wc-block-product-filter-checkbox-list"></div>
<!-- /wp:woocommerce/product-filter-checkbox-list --></div>
<!-- /wp:woocommerce/product-filter-taxonomy -->

<!-- wp:woocommerce/product-filter-price -->
<div class="wp-block-woocommerce-product-filter-price"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10","top":"0"}}},"fontSize":"100"} -->
<h3 class="wp-block-heading has-100-font-size" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--10)">Price Range</h3>
<!-- /wp:heading -->

<!-- wp:woocommerce/product-filter-price-slider -->
<div class="wp-block-woocommerce-product-filter-price-slider wc-block-product-filter-price-slider"></div>
<!-- /wp:woocommerce/product-filter-price-slider --></div>
<!-- /wp:woocommerce/product-filter-price -->

<!-- wp:woocommerce/product-filter-taxonomy {"showCounts":true} -->
<div class="wp-block-woocommerce-product-filter-taxonomy"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10","top":"0"}}},"fontSize":"100"} -->
<h3 class="wp-block-heading has-100-font-size" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--10)">Type</h3>
<!-- /wp:heading -->

<!-- wp:woocommerce/product-filter-checkbox-list -->
<div class="wp-block-woocommerce-product-filter-checkbox-list wc-block-product-filter-checkbox-list"></div>
<!-- /wp:woocommerce/product-filter-checkbox-list --></div>
<!-- /wp:woocommerce/product-filter-taxonomy -->

<!-- wp:woocommerce/product-filter-attribute {"attributeId":3,"showCounts":true} -->
<div class="wp-block-woocommerce-product-filter-attribute"><!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|10","top":"0"}}},"fontSize":"100"} -->
<h3 class="wp-block-heading has-100-font-size" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--10)">Volume</h3>
<!-- /wp:heading -->

<!-- wp:woocommerce/product-filter-checkbox-list -->
<div class="wp-block-woocommerce-product-filter-checkbox-list wc-block-product-filter-checkbox-list"></div>
<!-- /wp:woocommerce/product-filter-checkbox-list --></div>
<!-- /wp:woocommerce/product-filter-attribute --></div>
<!-- /wp:group --></div>
<!-- /wp:woocommerce/product-filters --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"78%","metadata":{"name":"Product Grid Column"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column" style="flex-basis:78%"><!-- wp:woocommerce/product-results-count {"style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|20"}}},"fontSize":"300"} /-->

<!-- wp:woocommerce/product-collection {"queryId":0,"query":{"woocommerceAttributes":[],"woocommerceStockStatus":["instock","outofstock","onbackorder"],"taxQuery":[],"isProductCollectionBlock":true,"perPage":8,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":true,"featured":false,"woocommerceOnSale":false,"woocommerceHandPickedProducts":[],"filterable":true,"relatedBy":{"categories":true,"tags":true}},"tagName":"div","displayLayout":{"type":"flex","columns":3,"shrinkColumns":true},"dimensions":{"widthType":"fill","fixedWidth":""},"queryContextIncludes":["collection"],"forcePageReload":true,"__privatePreviewState":{"isPreview":false,"previewMessage":"Actual products will vary depending on the page being viewed."},"align":"wide"} -->
<div class="wp-block-woocommerce-product-collection alignwide"><!-- wp:woocommerce/product-template -->
<!-- wp:template-part {"slug":"product-card","theme":"kwv-theme-2026"} /-->
<!-- /wp:woocommerce/product-template -->

<!-- wp:group {"metadata":{"name":"Pagination"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|20"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--20)"><!-- wp:group {"metadata":{"name":"Pagination"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40"},"padding":{"right":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--40);margin-bottom:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)"><!-- wp:query-pagination {"paginationArrow":"chevron","align":"wide","layout":{"type":"flex","justifyContent":"space-between"}} -->
<!-- wp:query-pagination-previous {"label":"Previous Page","backgroundColor":"neutral-200"} /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next {"label":"Next Page"} /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:woocommerce/product-collection-no-results -->
<!-- wp:group {"metadata":{"name":"No Results Message"},"style":{"spacing":{"blockGap":"var:preset|spacing|20","padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"border":{"radius":{"topLeft":"var:preset|border-radius|200","topRight":"var:preset|border-radius|200","bottomLeft":"var:preset|border-radius|200","bottomRight":"var:preset|border-radius|200"}}},"backgroundColor":"brand-100","layout":{"type":"flex","orientation":"vertical","justifyContent":"center","flexWrap":"wrap"}} -->
<div class="wp-block-group has-brand-100-background-color has-background" style="border-top-left-radius:var(--wp--preset--border-radius--200);border-top-right-radius:var(--wp--preset--border-radius--200);border-bottom-left-radius:var(--wp--preset--border-radius--200);border-bottom-right-radius:var(--wp--preset--border-radius--200);padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--30)"><!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"fontSize":"400"} -->
<p class="has-text-align-center has-400-font-size"><strong>No products found</strong></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"has-neutral-700-color has-text-color has-link-color","style":{"elements":{"link":{"color":{"text":"var:preset|color|brand-500"}}},"typography":{"textAlign":"center"}},"textColor":"neutral-700"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color has-link-color">Try <a href="#" class="wc-link-clear-any-filters">clearing any filters</a> or head to our <a href="#" class="wc-link-stores-home">store home</a>.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
<!-- /wp:woocommerce/product-collection-no-results --></div>
<!-- /wp:woocommerce/product-collection --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></main>
<!-- /wp:group -->

