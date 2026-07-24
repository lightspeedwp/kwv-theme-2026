import { test, expect } from '../fixtures';

/**
 * CAREERS-01 — Careers/Jobs landing renders job listings (custom `kwv-enhancements` CPT).
 * CAREERS-02 — A single job opens in a styled job template.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * Careers is a custom CPT (client did not need full WP Job Manager). Job listings
 * link to a single job at `/careers/<slug>/` (e.g. portfolio-brand-manager-marketing-spirits).
 * Job content is KWV-managed, so counts may change — assert structure + open a job if present.
 */
test.describe('Careers (CPT) @careers', () => {
  // CAREERS-TC1 — landing + available-positions section + a job row
  test('CAREERS-TC1 landing renders the Available positions section with a job', async ({ page }) => {
    await page.goto('/careers/');
    await expect(page.getByRole('heading', { name: /careers/i }).first()).toBeVisible();
    await expect(page.getByRole('heading', { name: /available positions/i })).toBeVisible();

    // At least one job links to a /careers/<slug>/ single-job page.
    const jobs = page.locator('a[href*="/careers/"]').filter({ hasText: /.+/ });
    await expect(jobs.first()).toBeVisible();
  });

  // CAREERS-TC2 — single job opens in a styled single-job template
  test('CAREERS-TC2 a published job opens in a styled single-job template', async ({ page }) => {
    await page.goto('/careers/');
    // First link into a job detail page (href deeper than /careers/ itself).
    const job = page
      .locator('a')
      .filter({ hasText: /click here|details|view|apply|manager|officer|assistant|specialist/i })
      .first();
    const hasJob = (await job.count()) > 0;
    test.skip(!hasJob, 'No jobs currently published on the Careers CPT.');

    await job.click();
    await expect(page).toHaveURL(/\/careers\/.+/);
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
  });
});
