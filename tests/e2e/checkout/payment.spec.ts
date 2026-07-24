import { test, expect, addSeededProductToCart } from '../fixtures';

/**
 * RQ-005 — Payflex is available and selectable at checkout.
 * RQ-010 — Payment gateway configuration is deliberate (PayFast + Payflex present).
 *          F2: both PayFast and Payflex currently co-exist — this records that and
 *          is the explicit check for the consolidation decision.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 */
test.describe('Checkout payment methods @checkout', () => {
  // TC-009 — Payment methods present; PayFast + Payflex
  test('TC-009 shows PayFast + Payflex, and Payflex is selectable', async ({ page }) => {
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

    const payment = page.getByRole('group', { name: /payment options/i });

    const payfast = payment.getByRole('radio', { name: /payfast/i });
    const payflex = payment.getByRole('radio', { name: /payflex/i });

    await expect(payfast).toBeVisible();
    await expect(payflex).toBeVisible();

    await payflex.check();
    await expect(payflex).toBeChecked();
  });
});
