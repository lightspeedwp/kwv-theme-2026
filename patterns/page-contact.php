<?php
/**
 * Title: Page: Contact
 * Slug: kwv/page-contact
 * Description: Full Contact page layout — a centred "Contact Us" heading and form, the KWV contact-info directory and a newsletter sign-up. The form is left as an empty Gravity Forms block to be assigned and styled.
 * Categories: kwv/pages
 * Keywords: contact, page, starter, form, directory, newsletter
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1500
 * Inserter: true
 */
?>
<!-- wp:group {"metadata":{"name":"Contact Form"},"align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"560px"}} -->
<div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","lineHeight":"var:custom|line-height|heading","textTransform":"uppercase","letterSpacing":"1px"}},"fontSize":"500","fontFamily":"heading"} -->
<h1 class="wp-block-heading has-text-align-center has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);letter-spacing:1px;line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'Contact Us', 'kwv' ); ?></h1>
<!-- /wp:heading -->

<!-- wp:gravityforms/form {"formId":""} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<?php
// Inline the contact directory section. WordPress does not resolve a `wp:pattern`
// reference nested inside another pattern's content, so the section file is
// included directly — it stays the independently-registered kwv/contact-info
// pattern and remains the single source of truth (same approach as page-faq.php).
require __DIR__ . '/contact-info.php';
?>

<!-- wp:group {"metadata":{"name":"Newsletter"},"tagName":"section","align":"full","className":"is-style-light-page-section","layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull is-style-light-page-section"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group alignwide"><!-- wp:heading {"style":{"typography":{"fontWeight":"var:custom|font-weight|bold","textTransform":"uppercase","letterSpacing":"1px","lineHeight":"var:custom|line-height|heading"}},"fontSize":"500","fontFamily":"heading"} -->
<h2 class="wp-block-heading has-heading-font-family has-500-font-size" style="font-weight:var(--wp--custom--font-weight--bold);letter-spacing:1px;line-height:var(--wp--custom--line-height--heading);text-transform:uppercase"><?php esc_html_e( 'Subscribe to Our Newsletter', 'kwv' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:gravityforms/form {"formId":"2"} /--></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
