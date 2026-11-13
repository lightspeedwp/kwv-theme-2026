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

	private function group( ?string $html, string $class ): ?string {
		return apply_filters( 'render_block_core/group', $html, array( 'attrs' => array( 'className' => $class ) ) );
	}

	public function test_an_opted_in_group_with_null_markup_renders_nothing_and_raises_nothing(): void {
		// A null is coerced to a string before it reaches the HTML processor, which would otherwise complain.
		$this->assertSame( '', $this->withoutDeprecations( fn() => $this->group( null, 'kwv-hide-if-empty' ) ) );
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

	/**
	 * @return array<string,array{0:string}>
	 */
	public function separators(): array {
		return array(
			'tab'     => array( "kwv-hide-if-empty\tfoo" ),
			'newline' => array( "foo\nkwv-hide-if-empty" ),
			'many'    => array( 'a   b  kwv-hide-if-empty   c' ),
		);
	}

	/**
	 * @dataProvider separators
	 */
	public function test_any_whitespace_separates_the_opt_in_class( string $class ): void {
		$this->assertSame( '', $this->group( self::EMPTY, $class ) );
	}

	/**
	 * @return array<string,array{0:string}>
	 */
	public function nonOptInClasses(): array {
		return array(
			'upper case'      => array( 'KWV-HIDE-IF-EMPTY' ),
			'prefix'          => array( 'x-kwv-hide-if-empty' ),
			'suffix'          => array( 'kwv-hide-if-empty2' ),
			'empty'           => array( '' ),
			'whitespace only' => array( '   ' ),
		);
	}

	/**
	 * @dataProvider nonOptInClasses
	 */
	public function test_only_the_exact_class_token_opts_in( string $class ): void {
		$this->assertSame( self::EMPTY, $this->group( self::EMPTY, $class ) );
	}

	public function test_a_similar_post_template_class_does_not_count_as_a_query_result(): void {
		$html = '<div class="wp-block-group kwv-hide-if-empty"><ul class="wp-block-post-template-extra"><li>x</li></ul></div>';

		$this->assertSame( '', $this->group( $html, 'kwv-hide-if-empty' ) );
	}

	public function test_a_post_template_with_other_classes_counts_as_a_query_result(): void {
		$html = '<div class="wp-block-group kwv-hide-if-empty"><ul class="is-layout-grid wp-block-post-template columns-3"><li>x</li></ul></div>';

		$this->assertSame( $html, $this->group( $html, 'kwv-hide-if-empty' ) );
	}

	public function test_an_empty_opted_in_group_renders_nothing(): void {
		$this->assertSame( '', $this->group( '', 'kwv-hide-if-empty' ) );
	}

	public function test_an_opted_in_group_with_a_post_template_deep_inside_is_kept(): void {
		$html = '<div class="wp-block-group kwv-hide-if-empty"><div><div><ul class="wp-block-post-template"><li>x</li></ul></div></div></div>';

		$this->assertSame( $html, $this->group( $html, 'kwv-hide-if-empty' ) );
	}

	public function test_other_blocks_are_not_affected_by_the_group_filter(): void {
		$html = apply_filters( 'render_block_core/columns', self::EMPTY, array( 'attrs' => array( 'className' => 'kwv-hide-if-empty' ) ) );

		$this->assertSame( self::EMPTY, $html );
	}

	public function test_a_missing_attrs_key_keeps_the_group(): void {
		$this->assertSame( self::EMPTY, apply_filters( 'render_block_core/group', self::EMPTY, array() ) );
	}

	public function test_a_group_with_a_working_query_survives_do_blocks(): void {
		$markup = '<!-- wp:group {"className":"kwv-hide-if-empty"} --><div class="wp-block-group kwv-hide-if-empty"><!-- wp:html --><ul class="wp-block-post-template"><li>Event</li></ul><!-- /wp:html --></div><!-- /wp:group -->';

		$this->assertStringContainsString( 'wp-block-post-template', $this->render( $markup ) );
	}
}
