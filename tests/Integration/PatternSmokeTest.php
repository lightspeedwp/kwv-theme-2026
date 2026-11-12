<?php
/**
 * Smoke test: every file in patterns/ registers, is well formed and renders.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @coversNothing
 */
final class PatternSmokeTest extends TestCase {

	/**
	 * Patterns that currently make WordPress raise a _doing_it_wrong notice when
	 * rendered. Each is a real finding, reported rather than fixed here.
	 *
	 * careers-hero.php (and the patterns that embed it) put the button label in
	 * the block comment as `{"text":"Scroll to content",...}` as well as in the
	 * markup. WordPress 7.1 validates that attribute against a schema whose type
	 * is "text" and warns on every render.
	 *
	 * @var array<string,string>
	 */
	private const KNOWN_BUGS = array();

	/**
	 * One case per pattern file.
	 *
	 * @return array<string,array{0:string}>
	 */
	public function patternFiles(): array {
		$cases = array();
		foreach ( glob( dirname( __DIR__, 2 ) . '/patterns/*.php' ) as $file ) {
			$cases[ basename( $file ) ] = array( $file );
		}

		return $cases;
	}

	/**
	 * @return array<string,string>
	 */
	private function headers( string $file ): array {
		return get_file_data(
			$file,
			array(
				'title'      => 'Title',
				'slug'       => 'Slug',
				'categories' => 'Categories',
			)
		);
	}

	public function test_there_are_patterns_to_test(): void {
		$this->assertNotEmpty( $this->patternFiles() );
	}

	/**
	 * @dataProvider patternFiles
	 */
	public function test_the_pattern_has_a_title_and_a_namespaced_slug( string $file ): void {
		$headers = $this->headers( $file );

		$this->assertNotSame( '', $headers['title'], 'Missing Title header.' );
		$this->assertNotSame( '', $headers['slug'], 'Missing Slug header.' );
		$this->assertStringStartsWith( 'kwv/', $headers['slug'], 'Slug must be namespaced kwv/.' );
		$this->assertMatchesRegularExpression( '#^kwv/[a-z0-9-]+$#', $headers['slug'] );
	}

	/**
	 * @dataProvider patternFiles
	 */
	public function test_the_pattern_is_registered_and_renders_without_notices( string $file ): void {
		$headers  = $this->headers( $file );
		$registry = \WP_Block_Patterns_Registry::get_instance();

		if ( isset( self::KNOWN_BUGS[ basename( $file ) ] ) ) {
			$this->markTestSkipped( 'BUG: ' . basename( $file ) . ': ' . self::KNOWN_BUGS[ basename( $file ) ] );
		}

		$this->assertTrue( $registry->is_registered( $headers['slug'] ), "{$headers['slug']} is not registered." );

		$pattern = $registry->get_registered( $headers['slug'] );
		$this->assertSame( $headers['title'], $pattern['title'] );
		$this->assertNotSame( '', trim( $pattern['content'] ), 'Pattern content is empty.' );
		$this->assertNotEmpty( parse_blocks( $pattern['content'] ) );

		// Notices, warnings and deprecations are turned into exceptions by PHPUnit.
		// Render inside a product page, with the post context a template would
		// provide, so WooCommerce's product blocks have something to read.
		$product = $this->makeProduct();
		$this->go_to( get_permalink( $product ) );
		$context = array(
			'postId'   => $product,
			'postType' => 'product',
		);
		$html    = '';
		foreach ( parse_blocks( $pattern['content'] ) as $block ) {
			$html .= ( new \WP_Block( $block, $context ) )->render();
		}

		$this->assertIsString( $html );
	}

	public function test_slugs_are_unique(): void {
		$seen = array();
		foreach ( $this->patternFiles() as $name => list( $file ) ) {
			$slug = $this->headers( $file )['slug'];
			$this->assertArrayNotHasKey( $slug, $seen, "{$slug} is declared by both {$name} and " . ( $seen[ $slug ] ?? '' ) );
			$seen[ $slug ] = $name;
		}
	}

	public function test_every_declared_category_is_registered(): void {
		$categories = array_column( \WP_Block_Pattern_Categories_Registry::get_instance()->get_all_registered(), 'name' );

		foreach ( $this->patternFiles() as $name => list( $file ) ) {
			$declared = array_filter( array_map( 'trim', explode( ',', $this->headers( $file )['categories'] ) ) );
			foreach ( $declared as $category ) {
				if ( 'hidden' === $category ) {
					continue; // Used by the template patterns; not shown in the inserter.
				}
				$this->assertContains( $category, $categories, "{$name} uses unregistered category {$category}." );
			}
		}
	}
}
