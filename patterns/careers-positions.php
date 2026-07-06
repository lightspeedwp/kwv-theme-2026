<?php
/**
 * Title: Careers — Available Positions
 * Slug: kwv/careers-positions
 * Description: "Available positions" job board — a Title / Date / Details header row over a query loop of Careers (kwv_career) posts. Each row shows the role, its reference number (kwv_career_reference meta via block bindings), the posted date and an outlined "Click here" link to the single job.
 * Categories: kwv/pages
 * Keywords: careers, positions, jobs, vacancies, query, loop
 * Viewport Width: 1500
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Careers Available Positions"},"anchor":"positions","align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div id="positions" class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:separator {"className":"kwv-rule-brand"} -->
<hr class="wp-block-separator kwv-rule-brand"/>
<!-- /wp:separator -->

<!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color has-heading-font-family has-400-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">Available positions</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:paragraph {"textColor":"contrast","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<p class="has-contrast-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">Title</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:paragraph {"textColor":"contrast","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<p class="has-contrast-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">Date</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:paragraph {"textColor":"contrast","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<p class="has-contrast-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">Details</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:query {"queryId":40,"query":{"perPage":10,"pages":0,"offset":0,"postType":"kwv_career","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"metadata":{"name":"Available Positions"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->
<!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|neutral-100","width":"1px"},"top":{},"right":{},"left":{}}}} -->
<div class="wp-block-columns are-vertically-aligned-center" style="border-bottom-color:var(--wp--preset--color--neutral-100);border-bottom-width:1px"><!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":3,"isLink":false,"fontSize":"200","style":{"typography":{"fontWeight":"var:custom|font-weight|regular","lineHeight":"var:custom|line-height|body"}},"fontFamily":"body"} /-->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_career_reference"}}},"name":"Reference"},"className":"kwv-position-ref","fontSize":"200","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} -->
<p class="kwv-position-ref has-200-font-size" style="line-height:var(--wp--custom--line-height--body)">BMMS0128</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:post-date {"format":"j F Y","fontSize":"200","style":{"typography":{"textTransform":"uppercase","lineHeight":"var:custom|line-height|body"}}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:read-more {"content":"Click here","fontSize":"200","backgroundColor":"base","textColor":"contrast","style":{"border":{"color":"var:preset|color|contrast","width":"1px","radius":"0px"},"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase","textDecoration":"none"},"spacing":{"padding":{"top":"var:preset|spacing|20","right":"var:preset|spacing|50","bottom":"var:preset|spacing|20","left":"var:preset|spacing|50"}}},"fontFamily":"heading"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"fontSize":"200","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} -->
<p class="has-200-font-size" style="line-height:var(--wp--custom--line-height--body)">There are no positions available at present — please check back soon.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
