import { test, expect, TEST_CUSTOMER, loginCustomer } from '../fixtures';

/**
 * ACCT-01 — Account/Login page renders; a valid customer can log in.
 * ACCT-02 — My Account shows account screens post-login.
 * ACCT-03 — Reset-password flow requests a reset.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * @stateful — needs a dedicated test customer (KWV_TEST_CUSTOMER_EMAIL/_PASSWORD).
 * Skips when not provided.
 */
test.describe('WooCommerce account @account @stateful', () => {
  // ACCT-TC1 — login page + successful login
  test('ACCT-TC1 login page renders and a valid customer logs in', async ({ page }) => {
    await page.goto('/my-account/');
    await expect(page.getByRole('textbox', { name: /username or email/i })).toBeVisible();

    test.skip(!TEST_CUSTOMER.email, 'Set KWV_TEST_CUSTOMER_EMAIL/_PASSWORD to run login.');
    await loginCustomer(page);
    await expect(page.getByRole('link', { name: /log out/i }).first()).toBeVisible({ timeout: 30_000 });
  });

  // ACCT-TC2 — My Account screens (pre-authenticated via saved session)
  test('ACCT-TC2 My Account shows orders and account details @auth', async ({ page }) => {
    test.skip(!TEST_CUSTOMER.email, 'Set KWV_TEST_CUSTOMER_EMAIL/_PASSWORD to run My Account.');
    await page.goto('/my-account/');
    await expect(page.getByRole('link', { name: /orders/i }).first()).toBeVisible();
    await expect(page.getByRole('link', { name: /account details|addresses/i }).first()).toBeVisible();
  });

  // ACCT-TC3 — reset-password request (completion via emailed link is verified manually)
  test('ACCT-TC3 reset-password request is accepted', async ({ page }) => {
    await page.goto('/my-account/lost-password/');
    const emailField = page.getByRole('textbox', { name: /username or email/i }).first();
    await expect(emailField).toBeVisible();

    test.skip(!TEST_CUSTOMER.email, 'Set KWV_TEST_CUSTOMER_EMAIL to run the reset request.');
    await emailField.fill(TEST_CUSTOMER.email);
    await page.getByRole('button', { name: /reset password/i }).click();
    await expect(page.getByText(/email.*sent|check your email|link to reset/i).first()).toBeVisible({
      timeout: 30_000,
    });
  });
});
