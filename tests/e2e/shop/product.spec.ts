import { test, expect, PRODUCT, cartButton, waitForStoreApi } from '../fixtures';

/**
 * Single product page.
 * Pack: test-packs/launch-coverage-2026-10-22.md (PDP-TC1, PDP-TC2)
 *
 * PDP-TC1 — the PRD requires Payflex to be verified in testing. The Payflex plugin
 *   injects its calculator widget (`.payflexCalculatorWidgetContainer`) into the
 *   add-to-cart form; assets/js/payflex-relocate.js moves it below the description.
 * PDP-TC2 — "Shop the range" (a related-products Product Collection) must not be
 *   empty: WebToffee Custom Related Products once emptied it on every product.
 */
test.describe('Single product @shop', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto(`/product/${PRODUCT.slug}/`);
    await expect(page.getByRole('heading', { level: 1, name: PRODUCT.name })).toBeVisible();
  });

  // PDP-TC1 — Payflex widget + add to cart
  test('PDP-TC1 shows the Payflex widget and adds the product to the cart', async ({ page }) => {
    await expect(page.locator('.payflexCalculatorWidgetContainer')).toBeVisible({ timeout: 20_000 });

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
