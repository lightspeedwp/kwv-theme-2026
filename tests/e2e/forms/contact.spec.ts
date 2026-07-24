import { test, expect, ALLOW_FORM_SUBMIT } from '../fixtures';

/**
 * CONTACT-01 — Contact Us form submits and redirects to the Contact thank-you page.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * Contact is a Gravity Form. Structure is asserted always; submission is guarded
 * by KWV_ALLOW_FORM_SUBMIT (creates an entry + fires notifications).
 */
test.describe('Contact form @forms', () => {
  // CONTACT-TC1 — form renders; (guarded) submit → thank-you
  test('CONTACT-TC1 renders the contact form and submits to a thank-you', async ({ page }) => {
    await page.goto('/contact/');
    const form = page.locator('form.gform_wrapper, form[id^="gform"]').first();
    await expect(form).toBeVisible();
    await expect(form.getByRole('textbox').first()).toBeVisible();

    test.skip(!ALLOW_FORM_SUBMIT, 'Set KWV_ALLOW_FORM_SUBMIT=1 to submit the contact form.');

    // Fill common Gravity Forms fields (name/email/message) as available.
    const email = form.getByRole('textbox', { name: /email/i }).first();
    if (await email.count()) await email.fill('zared@lightspeed.dev');
    const name = form.getByRole('textbox', { name: /name/i }).first();
    if (await name.count()) await name.fill('Zared Test');
    const message = form.getByRole('textbox', { name: /message|comment|enquiry/i }).first();
    if (await message.count()) await message.fill('E2E test submission — please ignore.');

    await form.getByRole('button', { name: /submit|send/i }).first().click();
    await expect(
      page.getByText(/thank you|thanks|received|message.*sent/i).first(),
    ).toBeVisible({ timeout: 30_000 });
  });
});
