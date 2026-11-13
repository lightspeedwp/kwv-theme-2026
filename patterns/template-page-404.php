<?php
/**
 * Title: Template: 404 Not Found
 * Slug: kwv/template-page-404
 * Description: 404 error page body — light header, a centred "page not found" message with a search form and a link back home, then the dark footer. Used by the 404 template.
 * Categories: hidden
 * Keywords: template, 404, error, not found
 * Template Types: 404
 * Post Types: wp_template
 * Inserter: false
 * Viewport Width: 1500
 *
 * @package kwv
 */

?>

<!-- wp:group {"tagName":"main","metadata":{"name":"404"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained","contentSize":"640px"}} -->
<main class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|5"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"spacing":{"margin":{"top":"0","bottom":"0"}},"typography":{"textAlign":"center"}},"textColor":"brand-600","fontSize":"900"} -->
<h1 class="wp-block-heading has-text-align-center has-brand-600-color has-text-color has-900-font-size" style="margin-top:0;margin-bottom:0">404</h1>
<!-- /wp:heading -->

<!-- wp:heading {"style":{"typography":{"textAlign":"center"}},"textColor":"contrast"} -->
<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color"><?php esc_html_e( 'Page not found', 'kwv' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"has-neutral-700-color has-text-color","style":{"typography":{"textAlign":"center"}},"textColor":"neutral-700","fontSize":"300"} -->
<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size"><?php esc_html_e( 'The page you are looking for could not be found. It may have moved, or the link may be broken. Try a search, or head back to the homepage.', 'kwv' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:search {"label":"<?php echo esc_attr_x( 'Search', 'search form label', 'kwv' ); ?>","showLabel":false,"placeholder":"<?php echo esc_attr_x( 'Search the site…', 'search form placeholder', 'kwv' ); ?>","buttonText":"<?php echo esc_attr_x( 'Search', 'search form button', 'kwv' ); ?>","buttonPosition":"button-inside","buttonUseIcon":true,"align":"center","className":"is-style-kwv-megamenu-search","fontSize":"200"} /--></div>
<!-- /wp:group -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-cta"} -->
<div class="wp-block-button is-style-cta"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Homepage', 'kwv' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></main>
<!-- /wp:group -->
