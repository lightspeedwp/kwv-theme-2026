# Theme PHP tests

Two PHPUnit suites cover `functions.php` and `inc/`, plus smoke tests for `patterns/`, `templates/` and `parts/`. The Playwright suite in `tests/e2e/` is separate and unchanged.

| Suite | Config | What it runs on |
|---|---|---|
| unit | `phpunit.xml.dist` | PHP only. WordPress functions are mocked with Brain Monkey. No database. |
| integration | `phpunit.integration.xml.dist` | Real WordPress 7.1.3 with this theme active, plus WooCommerce, Secure Custom Fields and (if present) Gravity Forms, in a throwaway MySQL database. |

## Install

```bash
composer install            # dev tools only; the theme has no runtime dependencies
```

Coverage needs PCOV. It is usually installed but disabled; the coverage script enables it per run (`php -d pcov.enabled=1`). On CI, `shivammathur/setup-php` installs it.

## Integration database

Create an empty MySQL database. **The WordPress test library drops every table in it on each run**, so never point it at a real site.

```bash
mysql -uroot -e 'CREATE DATABASE IF NOT EXISTS kwv_theme_tests'
```

Defaults are database `kwv_theme_tests`, user `root`, no password, host `127.0.0.1`. Override with `WP_TESTS_DB_NAME`, `WP_TESTS_DB_USER`, `WP_TESTS_DB_PASSWORD` and `WP_TESTS_DB_HOST`.

## Third-party plugins

Set `KWV_WP_PLUGINS_DIR` to a plugins folder containing:

- `woocommerce/` and `secure-custom-fields/` (required; free on wordpress.org).
- `gravityforms/` (optional, commercial). Tests that need it skip when `GFAPI` is missing.
- `carousel-block/` (optional). Brand-page tests assert Swiper loads when its bundle is present and does not when it is absent.

Locally, `KWV_WP_PLUGINS_DIR=/path/to/wp-content/plugins`. Nothing is written to that folder.

## Running

```bash
composer test               # unit, then integration
composer test:unit
composer test:integration
composer test:coverage      # both with PCOV, merged by phpcov; fails below 80% of lines in inc/
vendor/bin/phpunit -c phpunit.integration.xml.dist --filter BrandPage
```

Coverage reports land in `build/coverage/` (`clover.xml`, `html/`). The 80% gate is `tests/bin/coverage-check.php`. Branches that need a plugin not installed here (for example the Ollie Menu Designer script load in `inc/mobile-menu.php`) are left uncovered rather than excluded.

Other tools: `composer lint` (parallel-lint + PHPCS), `composer analyse` (PHPStan level 5 with WordPress and WooCommerce stubs; pre-existing findings are in `phpstan-baseline.neon`), `composer wpcs:fix`.

## Adding a test for a new `inc/` file

1. Add the file to `functions.php` as usual.
2. Decide the suite. Pure logic with simple inputs goes in `tests/Unit/` (extend `Kwv\Tests\Unit\TestCase`; call `$this->loadThemeFile( 'inc/your-file.php' )`, then stub WordPress functions with Brain Monkey). Anything that depends on hooks, queries, enqueues, blocks or the database goes in `tests/Integration/` (extend `Kwv\Tests\Integration\TestCase`).
3. Integration helpers: `go_to()` plus `makeTerm()`/`makeProduct()` to simulate page types; `runEnqueue()` and `isQueued()` for assets; `simulateBlockRender()` for `wp_enqueue_block_style()` assets; `applyRenderBlock()` for `render_block` filters; `actAs()` for roles.
4. The test library unregisters all meta keys after each test, so call your `register_*_meta()` function in `set_up()`.
5. Name the file `<Thing>Test.php`; add `@covers` tags.

## Gravity Forms

Gravity Forms is commercial and absent on CI. `TestCase::requireGravityForms()` skips a test when it is missing. The theme's own Gravity Forms code only attaches a stylesheet to a block, so it is tested without the plugin.

## Known findings (skipped, not fixed)

None at present. A finding is recorded as a skipped test whose message starts with `BUG:` by adding an entry to `KNOWN_BUGS` in `PatternSmokeTest` (pattern file => reason) or `KNOWN_DANGLING` in `TemplateSmokeTest` (template file => missing references). Delete the entry when it is fixed.

## CI

`.github/workflows/php-tests.yml` runs on pull requests to `develop`, `main` and `master`: lint (PHPCS is `continue-on-error` until the existing findings are cleaned up), PHPStan, and the test job (MySQL 8 service, PHP 8.2, PCOV, WooCommerce 10.9.1 and Secure Custom Fields downloaded from wordpress.org, `composer test:coverage`). No secrets are used.
