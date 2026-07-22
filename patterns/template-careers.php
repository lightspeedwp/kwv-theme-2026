<?php
/**
 * Title: Template: Careers
 * Slug: kwv/template-careers
 * Description: Careers page template body — the Careers hero (which carries its own transparent header) over full-width sections and a dark footer, with no separate site header or page title. Used by the "Careers" page template.
 * Categories: hidden
 * Keywords: template, careers, page, no title
 * Block Types: core/post-content
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 */

// The Careers hero embeds its own transparent header (like the home hero), so
// this template deliberately omits the `header` template part to avoid a double
// header — mirroring front-page.html. Each section stays an independently-
// registered kwv/careers-* pattern and remains the single source of truth.
require __DIR__ . '/careers-hero.php';
?>

<!-- wp:group {"tagName":"main","metadata":{"name":"Careers"},"anchor":"content","align":"full","style":{"spacing":{"blockGap":"var:preset|spacing|0","margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<main id="content" class="wp-block-group alignfull" style="margin-top:0">
<?php
require __DIR__ . '/careers-intro.php';
require __DIR__ . '/careers-portfolio.php';
require __DIR__ . '/careers-positions.php';
require __DIR__ . '/careers-team.php';
?>
</main>
<!-- /wp:group -->

