<?php
/**
 * Integration tests for inc/mega-menu.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\MegaMenu as M;

/**
 * @covers ::Kwv\MegaMenu\register_assets
 * @covers ::Kwv\MegaMenu\enqueue_nav_scripts
 * @covers ::Kwv\MegaMenu\register_shortcode
 * @covers ::Kwv\MegaMenu\register_search_style
 * @covers ::Kwv\MegaMenu\get_children
 * @covers ::Kwv\MegaMenu\render
 */
final class MegaMenuTest extends TestCase {

	/**
	 * Create a product_cat term with an optional parent and display order.
	 */
	private function cat( string $name, string $slug, int $parent = 0, int $order = 0 ): int {
		$id = self::factory()->term->create(
			array(
				'taxonomy' => 'product_cat',
				'name'     => $name,
				'slug'     => $slug,
				'parent'   => $parent,
			)
		);
		update_term_meta( $id, 'order', $order );

		return $id;
	}

	public function set_up(): void {
		parent::set_up();
		$this->resetAssets();
	}

	public function test_the_shortcode_is_registered(): void {
		M\register_shortcode();

		$this->assertTrue( shortcode_exists( 'kwv_mega_menu' ) );

		// ...and it calls the renderer, not just any callable.
		global $shortcode_tags;
		$this->assertSame( 'Kwv\MegaMenu\render', $shortcode_tags['kwv_mega_menu'] );
	}

	public function test_the_search_block_style_is_registered(): void {
		M\register_search_style();

		$styles = \WP_Block_Styles_Registry::get_instance()->get_registered_styles_for_block( 'core/search' );
		$this->assertArrayHasKey( 'kwv-megamenu-search', $styles );
	}

	public function test_the_script_module_is_registered_and_the_stylesheet_follows_the_ollie_block(): void {
		M\register_assets();

		$this->assertNotNull( wp_script_modules()->get_registered( M\MODULE_ID ) );

		$this->applyRenderBlock( '', array( 'blockName' => 'ollie/mega-menu' ) );
		do_action( 'wp_enqueue_scripts' );
		$this->assertTrue( $this->isQueued( 'style', M\STYLE_HANDLE ) );
	}

	public function test_nav_scripts_load_only_for_the_native_nav_variant(): void {
		$html = '<div class="kwv-mega-menu-nav-wrap">menu</div>';

		$this->assertSame( $html, M\enqueue_nav_scripts( $html ) );
		$this->assertTrue( $this->isQueued( 'script', M\PANEL_HANDLE ) );
		$this->assertTrue( $this->isQueued( 'script', M\HOVER_HANDLE ) );
	}

	public function test_nav_scripts_do_not_load_for_other_dropdowns(): void {
		$html = '<div class="other">menu</div>';

		$this->assertSame( $html, M\enqueue_nav_scripts( $html ) );
		$this->assertFalse( $this->isQueued( 'script', M\PANEL_HANDLE ) );
		$this->assertFalse( $this->isQueued( 'script', M\HOVER_HANDLE ) );
	}

	public function test_children_are_ordered_by_display_order_then_name(): void {
		$parent = $this->cat( 'Parent', 'parent' );
		$this->cat( 'Zeta', 'zeta', $parent, 1 );
		$this->cat( 'Beta', 'beta', $parent, 2 );
		$this->cat( 'Alpha', 'alpha', $parent, 2 );

		$names = array_map( static fn( $t ) => $t->name, M\get_children( $parent ) );

		$this->assertSame( array( 'Zeta', 'Alpha', 'Beta' ), $names );
	}

	public function test_no_children_gives_an_empty_array(): void {
		$this->assertSame( array(), M\get_children( $this->cat( 'Lonely', 'lonely' ) ) );
	}

	public function test_render_is_empty_without_the_shop_root_categories(): void {
		$this->cat( 'Other', 'other-root' );

		$this->assertSame( '', M\render( array() ) );
	}

