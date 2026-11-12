<?php
/**
 * Integration tests for inc/lazy-video.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @covers ::Kwv\LazyVideo\defer_cover_video
 */
final class LazyVideoTest extends TestCase {

	private const COVER = '<div class="wp-block-cover kwv-lazy-video"><video class="wp-block-cover__video-background intrinsic-ignore" autoplay muted loop playsinline src="https://example.test/legacy.mp4" poster="p.jpg"></video><div class="wp-block-cover__inner-container"></div></div>';

	public function set_up(): void {
		parent::set_up();
		$this->resetAssets();
	}

	private function cover( string $html, string $class = 'kwv-lazy-video' ): string {
		return apply_filters( 'render_block_core/cover', $html, array( 'attrs' => array( 'className' => $class ) ) );
	}

	public function test_an_opted_in_video_is_deferred(): void {
		$out = $this->cover( self::COVER );

		$this->assertStringContainsString( 'data-src="https://example.test/legacy.mp4"', $out );
		$this->assertStringNotContainsString( ' src="', $out );
		$this->assertStringNotContainsString( 'autoplay', $out );
		$this->assertStringContainsString( 'preload="none"', $out );
		$this->assertStringContainsString( 'aria-hidden="true"', $out );
		$this->assertStringContainsString( 'tabindex="-1"', $out );
		$this->assertStringContainsString( 'data-kwv-lazy-video', $out );
		$this->assertStringContainsString( 'poster="p.jpg"', $out, 'The poster is kept.' );
		$this->assertTrue( $this->isQueued( 'script', 'kwv-lazy-video' ) );
	}

	public function test_a_cover_that_did_not_opt_in_is_untouched(): void {
		$this->assertSame( self::COVER, $this->cover( self::COVER, 'other' ) );
		$this->assertFalse( $this->isQueued( 'script', 'kwv-lazy-video' ) );
	}

	public function test_an_opted_in_cover_without_a_video_is_untouched(): void {
		$html = '<div class="wp-block-cover kwv-lazy-video"><img class="wp-block-cover__image-background" src="a.jpg" alt=""></div>';

		$this->assertSame( $html, $this->cover( $html ) );
		$this->assertFalse( $this->isQueued( 'script', 'kwv-lazy-video' ) );
	}

	public function test_a_video_without_a_src_is_untouched(): void {
		$html = '<div class="wp-block-cover kwv-lazy-video"><video class="wp-block-cover__video-background"></video></div>';

		$this->assertSame( $html, $this->cover( $html ) );
		$this->assertFalse( $this->isQueued( 'script', 'kwv-lazy-video' ) );
	}

	public function test_a_block_with_no_class_attribute_is_untouched(): void {
		$this->assertSame( self::COVER, apply_filters( 'render_block_core/cover', self::COVER, array() ) );
	}
}
