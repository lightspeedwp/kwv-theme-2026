<?php
/**
 * Title: Page: History
 * Slug: kwv/page-history
 * Description: Full History / About page layout — full-bleed heritage hero, a hover-reveal year timeline carousel and the KWV Archives grid. A page starter built from the kwv/history-* section patterns. Pair with a template that uses the transparent header so the hero sits under it.
 * Categories: kwv/pages, kwv/history
 * Keywords: history, about, page, starter, timeline, heritage, archives
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1500
 * Inserter: true
 */

// Inline each History section pattern. WordPress does not resolve a `wp:pattern`
// reference nested inside another pattern's content, so the section files are
// included directly — each stays an independently-registered kwv/history-*
// pattern and remains the single source of truth.
require __DIR__ . '/history-hero.php';
require __DIR__ . '/history-timeline.php';
require __DIR__ . '/history-archives.php';
