/**
 * QG-R1 / TC-QG1 — Console-error budget on in-scope WooCommerce flows.
 *
 * Pack: test-packs/quality-gates-2026-07-31.md
 * Requirement: QG-R1 (error or empty state) — coverage expansion on the in-scope
 * flows the PRD requires to be preserved through testing (PRD L312, L354).
 *
 * Asserts no NEW console errors against the baseline recorded in fixtures.ts.
 * This is deliberately NOT an accessibility or SEO suite — both are excluded by
 * Estimate 3168 (see the pack's Change-Control Register, CC-1 / CC-2).
 */
import {
  test,
  expect,
  PRODUCT,
  watchConsoleErrors,
  newConsoleErrors,
  addSeededProductToCart,
} from '../fixtures';

test.describe('Console-error budget @console', () => {
  for (const path of ['/', '/shop/', '/cart/']) {
    test(`TC-QG1 — no new console errors on ${path}`, async ({ page }) => {
      const watcher = watchConsoleErrors(page);
      await page.goto(path, { waitUntil: 'load' });
      // Analytics and Store API scripts bind after load; give them a beat to throw.
      await page.waitForLoadState('networkidle').catch(() => {
        /* slow staging may never fully idle — the load event is enough */
      });
      expect(newConsoleErrors(watcher.errors(), path)).toEqual([]);
    });
  }

  test(`TC-QG1 — no new console errors on the single product template`, async ({ page }) => {
    const watcher = watchConsoleErrors(page);
    await page.goto(`/product/${PRODUCT.slug}/`, { waitUntil: 'load' });
    await page.waitForLoadState('networkidle').catch(() => {});
    // No dedicated baseline key: the product template must be error-free.
    expect(newConsoleErrors(watcher.errors(), `/product/${PRODUCT.slug}/`)).toEqual([]);
  });

  test('TC-QG1 — no new console errors on /checkout/', async ({ page }) => {
    // Checkout redirects to /cart/ when the cart is empty, so seed it first.
    await addSeededProductToCart(page);
    const watcher = watchConsoleErrors(page);
    await page.goto('/checkout/', { waitUntil: 'load' });
    await page.waitForLoadState('networkidle').catch(() => {});
    await expect(page).toHaveURL(/\/checkout\//);
    expect(newConsoleErrors(watcher.errors(), '/checkout/')).toEqual([]);
  });
});
