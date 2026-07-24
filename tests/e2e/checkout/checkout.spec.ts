import { test, expect, addSeededProductToCart } from '../fixtures';

/**
 * RQ-004 — Checkout renders and can be completed (structure here; place-order in
 *          place-order.spec.ts).
 * RQ-007 — Tax (VAT) is calculated/displayed on the order total.
 * RQ-009 — Guests can reach checkout.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 *
 * Checkout is WooCommerce **Blocks** (.wc-block-checkout) — accessible locators.
 */
test.describe('Checkout structure @checkout', () => {
  test.beforeEach(async ({ page }) => {
    await addSeededProductToCart(page);
    await page.goto('/checkout/');
  });

  // TC-005 — Checkout page structure (guest)
  test('TC-005 renders contact, shipping, payment groups and Place Order', async ({ page }) => {
    // The block checkout <form> has no stable accessible name; assert the fieldset
    // groups + Place Order button, which are the reliable structure.
    await expect(page.getByRole('group', { name: /contact information/i })).toBeVisible();
    await expect(page.getByRole('group', { name: /shipping address/i })).toBeVisible();
    await expect(page.getByRole('group', { name: /payment options/i })).toBeVisible();

    await expect(page.getByRole('textbox', { name: /email address/i })).toBeVisible();
    await expect(page.getByRole('button', { name: /place order/i })).toBeVisible();
  });

  // TC-007 — VAT/tax displayed on the order total
  test('TC-007 shows a VAT line in the order summary', async ({ page }) => {
    // Mechanics only (see G1/A2): assert a VAT line is present, not a re-derived rate.
    await expect(page.getByText(/vat/i).first()).toBeVisible();
    await expect(page.getByText(/total/i).first()).toBeVisible();
  });
});
