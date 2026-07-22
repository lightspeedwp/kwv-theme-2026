<?php
/**
 * Title: Template: FAQ
 * Slug: kwv/template-faq
 * Description: FAQ page template body — light header, full-width FAQ sections with no page title, dark footer. Used by the "Page — FAQ" template.
 * Categories: hidden
 * Keywords: template, faq, page, no title
 * Block Types: core/post-content
 * Template Types: page
 * Post Types: wp_template
 * Inserter: false
 */
?>

<!-- wp:group {"tagName":"main","align":"full","style":{"spacing":{"margin":{"top":"0"}}},"layout":{"type":"constrained"}} -->
<main class="wp-block-group alignfull" style="margin-top:0">
<?php
// Inline the FAQ section patterns directly so assigning this template renders
// the full FAQ page. Each section stays an independently-registered kwv/faq-*
// pattern and remains the single source of truth (same approach as page-faq.php).
require __DIR__ . '/faq-hero.php';
require __DIR__ . '/faq-intro-grid.php';
require __DIR__ . '/faq-team.php';
require __DIR__ . '/faq-wine-worlds.php';
require __DIR__ . '/faq-cognac-brandy.php';
require __DIR__ . '/faq-contact-cta.php';
?>
</main>
<!-- /wp:group -->

