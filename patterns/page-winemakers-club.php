<?php
/**
 * Title: Page: Winemaker’s Club
 * Slug: kwv/page-winemakers-club
 * Description: Full Winemaker’s Selection club page — hero, introduction, edition details, pricing bar, story, gallery and benefits. A page starter built from the kwv/winemakers-club-* section patterns.
 * Categories: kwv/pages, kwv/winemakers-club
 * Keywords: winemaker, wine club, page, starter, membership, subscription
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1500
 * Inserter: true
 */

// Inline each Winemaker's Club section pattern. WordPress does not resolve a
// `wp:pattern` reference nested inside another pattern's content, so the section
// files are included directly — each stays an independently-registered
// kwv/winemakers-club-* pattern and remains the single source of truth.
require __DIR__ . '/winemakers-club-hero.php';
require __DIR__ . '/winemakers-club-intro.php';
require __DIR__ . '/winemakers-club-edition.php';
require __DIR__ . '/winemakers-club-pricing.php';
require __DIR__ . '/winemakers-club-story.php';
require __DIR__ . '/winemakers-club-gallery.php';
require __DIR__ . '/winemakers-club-benefits.php';
