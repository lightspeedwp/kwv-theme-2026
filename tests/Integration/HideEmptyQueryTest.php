<?php
/**
 * Integration tests for inc/hide-empty-query.php.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @covers ::Kwv\HideEmptyQuery\hide_empty_query_group
 */
final class HideEmptyQueryTest extends TestCase {

	private const WITH_POSTS = '<div class="wp-block-group kwv-hide-if-empty"><h2>Upcoming</h2><ul class="wp-block-post-template"><li>Event</li></ul></div>';
	private const EMPTY      = '<div class="wp-block-group kwv-hide-if-empty"><h2>Upcoming</h2><p>No events</p></div>';

	private function group( string $html, string $class ): string {
		return apply_filters( 'render_block_core/group', $html, array( 'attrs' => array( 'className' => $class ) ) );
	}

	public function test_an_opted_in_group_with_posts_is_kept(): void {
		$this->assertSame( self::WITH_POSTS, $this->group( self::WITH_POSTS, 'kwv-hide-if-empty' ) );
	}

	public function test_an_opted_in_group_without_a_post_template_renders_nothing(): void {
		$this->assertSame( '', $this->group( self::EMPTY, 'kwv-hide-if-empty' ) );
	}

	public function test_the_class_can_sit_among_others(): void {
		$this->assertSame( '', $this->group( self::EMPTY, 'is-style-x  kwv-hide-if-empty foo' ) );
	}

	public function test_a_group_that_did_not_opt_in_is_kept_even_when_empty(): void {
		$this->assertSame( self::EMPTY, $this->group( self::EMPTY, 'something-else' ) );
	}

	public function test_a_similarly_named_class_does_not_opt_in(): void {
		$this->assertSame( self::EMPTY, $this->group( self::EMPTY, 'kwv-hide-if-empty-not' ) );
	}

	public function test_a_group_without_a_class_is_kept(): void {
		$this->assertSame( self::EMPTY, apply_filters( 'render_block_core/group', self::EMPTY, array( 'attrs' => array() ) ) );
	}

	public function test_it_works_through_do_blocks(): void {
		$markup = '<!-- wp:group {"className":"kwv-hide-if-empty"} --><div class="wp-block-group kwv-hide-if-empty"><!-- wp:paragraph --><p>No events</p><!-- /wp:paragraph --></div><!-- /wp:group -->';

		$this->assertSame( '', trim( $this->render( $markup ) ) );
	}
}
