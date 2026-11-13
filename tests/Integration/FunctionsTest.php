<?php
/**
 * Integration tests for the hooks in functions.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @covers ::Kwv\asset_version
 * @covers ::Kwv\setup
 * @covers ::Kwv\enqueue_style_sheet
 * @covers ::Kwv\register_block_styles
 * @covers ::Kwv\enqueue_custom_block_styles
 * @covers ::Kwv\pattern_categories
 * @covers ::Kwv\is_paginated
 */
final class FunctionsTest extends TestCase {

	public function test_every_inc_module_is_loaded(): void {
		foreach ( glob( $this->themeDir() . '/inc/*.php' ) as $file ) {
			$this->assertContains( realpath( $file ), array_map( 'realpath', get_included_files() ), basename( $file ) . ' is not loaded by functions.php.' );
		}
	}

	public function test_asset_version_changes_with_the_file(): void {
		$this->assertSame( (string) filemtime( $this->themeDir() . '/style.css' ), \Kwv\asset_version( 'style.css' ) );
	}

	public function test_asset_version_falls_back_to_the_theme_version_for_a_missing_file(): void {
		$this->assertSame( wp_get_theme()->get( 'Version' ), \Kwv\asset_version( 'assets/does-not-exist.css' ) );
	}

	public function test_the_theme_stylesheet_is_enqueued_with_a_file_based_version(): void {
		$this->runEnqueue();

		$this->assertTrue( $this->isQueued( 'style', 'kwv' ) );
		$this->assertSame( \Kwv\asset_version( 'style.css' ), wp_styles()->registered['kwv']->ver );
	}

	public function test_setup_removes_core_block_patterns_support(): void {
		add_theme_support( 'core-block-patterns' );

		\Kwv\setup();

		$this->assertFalse( current_theme_supports( 'core-block-patterns' ) );
	}

	public function test_custom_block_styles_are_registered(): void {
		\Kwv\register_block_styles();

		$registry = \WP_Block_Styles_Registry::get_instance();
		$this->assertArrayHasKey( 'list-check', $registry->get_registered_styles_for_block( 'core/list' ) );
		$this->assertArrayHasKey( 'background-blur', $registry->get_registered_styles_for_block( 'core/group' ) );
		$this->assertArrayHasKey( 'separator-thin', $registry->get_registered_styles_for_block( 'core/separator' ) );
	}

	public function test_each_core_block_stylesheet_loads_when_its_block_renders(): void {
		$files = glob( $this->themeDir() . '/assets/styles/core-*.css' );
		$this->assertNotEmpty( $files );

		$this->resetAssets();
		\Kwv\enqueue_custom_block_styles();

		foreach ( $files as $file ) {
			$name  = basename( $file, '.css' );
			$block = str_replace( 'core-', 'core/', $name );
			$this->simulateBlockRender( $block );
			$this->assertTrue( $this->isQueued( 'style', "kwv-block-{$name}" ), "{$name} did not load with {$block}." );
		}
	}

	public function test_pattern_categories_are_registered(): void {
		\Kwv\pattern_categories();

		$registry = \WP_Block_Pattern_Categories_Registry::get_instance();
		foreach ( array( 'kwv/card', 'kwv/hero', 'kwv/woocommerce', 'kwv/winemakers-club' ) as $name ) {
			$this->assertTrue( $registry->is_registered( $name ), $name );
		}
	}

	public function test_the_last_separator_is_hidden_when_nothing_paginates(): void {
		$this->go_to( home_url( '/' ) );
		$GLOBALS['wp_query']->max_num_pages = 1;

		ob_start();
		\Kwv\is_paginated();
		$this->assertStringContainsString( '<style>', ob_get_clean() );
	}

	public function test_no_separator_rule_is_printed_when_there_are_pages(): void {
		$this->go_to( home_url( '/' ) );
		$GLOBALS['wp_query']->max_num_pages = 3;

		ob_start();
		\Kwv\is_paginated();
		$this->assertSame( '', ob_get_clean() );
	}

	/**
	 * @return array<string,array{0:int,1:bool}> max_num_pages => whether the rule is printed.
	 */
	public function pageCounts(): array {
		return array(
			'no pages at all' => array( 0, true ),
			'one page'        => array( 1, true ),
			'two pages'       => array( 2, false ),
			'three pages'     => array( 3, false ),
			'many pages'      => array( 50, false ),
		);
	}

	/**
	 * @dataProvider pageCounts
	 */
	public function test_the_separator_rule_is_printed_only_below_two_pages( int $pages, bool $printed ): void {
		$this->go_to( home_url( '/' ) );
		$GLOBALS['wp_query']->max_num_pages = $pages;

		ob_start();
		\Kwv\is_paginated();
		$out = ob_get_clean();

		if ( $printed ) {
			$this->assertStringStartsWith( '<style>', $out );
			$this->assertStringEndsWith( '</style>', $out );
			$this->assertStringContainsString( '.search .wp-block-post-template .wp-block-post:last-child .wp-block-post-excerpt + .wp-block-separator', $out );
			$this->assertStringContainsString( 'display: none', $out );
		} else {
			$this->assertSame( '', $out );
		}
	}

	public function test_the_head_hook_prints_the_rule_on_the_front_page(): void {
		$this->go_to( home_url( '/' ) );
		$GLOBALS['wp_query']->max_num_pages = 1;

		ob_start();
		do_action( 'wp_head' );

		$this->assertStringContainsString( 'wp-block-separator', ob_get_clean() );
	}

