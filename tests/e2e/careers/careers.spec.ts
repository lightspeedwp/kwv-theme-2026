import { test, expect } from '../fixtures';

/**
 * CAREERS-01 — Careers/Jobs landing renders job listings (custom `kwv-enhancements` CPT).
 * CAREERS-02 — A single job opens in a styled job template.
 * CAREERS-03 — "Read More" on the landing opens the job modal (content + apply form).
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * Careers is a custom CPT (client did not need full WP Job Manager). Job listings
 * link to a single job at `/careers/<slug>/` (e.g. portfolio-brand-manager-marketing-spirits),
 * but with JavaScript a plain click on "Read More" / the title opens the job modal
 * (kwv-enhancements CareersModal) instead — the link is the no-JS fallback.
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
    // A plain click opens the modal, so follow the link's href instead.
    const job = page.locator('a[data-kwv-career-open], .is-style-career-card a.wp-block-read-more').first();
    const hasJob = (await job.count()) > 0;
    test.skip(!hasJob, 'No jobs currently published on the Careers CPT.');

    const href = await job.getAttribute('href');
    await page.goto(href!);
    await expect(page).toHaveURL(/\/careers\/.+/);
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
  });

  // CAREERS-TC3 — Read More opens the job modal with the job content and the apply form
  test('CAREERS-TC3 Read More opens the job modal with the application form', async ({ page }) => {
    await page.goto('/careers/');
    const readMore = page.locator('.is-style-career-card a.wp-block-read-more').first();
    test.skip((await readMore.count()) === 0, 'No jobs currently published on the Careers CPT.');

    await expect(readMore).toHaveText(/read more/i);
    await readMore.click();

    const dialog = page.getByRole('dialog');
    await expect(dialog).toBeVisible();
    await expect(page).toHaveURL(/\/careers\/?(#.*)?$/); // did not navigate
    await expect(dialog.getByRole('heading', { level: 2 })).toBeVisible();
    await expect(dialog.getByRole('heading', { name: /apply for this position/i })).toBeVisible();
    await expect(dialog.getByLabel(/^name/i)).toBeVisible();
    await expect(dialog.getByLabel(/surname/i)).toBeVisible();
    await expect(dialog.getByLabel(/email/i)).toBeVisible();
    await expect(dialog.getByLabel(/phone/i)).toBeVisible();
    await expect(dialog.locator('input[type="file"]')).toBeAttached();

    await page.keyboard.press('Escape');
    await expect(dialog).toBeHidden();
    await expect(readMore).toBeFocused();
  });
});
