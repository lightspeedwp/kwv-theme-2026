<?php
/**
 * Title: Careers — Available Positions
 * Slug: kwv/careers-positions
 * Description: "Available positions" job board — a centred heading over a brand rule, a Title / Date / Details header row, then a query loop of Careers (kwv_career) posts rendered as the kwv/career-card (role linked to the job, its reference number, the posted date and an outlined "Click here" link).
 * Categories: kwv/pages
 * Keywords: careers, positions, jobs, vacancies, query, loop
 * Viewport Width: 1500
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Careers Available Positions"},"anchor":"positions","align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div id="positions" class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"bottom":"var:preset|spacing|0"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="padding-bottom:var(--wp--preset--spacing--0)"><!-- wp:heading {"textAlign":"center","level":2,"textColor":"contrast","fontSize":"400","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<h2 class="wp-block-heading has-text-align-center has-contrast-color has-text-color has-heading-font-family has-400-font-size" style="font-weight:var(--wp--custom--font-weight--bold);line-height:var(--wp--custom--line-height--heading);text-transform:uppercase">Available positions</h2>
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

<!-- wp:column {"verticalAlignment":"center","width":"15%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:15%"><!-- wp:paragraph {"textColor":"contrast","fontSize":"300","style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase"}},"fontFamily":"heading"} -->
<p class="has-contrast-color has-text-color has-heading-font-family has-300-font-size" style="font-weight:var(--wp--custom--font-weight--bold);text-transform:uppercase">Details</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:query {"queryId":40,"query":{"perPage":10,"pages":0,"offset":0,"postType":"kwv_career","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"metadata":{"name":"Available Positions"}} -->
<div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"default"}} -->
<?php
// The job "card" lives in its own pattern (kwv/career-card). It is inlined here
// rather than referenced with wp:pattern because a nested wp:pattern is not
// resolved inside another pattern on the front end — it silently drops.
require __DIR__ . '/career-card.php';
?>
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center","fontSize":"400","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} -->
<p class="has-text-align-center has-400-font-size" style="line-height:var(--wp--custom--line-height--body)">There are no positions available at present — please check back soon.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
