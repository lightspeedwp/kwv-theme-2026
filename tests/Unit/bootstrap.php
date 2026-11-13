<?php
/**
 * Unit-suite bootstrap: no WordPress, no database. WordPress functions are
 * replaced per test with Brain Monkey.
 *
 * @package kwv
 */

require_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';

// The theme files bail out when ABSPATH is missing; the value is never read.
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', sys_get_temp_dir() . '/kwv-unit-abspath/' );
}
