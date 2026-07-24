import { test, expect, addSeededProductToCart, waitForStoreApi } from '../fixtures';

/**
 * RQ-006 — Shipping rules apply at checkout once an address is entered.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 *
 * Mechanics only (G1): exact rates are not asserted until the rule-value source
 * is provided; we assert that a shipping option becomes available.
 */
test.describe('Checkout shipping @checkout', () => {
  // TC-006 — Shipping options appear after address
  test('TC-006 offers a shipping option once a SA address is entered', async ({ page }) => {
    await addSeededProductToCart(page);
    await page.goto('/checkout/');

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

    const options = page.getByRole('group', { name: /shipping options/i });
    // The "enter an address" placeholder is replaced by a selectable method.
    await expect(options.getByRole('radio')).not.toHaveCount(0, { timeout: 20_000 });
  });
});
