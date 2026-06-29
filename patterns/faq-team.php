<?php
/**
 * Title: FAQ — Team
 * Slug: kwv/faq-team
 * Description: "Who is the wine and brandy making team?" — a responsive grid of static team member cards (photo, name, role). Replace the placeholder images with real portraits.
 * Categories: kwv/card, kwv/pages
 * Keywords: faq, team, winemakers, people, staff, cards
 * Viewport Width: 1500
 * Inserter: true
 */

$kwv_faq_team = array(
	array( 'Justin Corrans', __( 'Chief Winemaker', 'kwv' ) ),
	array( 'Marco Ventrella', __( 'Chief Viticulturist', 'kwv' ) ),
	array( 'Pieter de Bod', __( 'Brandy Master', 'kwv' ) ),
	array( 'Izele van Blerk', __( 'Winemaker', 'kwv' ) ),
	array( 'Justin Corrans', __( 'Chief Winemaker', 'kwv' ) ),
	array( 'Marco Ventrella', __( 'Chief Viticulturist', 'kwv' ) ),
	array( 'Pieter de Bod', __( 'Brandy Master', 'kwv' ) ),
	array( 'Izele van Blerk', __( 'Winemaker', 'kwv' ) ),
);
?>
<!-- wp:group {"metadata":{"name":"FAQ Team"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color has-heading-font-family has-400-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">Who is the wine and brandy making team?</h2>
<!-- /wp:heading -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","minimumColumnWidth":"300px"}} -->
<div class="wp-block-group alignwide">
<?php foreach ( $kwv_faq_team as $kwv_member ) : ?>
<!-- wp:group {"metadata":{"name":"Team Card"},"className":"is-style-team-member-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group is-style-team-member-card"><!-- wp:group {"metadata":{"name":"Media"},"className":"team-member-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group team-member-card__media" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Caption"},"className":"team-member-card__footer","backgroundColor":"base","style":{"spacing":{"blockGap":"var:preset|spacing|5","padding":{"top":"var:preset|spacing|30","right":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group team-member-card__footer has-base-background-color has-background" style="padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:heading {"level":3,"textColor":"contrast","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<h3 class="wp-block-heading has-contrast-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php echo esc_html( $kwv_member[0] ); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"neutral-700","fontSize":"200","style":{"typography":{"lineHeight":"var:custom|line-height|snug"}}} -->
<p class="has-neutral-700-color has-text-color has-200-font-size" style="line-height:var(--wp--custom--line-height--snug)"><?php echo esc_html( $kwv_member[1] ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<?php endforeach; ?>
</div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