	public function test_render_outputs_three_columns_for_the_shop_roots(): void {
		$wines   = $this->cat( 'All Wines', 'all-wines', 0, 1 );
		$spirits = $this->cat( 'All Spirits', 'all-spirits', 0, 2 );
		$red     = $this->cat( 'Red', 'red', $wines, 1 );
		$this->cat( 'White', 'white', $wines, 2 );
		$this->cat( 'Shiraz', 'shiraz', $red );
		$this->cat( 'Brandy', 'brandy', $spirits );
		$this->cat( 'Uncategorized Root', 'uncat' );

		$html = do_shortcode( '[kwv_mega_menu]' );

		$this->assertStringContainsString( 'kwv-mega-menu__parents', $html );
		$this->assertStringContainsString( 'All Wines', $html );
		$this->assertStringContainsString( 'All Spirits', $html );
		$this->assertStringNotContainsString( 'Uncategorized Root', $html );
		$this->assertStringContainsString( 'Shiraz', $html, 'Grandchildren render.' );
		$this->assertStringContainsString( 'White', $html );

		// Default open parent is the first root; its default child is the first one with grandchildren.
		$this->assertStringContainsString( '{"activeParent":"all-wines","activeChild":"red"}', html_entity_decode( $html ) );
		$this->assertContains( M\MODULE_ID, wp_script_modules()->get_queue() );
	}

	public function test_the_root_attribute_picks_the_default_open_parent(): void {
		$wines   = $this->cat( 'All Wines', 'all-wines' );
		$spirits = $this->cat( 'All Spirits', 'all-spirits' );
		$this->cat( 'Red', 'red', $wines );
		$this->cat( 'Brandy', 'brandy', $spirits );

		$html = M\render( array( 'root' => 'all-spirits' ) );

		$this->assertMatchesRegularExpression( '/activeParent(&quot;|")?:(&quot;|")?all-spirits/', $html );
		$this->assertStringContainsString( 'brandy', $html );
	}

	public function test_a_parent_without_children_has_no_chevron_or_child_list(): void {
		$this->cat( 'All Wines', 'all-wines' );

		$html = M\render( array() );

		$this->assertStringNotContainsString( 'kwv-mega-menu__chevron', $html );
		$this->assertStringNotContainsString( 'kwv-mega-menu__list', $html );
	}

	public function test_a_root_with_no_children_leaves_a_gap_in_the_child_column_without_breaking_it(): void {
		$wines   = $this->cat( 'All Wines', 'all-wines', 0, 1 );
		$spirits = $this->cat( 'All Spirits', 'all-spirits', 0, 2 );
		$this->cat( 'Red', 'red', $wines );

		$html = M\render( array() );

		$this->assertStringContainsString( 'All Spirits', $html );
		$this->assertSame( 1, substr_count( $html, 'class="kwv-mega-menu__list"' ), 'Only the root that has children gets a child list.' );
		$this->assertSame( 1, substr_count( $html, 'kwv-mega-menu__chevron' ), 'Only the root with children has a chevron.' );
		$this->assertNotFalse( $spirits );
	}

	public function test_the_default_child_falls_back_to_the_first_child_when_none_has_grandchildren(): void {
		$wines = $this->cat( 'All Wines', 'all-wines' );
		$this->cat( 'Zinfandel', 'zinfandel', $wines, 2 );
		$this->cat( 'Cabernet', 'cabernet', $wines, 1 );

		$html = html_entity_decode( M\render( array() ) );

		$this->assertStringContainsString( '{"activeParent":"all-wines","activeChild":"cabernet"}', $html );
	}

	public function test_the_default_child_prefers_the_first_child_with_grandchildren_not_the_last(): void {
		$wines = $this->cat( 'All Wines', 'all-wines' );
		$this->cat( 'Aaa', 'aaa', $wines, 1 );
		$b = $this->cat( 'Bbb', 'bbb', $wines, 2 );
		$c = $this->cat( 'Ccc', 'ccc', $wines, 3 );
		$this->cat( 'Gb', 'gb', $b );
		$this->cat( 'Gc', 'gc', $c );

		$html = html_entity_decode( M\render( array() ) );

		$this->assertStringContainsString( '"activeChild":"bbb"', $html );
	}

	public function test_a_root_without_children_has_an_empty_default_child(): void {
		$this->cat( 'All Wines', 'all-wines' );

		$this->assertStringContainsString( '"activeChild":""', html_entity_decode( M\render( array() ) ) );
	}

