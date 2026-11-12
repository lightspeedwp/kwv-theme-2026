import {
  test,
  expect,
  PRODUCT,
  SANDBOX_READY,
  TEST_CUSTOMER,
  addSeededProductToCart,
  emptyCart,
  fillCheckoutAddress,
} from '../fixtures';
import type { Page } from '@playwright/test';

/**
 * After a sandbox payment PayFast shows "redirected back to the merchant shortly",
 * but its redirect can hang for over a minute. Click its "Back to" control if the
 * redirect has not happened, as a customer would, and land on the confirmation.
 */
async function returnFromPayFast(page: Page): Promise<void> {
  const back = page
    .getByRole('button', { name: /^back to/i })
    .or(page.getByRole('link', { name: /^back to/i }))
    .first();
  await expect(async () => {
    if (/payfast\.co\.za/i.test(page.url()) && (await back.isVisible())) {
      await back.click({ timeout: 5_000 }).catch(() => {});
    }
    await expect(page).toHaveURL(/order-received|order-confirmation|thank/i, { timeout: 15_000 });
  }).toPass({ timeout: 90_000 });
}

/**
 * RQ-004 / RQ-005 / RQ-009 — Complete checkout and place an order.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 *
 * @stateful — these CREATE ORDERS. Completion uses **PayFast sandbox**, which is in
 * TEST mode on dev and needs NO real card. They run only when KWV_SANDBOX=1 and are
 * excluded from read-only smoke runs. NEVER pay with Payflex here: Payflex on dev
 * runs in PRODUCTION mode, so a Payflex order would be real.
 */
test.describe('Place order @checkout @stateful', () => {
  test.skip(
    !SANDBOX_READY,
    'Requires sandbox mode (set KWV_SANDBOX=1). PayFast sandbox needs no card.',
  );

  // TC-010 — Place order (sandbox), guest
  test('TC-010 places a guest order via PayFast (sandbox) and shows the order confirmation', async ({ page }) => {
    test.setTimeout(180_000); // round trip through the external PayFast sandbox
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    // This store requires an account at checkout (no true guest checkout): the
    // "Create a password" field is mandatory. Use a unique email per run so repeat
    // runs don't hit "an account is already registered with your email".
    const email = `guest.e2e.${Date.now()}@example.com`;
    await page.getByRole('textbox', { name: /email address/i }).fill(email);
    const createPassword = page.getByRole('textbox', { name: /create a password/i });
    if (await createPassword.count()) await createPassword.fill('E2e-Test-Pass-123!');
    await fillCheckoutAddress(page);
    await page.getByRole('radio', { name: /payfast/i }).check();

    await page.getByRole('button', { name: /place order/i }).click();

    // Redirects to the PayFast sandbox payment page (no real charge).
    await expect(page).toHaveURL(/payfast\.co\.za|order-received/i, { timeout: 60_000 });
    // Complete the simulated sandbox payment if the sandbox control is shown,
    // then land on order-received. (Sandbox page markup is finalised at execution.)
    const complete = page
      .getByRole('button', { name: /complete payment|pay now|complete/i })
      .or(page.getByRole('link', { name: /complete payment|pay now|complete/i }))
      .first();
    // The URL matches /payfast/ on the redirect, before the sandbox page has
    // rendered its button, so wait for the control rather than counting it.
    if (/payfast\.co\.za/i.test(page.url())) {
      await complete.click({ timeout: 45_000 });
      await returnFromPayFast(page);
    }
    // Order confirmation (order-confirmation template): order number, the line
    // item and a total. Reached directly or after the sandbox control above.
    await expect(page).toHaveURL(/order-received/i, { timeout: 60_000 });
    await expect(page.getByText(/order (number|#)/i).first()).toBeVisible();
    await expect(page.getByText(PRODUCT.name).first()).toBeVisible();
    await expect(page.getByText(/total/i).first()).toBeVisible();
  });

  // TC-011 — Logged-in checkout (pre-authenticated via saved session)
  test('TC-011 completes checkout as a logged-in customer @auth', async ({ page }) => {
    test.setTimeout(180_000); // round trip through the external PayFast sandbox
    test.skip(!TEST_CUSTOMER.email, 'Set KWV_TEST_CUSTOMER_EMAIL / _PASSWORD to run TC-011.');

    await emptyCart(page); // persistent account cart may hold leftover items
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    // Address may be prefilled from the account; fillCheckoutAddress is idempotent
    // and ensures shipping rates calculate before submit.
    await fillCheckoutAddress(page);
    await page.getByRole('radio', { name: /payfast/i }).check();
    await page.getByRole('button', { name: /place order/i }).click();

    await expect(page).toHaveURL(/payfast\.co\.za|order-received/i, { timeout: 60_000 });
    const complete = page
      .getByRole('button', { name: /complete payment|pay now|complete/i })
      .or(page.getByRole('link', { name: /complete payment|pay now|complete/i }))
      .first();
    // The URL matches /payfast/ on the redirect, before the sandbox page has
    // rendered its button, so wait for the control rather than counting it.
    if (/payfast\.co\.za/i.test(page.url())) {
      await complete.click({ timeout: 45_000 });
      await returnFromPayFast(page);
    }
  });
});
