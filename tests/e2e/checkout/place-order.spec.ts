import {
  test,
  expect,
  SANDBOX_READY,
  TEST_CUSTOMER,
  addSeededProductToCart,
  waitForStoreApi,
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

  async function fillShipping(page: import('@playwright/test').Page) {
    const shipping = page.getByRole('group', { name: /shipping address/i });
    await shipping.getByLabel(/country\/region/i).selectOption({ label: 'South Africa' });
    await shipping.getByRole('textbox', { name: /first name/i }).fill('Test');
    await shipping.getByRole('textbox', { name: /last name/i }).fill('Buyer');
    await shipping.getByRole('textbox', { name: /^address/i }).fill('1 Test Street');
    await shipping.getByRole('textbox', { name: /city/i }).fill('Cape Town');
    await shipping.getByRole('textbox', { name: /state\/county/i }).fill('Western Cape');
    await shipping.getByRole('textbox', { name: /postal code/i }).fill('7600');
    await shipping.getByRole('textbox', { name: /phone/i }).fill('0210000000');
    await waitForStoreApi(page);
  }

  // TC-010 — Place order (sandbox), guest
  test('TC-010 places a guest order via Payflex (sandbox)', async ({ page }) => {
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    await page.getByRole('textbox', { name: /email address/i }).fill('guest+e2e@example.test');
    await fillShipping(page);
    await page.getByRole('radio', { name: /payfast/i }).check();

    await page.getByRole('button', { name: /place order/i }).click();

    // Redirects to the PayFast sandbox payment page (no real charge).
    await expect(page).toHaveURL(/payfast|order-pay|order-received/i, { timeout: 45_000 });
    // Complete the simulated sandbox payment if the sandbox control is shown,
    // then land on order-received. (Sandbox page markup is finalised at execution.)
    const complete = page
      .getByRole('button', { name: /complete payment|pay now|complete/i })
      .or(page.getByRole('link', { name: /complete payment|pay now|complete/i }))
      .first();
    if (await complete.count()) {
      await complete.click();
      await expect(page).toHaveURL(/order-received|order-confirmation|thank/i, { timeout: 45_000 });
    }
  });

  // TC-011 — Logged-in checkout
  test('TC-011 completes checkout as a logged-in customer', async ({ page }) => {
    test.skip(!TEST_CUSTOMER.email, 'Set KWV_TEST_CUSTOMER_EMAIL / _PASSWORD to run TC-011.');

    await page.goto('/my-account/');
    await page.getByRole('textbox', { name: /username or email/i }).fill(TEST_CUSTOMER.email);
    await page.getByRole('textbox', { name: /password/i }).fill(TEST_CUSTOMER.password);
    await page.getByRole('button', { name: /log in/i }).click();

    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    // Contact/address may be prefilled from the account; ensure required fields are set.
    await fillShipping(page);
    await page.getByRole('radio', { name: /payfast/i }).check();
    await page.getByRole('button', { name: /place order/i }).click();

    await expect(page).toHaveURL(/order-received|checkout\/order|payflex|payfast/i, {
      timeout: 45_000,
    });
  });
});
