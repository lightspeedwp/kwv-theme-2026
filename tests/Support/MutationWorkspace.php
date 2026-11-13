<?php
/**
 * Builds the copy of the theme that Infection mutates.
 *
 * Infection only mutates code inside class methods and closures that sit in a
 * class: its parser visitor stops at the first top-level `function`. The theme
 * is procedural (namespaced functions plus add_action() calls at load), so run
 * as-is Infection generates no mutants at all.
 *
 * This copies the theme to a workspace and rewrites every top-level function
 * `foo()` into a static method of a throwaway class, keeping a plain `foo()`
 * wrapper that forwards to it:
 *
 *     final class KwvMutation_foo { public static function foo( $a ) { ... } } function foo( $a ) { return KwvMutation_foo::foo( $a ); }
 *
 * The rewrite adds no new lines, so line numbers in Infection's reports match
 * the real source files. Callers, hooks and tests keep using `foo()` and see
 * no difference; Infection sees ordinary class methods and mutates them. Code
 * that runs at load time (add_action() and friends) is outside any function, so
 * it is not mutated; the hook-contract tests pin it instead.
 *
 * In the copied tests, `@covers` tags are removed so each test is credited with
 * every line it actually runs, including the methods above. Composer's
 * autoloader is shared with the real checkout (the vendor folder is symlinked),
 * so the copied bootstraps re-point the `Kwv\Tests\` namespace at the copied
 * tests; otherwise the base test classes would load from the real checkout and
 * run against the real, unmutated theme.
 *
 * @package kwv
 */

namespace Kwv\Tests\Support;

// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase -- PhpParser's AST properties are camelCase.

use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeFinder;
use PhpParser\ParserFactory;

/**
 * Copies the theme and rewrites its functions so Infection can mutate them.
 */
final class MutationWorkspace {

	/**
	 * Top-level names that are never copied.
	 */
	private const SKIP = array( '.git', '.github', '.DS_Store', '.phpcs-cache', 'build', 'node_modules', 'vendor' );

	/**
	 * Files whose functions are rewritten, relative to the theme root.
	 */
	private const SOURCE_DIRS = array( 'inc' );

	/**
	 * Absolute path to the theme.
	 *
	 * @var string
	 */
	private $root;

	/**
	 * Absolute path to the workspace.
	 *
	 * @var string
	 */
	private $target;

	/**
	 * @param string $root   Absolute path to the theme.
	 * @param string $target Absolute path to the workspace to (re)create.
	 */
	public function __construct( string $root, string $target ) {
		$this->root   = rtrim( $root, '/' );
		$this->target = rtrim( $target, '/' );
	}

	/**
	 * Rewrite every top-level function in a PHP file into a static method plus a wrapper.
	 *
	 * @param string $code PHP source.
	 * @return string PHP source with the same number of lines.
	 *
	 * @throws \RuntimeException When a function cannot be rewritten safely.
	 */
	public function transform( string $code ): string {
		$parser     = ( new ParserFactory() )->createForNewestSupportedVersion();
		$statements = $parser->parse( $code );
		$functions  = ( new NodeFinder() )->findInstanceOf( (array) $statements, Function_::class );

		// Rewrite from the end of the file so earlier offsets stay valid.
		usort(
			$functions,
			static function ( Function_ $a, Function_ $b ): int {
				return $b->getStartFilePos() <=> $a->getStartFilePos();
			}
		);

		foreach ( $functions as $function ) {
			$code = $this->rewrite( $code, $function );
		}

		return $code;
	}

	/**
	 * Rewrite one function declaration.
	 *
	 * @param string   $code     Source.
	 * @param Function_ $function Declaration.
	 * @return string
	 *
	 * @throws \RuntimeException For by-reference returns, which a wrapper cannot forward.
	 */
	private function rewrite( string $code, Function_ $function ): string {

		if ( $function->byRef ) {
			throw new \RuntimeException( 'Cannot rewrite a function that returns by reference: ' . $function->name->toString() );
		}

		$name  = $function->name->toString();
		$start = $function->getStartFilePos();
		$end   = $function->getEndFilePos();
		$class = 'KwvMutation_' . $name;

		$params = array();
		$args   = array();
		foreach ( $function->params as $param ) {
			$params[] = $this->slice( $code, $param );
			$args[]   = ( $param->variadic ? '...' : '' ) . '$' . $param->var->name;
		}

		$return_type = $function->returnType ? ': ' . $this->slice( $code, $function->returnType ) : '';
		$signature   = $name . '( ' . implode( ', ', $params ) . ' )' . $return_type;
		$call        = $class . '::' . $name . '( ' . implode( ', ', $args ) . ' );';
		$wrapper     = ' function ' . $signature . ' { ' . ( ': void' === $return_type ? '' : 'return ' ) . $call . ' }';

		$declaration = substr( $code, $start, $end - $start + 1 );

		return substr( $code, 0, $start )
			. 'final class ' . $class . ' { public static ' . $declaration . ' }' . $wrapper
			. substr( $code, $end + 1 );
	}

