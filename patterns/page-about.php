<?php
/**
 * Title: Page: About
 * Slug: kwv/page-about
 * Description: Full About page layout — hero, vision & mission, values grid and executive team. A page starter built from the kwv/about-* section patterns.
 * Categories: kwv/pages, kwv/about
 * Keywords: about, page, starter, team, values, mission
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1500
 * Inserter: true
 */

// Inline each About section pattern. WordPress does not resolve a `wp:pattern`
// reference nested inside another pattern's content, so the section files are
// included directly — each stays an independently-registered kwv/about-* pattern
// and remains the single source of truth.
require __DIR__ . '/about-hero.php';
require __DIR__ . '/about-vision-mission.php';
require __DIR__ . '/about-values.php';
require __DIR__ . '/about-executive-team.php';
