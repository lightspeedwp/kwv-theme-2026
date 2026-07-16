<?php
/**
 * Title: Careers — Job Card
 * Slug: kwv/career-card
 * Description: A single job row for the Careers query loop — the role (linked to the job), its reference number (kwv_career_reference meta via block bindings), the posted date and an outlined "Click here" link. Carries the "Career Card" section style (medium-weight, non-capitalised, no-underline title). Designed to sit inside a Query Loop post-template over kwv_career.
 * Categories: kwv/pages
 * Keywords: careers, job, card, position, vacancy, query, loop
 * Block Types: core/post-template
 * Viewport Width: 1200
 * Inserter: true
 */
?>
<!-- wp:columns {"verticalAlignment":"center","className":"is-style-career-card","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"}},"border":{"bottom":{"color":"var:preset|color|neutral-100","width":"1px"},"top":{},"right":{},"left":{}}}} -->
<div class="wp-block-columns are-vertically-aligned-center is-style-career-card" style="border-bottom-color:var(--wp--preset--color--neutral-100);border-bottom-width:1px"><!-- wp:column {"verticalAlignment":"center","width":"50%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:post-title {"level":3,"isLink":true} /-->

<!-- wp:paragraph {"metadata":{"bindings":{"content":{"source":"core/post-meta","args":{"key":"kwv_career_reference"}}},"name":"Reference"},"className":"kwv-position-ref","fontSize":"200","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} -->
<p class="kwv-position-ref has-200-font-size" style="line-height:var(--wp--custom--line-height--body)">BMMS0128</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:post-date {"format":"j F Y","fontSize":"200","style":{"typography":{"textTransform":"uppercase","lineHeight":"var:custom|line-height|body"}}} /--></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"25%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:25%"><!-- wp:read-more {"content":"Click here"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
