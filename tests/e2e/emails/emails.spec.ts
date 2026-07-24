import { test, expect } from '../fixtures';

/**
 * LS-1175 "Verify email templates" — verify WooCommerce transactional email
 * templates are enabled and render with KWV branding.
 *
 * Dev has the "Disable Emails" plugin active (transactional mail does NOT send),
 * so templates are verified via the WooCommerce admin **email preview**, which
 * renders the template HTML without sending. That requires an ADMIN account —
 * the customer test account cannot reach wp-admin — so these are guarded behind
 * KWV_ADMIN_EMAIL / KWV_ADMIN_PASSWORD and skip when absent.
 *
 * NOTE: selectors target the WooCommerce 10.x email settings + preview UI; verify
 * on first run with real admin credentials and adjust if the admin markup differs.
 */
const ADMIN = {
  email: process.env.KWV_ADMIN_EMAIL ?? '',
  password: process.env.KWV_ADMIN_PASSWORD ?? '',
};

test.describe('WooCommerce email templates @emails @admin', () => {
  test.skip(!ADMIN.email, 'Set KWV_ADMIN_EMAIL / KWV_ADMIN_PASSWORD (admin) to verify email templates.');

  test.beforeEach(async ({ page }) => {
    await page.goto('/wp-login.php');
    await page.locator('#user_login').fill(ADMIN.email);
    await page.locator('#user_pass').fill(ADMIN.password);
    await page.getByRole('button', { name: /log in/i }).click();
    await expect(page).toHaveURL(/wp-admin/, { timeout: 30_000 });
  });

  // EMAIL-TC1 — the scoped transactional emails exist and are enabled
  test('EMAIL-TC1 key transactional emails are configured', async ({ page }) => {
    await page.goto('/wp-admin/admin.php?page=wc-settings&tab=email');
    await expect(page.getByRole('heading', { name: /emails/i }).first()).toBeVisible();

    for (const email of [
      /new order/i,
      /processing order/i,
      /completed order/i,
      /invoice/i,
      /new account/i,
    ]) {
      await expect(page.getByText(email).first()).toBeVisible();
    }
  });

  // EMAIL-TC2 — the email template preview renders with KWV branding
  test('EMAIL-TC2 the email preview renders with KWV branding', async ({ page }) => {
    await page.goto('/wp-admin/admin.php?page=wc-settings&tab=email');

    // WC 10.x renders a live email preview; open it if it's behind a control.
    const previewTrigger = page
      .getByRole('button', { name: /preview/i })
      .or(page.getByRole('link', { name: /preview/i }))
      .first();
    if (await previewTrigger.count()) await previewTrigger.click().catch(() => {});

    // The preview renders inside an iframe.
    const preview = page
      .frameLocator(
        'iframe.wc-settings-email-preview__iframe, iframe[title*="preview" i], .wc-settings-email-preview iframe',
      )
      .first();

    // KWV styling: the header logo and/or the footer copy should be present.
    await expect(
      preview.getByText(/kwv|please drink responsibly/i).first(),
    ).toBeVisible({ timeout: 30_000 });
  });
});
