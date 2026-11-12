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
}
