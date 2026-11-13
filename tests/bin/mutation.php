<?php
/**
 * Run Infection against the theme.
 *
 * Infection cannot mutate procedural code (see tests/Support/MutationWorkspace.php),
 * so this first builds build/mutation-workspace/ (a copy of the theme with every
 * function rewritten into a static method) and then runs Infection inside it.
 * For the integration suite it also creates and installs the per-thread
 * databases (tests/bin/mutation-databases.php) and reuses them for every mutant.
 *
 * Usage: php tests/bin/mutation.php <unit|integration> [infection options...]
 *
 * @package kwv
 */

// phpcs:disable WordPress.WP.AlternativeFunctions, WordPress.Security.EscapeOutput, WordPress.PHP.DiscouragedPHPFunctions -- CLI script, not WordPress.

$kwv_root = dirname( __DIR__, 2 );

require_once $kwv_root . '/vendor/autoload.php';

/**
 * Builds the workspace and runs Infection in it.
 */
final class KwvMutationRun {

	/**
	 * Theme root.
	 *
	 * @var string
	 */
	private $root;

	/**
	 * @param string $root Theme root.
	 */
	public function __construct( string $root ) {
		$this->root = $root;
	}

	/**
	 * Run one suite.
	 *
	 * @param string   $suite 'unit' or 'integration'.
	 * @param string[] $extra Extra Infection options.
	 * @return int Exit code.
	 */
	public function run( string $suite, array $extra ): int {

		if ( ! in_array( $suite, array( 'unit', 'integration' ), true ) ) {
			fwrite( STDERR, "Usage: php tests/bin/mutation.php <unit|integration> [infection options...]\n" );
			return 2;
		}

		$workspace = $this->root . '/build/mutation-workspace';
		( new Kwv\Tests\Support\MutationWorkspace( $this->root, $workspace ) )->build();
		echo "Mutation workspace ready: build/mutation-workspace\n";

		chdir( $workspace );
		$php = escapeshellarg( PHP_BINARY );

		if ( 'integration' === $suite ) {
			passthru( $php . ' tests/bin/mutation-databases.php 6', $status );
			if ( 0 !== $status ) {
				return $status;
			}
			putenv( 'WP_TESTS_SKIP_INSTALL=1' );
		}

		$command = $php . ' vendor/bin/infection --only-covering-test-cases --no-progress'
			. ( 'integration' === $suite ? ' --configuration=infection.integration.json5' : '' )
			. ' ' . implode( ' ', array_map( 'escapeshellarg', $extra ) );

		passthru( $command, $status );

		// The next run deletes the workspace, so keep the reports next to the theme.
		$reports = $this->root . '/build/infection';
		if ( ! is_dir( $reports ) ) {
			mkdir( $reports, 0777, true );
		}
		foreach ( (array) glob( $workspace . '/build/infection/*' ) as $report ) {
			copy( $report, $reports . '/' . basename( $report ) );
		}

		return $status;
	}
}

exit( ( new KwvMutationRun( $kwv_root ) )->run( (string) ( $argv[1] ?? '' ), array_slice( $argv, 2 ) ) );
