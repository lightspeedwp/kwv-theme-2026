import { test, expect, ALLOW_FORM_SUBMIT, FORM_TEST_EMAIL, TEST_MESSAGE, enquiryForm } from '../fixtures';

/**
 * CONTACT-01 — Contact Us form validates and redirects to the Contact thank-you page.
 * Packs: reports/test-packs/full-site-2026-07-24.md, test-packs/launch-coverage-2026-10-22.md
 *
 * Contact is a Gravity Form (Name, E-mail, Reason for contact required; message
 * optional). Validation is asserted always; a valid submission is guarded by
 * KWV_ALLOW_FORM_SUBMIT (creates an entry + fires notifications).
 */
test.describe('Contact form @forms', () => {
  // CONTACT-TC1 — required-field validation; (guarded) submit → thank-you
  test('CONTACT-TC1 validates required fields and submits to the thank-you page', async ({ page }) => {
    await page.goto('/contact/');
    const form = enquiryForm(page);
    await expect(form).toBeVisible();

    // Empty submit → Gravity Forms validation, no redirect.
    await form.getByRole('button', { name: /send message/i }).click();
    await expect(page.getByText(/there was a problem with your submission/i).first()).toBeVisible();
    await expect(page).toHaveURL(/\/contact\/?(#.*)?$/);

    test.skip(!ALLOW_FORM_SUBMIT || !FORM_TEST_EMAIL, 'Set KWV_ALLOW_FORM_SUBMIT=1 and KWV_FORM_TEST_EMAIL to submit.');

    const valid = enquiryForm(page);
    await valid.getByRole('textbox', { name: /^name/i }).fill('E2E Test');
    await valid.getByRole('textbox', { name: /^e-?mail/i }).fill(FORM_TEST_EMAIL);
    await valid.getByRole('textbox', { name: /reason for contact/i }).fill('E2E test');
    await valid.getByRole('textbox', { name: /message/i }).fill(TEST_MESSAGE);
    await valid.getByRole('button', { name: /send message/i }).click();

    await expect(page).toHaveURL(/\/contact\/thank-you\/?$/, { timeout: 45_000 });
  });
});
