<?php
/**
 * Title: Page — Careers
 * Slug: kwv/page-careers
 * Description: Full Careers page layout — hero, intro (why join us / innovation), award-winning brand portfolio, an available-positions job board (kwv_career query loop) and team. A page starter built from the kwv/careers-* section patterns.
 * Categories: kwv/pages
 * Keywords: careers, page, starter, jobs, team, vacancies
 * Block Types: core/post-content
 * Post Types: page
 * Viewport Width: 1500
 * Inserter: true
 */

// Inline each Careers section pattern. WordPress does not resolve a `wp:pattern`
// reference nested inside another pattern's content, so the section files are
// included directly — each stays an independently-registered kwv/careers-*
// pattern and remains the single source of truth (same approach as page-faq.php).
require __DIR__ . '/careers-hero.php';
require __DIR__ . '/careers-intro.php';
require __DIR__ . '/careers-portfolio.php';
require __DIR__ . '/careers-positions.php';
require __DIR__ . '/careers-team.php';
