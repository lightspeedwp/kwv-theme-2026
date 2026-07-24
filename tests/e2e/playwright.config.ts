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
    {
      name: 'chromium',
      use: { ...devices['Desktop Chrome'] },
    },
    // Responsive adaptations of the supplied desktop designs are covered under
    // LS-1175; enable this project when mobile checkout is in scope for a run.
    // {
    //   name: 'mobile-chrome',
    //   use: { ...devices['Pixel 7'] },
    // },
  ],
});
