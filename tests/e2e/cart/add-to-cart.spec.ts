import { test, expect, PRODUCT, cartButton, addSeededProductToCart } from '../fixtures';

/**
 * RQ-001 — Add a product to the cart from Shop/product pages.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 */
test.describe('Add to cart @cart', () => {
  // TC-001 — Add to cart from Shop
  test('TC-001 adds the seeded product from the Shop grid', async ({ page }) => {
    await page.goto('/shop/');

    const card = page.getByRole('listitem').filter({ hasText: PRODUCT.name }).first();
    await expect(card).toBeVisible();
    await card.getByRole('button', { name: /add to cart/i }).click();

    // "Added to cart" notification confirms the action.
    await expect(page.getByText(/added to cart/i)).toBeVisible();

    // Header cart count reflects the addition (Store API async update).
    await expect(cartButton(page)).toHaveAccessibleName(/:\s*[1-9]/);
  });

  test('TC-001b helper seeds a cart for downstream flows', async ({ page }) => {
    await addSeededProductToCart(page);
    await expect(cartButton(page)).toHaveAccessibleName(/:\s*[1-9]/);
  });
});
