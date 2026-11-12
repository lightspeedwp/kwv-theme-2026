import { test, expect, ALLOW_FORM_SUBMIT, FORM_TEST_EMAIL, TEST_MESSAGE } from '../fixtures';
import path from 'node:path';

/**
 * CAREERS-01 — Careers/Jobs landing renders job listings (custom `kwv-enhancements` CPT).
 * CAREERS-02 — A single job opens in a styled job template.
 * CAREERS-03 — "Read More" on the landing opens the job modal (content + apply form).
 * CAR-TC4…TC6 — title opens the modal, apply submits, no expired job is listed.
 * Packs: reports/test-packs/full-site-2026-07-24.md, test-packs/launch-coverage-2026-10-22.md
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

  // CAR-TC4 — the job title also opens the modal; LinkedIn apply only when set
  test('CAR-TC4 the job title opens the modal, and LinkedIn apply links out to LinkedIn', async ({ page }) => {
    await page.goto('/careers/');
    const title = page.getByRole('listitem').getByRole('heading', { level: 3 }).getByRole('link').first();
    test.skip((await title.count()) === 0, 'No jobs currently published on the Careers CPT.');

    await title.click();
    const dialog = page.getByRole('dialog');
    await expect(dialog).toBeVisible();
    await expect(page).toHaveURL(/\/careers\/?(#.*)?$/);

    // Easy Apply is optional per job; when shown it must go to LinkedIn.
    const linkedin = dialog.getByRole('link', { name: /linkedin/i });
    if (await linkedin.count()) {
      await expect(linkedin.first()).toHaveAttribute('href', /^https:\/\/([a-z]+\.)?linkedin\.com\//);
    }
  });

  // CAR-TC6 — expired jobs drop off the board (hourly expiry in kwv-enhancements)
  test('CAR-TC6 no listed job is past its closing date', async ({ page }) => {
    await page.goto('/careers/');
    const rows = page.getByRole('listitem').filter({ has: page.locator('time') });
    test.skip((await rows.count()) === 0, 'No jobs currently published on the Careers CPT.');

    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const expired: string[] = [];
    for (const row of await rows.all()) {
      const time = row.locator('time').first();
      const stamp = (await time.getAttribute('datetime')) ?? (await time.innerText());
      const closes = new Date(stamp);
      expect(Number.isNaN(closes.getTime()), `unparseable closing date "${stamp}"`).toBeFalsy();
      if (closes < today) {
        expired.push(`${(await row.getByRole('heading').first().innerText()).trim()} (closed ${stamp})`);
      }
    }
    expect(expired).toEqual([]);
  });

  // CAR-TC5 — apply from the modal (creates an application entry)
  test('CAR-TC5 applying from the modal with a CV shows the confirmation', async ({ page }) => {
    test.skip(!ALLOW_FORM_SUBMIT || !FORM_TEST_EMAIL, 'Set KWV_ALLOW_FORM_SUBMIT=1 and KWV_FORM_TEST_EMAIL to submit.');
    await page.goto('/careers/');
    // Prefer a placeholder job so test applications never land on a real vacancy.
    const cards = page.locator('.is-style-career-card');
    const card = (await cards.filter({ hasText: /\[placeholder\]/i }).count())
      ? cards.filter({ hasText: /\[placeholder\]/i }).first()
      : cards.first();
    test.skip((await card.count()) === 0, 'No jobs currently published on the Careers CPT.');

    await card.locator('a.wp-block-read-more').click();
    const dialog = page.getByRole('dialog');
    await expect(dialog).toBeVisible();
    await dialog.getByLabel(/^name/i).fill('E2E');
    await dialog.getByLabel(/surname/i).fill('Test');
    await dialog.getByLabel(/email/i).fill(FORM_TEST_EMAIL);
    await dialog.getByLabel(/phone/i).fill('0210000000');
    const message = dialog.getByLabel(/message|cover|motivation/i);
    if (await message.count()) await message.first().fill(TEST_MESSAGE);
    await dialog.locator('input[type="file"]').setInputFiles(path.join(__dirname, '..', 'assets', 'e2e-test-cv.pdf'));
    const consent = dialog.getByRole('checkbox');
    for (const box of await consent.all()) await box.check();

    await dialog.getByRole('button', { name: /submit|apply|send/i }).click();
    await expect(dialog.getByText(/thank you|received|submitted|successfully/i).first()).toBeVisible({ timeout: 45_000 });
  });
});
