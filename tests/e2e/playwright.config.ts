import { defineConfig, devices } from '@playwright/test';
import 'dotenv/config';

/**
 * KWV 2026 — Playwright E2E config.
 *
 * Base URL and all credentials come from the environment (see .env.example).
 * NEVER hard-code secrets or real customer data here.
 *
 * Staging note: the KWV dev/staging site is slow (whole-page loads can take
 * tens of seconds). Timeouts below are deliberately generous — do not read
 * Store API latency as a failure.
 */
const BASE_URL = process.env.KWV_BASE_URL ?? 'https://kwv.lightspeedwp.dev';

/**
 * Read-only specs: they render and interact but never place orders, submit
 * forms or need an account. These are the ones run cross-browser.
 */
const READ_ONLY = /(smoke|global|shop|brand|responsive)\/.+\.spec\.ts$/;
/**
 * Installed Google Chrome, not Playwright's bundled Chromium (149 with
 * @playwright/test 1.61): after a Gravity Forms AJAX validation the bundled
 * build stops painting frames, so the next click never passes the "stable"
 * check. Chrome 157 is unaffected. Override with KWV_BROWSER_CHANNEL. Applied
 * to the Chromium-based projects only (Firefox/WebKit have no such channel).
 */
const CHROME = { channel: process.env.KWV_BROWSER_CHANNEL ?? 'chrome' };

/** Cases kept to Chromium: state-changing, account-bound, or one-browser baselines. */
const CHROMIUM_ONLY = /@auth|@stateful|@visual|@a11y/;

export default defineConfig({
  testDir: '.',
  testMatch: '**/*.spec.ts',
  fullyParallel: false, // cart/checkout mutate shared state per context; keep ordered within a file
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  workers: process.env.CI ? 1 : undefined,
  reporter: process.env.CI
    ? [['github'], ['html', { open: 'never' }], ['list']]
    : [['html', { open: 'never' }], ['list']],

  timeout: 90_000, // per-test; slow staging
  expect: { timeout: 20_000 },

  use: {
    baseURL: BASE_URL,
    navigationTimeout: 45_000,
    actionTimeout: 20_000,
    trace: 'on-first-retry',
    screenshot: 'only-on-failure',
    video: 'off',
  },

  projects: [
    { name: 'setup', testMatch: /auth\.setup\.ts/, use: CHROME },
    {
      // Guest / unauthenticated — everything except @auth specs.
      name: 'chromium',
      use: { ...devices['Desktop Chrome'], ...CHROME },
      grepInvert: /@auth|@mobile/,
      testIgnore: /auth\.setup\.ts/,
    },
    {
      // Cross-browser: the read-only specs in Firefox and WebKit (Safari's engine).
      // Playwright's own builds, so no `channel`.
      name: 'firefox',
      testMatch: READ_ONLY,
      use: { ...devices['Desktop Firefox'] },
      grepInvert: new RegExp(`${CHROMIUM_ONLY.source}|@mobile`),
    },
    {
      name: 'webkit',
      testMatch: READ_ONLY,
      use: { ...devices['Desktop Safari'] },
      grepInvert: new RegExp(`${CHROMIUM_ONLY.source}|@mobile`),
    },
    {
      // Phone on WebKit (iPhone 13): the read-only @mobile cases, alongside the
      // Pixel 7 project below.
      name: 'mobile-safari',
      testMatch: READ_ONLY,
      use: { ...devices['iPhone 13'] },
      grep: /@mobile/,
      grepInvert: CHROMIUM_ONLY,
    },
    {
      // Phone — touch + mobile UA for the @mobile interaction cases. Mobile and
      // tablet are responsive adaptations of the desktop designs, so this runs
      // only the cases that need a real phone context (RESP-TC2).
      name: 'mobile-chrome',
      use: { ...devices['Pixel 7'], ...CHROME },
      grep: /@mobile/,
      grepInvert: /@auth/,
      testIgnore: /auth\.setup\.ts/,
    },
    {
      // Authenticated — @auth specs reuse the session saved by `setup`.
      name: 'chromium-auth',
      use: {
        ...devices['Desktop Chrome'],
        ...CHROME,
        storageState: 'playwright/.auth/customer.json',
      },
      grep: /@auth/,
      dependencies: ['setup'],
    },
  ],
});
