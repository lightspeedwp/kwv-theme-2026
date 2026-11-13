<?php
/**
 * Unit tests for the rewrite that lets Infection mutate the procedural theme.
 *
 * @package kwv
 */

namespace Kwv\Tests\Unit;

use Kwv\Tests\Support\MutationWorkspace;

/**
 * @covers \Kwv\Tests\Support\MutationWorkspace
 */
final class MutationWorkspaceTest extends TestCase {

	/**
	 * Files written by a test, removed afterwards.
	 *
	 * @var string[]
	 */
	private $files = array();

	protected function tear_down(): void {
		foreach ( $this->files as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
		parent::tear_down();
	}

	private function workspace(): MutationWorkspace {
		return new MutationWorkspace( $this->themeDir(), sys_get_temp_dir() . '/kwv-unused-workspace' );
	}

	/**
	 * Write PHP to a file and load it, so the functions it declares exist.
	 *
	 * @param string $code PHP source.
	 */
	private function load( string $code ): void {
		$file          = tempnam( sys_get_temp_dir(), 'kwvmut' );
		$this->files[] = $file;
		file_put_contents( $file, $code );
		require $file;
	}

	public function test_the_rewrite_keeps_every_line_where_it_was(): void {
		$code = "<?php\nnamespace Kwv\\MutA;\n\n/**\n * Doc.\n */\nfunction one( \$a ) {\n\treturn \$a + 1;\n}\nadd_action( 'init', 'x' );\nfunction two() {\n\treturn 2;\n}\n";

		$out = $this->workspace()->transform( $code );

		$this->assertSame( substr_count( $code, "\n" ), substr_count( $out, "\n" ) );
		$this->assertStringContainsString( 'final class KwvMutation_one { public static function one( $a ) {', $out );
		$this->assertStringContainsString( "add_action( 'init', 'x' );", $out );
	}

	public function test_a_rewritten_function_behaves_like_the_original(): void {
		$code = "<?php\nnamespace Kwv\\MutB;\n\nfunction add( \$a, \$b = 10, ...\$rest ) {\n\treturn \$a + \$b + array_sum( \$rest );\n}\nfunction bump( &\$n ) {\n\t\$n++;\n}\nfunction typed( int \$n ): string {\n\treturn (string) ( \$n * 2 );\n}\nfunction nothing(): void {\n\t\$GLOBALS['kwv_mut_ran'] = true;\n}\n";

		$this->load( $this->workspace()->transform( $code ) );

		$this->assertSame( 13, \Kwv\MutB\add( 1, 2, 10 ) );
		$this->assertSame( 11, \Kwv\MutB\add( 1 ) );
		$n = 1;
		\Kwv\MutB\bump( $n );
		$this->assertSame( 2, $n, 'By-reference arguments survive the wrapper.' );
		$this->assertSame( '8', \Kwv\MutB\typed( 4 ) );
		\Kwv\MutB\nothing();
		$this->assertTrue( $GLOBALS['kwv_mut_ran'] );
		unset( $GLOBALS['kwv_mut_ran'] );
	}

	public function test_the_body_becomes_a_class_method_that_infection_can_reach(): void {
		$this->load( $this->workspace()->transform( "<?php\nnamespace Kwv\\MutC;\nfunction answer() {\n\treturn 42;\n}\n" ) );

		$method = new \ReflectionMethod( '\Kwv\MutC\KwvMutation_answer', 'answer' );

		$this->assertTrue( $method->isStatic() );
		$this->assertSame( 42, $method->invoke( null ) );
	}

	public function test_a_function_that_returns_by_reference_is_refused(): void {
		$this->expectException( \RuntimeException::class );
		$this->expectExceptionMessage( 'returns by reference: broken' );

		$this->workspace()->transform( "<?php\nfunction &broken() {\n\t\$x = 1;\n\treturn \$x;\n}\n" );
	}

	public function test_covers_tags_are_removed_but_coversnothing_is_kept(): void {
		$code = "/**\n * Docs.\n *\n * @covers ::Kwv\\one\n * @covers \\Kwv\\Two\n * @coversNothing\n */\nclass X {}\n";

		$out = $this->workspace()->stripCovers( $code );

		$this->assertStringNotContainsString( '@covers ', $out );
		$this->assertStringContainsString( '@coversNothing', $out );
		$this->assertStringContainsString( 'Docs.', $out );
	}

	public function test_the_bootstraps_are_pointed_at_the_copied_tests(): void {
		$workspace = $this->workspace();

		$unit        = $workspace->repointAutoloader( "<?php\nrequire_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';\n" );
		$integration = $workspace->repointAutoloader( "<?php\nrequire_once \$kwv_root . '/vendor/autoload.php';\n" );

		$this->assertStringContainsString( "\$kwv_loader = require dirname( __DIR__, 2 ) . '/vendor/autoload.php'; \$kwv_loader->setPsr4( 'Kwv\\\\Tests\\\\', dirname( __DIR__ ) );", $unit );
		$this->assertStringContainsString( "\$kwv_loader = require \$kwv_root . '/vendor/autoload.php';", $integration );
	}

	public function test_a_bootstrap_without_the_autoloader_is_refused(): void {
		$this->expectException( \RuntimeException::class );

		$this->workspace()->repointAutoloader( "<?php\necho 'no autoloader';\n" );
	}

	public function test_the_workspace_is_built_from_a_fixture_theme(): void {
		$base   = sys_get_temp_dir() . '/kwv-mut-fixture-' . getmypid();
		$theme  = $base . '/theme';
		$target = $base . '/workspace';
		mkdir( $theme . '/inc', 0777, true );
		mkdir( $theme . '/tests/Unit', 0777, true );
		mkdir( $theme . '/tests/e2e', 0777, true );
		mkdir( $theme . '/vendor', 0777, true );
		mkdir( $theme . '/build', 0777, true );
		file_put_contents( $theme . '/functions.php', "<?php\nfunction f() {\n\treturn 1;\n}\n" );
		file_put_contents( $theme . '/inc/a.php', "<?php\nfunction a() {\n\treturn 1;\n}\n" );
		file_put_contents( $theme . '/style.css', '/* theme */' );
		file_put_contents( $theme . '/tests/Unit/ATest.php', "<?php\n/**\n * @covers ::a\n */\nclass ATest {}\n" );
		file_put_contents( $theme . '/tests/Unit/bootstrap.php', "<?php\nrequire_once dirname( __DIR__, 2 ) . '/vendor/autoload.php';\n" );
		file_put_contents( $theme . '/tests/e2e/skip.php', '<?php' );
		file_put_contents( $theme . '/build/skip.txt', 'x' );

		( new MutationWorkspace( $theme, $target ) )->build();

		$this->assertStringContainsString( 'KwvMutation_f', file_get_contents( $target . '/functions.php' ) );
		$this->assertStringContainsString( 'KwvMutation_a', file_get_contents( $target . '/inc/a.php' ) );
		$this->assertSame( '/* theme */', file_get_contents( $target . '/style.css' ) );
		$this->assertStringNotContainsString( '@covers', file_get_contents( $target . '/tests/Unit/ATest.php' ) );
		$this->assertStringContainsString( 'setPsr4', file_get_contents( $target . '/tests/Unit/bootstrap.php' ) );
		$this->assertFileDoesNotExist( $target . '/tests/e2e/skip.php' );
		$this->assertFileDoesNotExist( $target . '/build/skip.txt' );
		$this->assertTrue( is_link( $target . '/vendor' ) );

		// Building again replaces the workspace and never follows the vendor symlink.
		( new MutationWorkspace( $theme, $target ) )->build();
		$this->assertDirectoryExists( $theme . '/vendor' );

		( new MutationWorkspace( $theme, $target ) )->build();
		$this->removeTree( $base );
	}

	private function removeTree( string $path ): void {
		if ( is_link( $path ) || is_file( $path ) ) {
			unlink( $path );
			return;
		}
		foreach ( array_diff( scandir( $path ), array( '.', '..' ) ) as $entry ) {
			$this->removeTree( $path . '/' . $entry );
		}
		rmdir( $path );
	}
}
