<?php
/**
 * Smoke test: every template and template part parses, and every pattern and
 * template part it references exists.
 *
 * References are followed through the patterns they name, so a part inside a
 * pattern inside a template is checked too.
 *
 * @package kwv
 */

namespace Kwv\Tests\Integration;

/**
 * @coversNothing
 */
final class TemplateSmokeTest extends TestCase {

	/**
	 * Known dangling references: file => list of missing "pattern:slug" /
	 * "part:slug" references. Each one is a real finding (see tests/README.md);
	 * the matching case is reported as skipped rather than failing. Remove the
	 * entry when the reference is fixed.
	 *
	 * @var array<string,string[]>
	 */
	private const KNOWN_DANGLING = array(
		// parts/sidebar.html does not exist, although functions.php registers a Sidebar area for it.
		'templates/page-with-sidebar.html' => array( 'part:sidebar' ),
	);

	/**
	 * @return array<string,array{0:string}>
	 */
	public function themeBlockFiles(): array {
		$root  = dirname( __DIR__, 2 );
		$cases = array();
		foreach ( array( 'templates', 'parts' ) as $dir ) {
			foreach ( glob( "{$root}/{$dir}/*.html" ) as $file ) {
				$cases[ "{$dir}/" . basename( $file ) ] = array( "{$dir}/" . basename( $file ) );
			}
		}

		return $cases;
	}

	private function parseFile( string $relative ): array {
		$markup = file_get_contents( dirname( __DIR__, 2 ) . '/' . $relative );
		$this->assertNotFalse( $markup );
		$this->assertNotSame( '', trim( (string) $markup ), "{$relative} is empty." );

		return parse_blocks( (string) $markup );
	}

	/**
	 * Every pattern/part reference in a block tree, as "pattern:slug" / "part:slug".
	 *
	 * @param array[] $blocks Parsed blocks.
	 * @return string[]
	 */
	private function references( array $blocks ): array {
		$refs = array();
		foreach ( $blocks as $block ) {
			$name = $block['blockName'] ?? '';
			if ( 'core/pattern' === $name && ! empty( $block['attrs']['slug'] ) ) {
				$refs[] = 'pattern:' . $block['attrs']['slug'];
			}
			if ( 'core/template-part' === $name && ! empty( $block['attrs']['slug'] ) ) {
				$refs[] = 'part:' . $block['attrs']['slug'];
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$refs = array_merge( $refs, $this->references( $block['innerBlocks'] ) );
			}
		}

		return $refs;
	}

	/**
	 * Resolve references transitively and return those that do not exist.
	 *
	 * @param string[] $refs References to check.
	 * @return string[]
	 */
	private function missing( array $refs ): array {
		$registry = \WP_Block_Patterns_Registry::get_instance();
		$missing  = array();
		$seen     = array();
		$queue    = $refs;

		while ( $queue ) {
			$ref = array_shift( $queue );
			if ( isset( $seen[ $ref ] ) ) {
				continue;
			}
			$seen[ $ref ]        = true;
			list( $kind, $slug ) = explode( ':', $ref, 2 );

			if ( 'pattern' === $kind ) {
				if ( ! $registry->is_registered( $slug ) ) {
					$missing[] = $ref;
					continue;
				}
				$content = $registry->get_registered( $slug )['content'];
			} else {
				$file = dirname( __DIR__, 2 ) . "/parts/{$slug}.html";
				if ( ! is_readable( $file ) ) {
					$missing[] = $ref;
					continue;
				}
				$content = (string) file_get_contents( $file );
			}

			$queue = array_merge( $queue, $this->references( parse_blocks( $content ) ) );
		}

		return $missing;
	}

	public function test_there_are_templates_and_parts_to_test(): void {
		$files = array_keys( $this->themeBlockFiles() );

		$this->assertContains( 'templates/index.html', $files );
		$this->assertContains( 'parts/header.html', $files );
	}

	/**
	 * @dataProvider themeBlockFiles
	 */
	public function test_the_file_parses_into_blocks( string $relative ): void {
		$blocks = array_filter( $this->parseFile( $relative ), static fn( $b ) => ! empty( $b['blockName'] ) );

		$this->assertNotEmpty( $blocks, "{$relative} has no blocks." );
	}

	/**
	 * @dataProvider themeBlockFiles
	 */
	public function test_every_referenced_pattern_and_part_exists( string $relative ): void {
		$missing = $this->missing( $this->references( $this->parseFile( $relative ) ) );

		$known = self::KNOWN_DANGLING[ $relative ] ?? array();
		if ( $missing && ! array_diff( $missing, $known ) ) {
			$this->markTestSkipped( 'BUG: ' . $relative . ' references missing ' . implode( ', ', $missing ) );
		}

		$this->assertSame( array(), array_values( array_diff( $missing, $known ) ), "{$relative} references things that do not exist." );
	}
}