	/**
	 * The source text of a node.
	 *
	 * @param string $code Source.
	 * @param Node   $node Node.
	 * @return string
	 */
	private function slice( string $code, Node $node ): string {
		return substr( $code, $node->getStartFilePos(), $node->getEndFilePos() - $node->getStartFilePos() + 1 );
	}

	/**
	 * Drop `@covers` tags from test source, so coverage is credited per executed line.
	 *
	 * @param string $code Test source.
	 * @return string
	 */
	public function stripCovers( string $code ): string {
		return (string) preg_replace( '/^[ \t]*\*[ \t]*@covers[ \t]+\S.*\R/m', '', $code );
	}

	/**
	 * Make a bootstrap map `Kwv\Tests\` to the tests next to it.
	 *
	 * @param string $code Bootstrap source.
	 * @return string
	 *
	 * @throws \RuntimeException When the bootstrap does not load the autoloader the expected way.
	 */
	public function repointAutoloader( string $code ): string {

		$pattern = '#require_once ([^;]*/vendor/autoload\.php\');#';
		if ( 1 !== preg_match( $pattern, $code ) ) {
			throw new \RuntimeException( 'The bootstrap does not require vendor/autoload.php the expected way.' );
		}

		return (string) preg_replace_callback(
			$pattern,
			static function ( array $match ): string {
				return '$kwv_loader = require ' . $match[1] . "; \$kwv_loader->setPsr4( 'Kwv\\\\Tests\\\\', dirname( __DIR__ ) );";
			},
			$code,
			1
		);
	}

	/**
	 * Build the workspace: a fresh copy of the theme with its functions rewritten.
	 */
	public function build(): void {

		$this->remove( $this->target );
		mkdir( $this->target, 0777, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir

		$this->copy( $this->root, $this->target, '' );

		// Patchwork (Brain Monkey's engine) reads and rewrites every file it loads,
		// which bypasses Infection's include interceptor, so the mutated theme
		// code would never run. Keep it away from the files Infection mutates.
		file_put_contents( $this->target . '/patchwork.json', "{\n\t\"blacklist\": [ \"functions.php\", \"inc\" ]\n}\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions

		// Dependencies are shared, not copied.
		symlink( $this->root . '/vendor', $this->target . '/vendor' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_symlink
	}

	/**
	 * Copy a directory, rewriting functions in source files and covers in tests.
	 *
	 * @param string $from     Source directory.
	 * @param string $to       Destination directory.
	 * @param string $relative Path of $from relative to the theme root.
	 */
	private function copy( string $from, string $to, string $relative ): void {

		foreach ( scandir( $from ) as $entry ) {
			if ( '.' === $entry || '..' === $entry ) {
				continue;
			}

			$path = $relative . $entry;
			if ( '' === $relative && in_array( $entry, self::SKIP, true ) ) {
				continue;
			}
			if ( 'tests/e2e' === $path ) {
				continue;
			}

			if ( is_dir( $from . '/' . $entry ) ) {
				mkdir( $to . '/' . $entry, 0777, true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
				$this->copy( $from . '/' . $entry, $to . '/' . $entry, $path . '/' );
				continue;
			}

			$this->copyFile( $from . '/' . $entry, $to . '/' . $entry, $path );
		}
	}

	/**
	 * Copy one file, transforming it where needed.
	 *
	 * @param string $from Source file.
	 * @param string $to   Destination file.
	 * @param string $path Path relative to the theme root.
	 */
	private function copyFile( string $from, string $to, string $path ): void {

		$is_source = 'functions.php' === $path;
		foreach ( self::SOURCE_DIRS as $dir ) {
			$is_source = $is_source || ( 0 === strpos( $path, $dir . '/' ) && '.php' === substr( $path, -4 ) );
		}

		if ( $is_source ) {
			file_put_contents( $to, $this->transform( (string) file_get_contents( $from ) ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			return;
		}

		if ( 0 === strpos( $path, 'tests/' ) && '.php' === substr( $path, -4 ) ) {
			$code = $this->stripCovers( (string) file_get_contents( $from ) );
			if ( in_array( $path, array( 'tests/Unit/bootstrap.php', 'tests/Integration/bootstrap.php' ), true ) ) {
				$code = $this->repointAutoloader( $code );
			}
			file_put_contents( $to, $code ); // phpcs:ignore WordPress.WP.AlternativeFunctions
			return;
		}

		copy( $from, $to );
	}

	/**
	 * Delete a file or directory tree (a symlink is unlinked, never followed).
	 *
	 * @param string $path Path.
	 */
	private function remove( string $path ): void {

		if ( is_link( $path ) || is_file( $path ) ) {
			unlink( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.unlink_unlink
			return;
		}

		if ( ! is_dir( $path ) ) {
			return;
		}

		foreach ( scandir( $path ) as $entry ) {
			if ( '.' !== $entry && '..' !== $entry ) {
				$this->remove( $path . '/' . $entry );
			}
		}

		rmdir( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.rmdir_rmdir
	}
}
