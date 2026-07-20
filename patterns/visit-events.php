<?php
/**
 * Title: Visit — Upcoming Events
 * Slug: kwv/visit-events
 * Description: An "Upcoming Events" strip with a gold accent rule and four event cards — image, title and a Learn More link — for the Visit Us page.
 * Categories: kwv/visit, kwv/card
 * Keywords: visit, events, upcoming, tours, cards, news
 * Viewport Width: 1500
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Visit — Upcoming Events"},"tagName":"section","align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|10"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:separator {"className":"kwv-rule-brand","textColor":"brand-500","style":{"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|10","left":"0"}}}} -->
<hr class="wp-block-separator has-text-color has-brand-500-color has-alpha-channel-opacity kwv-rule-brand" style="margin-top:0;margin-bottom:var(--wp--preset--spacing--10);margin-left:0"/>
<!-- /wp:separator -->

<!-- wp:heading {"level":2,"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase","letterSpacing":"1px"}},"textColor":"contrast","fontSize":"500","fontFamily":"heading"} -->
<h2 class="wp-block-heading has-contrast-color has-text-color has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);letter-spacing:1px;line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'Upcoming Events', 'kwv' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:query {"queryId":48,"query":{"perPage":4,"pages":0,"offset":0,"postType":"kwv_event","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"align":"wide","metadata":{"name":"Upcoming Events"}} -->
<div class="wp-block-query alignwide"><!-- wp:post-template {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|30"}}},"layout":{"type":"grid","columnCount":4}} -->
<?php
// The event "card" lives in its own pattern (kwv/event-card). It is inlined here
// rather than referenced with wp:pattern because a nested wp:pattern is not
// resolved inside another pattern on the front end — it silently drops
// (same approach as careers-positions.php / page-visit.php).
require __DIR__ . '/event-card.php';
?>
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"align":"center","fontSize":"300","style":{"typography":{"lineHeight":"var:custom|line-height|body"}}} -->
<p class="has-text-align-center has-300-font-size" style="line-height:var(--wp--custom--line-height--body)"><?php esc_html_e( 'There are no upcoming events at present — please check back soon.', 'kwv' ); ?></p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
