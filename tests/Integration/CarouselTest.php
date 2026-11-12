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
}
