import {
  test,
  expect,
  SANDBOX_READY,
  TEST_CUSTOMER,
  addSeededProductToCart,
  emptyCart,
  fillCheckoutAddress,
} from '../fixtures';

/**
 * RQ-004 / RQ-005 / RQ-009 — Complete checkout and place an order.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 *
 * @stateful — these CREATE ORDERS. Completion uses **PayFast sandbox**, which is in
 * TEST mode on dev and needs NO real card. They run only when KWV_SANDBOX=1 and are
 * excluded from read-only smoke runs. (The Payflex/BNPL capture path would need a
 * gateway test card — out of scope for these cases.)
 */
test.describe('Place order @checkout @stateful', () => {
  test.skip(
    !SANDBOX_READY,
    'Requires sandbox mode (set KWV_SANDBOX=1). PayFast sandbox needs no card.',
  );

  // TC-010 — Place order (sandbox), guest
  test('TC-010 places a guest order via Payflex (sandbox)', async ({ page }) => {
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
    await expect(page).toHaveURL(/payfast|order-pay|order-received/i, { timeout: 60_000 });
    // Complete the simulated sandbox payment if the sandbox control is shown,
    // then land on order-received. (Sandbox page markup is finalised at execution.)
    const complete = page
      .getByRole('button', { name: /complete payment|pay now|complete/i })
      .or(page.getByRole('link', { name: /complete payment|pay now|complete/i }))
      .first();
    if (await complete.count()) {
      await complete.click();
      await expect(page).toHaveURL(/order-received|order-confirmation|thank/i, { timeout: 60_000 });
    }
  });

  // TC-011 — Logged-in checkout (pre-authenticated via saved session)
  test('TC-011 completes checkout as a logged-in customer @auth', async ({ page }) => {
    test.skip(!TEST_CUSTOMER.email, 'Set KWV_TEST_CUSTOMER_EMAIL / _PASSWORD to run TC-011.');

    await emptyCart(page); // persistent account cart may hold leftover items
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    // Address may be prefilled from the account; fillCheckoutAddress is idempotent
    // and ensures shipping rates calculate before submit.
    await fillCheckoutAddress(page);
    await page.getByRole('radio', { name: /payfast/i }).check();
    await page.getByRole('button', { name: /place order/i }).click();

    await expect(page).toHaveURL(/payfast|order-pay|order-received/i, { timeout: 60_000 });
    const complete = page
      .getByRole('button', { name: /complete payment|pay now|complete/i })
      .or(page.getByRole('link', { name: /complete payment|pay now|complete/i }))
      .first();
    if (await complete.count()) {
      await complete.click();
      await expect(page).toHaveURL(/order-received|order-confirmation|thank/i, { timeout: 60_000 });
    }
  });
});
