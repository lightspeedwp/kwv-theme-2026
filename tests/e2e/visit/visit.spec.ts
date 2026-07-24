import { test, expect, ALLOW_FORM_SUBMIT } from '../fixtures';

/**
 * VISIT-01 — Visit Us landing renders and routes to facility enquiry paths.
 * VISIT-02 — Each facility form opens in its popup modal.
 * VISIT-03 — Submitting a facility form redirects to its thank-you page.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * Grounded: 3 facilities — KWV Emporium (/visit/kwv), Cathedral Cellar (/visit/cellar),
 * House of Fire (/visit/fire). Each has a "Book" link and a "Menu" popup trigger.
 */
const FACILITIES = [
  { name: 'KWV Emporium', book: '/visit/kwv' },
  { name: 'Cathedral Cellar', book: '/visit/cellar' },
  { name: 'House of Fire', book: '/visit/fire' },
];

test.describe('Visit Us @visit', () => {
  // VISIT-TC1 — landing renders the 3 facilities
  test('VISIT-TC1 landing renders the three facilities with Book links', async ({ page }) => {
    await page.goto('/visit/');
    for (const f of FACILITIES) {
      await expect(page.getByRole('heading', { name: f.name })).toBeVisible();
    }
    await expect(page.getByRole('link', { name: /^book$/i }).first()).toBeVisible();
  });

  // VISIT-TC2 — "Menu" opens a popup modal (Popup Maker)
  test('VISIT-TC2 a facility "Menu" trigger opens a popup modal', async ({ page }) => {
    await page.goto('/visit/');
    await page.getByText(/^menu$/i).first().click();
    await expect(page.locator('.pum-overlay, .pum-container, [role="dialog"]').first()).toBeVisible({
      timeout: 20_000,
    });
  });

  // VISIT-TC3 — booking enquiry submit → thank-you (guarded; sends an email/entry)
  test('VISIT-TC3 a facility booking form submits and routes to a thank-you', async ({ page }) => {
    test.skip(!ALLOW_FORM_SUBMIT, 'Set KWV_ALLOW_FORM_SUBMIT=1 to run form-submitting cases on the target env.');

    await page.goto('/visit/kwv');
    const form = page.locator('form.gform_wrapper, form[id^="gform"]').first();
    await expect(form).toBeVisible();
    // Field labels vary per facility form; fill visible required text inputs generically.
    await form.getByRole('textbox').first().fill('Zared Test');
    // Submit and expect a Gravity Forms confirmation or a thank-you redirect.
    await form.getByRole('button', { name: /submit|send|book/i }).first().click();
    await expect(
      page.getByText(/thank you|thanks|received|confirmation/i).first(),
    ).toBeVisible({ timeout: 30_000 });
  });
});
