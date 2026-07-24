import { test, expect, PRODUCT, addSeededProductToCart } from '../fixtures';

/**
 * RQ-003 — The Cart page renders cart contents with scoped KWV styling.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 */
test.describe('Cart page @cart', () => {
  // TC-004 — Cart page renders
  test('TC-004 renders the line item, totals, and a route to checkout', async ({ page }) => {
    await addSeededProductToCart(page);
    await page.goto('/cart/');

    // Line item present.
    await expect(page.getByRole('link', { name: PRODUCT.name }).first()).toBeVisible();

    // A subtotal / total is shown (mechanics only — exact value not asserted; see G1).
    await expect(page.getByText(/subtotal|total/i).first()).toBeVisible();

    // A route to checkout exists (link or button).
    const proceed = page.getByRole('link', { name: /checkout/i }).first();
    await expect(proceed).toBeVisible();
  });
});