	public function test_roots_are_ordered_by_display_order_so_the_lower_order_opens_first(): void {
		$this->cat( 'All Wines', 'all-wines', 0, 5 );
		$this->cat( 'All Spirits', 'all-spirits', 0, 1 );

		$html = html_entity_decode( M\render( array() ) );

		$this->assertStringContainsString( '"activeParent":"all-spirits"', $html );
		$this->assertLessThan( strpos( $html, 'All Wines' ), strpos( $html, 'All Spirits' ) );
	}

	public function test_an_unknown_root_attribute_falls_back_to_the_first_parent(): void {
		$this->cat( 'All Wines', 'all-wines', 0, 1 );
		$this->cat( 'All Spirits', 'all-spirits', 0, 2 );

		$html = html_entity_decode( M\render( array( 'root' => 'does-not-exist' ) ) );

		$this->assertStringContainsString( '"activeParent":"all-wines"', $html );
	}

	public function test_a_hostile_root_attribute_never_reaches_the_markup(): void {
		$this->cat( 'All Wines', 'all-wines' );

		$html = M\render( array( 'root' => '"><script>alert(1)</script>' ) );

		$this->assertStringNotContainsString( '<script', $html );
		$this->assertStringNotContainsString( 'alert(1)', $html );
	}

	public function test_hostile_term_names_and_slugs_are_escaped_in_every_column(): void {
		global $wpdb;
		$wines = $this->cat( 'All Wines', 'all-wines' );
		$child = $this->cat( 'Child', 'child-slug', $wines );
		$grand = $this->cat( 'Grand', 'grand-slug', $child );
		$evil  = '"><script>alert(1)</script>';
		foreach ( array( $wines, $child, $grand ) as $id ) {
			$wpdb->update( $wpdb->terms, array( 'name' => $evil ), array( 'term_id' => $id ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}
		$wpdb->update( $wpdb->terms, array( 'slug' => 'child"><b onmouseover=x>' ), array( 'term_id' => $child ) ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		clean_term_cache( array( $wines, $child, $grand ), 'product_cat' );

		$html = M\render( array() );

		$this->assertStringNotContainsString( '<script>', $html );
		$this->assertStringNotContainsString( '<b onmouseover', $html );
		$this->assertStringContainsString( '&lt;script&gt;', $html );
	}

	public function test_links_point_at_the_term_archives(): void {
		$wines = $this->cat( 'All Wines', 'all-wines' );
		$red   = $this->cat( 'Red', 'red', $wines );
		$this->cat( 'Shiraz', 'shiraz', $red );

		$html = M\render( array() );

		foreach ( array( $wines, $red, get_term_by( 'slug', 'shiraz', 'product_cat' )->term_id ) as $id ) {
			$this->assertStringContainsString( 'href="' . esc_url( get_term_link( $id, 'product_cat' ) ) . '"', $html );
		}
	}

	public function test_a_term_list_error_yields_no_children(): void {
		add_filter( 'get_terms', static fn() => new \WP_Error( 'boom' ) );

		$this->assertSame( array(), M\get_children( 0 ) );
		$this->assertSame( '', M\render( array() ) );
	}

	public function test_children_accepts_a_numeric_string_parent_and_only_returns_direct_children(): void {
		$parent = $this->cat( 'P', 'p' );
		$child  = $this->cat( 'C', 'c', $parent );
		$this->cat( 'G', 'g', $child );

		$names = array_map( static fn( $t ) => $t->name, M\get_children( (string) $parent ) );

		$this->assertSame( array( 'C' ), $names );
	}

	public function test_empty_categories_are_listed(): void {
		$wines = $this->cat( 'All Wines', 'all-wines' );
		$this->cat( 'No products here', 'empty-child', $wines );

		$this->assertStringContainsString( 'No products here', M\render( array() ) );
	}

	public function test_children_with_equal_order_sort_case_insensitively_by_name(): void {
		$parent = $this->cat( 'P', 'p' );
		$this->cat( 'banana', 'banana', $parent );
		$this->cat( 'Apple', 'apple', $parent );
		$this->cat( 'cherry', 'cherry', $parent );

		$names = array_map( static fn( $t ) => $t->name, M\get_children( $parent ) );

		$this->assertSame( array( 'Apple', 'banana', 'cherry' ), $names );
	}

	public function test_a_negative_display_order_sorts_before_zero(): void {
		$parent = $this->cat( 'P', 'p' );
		$this->cat( 'Zero', 'zero', $parent, 0 );
		$this->cat( 'Neg', 'neg', $parent, -3 );

		$names = array_map( static fn( $t ) => $t->name, M\get_children( $parent ) );

		$this->assertSame( array( 'Neg', 'Zero' ), $names );
	}

	public function test_the_menu_root_carries_the_interactivity_store_and_context(): void {
		$this->cat( 'All Wines', 'all-wines' );

		$html = M\render( array() );

		$this->assertStringContainsString( 'data-wp-interactive="kwv/megamenu"', $html );
		$this->assertStringContainsString( 'data-wp-context=', $html );
		$this->assertSame( 'kwv/megamenu', M\STORE );
	}

	public function test_nav_scripts_with_the_flag_at_the_start_still_load_and_are_deferred_in_the_footer(): void {
		$html = 'kwv-mega-menu-nav-wrap at the start';

		$this->assertSame( $html, M\enqueue_nav_scripts( $html ) );

		foreach ( array( M\PANEL_HANDLE, M\HOVER_HANDLE ) as $handle ) {
			$this->assertSame( 'defer', wp_scripts()->get_data( $handle, 'strategy' ), $handle );
			$this->assertSame( 1, wp_scripts()->get_data( $handle, 'group' ), $handle );
		}
	}

	public function test_nav_scripts_ignore_empty_and_null_markup_and_other_case(): void {
		$this->assertSame( '', M\enqueue_nav_scripts( '' ) );
		$this->assertNull( M\enqueue_nav_scripts( null ) );
		$this->assertSame( 'KWV-MEGA-MENU-NAV-WRAP', M\enqueue_nav_scripts( 'KWV-MEGA-MENU-NAV-WRAP' ) );
		$this->assertFalse( $this->isQueued( 'script', M\PANEL_HANDLE ) );
	}

	public function test_the_script_module_depends_on_the_interactivity_api(): void {
		M\register_assets();

		$this->assertTrue( $this->moduleDependsOnInteractivity( M\MODULE_ID ) );
	}

	/**
	 * Read the registered dependencies of a script module (the registry keeps them private).
	 */
	private function moduleDependsOnInteractivity( string $id ): bool {
		$registry = new \ReflectionProperty( wp_script_modules(), 'registered' );
		$registry->setAccessible( true );
		$module = $registry->getValue( wp_script_modules() )[ $id ] ?? array();

		foreach ( $module['dependencies'] ?? array() as $dependency ) {
			if ( '@wordpress/interactivity' === ( is_array( $dependency ) ? $dependency['id'] : $dependency ) ) {
				return true;
			}
		}

		return false;
	}

	public function test_the_search_style_is_registered_with_its_label(): void {
		M\register_search_style();

		$styles = \WP_Block_Styles_Registry::get_instance()->get_registered_styles_for_block( 'core/search' );

		$this->assertSame( 'KWV Search', $styles['kwv-megamenu-search']['label'] );
	}

	public function test_the_shortcode_renders_through_do_shortcode_with_a_root_attribute(): void {
		$wines   = $this->cat( 'All Wines', 'all-wines', 0, 1 );
		$spirits = $this->cat( 'All Spirits', 'all-spirits', 0, 2 );
		$this->cat( 'Red', 'red', $wines );
		$this->cat( 'Brandy', 'brandy', $spirits );

		$html = html_entity_decode( do_shortcode( '[kwv_mega_menu root="all-spirits"]' ) );

		$this->assertStringContainsString( '"activeParent":"all-spirits"', $html );
		$this->assertStringContainsString( '"activeChild":"brandy"', $html );
	}

	/**
	 * Every data-wp-context attribute in the markup, decoded, in document order.
	 *
	 * @param string $html Rendered menu.
	 * @return string[]
	 */
	private function contexts( string $html ): array {
		preg_match_all( '/data-wp-context="([^"]*)"/', $html, $matches );

		return array_map( 'html_entity_decode', $matches[1] );
	}

	public function test_every_interactivity_context_carries_the_exact_slugs(): void {
		$wines   = $this->cat( 'All Wines', 'all-wines', 0, 1 );
		$spirits = $this->cat( 'All Spirits', 'all-spirits', 0, 2 );
		$this->cat( 'Zeta', 'zeta', $wines, 1 );
		$red = $this->cat( 'Red', 'red', $wines, 2 );
		$this->cat( 'Shiraz', 'shiraz', $red );
		$this->cat( 'Brandy', 'brandy', $spirits, 1 );
		$this->cat( 'Whisky', 'whisky', $spirits, 2 );

		$contexts = $this->contexts( M\render( array() ) );

		$this->assertSame(
			array(
				// The menu root.
				'{"activeParent":"all-wines","activeChild":"red"}',
				// Column 1: each parent with the child it opens first.
				'{"parentSlug":"all-wines","firstChildSlug":"red"}',
				'{"parentSlug":"all-spirits","firstChildSlug":"brandy"}',
				// Column 2: one list per parent, each row a child.
				'{"parentSlug":"all-wines"}',
				'{"childSlug":"zeta"}',
				'{"childSlug":"red"}',
				'{"parentSlug":"all-spirits"}',
				'{"childSlug":"brandy"}',
				'{"childSlug":"whisky"}',
				// Column 3: the grandchildren of each child that has any.
				'{"parentSlug":"all-wines","childSlug":"red"}',
			),
			$contexts
		);
	}

	public function test_a_parent_without_children_opens_no_child(): void {
		$this->cat( 'All Wines', 'all-wines' );

		$this->assertSame(
			array(
				'{"activeParent":"all-wines","activeChild":""}',
				'{"parentSlug":"all-wines","firstChildSlug":""}',
			),
			$this->contexts( M\render( array() ) )
		);
	}

	public function test_the_first_child_without_grandchildren_is_the_fallback_for_a_parent_row(): void {
		$spirits = $this->cat( 'All Spirits', 'all-spirits' );
		$this->cat( 'Whisky', 'whisky', $spirits, 2 );
		$this->cat( 'Brandy', 'brandy', $spirits, 1 );

		$this->assertContains( '{"parentSlug":"all-spirits","firstChildSlug":"brandy"}', $this->contexts( M\render( array() ) ) );
	}

	public function test_a_parent_row_opens_its_first_child_with_grandchildren_not_the_last(): void {
		$spirits = $this->cat( 'All Spirits', 'all-spirits' );
		$this->cat( 'Aaa', 'aaa', $spirits, 1 );
		$b = $this->cat( 'Bbb', 'bbb', $spirits, 2 );
		$c = $this->cat( 'Ccc', 'ccc', $spirits, 3 );
		$this->cat( 'Gb', 'gb', $b );
		$this->cat( 'Gc', 'gc', $c );

		$this->assertContains( '{"parentSlug":"all-spirits","firstChildSlug":"bbb"}', $this->contexts( M\render( array() ) ) );
	}

	public function test_a_root_that_matches_the_attribute_wins_even_when_it_is_not_first(): void {
		$this->cat( 'All Wines', 'all-wines', 0, 1 );
		$this->cat( 'All Spirits', 'all-spirits', 0, 2 );

		$this->assertSame( '{"activeParent":"all-spirits","activeChild":""}', $this->contexts( M\render( array( 'root' => 'all-spirits' ) ) )[0] );
	}

	public function test_the_default_child_is_taken_from_the_default_parent_only(): void {
		$wines   = $this->cat( 'All Wines', 'all-wines', 0, 1 );
		$spirits = $this->cat( 'All Spirits', 'all-spirits', 0, 2 );
		$this->cat( 'Red', 'red', $wines );
		$this->cat( 'Brandy', 'brandy', $spirits );

		$this->assertSame( '{"activeParent":"all-spirits","activeChild":"brandy"}', $this->contexts( M\render( array( 'root' => 'all-spirits' ) ) )[0] );
		$this->assertSame( '{"activeParent":"all-wines","activeChild":"red"}', $this->contexts( M\render( array() ) )[0] );
	}
}