	public function test_the_editor_style_is_registered_by_setup(): void {
		$GLOBALS['editor_styles'] = array(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		\Kwv\setup();

		$this->assertContains( 'style.css', $GLOBALS['editor_styles'] );
	}

	public function test_the_theme_stylesheet_url_is_the_themes_style_css(): void {
		$this->runEnqueue();

		$this->assertSame( get_template_directory_uri() . '/style.css', wp_styles()->registered['kwv']->src );
		$this->assertSame( array(), wp_styles()->registered['kwv']->deps );
	}

	/**
	 * @return array<string,array{0:string,1:string,2:string}>
	 */
	public function blockStyles(): array {
		return array(
			'list check'         => array( 'core/list', 'list-check', 'Check' ),
			'excerpt truncate'   => array( 'core/post-excerpt', 'excerpt-truncate-3', 'Truncate 3 Lines' ),
			'group blur'         => array( 'core/group', 'background-blur', 'Background Blur' ),
			'separator thin'     => array( 'core/separator', 'separator-thin', 'Thin' ),
			'categories chevron' => array( 'core/categories', 'categories-chevron', 'Chevron' ),
		);
	}

	/**
	 * @dataProvider blockStyles
	 */
	public function test_each_block_style_is_registered_with_its_label( string $block, string $name, string $label ): void {
		\Kwv\register_block_styles();

		$styles = \WP_Block_Styles_Registry::get_instance()->get_registered_styles_for_block( $block );

		$this->assertSame( $label, $styles[ $name ]['label'] );
	}

	/**
	 * @return array<string,array{0:string,1:string}>
	 */
	public function patternCategories(): array {
		return array(
			'card'            => array( 'kwv/card', 'Cards' ),
			'product card'    => array( 'kwv/product-card', 'Product Cards' ),
			'call to action'  => array( 'kwv/call-to-action', 'Call To Action' ),
			'features'        => array( 'kwv/features', 'Features' ),
			'hero'            => array( 'kwv/hero', 'Hero' ),
			'pages'           => array( 'kwv/pages', 'Pages' ),
			'posts'           => array( 'kwv/posts', 'Posts' ),
			'pricing'         => array( 'kwv/pricing', 'Pricing' ),
			'testimonial'     => array( 'kwv/testimonial', 'Testimonials' ),
			'menu'            => array( 'kwv/menu', 'Menu' ),
			'about'           => array( 'kwv/about', 'About Page' ),
			'history'         => array( 'kwv/history', 'History Page' ),
			'visit'           => array( 'kwv/visit', 'Visit Us Page' ),
			'winemakers club' => array( 'kwv/winemakers-club', 'Winemaker’s Club' ),
			'woocommerce'     => array( 'kwv/woocommerce', 'WooCommerce' ),
		);
	}

	/**
	 * @dataProvider patternCategories
	 */
	public function test_each_pattern_category_has_its_label( string $name, string $label ): void {
		\Kwv\pattern_categories();

		$registered = \WP_Block_Pattern_Categories_Registry::get_instance()->get_registered( $name );

		$this->assertSame( $label, $registered['label'] );
	}

	public function test_only_core_prefixed_sheets_are_attached_as_block_styles(): void {
		$this->resetAssets();
		\Kwv\enqueue_custom_block_styles();
		$this->applyRenderBlock( '', array( 'blockName' => 'core/button' ) );
		do_action( 'wp_enqueue_scripts' );

		$this->assertFalse( $this->isQueued( 'style', 'kwv-block-woocommerce' ), 'woocommerce.css is not a core block sheet.' );
		$this->assertFalse( $this->isQueued( 'style', 'kwv-block-carousel-block' ) );
		$this->assertFalse( $this->isQueued( 'style', 'kwv-block-core-image' ), 'A sheet loads only with its own block.' );
	}

	public function test_a_core_block_sheet_does_not_load_without_its_block(): void {
		$this->resetAssets();
		\Kwv\enqueue_custom_block_styles();
		$this->applyRenderBlock( '', array( 'blockName' => 'core/paragraph' ) );

		foreach ( glob( $this->themeDir() . '/assets/styles/core-*.css' ) as $file ) {
			if ( 'core-paragraph' !== basename( $file, '.css' ) ) {
				$this->assertFalse( $this->isQueued( 'style', 'kwv-block-' . basename( $file, '.css' ) ), basename( $file ) );
			}
		}
	}

	public function test_asset_version_is_always_a_string(): void {
		$this->assertIsString( \Kwv\asset_version( 'style.css' ) );
		$this->assertIsString( \Kwv\asset_version( 'assets/missing.css' ) );
		$this->assertNotSame( '', \Kwv\asset_version( 'assets/missing.css' ) );
	}

	public function test_asset_version_tracks_a_changing_file(): void {
		$path = $this->themeDir() . '/build/kwv-mtime-probe.css';
		file_put_contents( $path, 'a' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
		touch( $path, 1700000000 );
		clearstatcache();
		$first = \Kwv\asset_version( 'build/kwv-mtime-probe.css' );
		touch( $path, 1700000500 );
		clearstatcache();
		$second = \Kwv\asset_version( 'build/kwv-mtime-probe.css' );
		wp_delete_file( $path );

		$this->assertSame( '1700000000', $first );
		$this->assertSame( '1700000500', $second );
	}
}
