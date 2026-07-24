import { test as setup, expect } from '@playwright/test';
import { TEST_CUSTOMER } from './fixtures';

/**
 * Authenticate ONCE and persist the session, so authenticated specs reuse it
 * instead of logging in repeatedly. The dev site throttles/locks after a couple
 * of failed logins, so minimising logins is important.
 *
 * Always writes the state file (seeding the age-gate cookie) so the authenticated
 * project can create a context even when no test customer is configured; those
 * specs then self-skip via their own TEST_CUSTOMER guard.
 */
const authFile = 'playwright/.auth/customer.json';

setup('authenticate', async ({ page, context, baseURL }) => {
  if (baseURL) {
    await context.addCookies([
      { name: 'age_gate', value: '18', domain: new URL(baseURL).hostname, path: '/' },
    ]);
  }

  if (TEST_CUSTOMER.email) {
    await page.goto('/my-account/');
    await page.locator('#username').fill(TEST_CUSTOMER.email);
    await page.locator('#password').fill(TEST_CUSTOMER.password);
    await page.getByRole('button', { name: /^log in$/i }).click();
    await expect(
      page.getByText(/username or password you entered is incorrect|unknown email/i),
      'Login failed — KWV_TEST_CUSTOMER_PASSWORD does not match the account',
    ).toHaveCount(0, { timeout: 10_000 });
    await expect(page.getByRole('link', { name: /log out/i }).first()).toBeVisible({ timeout: 30_000 });
  }

  await context.storageState({ path: authFile });
});
