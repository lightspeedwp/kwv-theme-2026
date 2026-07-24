import { test, expect, PRODUCT } from '../fixtures';

/**
 * SHOP-02 — Category/shop filters apply and update results.
 * SHOP-03 — Single product uses the standard WooCommerce layout; no bespoke awards display.
 * SHOP-05 — Single Brand page renders its products.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 */
test.describe('Shop discovery @shop', () => {
  // SHOP-TC2 — filters update results
  test('SHOP-TC2 applying a Brand filter updates the product grid', async ({ page }) => {
    await page.goto('/shop/');
    const status = page.getByRole('status').filter({ hasText: /showing|results/i }).first();
    await expect(status).toBeVisible();
    const before = (await status.textContent())?.trim();

    // The Brand filter accordion is collapsed by default — expand it, then tick.
    const brandToggle = page.getByRole('button', { name: /^brand$/i });
    if ((await brandToggle.getAttribute('aria-expanded')) === 'false') await brandToggle.click();
    const mentors = page.getByRole('checkbox', { name: /the mentors/i });
    await mentors.scrollIntoViewIfNeeded();
    await mentors.check({ force: true });
    // Results refresh (URL param or status text changes).
    await expect
      .poll(async () => (await status.textContent())?.trim(), { timeout: 20_000 })
      .not.toBe(before);
  });

  // SHOP-TC3 — single product standard layout, no awards block
  test('SHOP-TC3 single product renders standard layout without a bespoke awards display', async ({ page }) => {
    await page.goto(`/product/${PRODUCT.slug}/`);
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    await expect(page.getByText(/R\s?\d/).first()).toBeVisible(); // price
    await expect(page.getByRole('button', { name: /add to cart/i }).first()).toBeVisible();
    // Scope guard: no bespoke awards section (out of scope per PRD).
    await expect(page.locator('[class*="award"], [id*="award"]')).toHaveCount(0);
  });

  // SHOP-TC5 — single brand page
  test('SHOP-TC5 a Single Brand page lists that brand’s products', async ({ page }) => {
    await page.goto('/brand/the-mentors/');
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    await expect(page.getByRole('listitem').filter({ hasText: /R\s?\d/ }).first()).toBeVisible();
  });
});
