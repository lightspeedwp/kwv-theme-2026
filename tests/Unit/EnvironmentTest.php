<?php
/**
 * Unit tests for the theme's behaviour in environments the integration suite
 * cannot reproduce: an older WordPress without a function, the Ollie Menu
 * Designer plugin being present, the Swiper bundle being missing, and a file
 * being requested directly.
 *
 * Every test runs in its own process because each one needs a global function
 * or constant to be absent or present from the start.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Brain\Monkey\Functions;

/**
 * Environment-dependent branches of inc/*.php.
 */
final class EnvironmentTest extends TestCase {

	/**
	 * Theme files whose registering function must quietly do nothing when the
	 * WordPress function it wraps does not exist.
	 *
	 * @return array<string,array{0:string,1:string,2:string}>
	 */
	public function guardedRegistrations(): array {
		return array(
			'mini-cart block styles'    => array( 'inc/woocommerce.php', 'Kwv\register_mini_cart_block_styles', 'register_block_style' ),
			'product grid block style'  => array( 'inc/woocommerce.php', 'Kwv\enqueue_product_grid_block_style', 'wp_enqueue_block_style' ),
			'term banner binding'       => array( 'inc/term-banner.php', 'Kwv\register_term_banner_binding', 'register_block_bindings_source' ),
			'author role binding'       => array( 'inc/user-job-title.php', 'Kwv\register_author_role_binding', 'register_block_bindings_source' ),
			'search block styles'       => array( 'inc/search.php', 'Kwv\Search\register_block_styles', 'register_block_style' ),
			'gravity forms block style' => array( 'inc/gravity-forms.php', 'Kwv\GravityForms\enqueue_block_style', 'wp_enqueue_block_style' ),
		);
	}

