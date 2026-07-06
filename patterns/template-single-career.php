<?php
/**
 * Title: Template: Single Job
 * Slug: kwv/template-single-career
 * Description: Single Careers (kwv_career) template body — the Careers hero (with its own transparent header) over a two-column layout: the role, its reference number and the job description on the left, the shared Gravity Forms job application form on the right, then the dark footer.
 * Categories: hidden
 * Keywords: template, careers, job, single, application, form
 * Template Types: single
 * Post Types: wp_template
 * Inserter: false
 */

// The Careers hero embeds its own transparent header (like the home hero), so
// this template deliberately omits the `header` template part to avoid a double
// header — mirroring template-careers.php.
require __DIR__ . '/careers-hero.php';

// The shared job-application form is created by the kwv-careers plugin, which
// stores its Gravity Forms id in this option. Two hidden fields on the form
// resolve the job being applied for from the embed post
// ({embed_post:post_title} / {custom_field:kwv_career_reference}).
$kwv_careers_form_id = (string) (int) get_option( 'kwv_careers_form_id', 1 );
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Single Job"},"anchor":"content","align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|0","margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<main id="content" class="wp-block-group alignfull" style="margin-top:0">
<!-- wp:group {"metadata":{"name":"Job Detail"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section"><!-- wp:columns {"verticalAlignment":"top","align":"wide","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-top"><!-- wp:column {"verticalAlignment":"top","width":"55%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:55%"><!-- wp:group {"metadata":{"name":"Role + Reference"},"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":2,"isLink":false,"textColor":"brand-500","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading"} /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"textColor":"brand-500","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<p class="has-brand-500-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">Ref:</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_career_reference"}}},"name":"Reference"},"textColor":"brand-500","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<p class="has-brand-500-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">BMMS0128</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:post-content {"layout":{"type":"default"}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"top","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-top" style="flex-basis:45%"><!-- wp:group {"metadata":{"name":"Application Form"},"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":2,"textColor":"contrast","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<h2 class="wp-block-heading has-contrast-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">Apply for this position</h2>
<!-- /wp:heading -->

<!-- wp:gravityforms/form {"formId":"<?php echo esc_attr( $kwv_careers_form_id ); ?>","title":false,"description":false,"ajax":true} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
</main>
<!-- /wp:group -->

<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->
