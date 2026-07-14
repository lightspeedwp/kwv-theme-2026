<?php
/**
 * Title: Page: Visit Us
 * Slug: kwv/page-visit
 * Description: Full Visit Us page layout — a full-bleed hero, the three tour venues (KWV Emporium, Cathedral Cellar, House of Fire) and an Upcoming Events strip. A page starter built from the kwv/visit-* section patterns.
 * Categories: kwv/pages, kwv/visit
 * Keywords: visit, page, starter, emporium, cathedral cellar, house of fire, events
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1500
 * Inserter: true
 */

// Inline each Visit section pattern. WordPress does not resolve a `wp:pattern`
// reference nested inside another pattern's content, so the section files are
// included directly — each stays an independently-registered kwv/visit-* pattern
// and remains the single source of truth (same approach as page-about.php).
require __DIR__ . '/visit-hero.php';
require __DIR__ . '/visit-venues.php';
require __DIR__ . '/visit-events.php';
