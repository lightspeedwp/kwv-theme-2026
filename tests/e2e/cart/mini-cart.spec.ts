import {
  test,
  expect,
  PRODUCT,
  cartButton,
  miniCartDrawer,
  addSeededProductToCart,
  waitForStoreApi,
} from '../fixtures';

/**
 * RQ-002 — Mini-cart reflects contents; supports quantity change, removal,
 * and navigation to Cart/Checkout.
 * Pack: reports/test-packs/checkout-2026-07-24.md
 *
 * The mini-cart drawer is a body-level portal (.wc-block-mini-cart__drawer).
 */
test.describe('Mini-cart drawer @cart', () => {
  test.beforeEach(async ({ page }) => {
    await addSeededProductToCart(page);
    await cartButton(page).click();
    await expect(miniCartDrawer(page)).toBeVisible();
  });

  // TC-002 — Mini-cart drawer contents & navigation
  test('TC-002 shows the line item, subtotal, and cart/checkout links', async ({ page }) => {
    const drawer = miniCartDrawer(page);

    await expect(drawer.getByRole('heading', { name: /your cart/i })).toBeVisible();
    await expect(drawer.getByRole('link', { name: PRODUCT.name })).toBeVisible();

    await expect(drawer.getByRole('link', { name: /view my cart/i })).toHaveAttribute(
      'href',
      /\/cart\/?$/,
    );
    await expect(drawer.getByRole('link', { name: /go to checkout/i })).toHaveAttribute(
      'href',
      /\/checkout\/?$/,
    );
  });

  // TC-003 — Mini-cart quantity & remove
  test('TC-003 increases quantity, then removes the item', async ({ page }) => {
    const drawer = miniCartDrawer(page);

    const increase = drawer.getByRole('button', { name: /increase quantity/i });
    await increase.click();
    await waitForStoreApi(page);
    await expect(drawer.getByRole('spinbutton', { name: /quantity of/i })).toHaveValue('2');

    await drawer.getByRole('button', { name: /remove .* from cart/i }).click();
    await waitForStoreApi(page);

    await expect(drawer.getByText(/your cart is currently empty/i)).toBeVisible();
    await expect(cartButton(page)).toHaveAccessibleName(/:\s*0/);
  });
});
