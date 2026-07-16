<?php
/**
 * Title: Careers — Team
 * Slug: kwv/careers-team
 * Description: "Why a career with KWV?" — a static row of team member cards (photo, name, role). Each card reveals a bio overlay on hover; replace the placeholder images and lorem-ipsum bios with real content.
 * Categories: kwv/card, kwv/pages
 * Keywords: careers, team, winemakers, people, staff, cards
 * Viewport Width: 1500
 * Inserter: true
 */

$kwv_careers_team = array(
	array( 'Justin Corrans', __( 'Chief Winemaker', 'kwv' ) ),
	array( 'Marco Ventrella', __( 'Chief Viticulturist', 'kwv' ) ),
	array( 'Pieter de Bod', __( 'Brandy Master', 'kwv' ) ),
	array( 'Izele van Blerk', __( 'Winemaker', 'kwv' ) ),
);

$kwv_careers_bio = __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua, striving for excellence as we grow our portfolio of leading brands.', 'kwv' );
?>
<!-- wp:group {"metadata":{"name":"Careers Team"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|60"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained","justifyContent":"center"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"bottom":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--0)"><!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color has-heading-font-family has-400-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">Why a career at KWV?</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:separator {"className":"kwv-rule-brand"} -->
<hr class="wp-block-separator kwv-rule-brand"/>
<!-- /wp:separator --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"align":"center","fontSize":"300","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} -->
<p class="has-text-align-center has-300-font-size" style="line-height:var(--wp--custom--line-height--body)">Don't take our word for it – let our employees tell you why they enjoy a career at KWV.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"grid","minimumColumnWidth":"16rem"}} -->
<div class="wp-block-group alignwide">
<?php foreach ( $kwv_careers_team as $kwv_member ) : ?>
<!-- wp:group {"metadata":{"name":"Team Member Card"},"className":"is-style-team-member-card","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"stretch"}} -->
<div class="wp-block-group is-style-team-member-card"><!-- wp:group {"metadata":{"name":"Media"},"className":"team-member-card__media","style":{"spacing":{"blockGap":"0","padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group team-member-card__media" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:image {"aspectRatio":"3/4","scale":"cover","sizeSlug":"full","linkDestination":"none"} /-->

<!-- wp:group {"metadata":{"name":"Overlay"},"className":"team-member-card__overlay","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|40"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
<div class="wp-block-group team-member-card__overlay" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--40)"><!-- wp:paragraph {"textColor":"base","fontSize":"300","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} -->
<p class="has-base-color has-text-color has-300-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php echo esc_html( $kwv_careers_bio ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
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
