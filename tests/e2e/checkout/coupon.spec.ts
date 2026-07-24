import { test, expect, COUPON, addSeededProductToCart, waitForStoreApi } from '../fixtures';

/**
 * RQ-008 — Discount / coupon rules apply where configured.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 *
 * Blocked (G3): requires a confirmed test coupon (and a cart meeting its
 * threshold). Skips until KWV_COUPON_CODE is set. Mechanics only (G1).
 */
test.describe('Checkout coupon @checkout', () => {
  test.skip(!COUPON, 'Set KWV_COUPON_CODE (a confirmed test coupon) to run TC-008.');

  // TC-008 — Coupon application
  test('TC-008 applies a valid coupon and rejects an invalid one', async ({ page }) => {
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    await page.getByRole('button', { name: /add coupons/i }).click();
    const codeField = page.getByRole('textbox', { name: /coupon|code/i }).first();

    // Valid coupon → total changes / discount line appears (mechanics only).
    await codeField.fill(COUPON);
    await page.getByRole('button', { name: /^apply/i }).click();
    await waitForStoreApi(page);
    await expect(page.getByText(new RegExp(COUPON, 'i')).first()).toBeVisible();

    // Invalid coupon → error state.
    await codeField.fill('DEFINITELY-NOT-A-COUPON');
    await page.getByRole('button', { name: /^apply/i }).click();
    await waitForStoreApi(page);
    await expect(page.getByText(/coupon.*(not|invalid|does not exist)/i)).toBeVisible();
  });
});
