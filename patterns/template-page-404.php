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
 */
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"404"},"align":"full","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"640px"}} -->
<main class="wp-block-group alignfull" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)">

	<!-- wp:heading {"textAlign":"center","level":1,"fontFamily":"heading","fontSize":"800","textColor":"brand-500","style":{"typography":{"fontWeight":"var:custom|font-weight|black","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
	<h1 class="wp-block-heading has-text-align-center has-brand-500-color has-text-color has-heading-font-family has-800-font-size" style="margin-top:0;margin-bottom:0;font-weight:var(--wp--custom--font-weight--black);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">404</h1>
	<!-- /wp:heading -->

	<!-- wp:heading {"textAlign":"center","level":2,"fontFamily":"heading","fontSize":"500","textColor":"contrast","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"},"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
	<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color has-heading-font-family has-500-font-size" style="margin-top:0;margin-bottom:0;font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase"><?php esc_html_e( 'Page not found', 'kwv' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","textColor":"neutral-700","fontSize":"300","style":{"spacing":{"margin":{"top":"0","bottom":"0"}}}} -->
	<p class="has-text-align-center has-neutral-700-color has-text-color has-300-font-size" style="margin-top:0;margin-bottom:0"><?php esc_html_e( 'The page you are looking for could not be found. It may have moved, or the link may be broken. Try a search, or head back to the homepage.', 'kwv' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:search {"label":"<?php echo esc_attr_x( 'Search', 'search form label', 'kwv' ); ?>","showLabel":false,"placeholder":"<?php echo esc_attr_x( 'Search the site…', 'search form placeholder', 'kwv' ); ?>","buttonText":"<?php echo esc_attr_x( 'Search', 'search form button', 'kwv' ); ?>","buttonPosition":"button-inside","align":"center"} /-->

	<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
	<div class="wp-block-buttons">
		<!-- wp:button {"backgroundColor":"brand-500","textColor":"base","style":{"typography":{"fontWeight":"700","textTransform":"uppercase"}}} -->
		<div class="wp-block-button"><a class="wp-block-button__link has-base-color has-brand-500-background-color has-text-color has-background wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>" style="font-weight:700;text-transform:uppercase"><?php esc_html_e( 'Back to homepage', 'kwv' ); ?></a></div>
		<!-- /wp:button -->
	</div>
	<!-- /wp:buttons -->

</main>
<!-- /wp:group -->

