import { test, expect, PRODUCT, BRAND, enquiryForm } from '../fixtures';

/**
 * RESP-01 — Key pages display and function on tablet and mobile viewports via
 *           responsive adaptation of the desktop designs (PRD Non-Goal: no bespoke
 *           breakpoint designs — so we assert usability, not pixel-specific layouts).
 * Packs: reports/test-packs/full-site-2026-07-24.md, test-packs/launch-coverage-2026-10-22.md
 *
 * RESP-TC2 cases are tagged @mobile and run in the `mobile-chrome` project
 * (Pixel 7: touch, mobile UA) rather than a resized desktop browser.
 */
const VIEWPORTS = [
  { name: 'mobile', width: 390, height: 844 },
  { name: 'tablet', width: 768, height: 1024 },
];
const PAGES = [
  '/',
  '/shop/',
  `/product/${PRODUCT.slug}/`,
  '/checkout/',
  '/visit/',
  '/wineclub/',
  '/about-us/history/',
  '/careers/',
  '/product-category/wine/',
  `/brand/${BRAND.slug}/`,
];

for (const vp of VIEWPORTS) {
  test.describe(`Responsive @responsive ${vp.name}`, () => {
    test.use({ viewport: { width: vp.width, height: vp.height } });

    for (const path of PAGES) {
      // RESP-TC1
      test(`RESP-TC1 ${path} has no horizontal overflow and renders chrome (${vp.name})`, async ({ page }) => {
        await page.goto(path);
        await expect(page.getByRole('contentinfo')).toBeVisible();

        // No horizontal overflow: the document is not wider than the viewport.
        const overflow = await page.evaluate(
          () => document.documentElement.scrollWidth - document.documentElement.clientWidth,
        );
        expect(overflow, `horizontal overflow on ${path}`).toBeLessThanOrEqual(2);
      });
    }
  });
}

// RESP-TC2 — key interactions on a phone
test.describe('Responsive interactions @responsive @mobile', () => {
  test('RESP-TC2 Visit carousel arrows sit below the slide and advance it', async ({ page }) => {
    await page.goto('/visit/');
    const carousel = page.locator('.kwv-venue-carousel').first();
    const active = carousel.locator('.swiper-slide-active');
    const next = carousel.getByRole('button', { name: /next slide/i });
    await next.scrollIntoViewIfNeeded();

    const slide = (await active.boundingBox())!;
    const arrow = (await next.boundingBox())!;
    expect(arrow.y, 'arrows are below the stacked slide').toBeGreaterThanOrEqual(slide.y + slide.height - 1);

    await next.tap();
    await expect(active).toHaveAttribute('aria-label', /^2 \//);
  });

  test('RESP-TC2 booking form fields fit the screen and accept input', async ({ page }) => {
    await page.goto('/visit/kwv/');
    const form = enquiryForm(page);
    const width = page.viewportSize()!.width;
    for (const field of await form.locator('input:visible, textarea:visible').all()) {
      const box = (await field.boundingBox())!;
      expect(box.x).toBeGreaterThanOrEqual(0);
      expect(box.x + box.width).toBeLessThanOrEqual(width + 1);
    }
    await form.getByRole('textbox', { name: /^name/i }).fill('E2E');
    await expect(form.getByRole('textbox', { name: /^name/i })).toHaveValue('E2E');
  });

  test('RESP-TC2 careers modal fits the screen', async ({ page }) => {
    await page.goto('/careers/');
    const readMore = page.locator('.is-style-career-card a.wp-block-read-more').first();
    test.skip((await readMore.count()) === 0, 'No jobs currently published on the Careers CPT.');
    await readMore.tap();
    const dialog = page.getByRole('dialog');
    await expect(dialog).toBeVisible();
    const box = (await dialog.boundingBox())!;
    expect(box.x).toBeGreaterThanOrEqual(0);
    expect(box.x + box.width).toBeLessThanOrEqual(page.viewportSize()!.width + 1);
    await expect(dialog.getByRole('heading', { name: /apply for this position/i })).toBeAttached();
  });

  test('RESP-TC2 shop filter overlay opens and closes', async ({ page }) => {
    await page.goto('/shop/');
    await page.getByRole('button', { name: /filter products/i }).tap();
    const filters = page.getByRole('dialog', { name: /product filters/i });
    await expect(filters.getByRole('button', { name: /^brand$/i })).toBeInViewport();
    await filters.getByRole('button', { name: /^close$/i }).tap();
    await expect(filters.getByRole('button', { name: /^brand$/i })).not.toBeInViewport();
  });
});
