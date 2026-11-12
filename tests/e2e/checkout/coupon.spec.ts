import { test, expect, COUPON, addSeededProductToCart, waitForStoreApi } from '../fixtures';

/**
 * RQ-008 — Discount / coupon rules apply where configured.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 *
 * Needs a confirmed test coupon in KWV_COUPON_CODE (dev: `test`, 10%). Mechanics
 * only (G1): the discount line appears; rule values are not asserted.
 */
test.describe('Checkout coupon @checkout', () => {
  test.skip(!COUPON, 'Set KWV_COUPON_CODE (a confirmed test coupon) to run TC-008.');

  // TC-008 — Coupon application
  test('TC-008 applies a valid coupon and rejects an invalid one', async ({ page }) => {
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    const panelToggle = page.getByRole('button', { name: /add coupons/i });
    // The coupon panel collapses after a successful apply, and a loose /code/
    // locator then matches "Postal code" — always target the coupon input itself.
    const codeField = page.getByRole('textbox', { name: /enter code|coupon code/i });
    const applyCoupon = async (code: string) => {
      if (!(await codeField.isVisible())) await panelToggle.click();
      await codeField.fill(code);
      await page.getByRole('button', { name: /^apply/i }).click();
      await waitForStoreApi(page);
    };

    // Valid coupon → a removable discount line appears (mechanics only).
    await applyCoupon(COUPON);
    await expect(
      page.getByRole('button', { name: new RegExp(`remove coupon .*${COUPON}`, 'i') }),
    ).toBeVisible();

    // Invalid coupon → error state.
    await applyCoupon('DEFINITELY-NOT-A-COUPON');
    await expect(page.getByText(/coupon.*(not|invalid|does not exist)/i).first()).toBeVisible();
  });
});
