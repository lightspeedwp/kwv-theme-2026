<?php
/**
 * Mobile menu drawer.
 *
 * The off-canvas drawer is Ollie Menu Designer's `mobileMenuSlug` feature: it
 * injects the `mobile-menu` template part into the header navigation's overlay
 * (see `wp-content/plugins/ollie-menu-designer/includes/omd-mobile-menu-filter.php`).
 * Two things need correcting on top of that:
 *
 * 1. Submenus in the drawer are permanently expanded and their toggles inert,
 *    because core's `aria-expanded` binding reads the *overlay's* open state.
 *    `assets/js/mobile-menu.js` takes those toggles over — see its header for
 *    the full explanation.
 * 2. The plugin paints the hamburger **and** the drawer's close button with the
 *    same `mobileIconColor`. The hamburger sits over the page (white over the
 *    home hero), the close button sits inside the light drawer — so one colour
 *    cannot serve both, and on the home page the close button came out white on
 *    white. `unpin_close_icon_colour()` drops the close button from that rule so
 *    it falls back to `currentColor`, which `assets/styles/core-navigation.css`
 *    sets to `contrast`.
 *
 * @package kwv
 * @author  LightSpeed
 * @license GNU General Public License v2 or later
 */

namespace Kwv\MobileMenu;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const SCRIPT_HANDLE = 'kwv-mobile-menu';

/**
 * Remove the drawer's close button from Ollie's mobile-icon colour rule.
 *
 * Ollie Menu Designer prints, ahead of the navigation block:
 *
 *     #nav-12 .wp-block-navigation__responsive-container-open svg,
 *     #nav-12 .wp-block-navigation__responsive-container-close svg
 *         { fill: var(--wp--preset--color--base) !important; }
 *
 * An `#id` selector plus `!important` cannot be outranked from a stylesheet
 * (the id is generated per request, so we cannot even name it), so the fix is to
 * edit the rule rather than fight it: strip the close-button half and leave the
 * hamburger half exactly as authored. The close button then inherits
 * `currentColor` from core's own `…-close svg { fill: currentColor }`.
 *
 * Runs at priority 20 — after the plugin's own `render_block` filter at 10.
 *
 * @param string $block_content The block content.
 * @param array  $block         The parsed block.
 * @return string The block content with the close button unpinned.
 */
function unpin_close_icon_colour( $block_content, $block ) {

	if ( 'core/navigation' !== ( $block['blockName'] ?? '' ) ) {
		return $block_content;
	}

	if ( false === strpos( $block_content, 'wp-block-navigation__responsive-container-close svg' ) ) {
		return $block_content;
	}

	return preg_replace(
		'/,\s*#[A-Za-z0-9_-]+\s+\.wp-block-navigation__responsive-container-close svg(?=\s*\{)/',
		'',
		$block_content,
		1
	);
}
add_filter( 'render_block', __NAMESPACE__ . '\unpin_close_icon_colour', 20, 2 );

/**
 * Enqueue the drawer submenu script.
 *
 * Only loads when Ollie Menu Designer is doing the injection — without it there
 * is no drawer content for the script to enhance.
 */
function enqueue_assets() {

	if ( is_admin() || ! function_exists( 'MenuDesigner\MobileMenu\add_mobile_menu_to_navigation' ) ) {
		return;
	}

	wp_enqueue_script(
		SCRIPT_HANDLE,
		get_theme_file_uri( 'assets/js/mobile-menu.js' ),
		array(),
		\Kwv\asset_version( 'assets/js/mobile-menu.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets', 20 );
