import { test, expect, PRODUCT, cartButton, waitForStoreApi } from '../fixtures';

/**
 * Single product page.
 * Pack: test-packs/launch-coverage-2026-10-22.md (PDP-TC1, PDP-TC2)
 *
 * PDP-TC1 — adding the product updates the cart count. Payflex is no longer shown
 *   on the product page (client decision, 2026-11): it lives at checkout as a
 *   payment method, which TC-009 in checkout/payment.spec.ts verifies.
 * PDP-TC2 — "Shop the range" (a related-products Product Collection) must not be
 *   empty: WebToffee Custom Related Products once emptied it on every product.
 */
test.describe('Single product @shop', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(`/product/${PRODUCT.slug}/`);
    await expect(page.getByRole('heading', { level: 1, name: PRODUCT.name })).toBeVisible();
  });

  // PDP-TC1 — add to cart (Payflex moved to checkout: see TC-009)
  test('PDP-TC1 adds the product to the cart', async ({ page }) => {
    await page.getByRole('button', { name: new RegExp(`add to cart.*${PRODUCT.name}`, 'i') }).click();
    await waitForStoreApi(page);
    await expect(cartButton(page)).toHaveAccessibleName(/:\s*[1-9]/);
  });

  // PDP-TC2 — related products
  test('PDP-TC2 "Shop the range" lists related products', async ({ page }) => {
    const related = page.locator('[data-collection="woocommerce/product-collection/related"]');
    await expect(related).toBeVisible();
    const items = related.getByRole('listitem');
    await expect(items.first()).toBeVisible();
    // Related means other products, never the one being viewed.
    await expect(items.filter({ hasText: PRODUCT.name })).toHaveCount(0);
  });
});