	/**
	 * @dataProvider guardedRegistrations
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 *
	 * @param string $file     Theme file.
	 * @param string $callback Function under test.
	 * @param string $missing  WordPress function that is not defined.
	 */
	public function test_a_registration_is_skipped_when_the_wordpress_function_is_missing( string $file, string $callback, string $missing ): void {
		$this->assertFalse( function_exists( $missing ), 'Precondition: the WordPress function is absent.' );
		$this->loadThemeFile( $file );

		$this->assertNull( $callback() );
		$this->assertFalse( function_exists( $missing ), 'Nothing defined it as a side effect.' );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_the_drawer_script_loads_when_ollie_menu_designer_is_active(): void {
		require_once $this->themeDir() . '/tests/Support/ollie-menu-designer-stub.php';
		$this->loadThemeFile( 'inc/mobile-menu.php' );
		Functions\when( 'is_admin' )->justReturn( false );
		Functions\when( 'get_theme_file_uri' )->alias( static fn( $path ) => 'https://example.test/' . $path );
		Functions\when( 'Kwv\asset_version' )->justReturn( '1700000000' );
		Functions\expect( 'wp_enqueue_script' )->once()->with(
			'kwv-mobile-menu',
			'https://example.test/assets/js/mobile-menu.js',
			array(),
			'1700000000',
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);

		\Kwv\MobileMenu\enqueue_assets();
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_the_drawer_script_never_loads_in_the_admin_even_with_ollie_menu_designer(): void {
		require_once $this->themeDir() . '/tests/Support/ollie-menu-designer-stub.php';
		$this->loadThemeFile( 'inc/mobile-menu.php' );
		Functions\when( 'is_admin' )->justReturn( true );
		Functions\expect( 'wp_enqueue_script' )->never();

		\Kwv\MobileMenu\enqueue_assets();
	}

	/**
	 * Which of the two Swiper files exist, and whether the slider should load.
	 *
	 * @return array<string,array{0:bool,1:bool,2:bool}>
	 */
	public function swiperBundles(): array {
		return array(
			'neither file'    => array( false, false, false ),
			'script only'     => array( true, false, false ),
			'stylesheet only' => array( false, true, false ),
			'both files'      => array( true, true, true ),
		);
	}

	/**
	 * @dataProvider swiperBundles
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 *
	 * @param bool $has_js   Whether swiper-bundle.min.js exists.
	 * @param bool $has_css  Whether swiper-bundle.min.css exists.
	 * @param bool $expected Whether the slider is expected to load.
	 */
	public function test_the_brand_slider_needs_both_swiper_files( bool $has_js, bool $has_css, bool $expected ): void {
		$plugins = sys_get_temp_dir() . '/kwv-plugins-' . getmypid();
		$swiper  = $plugins . '/carousel-block/blocks/vendor/swiper';
		mkdir( $swiper, 0777, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
		if ( $has_js ) {
			file_put_contents( $swiper . '/swiper-bundle.min.js', '//' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		}
		if ( $has_css ) {
			file_put_contents( $swiper . '/swiper-bundle.min.css', '/**/' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		}
		define( 'WP_PLUGIN_DIR', $plugins );

		$this->loadThemeFile( 'inc/brand-page.php' );
		Functions\when( 'is_tax' )->justReturn( true );
		Functions\when( 'plugins_url' )->alias( static fn( $path ) => 'https://example.test/plugins/' . $path );
		Functions\when( 'get_theme_file_uri' )->alias( static fn( $path ) => 'https://example.test/theme/' . $path );
		Functions\when( 'Kwv\asset_version' )->justReturn( '42' );

		$styles  = array();
		$sources = array();
		Functions\when( 'wp_enqueue_style' )->alias(
			static function ( $handle, $src, $deps, $ver ) use ( &$styles, &$sources ) {
				$styles[ $handle ]             = $deps;
				$sources[ 'style:' . $handle ] = array( $src, $ver );
			}
		);
		$scripts = array();
		Functions\when( 'wp_enqueue_script' )->alias(
			static function ( $handle, $src, $deps, $ver, $args ) use ( &$scripts, &$sources ) {
				$scripts[ $handle ]             = $deps;
				$sources[ 'script:' . $handle ] = array( $src, $ver, $args );
			}
		);
		$localized = array();
		Functions\when( 'wp_localize_script' )->alias(
			static function ( $handle, $name, $data ) use ( &$localized ) {
				$localized[ $handle ] = array( $name, $data );
			}
		);

		$mtimes = array(
			'js'  => $has_js ? (string) filemtime( $swiper . '/swiper-bundle.min.js' ) : null,
			'css' => $has_css ? (string) filemtime( $swiper . '/swiper-bundle.min.css' ) : null,
		);

		\Kwv\BrandPage\enqueue_assets();

		// Clean up before asserting, so a failure leaves nothing behind.
		foreach ( array( '/swiper-bundle.min.js', '/swiper-bundle.min.css' ) as $file ) {
			if ( file_exists( $swiper . $file ) ) {
				unlink( $swiper . $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			}
		}
		rmdir( $swiper ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
		rmdir( $plugins . '/carousel-block/blocks/vendor' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
		rmdir( $plugins . '/carousel-block/blocks' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
		rmdir( $plugins . '/carousel-block' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir
		rmdir( $plugins ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir

		$this->assertArrayHasKey( 'kwv-brand-page', $styles, 'The page stylesheet always loads on a brand page.' );

		if ( $expected ) {
			$this->assertSame( array( 'theme-woocommerce-style', 'kwv-brand-slider-swiper' ), $styles['kwv-brand-page'] );

			// Exact URLs, cache-busting versions (the files' mtimes, as strings) and footer/defer loading.
			$swiper_url = 'https://example.test/plugins/carousel-block/blocks/vendor/swiper/swiper-bundle.min.';
			$deferred   = array(
				'in_footer' => true,
				'strategy'  => 'defer',
			);
			$this->assertSame( array( $swiper_url . 'css', $mtimes['css'] ), $sources['style:kwv-brand-slider-swiper'] );
			$this->assertSame( array( $swiper_url . 'js', $mtimes['js'], $deferred ), $sources['script:kwv-brand-slider-swiper'] );
			$this->assertSame( array( 'https://example.test/theme/assets/js/brand-slider.js', '42', $deferred ), $sources['script:kwv-brand-slider'] );
			$this->assertSame( array( 'https://example.test/theme/assets/styles/woocommerce-brand-page.css', '42' ), $sources['style:kwv-brand-page'] );
			$this->assertArrayHasKey( 'kwv-brand-slider-swiper', $styles );
			$this->assertSame( array(), $scripts['kwv-brand-slider-swiper'] );
			$this->assertSame( array( 'kwv-brand-slider-swiper' ), $scripts['kwv-brand-slider'] );
			$this->assertSame( 'kwvBrandSlider', $localized['kwv-brand-slider'][0] );
			$this->assertSame(
				array( 'previous', 'next', 'thumbnails', 'show', 'slide' ),
				array_keys( $localized['kwv-brand-slider'][1] )
			);
			$this->assertSame( 'Show %s', $localized['kwv-brand-slider'][1]['show'] );
			$this->assertSame( 'Product {{index}} of {{slidesLength}}', $localized['kwv-brand-slider'][1]['slide'] );
		} else {
			$this->assertSame( array( 'theme-woocommerce-style' ), $styles['kwv-brand-page'] );
			$this->assertArrayNotHasKey( 'kwv-brand-slider-swiper', $styles );
			$this->assertSame( array(), $scripts );
			$this->assertSame( array(), $localized );
		}
	}

	/**
	 * Theme files that must refuse to run outside WordPress.
	 *
	 * @return array<string,array{0:string}>
	 */
	public function guardedFiles(): array {
		$cases = array();
		foreach ( glob( dirname( __DIR__, 2 ) . '/inc/*.php' ) as $path ) {
			if ( false !== strpos( (string) file_get_contents( $path ), "if ( ! defined( 'ABSPATH' ) )" ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
				$cases[ basename( $path ) ] = array( $path );
			}
		}

		return $cases;
	}

	/**
	 * @dataProvider guardedFiles
	 *
	 * @param string $path Absolute path to an inc/ file.
	 */
	public function test_a_file_opened_directly_exits_before_running_anything( string $path ): void {
		$code   = 'require ' . var_export( $path, true ) . '; echo "RAN";'; // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
		$output = shell_exec( escapeshellarg( PHP_BINARY ) . ' -d display_errors=1 -r ' . escapeshellarg( $code ) . ' 2>&1' ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.system_calls_shell_exec

		$this->assertSame( '', trim( (string) $output ), basename( $path ) . ' must exit silently without ABSPATH.' );
	}

	public function test_the_guard_covers_a_meaningful_set_of_files(): void {
		$this->assertGreaterThanOrEqual( 6, count( $this->guardedFiles() ) );
	}
}
