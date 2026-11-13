<?php
/**
 * Integration tests for inc/carousel.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

use Kwv\Carousel as C;

/**
 * @covers ::Kwv\Carousel\register_assets
 * @covers ::Kwv\Carousel\enqueue_hero_script
 * @covers ::Kwv\Carousel\enqueue_marquee_script
 * @covers ::Kwv\Carousel\enqueue_timeline_script
 */
final class CarouselTest extends TestCase {

	public function set_up(): void {
		parent::set_up();
		$this->resetAssets();
	}

	/**
	 * Render a carousel block whose output is $html and return it.
	 */
	private function carousel( string $html ): string {
		return apply_filters( 'render_block_cb/carousel-v2', $html, array( 'blockName' => 'cb/carousel-v2' ) );
	}

	public function test_the_nav_arrow_stylesheet_loads_with_the_carousel_block(): void {
		$this->resetAssets();
		C\register_assets();
		$this->simulateBlockRender( 'cb/carousel-v2' );

		$this->assertTrue( $this->isQueued( 'style', C\STYLE_HANDLE ) );
	}

	public function test_the_nav_arrow_stylesheet_does_not_load_for_other_blocks(): void {
		$this->resetAssets();
		C\register_assets();
		$this->applyRenderBlock( '', array( 'blockName' => 'core/paragraph' ) );

		$this->assertFalse( $this->isQueued( 'style', C\STYLE_HANDLE ) );
	}

	public function test_null_markup_raises_no_deprecations_in_any_of_the_three_filters(): void {
		foreach ( array( 'enqueue_hero_script', 'enqueue_marquee_script', 'enqueue_timeline_script' ) as $function ) {
			$this->assertNull( $this->withoutDeprecations( static fn() => ( 'Kwv\\Carousel\\' . $function )( null ) ), $function );
		}
	}

	public function test_the_hero_script_loads_only_for_the_hero_slider(): void {
		$html = '<div class="cb-hero-slider">x</div>';

		$this->assertSame( $html, $this->carousel( $html ) );
		$this->assertTrue( $this->isQueued( 'script', C\SCRIPT_HANDLE ) );
		$this->assertFalse( $this->isQueued( 'script', C\MARQUEE_HANDLE ) );
		$this->assertFalse( $this->isQueued( 'script', C\TIMELINE_HANDLE ) );
	}

	public function test_the_marquee_script_loads_only_for_the_brand_rows(): void {
		$html = '<div class="kwv-brand-marquee">x</div>';

		$this->assertSame( $html, $this->carousel( $html ) );
		$this->assertTrue( $this->isQueued( 'script', C\MARQUEE_HANDLE ) );
		$this->assertContains( 'cb-slider-script', wp_scripts()->registered[ C\MARQUEE_HANDLE ]->deps );
		$this->assertFalse( $this->isQueued( 'script', C\SCRIPT_HANDLE ) );
	}

	public function test_the_timeline_script_loads_only_for_the_milestone_cards(): void {
		$html = '<div class="is-style-timeline-milestone">x</div>';

		$this->assertSame( $html, $this->carousel( $html ) );
		$this->assertTrue( $this->isQueued( 'script', C\TIMELINE_HANDLE ) );
		$this->assertFalse( $this->isQueued( 'script', C\SCRIPT_HANDLE ) );
	}

	public function test_a_plain_carousel_loads_no_scripts(): void {
		$html = '<div class="wp-block-cb-carousel-v2">x</div>';

		$this->assertSame( $html, $this->carousel( $html ) );
		foreach ( array( C\SCRIPT_HANDLE, C\MARQUEE_HANDLE, C\TIMELINE_HANDLE ) as $handle ) {
			$this->assertFalse( $this->isQueued( 'script', $handle ), $handle );
		}
	}

	/**
	 * @return array<string,array{0:string,1:string}> Flag => the script handle it loads.
	 */
	public function flags(): array {
		return array(
			'hero'     => array( 'cb-hero-slider', C\SCRIPT_HANDLE ),
			'marquee'  => array( 'kwv-brand-marquee', C\MARQUEE_HANDLE ),
			'timeline' => array( 'is-style-timeline-milestone', C\TIMELINE_HANDLE ),
		);
	}

	/**
	 * @dataProvider flags
	 */
	public function test_a_flag_at_the_very_start_of_the_markup_still_loads_its_script( string $flag, string $handle ): void {
		$html = $flag . ' rest of the markup';

		$this->assertSame( $html, $this->carousel( $html ) );
		$this->assertTrue( $this->isQueued( 'script', $handle ) );
	}

	/**
	 * @dataProvider flags
	 */
	public function test_empty_and_null_markup_load_nothing( string $flag, string $handle ): void {
		$this->assertSame( '', $this->carousel( '' ) );
		$this->assertNull( apply_filters( 'render_block_cb/carousel-v2', null, array( 'blockName' => 'cb/carousel-v2' ) ) );
		$this->assertFalse( $this->isQueued( 'script', $handle ) );
	}

	/**
	 * @dataProvider flags
	 */
	public function test_the_flag_match_is_case_sensitive( string $flag, string $handle ): void {
		$html = '<div class="' . strtoupper( $flag ) . '">x</div>';

		$this->assertSame( $html, $this->carousel( $html ) );
		$this->assertFalse( $this->isQueued( 'script', $handle ) );
	}

	public function test_a_carousel_carrying_every_flag_loads_every_script_once(): void {
		$html = '<div class="cb-hero-slider kwv-brand-marquee"><div class="is-style-timeline-milestone"></div></div>';

		$this->assertSame( $html, $this->carousel( $html ) );

		foreach ( array( C\SCRIPT_HANDLE, C\MARQUEE_HANDLE, C\TIMELINE_HANDLE ) as $handle ) {
			$this->assertTrue( $this->isQueued( 'script', $handle ), $handle );
		}
		$this->assertCount( 3, wp_scripts()->queue );
	}

	public function test_the_scripts_load_in_the_footer_with_file_based_versions(): void {
		$this->carousel( '<div class="cb-hero-slider kwv-brand-marquee is-style-timeline-milestone"></div>' );

		$expected = array(
			C\SCRIPT_HANDLE   => 'assets/js/hero-slider.js',
			C\MARQUEE_HANDLE  => 'assets/js/brand-marquee.js',
			C\TIMELINE_HANDLE => 'assets/js/timeline-milestone.js',
		);
		foreach ( $expected as $handle => $path ) {
			$script = wp_scripts()->registered[ $handle ];
			$this->assertSame( 1, $script->extra['group'], "{$handle} loads in the footer." );
			$this->assertSame( \Kwv\asset_version( $path ), $script->ver, $handle );
			$this->assertStringContainsString( $path, $script->src );
		}
	}

	public function test_the_stylesheet_is_the_carousel_block_sheet_with_a_file_version(): void {
		C\register_assets();
		$this->applyRenderBlock( '', array( 'blockName' => 'cb/carousel-v2' ) );
		do_action( 'wp_enqueue_scripts' );

		$style = wp_styles()->registered[ C\STYLE_HANDLE ];
		$this->assertStringContainsString( 'assets/styles/carousel-block.css', (string) wp_styles()->get_data( C\STYLE_HANDLE, 'path' ) );
		$this->assertSame( \Kwv\asset_version( 'assets/styles/carousel-block.css' ), $style->ver );
	}
}
