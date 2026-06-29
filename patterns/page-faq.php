<?php
/**
 * Title: Page — FAQ
 * Slug: kwv/page-faq
 * Description: Full FAQ page layout — hero, intro questions, winemaking team, wine-world and cognac/brandy rows and a contact CTA. A page starter built from the kwv/faq-* section patterns.
 * Categories: kwv/pages
 * Keywords: faq, page, starter, questions, team, contact
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1500
 * Inserter: true
 */

// Inline each FAQ section pattern. WordPress does not resolve a `wp:pattern`
// reference nested inside another pattern's content, so the section files are
// included directly — each stays an independently-registered kwv/faq-* pattern
// and remains the single source of truth.
require __DIR__ . '/faq-hero.php';
require __DIR__ . '/faq-intro-grid.php';
require __DIR__ . '/faq-team.php';
require __DIR__ . '/faq-wine-worlds.php';
require __DIR__ . '/faq-cognac-brandy.php';
require __DIR__ . '/faq-contact-cta.php';
