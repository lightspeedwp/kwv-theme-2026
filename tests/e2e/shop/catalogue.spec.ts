import { test, expect, BRAND, storeCategories } from '../fixtures';

/**
 * Catalogue structure and shop archives.
 * Pack: test-packs/launch-coverage-2026-10-22.md (CAT-TC1…TC5)
 *
 * After the October 2026 restructure, brand is the `product_brand` taxonomy only:
 * there is no "Brands" product category. Category and brand data are read from
 * the public Store API at run time — the catalogue resync renumbers terms, so no
 * IDs or counts are hard-coded.
 */
test.describe('Catalogue @shop', () => {
  // CAT-TC1 — category tree + every populated category archive loads
  test('CAT-TC1 the category tree has no Brands category and every category archive loads', async ({ page, request }) => {
    const cats = await storeCategories(request);
    const names = cats.map((c) => c.name.toLowerCase());
    expect(names, 'brand is product_brand only — no Brands category').not.toContain('brands');
    const topLevel = cats.filter((c) => c.parent === 0).map((c) => c.slug);
    expect(topLevel).toEqual(expect.arrayContaining(['wine', 'spirits']));

    // The Shop's category filter mirrors the tree.
    await page.goto('/shop/');
    const filters = page.getByRole('dialog', { name: /product filters/i });
    await expect(filters.getByRole('checkbox', { name: 'Wine', exact: true })).toBeAttached();
    await expect(filters.getByRole('checkbox', { name: 'Spirits', exact: true })).toBeAttached();
    await expect(filters.getByRole('checkbox', { name: /^brands$/i })).toHaveCount(0);

    // Every populated category archive responds (serial — dev rate-limits bursts).
    const failures: string[] = [];
    for (const c of cats.filter((c) => c.count > 0)) {
      const res = await request.get(c.permalink);
      if (res.status() >= 400) failures.push(`${res.status()} ${c.permalink}`);
    }
    expect(failures).toEqual([]);

    // And a category archive renders its products.
    const wine = cats.find((c) => c.slug === 'wine')!;
    await page.goto(wine.permalink);
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    await expect(page.getByRole('button', { name: /add to cart/i }).first()).toBeVisible();
  });

  // CAT-TC2 — brand archives list their products
  test('CAT-TC2 brand archives list that brand’s products', async ({ page, request }) => {
    const res = await request.get('/wp-json/wc/store/v1/products/brands?per_page=100');
    expect(res.ok()).toBeTruthy();
    const brands: { name: string; slug: string; count: number }[] = await res.json();
    const sample = brands.filter((b) => b.count > 0).slice(0, 5);
    expect(sample.length).toBeGreaterThan(0);

    for (const b of sample) {
      await page.goto(`/brand/${b.slug}/`);
      await expect(page.getByRole('heading', { level: 1 }), b.slug).toBeVisible();
      await expect(page.getByRole('button', { name: /add to cart/i }).first(), b.slug).toBeVisible();
    }
  });

  // CAT-TC3 — filter sections fold, and empty sections don't render
  test('CAT-TC3 shop filter sections expand and collapse, and none render empty', async ({ page }) => {
    await page.goto('/shop/');
    const filters = page.getByRole('dialog', { name: /product filters/i });
    const brand = filters.getByRole('button', { name: /^brand$/i });
    await expect(brand).toHaveAttribute('aria-expanded', /true|false/);

    const before = await brand.getAttribute('aria-expanded');
    await brand.click();
    await expect(brand).toHaveAttribute('aria-expanded', before === 'true' ? 'false' : 'true');
    await brand.click();
    await expect(brand).toHaveAttribute('aria-expanded', before!);

    // WooCommerce marks filters with nothing to offer as hidden; the theme's
    // collapsible rules must not bring them back.
    await expect(page.locator('.wc-block-product-filter--hidden').filter({ visible: true })).toHaveCount(0);
  });

  // CAT-TC4 — 4-up grid at desktop, single-line Add to cart
  test('CAT-TC4 the shop grid is 4-up at 1440 and Add to cart stays on one line', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/shop/');
    const cards = page.locator('.wp-block-woocommerce-product-template > li');
    await expect(cards.nth(4)).toBeVisible();

    const tops = await Promise.all([0, 1, 2, 3, 4].map(async (i) => (await cards.nth(i).boundingBox())!.y));
    for (let i = 1; i < 4; i++) expect(Math.abs(tops[i] - tops[0]), `card ${i + 1} on row 1`).toBeLessThanOrEqual(2);
    expect(tops[4], 'card 5 starts row 2').toBeGreaterThan(tops[0] + 10);

    // Each label renders as a single line box.
    const lines = await cards
      .getByRole('button', { name: /add to cart/i })
      .evaluateAll((buttons) =>
        buttons.slice(0, 8).map((b) => {
          const range = document.createRange();
          range.selectNodeContents(b);
          const rows = new Set([...range.getClientRects()].filter((r) => r.width > 0).map((r) => Math.round(r.top)));
          return rows.size;
        }),
      );
    expect(lines.every((n) => n === 1), `line counts ${lines}`).toBeTruthy();
  });

  // CAT-TC5 — shop archives carry the promo bar and a sticky header
  for (const path of ['/shop/', '/product-category/wine/', `/brand/${BRAND.slug}/`]) {
    test(`CAT-TC5 ${path} shows the promo bar and keeps the header on screen when scrolled`, async ({ page }) => {
      test.fixme(path.startsWith('/brand/'), 'Brand pages are being redesigned (Nov 2026); re-enable after the update.');
      await page.setViewportSize({ width: 1440, height: 900 });
      await page.goto(path);
      await expect(page.locator('.is-style-header-promo-bar').first()).toBeVisible();

      const nav = page.getByRole('navigation', { name: 'Navigation', exact: true }).first();
      await expect(nav).toBeVisible();
      await page.mouse.wheel(0, 2000);
      await expect.poll(() => page.evaluate(() => window.scrollY)).toBeGreaterThan(1000);
      await expect(nav).toBeInViewport();
    });
  }
});
