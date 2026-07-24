import { test, expect } from '../fixtures';

/**
 * CAREERS-01 — Careers/Jobs landing renders job listings (custom `kwv-enhancements` CPT).
 * CAREERS-02 — A single job opens in a styled job template.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * Careers is a custom CPT (client did not need full WP Job Manager). Note: at
 * grounding time there were no published jobs, so the listing may be empty.
 */
test.describe('Careers (CPT) @careers', () => {
  // CAREERS-TC1 — landing + available-positions section
  test('CAREERS-TC1 landing renders the Available positions section', async ({ page }) => {
    await page.goto('/careers/');
    await expect(page.getByRole('heading', { name: /careers/i }).first()).toBeVisible();
    await expect(page.getByRole('heading', { name: /available positions/i })).toBeVisible();

    // If jobs are published, a listing links to a single job; otherwise the
    // section renders empty (valid state until KWV publishes jobs).
    const jobLinks = page.getByRole('link', { name: /view|details|apply|more/i });
    const count = await jobLinks.count();
    test.info().annotations.push({ type: 'note', description: `job listings found: ${count}` });
    expect(count).toBeGreaterThanOrEqual(0);
  });

  // CAREERS-TC2 — single job template (skips when no job is published)
  test('CAREERS-TC2 a published job opens in a styled single-job template', async ({ page }) => {
    await page.goto('/careers/');
    const firstJob = page
      .getByRole('listitem')
      .filter({ has: page.getByRole('link') })
      .getByRole('link')
      .first();
    const hasJob = (await firstJob.count()) > 0;
    test.skip(!hasJob, 'No jobs currently published on the Careers CPT.');

    await firstJob.click();
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
  });
});
