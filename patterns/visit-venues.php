<?php
/**
 * Title: Visit — Venues
 * Slug: kwv/visit-venues
 * Description: The three KWV tour venues — KWV Emporium, Cathedral Cellar (tinted band) and House of Fire — each a single-slide carousel of alternating image/text slides (the venue itself, then its tours and tastings), with Book and Menu calls to action on every slide. Arrows sit either side of the slide on desktop and beneath it on phones; no dots, no autoplay.
 * Categories: kwv/visit, kwv/features
 * Keywords: visit, emporium, cathedral cellar, house of fire, tours, tastings, venues, carousel
 * Viewport Width: 1500
 * Inserter: true
 *
 * Carousel behaviour (single slide, arrows, no pagination, no autoplay) is the
 * Carousel Block's own and stays editable in the block inspector; the arrow
 * gutters and the phone layout live in assets/styles/carousel-block.css under
 * `.kwv-venue-carousel`.
 *
 * Environment-specific values mirrored from kwv.lightspeedwp.dev: the image
 * IDs/URLs and the Popup Maker "Menu" popups (182953 Emporium, 182954 Cathedral
 * Cellar, 182955 House of Fire) are DB entities and must be remapped on
 * production.
 *
 * @package kwv
 */

?>
<!-- wp:group {"tagName":"section","metadata":{"name":"Visit — KWV Emporium"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section"><!-- wp:cb/carousel-v2 {"slidesPerView":1,"spaceBetween":40,"speed":500,"pagination":false,"resizeObserver":true,"align":"wide","className":"kwv-venue-carousel"} -->
<div class="wp-block-cb-carousel-v2 alignwide cb-carousel-block cb-single-slide kwv-venue-carousel" data-cb-slides-per-view="1" data-cb-slides-per-group="1" data-cb-space-between="40" data-cb-speed="500" data-cb-navigation="true" data-cb-resize-observer="true" data-cb-breakpoints="{&quot;768&quot;:{&quot;slidesPerView&quot;:1,&quot;slidesPerGroup&quot;:1}}"><div class="swiper"><div class="cb-wrapper swiper-wrapper"><!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"align":"wide","style":{"typography":{"textTransform":"uppercase"}},"fontFamily":"heading","anchor":"h-kwv-emporium"} -->
<h2 class="wp-block-heading alignwide has-heading-font-family" id="h-kwv-emporium" style="text-transform:uppercase"><?php esc_html_e( 'KWV Emporium', 'kwv' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'Spanning over 22 hectares, the KWV cellar complex in Paarl is one of the world\'s largest. Want to experience it for yourself? Visit us and shop the world’s most awarded brandy, premium wines from South Africa’s finest wine producer, and world-class spirits. Step into our immersive cellar tours and tastings that linger long after the last sip.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/kwv' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182953"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182953"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":159837,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2024/09/Emporium_Square_1.png' ); ?>" alt="<?php esc_attr_e( 'The KWV Emporium entrance', 'kwv' ); ?>" class="wp-image-159837" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 -->

<!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"textTransform":"uppercase","fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h3 class="wp-block-heading alignwide has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'Laborie Cap Classique & Macarons', 'kwv' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'CC Brut with Apple Danish macaron; CC Blanc de Blanc with Lemon, Macadamia & Lavender macaron; CC Brut Rosé with Pomegranate & Rose macaron; CC Grand Nectar with Pear & Vanilla macaron; CC Nectar Rosé with Strawberry & Candyfloss macaron.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/kwv' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182953"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182953"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183708,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-emporium-cap-classique-macarons.webp' ); ?>" alt="<?php esc_attr_e( 'Laborie Cap Classique being poured into a row of tasting flutes, each paired with a macaron on a wooden board', 'kwv' ); ?>" class="wp-image-183708" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 -->

<!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"textTransform":"uppercase","fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h3 class="wp-block-heading alignwide has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'KWV Brandy & Belgian Chocolates', 'kwv' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( '10 YO Brandy with Belgian milk chocolate; 12 YO with Belgian almond milk chocolate; 15 YO with Belgian dark chocolate; 20 YO with Belgian white chocolate.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/kwv' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182953"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182953"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183709,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-emporium-brandy-chocolates.webp' ); ?>" alt="<?php esc_attr_e( 'KWV 10, 12, 15 and 20 year old brandies behind a tasting flight of four glasses, each paired with a Belgian chocolate', 'kwv' ); ?>" class="wp-image-183709" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 -->

<!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"textTransform":"uppercase","fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h3 class="wp-block-heading alignwide has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'Wine & Brandy Tastings', 'kwv' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'Experience a wide range of tastings featuring premium brandies, whiskies and award winning wines.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/kwv' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182953"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182953"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183710,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-emporium-wine-brandy-tastings.webp' ); ?>" alt="<?php esc_attr_e( 'A host pours red wine for guests seated at a long tasting table at the KWV Emporium', 'kwv' ); ?>" class="wp-image-183710" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 --></div></div><div class="cb-button-prev swiper-button-prev"></div><div class="cb-button-next swiper-button-next"></div></div>
<!-- /wp:cb/carousel-v2 --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","metadata":{"name":"Visit — Cathedral Cellar"},"align":"full","className":"is-style-tinted-page-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-tinted-page-section"><!-- wp:cb/carousel-v2 {"slidesPerView":1,"spaceBetween":40,"speed":500,"pagination":false,"resizeObserver":true,"align":"wide","className":"kwv-venue-carousel"} -->
<div class="wp-block-cb-carousel-v2 alignwide cb-carousel-block cb-single-slide kwv-venue-carousel" data-cb-slides-per-view="1" data-cb-slides-per-group="1" data-cb-space-between="40" data-cb-speed="500" data-cb-navigation="true" data-cb-resize-observer="true" data-cb-breakpoints="{&quot;768&quot;:{&quot;slidesPerView&quot;:1,&quot;slidesPerGroup&quot;:1}}"><div class="swiper"><div class="cb-wrapper swiper-wrapper"><!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183711,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-cathedral-cellar.webp' ); ?>" alt="<?php esc_attr_e( 'Carved oak vats lining the KWV Cathedral Cellar beneath paper lanterns', 'kwv' ); ?>" class="wp-image-183711" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"align":"wide","style":{"typography":{"textTransform":"uppercase"}},"fontFamily":"heading","anchor":"h-cathedral-cellar"} -->
<h2 class="wp-block-heading alignwide has-heading-font-family" id="h-cathedral-cellar" style="text-transform:uppercase"><?php esc_html_e( 'Cathedral Cellar', 'kwv' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'Since 1930, the Cathedral Cellar has stood as a breathtaking tribute to winemaking, its soaring domed ceiling echoing the grandeur of a true cathedral. Step inside this iconic landmark and let the rich aromas and timeless tales of South Africa’s legendary winemakers draw you into a world where craftsmanship and legacy blend in every bottle.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/cellar' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182954"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182954"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 -->

<!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183712,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-cathedral-cellar-technical-wines-tour.webp' ); ?>" alt="<?php esc_attr_e( 'Red wine drawn straight from a steel tank into a tasting glass during the KWV Technical Wines Tour', 'kwv' ); ?>" class="wp-image-183712" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"textTransform":"uppercase","fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h3 class="wp-block-heading alignwide has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'KWV Technical Wines Tour', 'kwv' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'The last Saturday of each month at 11:00. Limited to 20 guests — bookings essential. The winemaker shares cellar insights alongside a preferred tasting selection.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/cellar' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182954"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182954"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 -->

<!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183713,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-cathedral-cellar-tours.webp' ); ?>" alt="<?php esc_attr_e( 'Guests in safety vests taste wine among the steel tanks on a KWV cellar tour', 'kwv' ); ?>" class="wp-image-183713" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"textTransform":"uppercase","fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h3 class="wp-block-heading alignwide has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'Cathedral Cellar Tours', 'kwv' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'Approximately 45 minutes. Wheelchair accessible. No under 18s. Closed shoes required for safety. Times: Mon–Fri 10:00, 11:00 & 14:15; Sat 10:00; Sun & Public Holidays 10:00 (by reservation). Private tours from R600 plus a per-person rate. German or French tours available by reservation.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/cellar' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182954"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182954"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 --></div></div><div class="cb-button-prev swiper-button-prev"></div><div class="cb-button-next swiper-button-next"></div></div>
<!-- /wp:cb/carousel-v2 --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","metadata":{"name":"Visit — House of Fire"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section"><!-- wp:cb/carousel-v2 {"slidesPerView":1,"spaceBetween":40,"speed":500,"pagination":false,"resizeObserver":true,"align":"wide","className":"kwv-venue-carousel"} -->
<div class="wp-block-cb-carousel-v2 alignwide cb-carousel-block cb-single-slide kwv-venue-carousel" data-cb-slides-per-view="1" data-cb-slides-per-group="1" data-cb-space-between="40" data-cb-speed="500" data-cb-navigation="true" data-cb-resize-observer="true" data-cb-breakpoints="{&quot;768&quot;:{&quot;slidesPerView&quot;:1,&quot;slidesPerGroup&quot;:1}}"><div class="swiper"><div class="cb-wrapper swiper-wrapper"><!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"align":"wide","style":{"typography":{"textTransform":"uppercase"}},"fontFamily":"heading","anchor":"h-house-of-fire"} -->
<h2 class="wp-block-heading alignwide has-heading-font-family" id="h-house-of-fire" style="text-transform:uppercase"><?php esc_html_e( 'House of Fire', 'kwv' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'Step into the KWV House of Fire and experience the magic of masterful brandy-making. Discover the artistry behind the World’s Most Awarded Brandy, where time-honoured traditions meet generations of expertise. Book your tasting, create your own signature blend, bottle it and add a personal touch.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/fire' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182955"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182955"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183714,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-house-of-fire.webp' ); ?>" alt="<?php esc_attr_e( 'A KWV brandy master nosing a glass beside a barrel set with KWV aged brandies in the House of Fire', 'kwv' ); ?>" class="wp-image-183714" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 -->

<!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"textTransform":"uppercase","fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h3 class="wp-block-heading alignwide has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'House of Fire Tour', 'kwv' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'Tour the historic building where the first KWV brandy was distilled in 1926, including a brandy & chocolate pairing. Approximately 75 minutes. Times: Mon–Fri 10:30 & 14:30; Sat–Sun 10:30 (by reservation). Not wheelchair accessible; no under-18s.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/fire' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182955"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182955"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183715,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-house-of-fire-tour.webp' ); ?>" alt="<?php esc_attr_e( 'A hand cradling a KWV-branded snifter of brandy', 'kwv' ); ?>" class="wp-image-183715" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 -->

<!-- wp:cb/slide-v2 -->
<div class="wp-block-cb-slide-v2 cb-slide swiper-slide"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"metadata":{"name":"Section Header"},"align":"wide","className":"is-style-section-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide is-style-section-header"><!-- wp:heading {"level":3,"align":"wide","style":{"typography":{"textTransform":"uppercase","fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h3 class="wp-block-heading alignwide has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'Brandy Blending Experience', 'kwv' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"lineHeight":"var:custom|line-height|body"}},"fontSize":"200"} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'Includes the House of Fire tour and a brandy tasting. Blend, bottle and name your own brandy, with a charcuterie snack platter. Approximately 75 minutes. By reservation only.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline-dark is-style-cta"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( '/visit/fire' ); ?>"><?php esc_html_e( 'Book', 'kwv' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-outline-dark is-style-cta","openPopupId":"182955"} -->
<div class="wp-block-button is-style-outline-dark is-style-cta popmake-182955"><a class="wp-block-button__link wp-element-button"><?php esc_html_e( 'Menu', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"id":183716,"aspectRatio":"3/2","scale":"cover","sizeSlug":"full","linkDestination":"none","className":"is-style-default"} -->
<figure class="wp-block-image size-full is-style-default"><img src="<?php echo esc_url( 'https://kwv.lightspeedwp.dev/wp-content/uploads/2026/10/visit-house-of-fire-brandy-blending.webp' ); ?>" alt="<?php esc_attr_e( 'Copper pot stills and the brick-floored distillery hall of the KWV House of Fire', 'kwv' ); ?>" class="wp-image-183716" style="aspect-ratio:3/2;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:cb/slide-v2 --></div></div><div class="cb-button-prev swiper-button-prev"></div><div class="cb-button-next swiper-button-next"></div></div>
<!-- /wp:cb/carousel-v2 --></section>
<!-- /wp:group -->
