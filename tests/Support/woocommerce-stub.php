<?php
/**
 * Stand-in for the WooCommerce main class, whose existence is what the theme
 * checks before registering its WooCommerce hooks.
 *
 * A real (user-defined) class is needed: aliasing an internal class such as
 * stdClass with class_alias() is a ValueError before PHP 8.3, and CI runs 8.2.
 *
 * Loaded only inside separate-process unit tests.
 *
 * @package kwv
 */

final class WooCommerce {}
