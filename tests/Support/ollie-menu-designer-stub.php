<?php
/**
 * Stand-in for the one Ollie Menu Designer function the theme probes for.
 *
 * Loaded only inside separate-process unit tests; never in the integration
 * suite, whose tests assert the plugin is absent.
 *
 * @package kwv
 */

namespace MenuDesigner\MobileMenu;

/**
 * The plugin's navigation filter. Its mere existence is what the theme checks.
 */
function add_mobile_menu_to_navigation() {
	return null;
}
