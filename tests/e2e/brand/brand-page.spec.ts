import { test, expect, waitForStoreApi } from '../fixtures';
import type { Locator, Page } from '@playwright/test';

/**
 * BRAND — redesigned brand page (/brand/…), Estimate 3439.
 *
 * Template: templates/taxonomy-product_brand.html → patterns/woo-brand-archive.php
 * Slide:    parts/brand-product-slide.html, slider: assets/js/brand-slider.js
 * Content:  KWV Enhancements → modules/brands.php (Brand Page Content fields)
 *
 * Two brands drive the run (override per environment):
 *   KWV_BRAND_SLUG        a brand WITH content (intro, awards, socials) and 2+ products.
 *   KWV_EMPTY_BRAND_SLUG  a brand with NO brand content entered (any product count).
 *
 * Read-only apart from BRAND-TC7, which adds one product to a fresh guest cart.
 */
const BRAND = process.env.KWV_BRAND_SLUG ?? 'kwv-brandy';
const EMPTY_BRAND = process.env.KWV_EMPTY_BRAND_SLUG ?? 'wild-africa-cream';

const slider = (page: Page) => page.locator('.kwv-brand-slider');
const slides = (page: Page) => page.locator('.kwv-brand-slider .wc-block-product');
const thumbs = (page: Page) => page.locator('.kwv-brand-slider__thumb');

/** The slide currently shown (the only one that is not inert). */
const activeSlide = (page: Page) => page.locator('.kwv-brand-slider .wc-block-product:not([inert])');

async function openBrand(page: Page, slug: string): Promise<void> {
  await page.goto(`/brand/${slug}/`);
  await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
}

/** Wait for brand-slider.js to have turned the list into a slider. */
async function waitForSlider(page: Page): Promise<void> {
  await expect(slider(page)).toHaveClass(/swiper-initialized/);
}

async function activeIndex(page: Page): Promise<number> {
  return page.locator('.kwv-brand-slider .wc-block-product').evaluateAll((items) =>
    items.findIndex((item) => !(item as HTMLElement).inert),
  );
}

async function box(locator: Locator) {
  const b = await locator.boundingBox();
  expect(b, 'element has a layout box').not.toBeNull();
  return b!;
}

test.describe('Brand page @brand', () => {
  // BRAND-TC1 — layout: breadcrumbs under the hero, no shop-archive chrome.
  test('BRAND-TC1 shows the banner and breadcrumbs, without filters, results count or pagination', async ({ page }) => {
    await openBrand(page, BRAND);

    const breadcrumbs = page.locator('.woocommerce-breadcrumb');
    await expect(breadcrumbs).toBeVisible();
    await expect(breadcrumbs.getByRole('link', { name: /home/i })).toBeVisible();

    await expect(page.locator('.kwv-shop-filters')).toHaveCount(0);
    await expect(page.locator('.wp-block-woocommerce-product-results-count')).toHaveCount(0);
    await expect(page.locator('.wp-block-query-pagination')).toHaveCount(0);
  });

  // BRAND-TC2 — brand content from the Brand's fields.
  test('BRAND-TC2 shows the brand introduction, awards and social links entered on the brand', async ({ page }) => {
    await openBrand(page, BRAND);

    const intro = page.locator('.kwv-brand-intro');
    await expect(intro).toBeVisible();
    await expect(intro.locator('p').first()).not.toBeEmpty();

    const awards = page.locator('.kwv-brand-awards');
    await expect(awards).toHaveAttribute('role', 'group');
    const awardImages = awards.locator('img');
    expect(await awardImages.count()).toBeGreaterThan(0);
    expect(await awardImages.count()).toBeLessThanOrEqual(8);

    // Every social icon that renders has a real https link that opens in a new tab.
    const socials = page.locator('.kwv-brand-links .wp-social-link a');
    expect(await socials.count()).toBeGreaterThan(0);
    for (const link of await socials.all()) {
      await expect(link).toHaveAttribute('href', /^https:\/\//);
      await expect(link).toHaveAttribute('target', '_blank');
    }
  });

  // BRAND-TC3 — a blank field is not shown; an empty intro takes its divider with it.
  test('BRAND-TC3 a brand with no content hides the intro section, awards, socials and website link', async ({ page }) => {
    await openBrand(page, EMPTY_BRAND);

    await expect(page.locator('.kwv-brand-intro')).toBeHidden();
    await expect(page.locator('.kwv-brand-awards')).toHaveCount(0);
    await expect(page.locator('.kwv-brand-links .wp-social-link')).toHaveCount(0);
    await expect(page.locator('.kwv-brand-links .is-style-text-link')).toHaveCount(0);
    // The editor placeholder copy never reaches the front end.
    await expect(page.getByText(/is entered on the Brand under/i)).toHaveCount(0);
  });

  // BRAND-TC4 — slider: arrows, keyboard, thumbnails, one product reachable at a time.
  test('BRAND-TC4 the slider shows one product at a time and moves by arrow, key and thumbnail', async ({ page }) => {
    await openBrand(page, BRAND);
    await waitForSlider(page);

    const count = await slides(page).count();
    test.skip(count < 2, `${BRAND} needs at least two products for the slider`);

    await expect(thumbs(page)).toHaveCount(count);
    await expect(activeSlide(page)).toHaveCount(1);
    expect(await activeIndex(page)).toBe(0);
    await expect(thumbs(page).first()).toHaveAttribute('aria-current', 'true');

    await page.getByRole('button', { name: /next product/i }).click();
    await expect.poll(() => activeIndex(page)).toBe(1);

    await page.getByRole('button', { name: /previous product/i }).click();
    await expect.poll(() => activeIndex(page)).toBe(0);

    await slider(page).scrollIntoViewIfNeeded();
    await page.keyboard.press('ArrowRight');
    await expect.poll(() => activeIndex(page)).toBe(1);

    // Thumbnails work by pointer and by keyboard.
    const last = count - 1;
    await thumbs(page).nth(last).click();
    await expect.poll(() => activeIndex(page)).toBe(last);
    await expect(thumbs(page).nth(last)).toHaveAttribute('aria-current', 'true');

    await thumbs(page).first().focus();
    await page.keyboard.press('Enter');
    await expect.poll(() => activeIndex(page)).toBe(0);
  });

  // BRAND-TC5 — slide content and order: price above Add to Cart.
  test('BRAND-TC5 each slide shows the brand and product name, price above Add to Cart, and a View product link', async ({ page }) => {
    await openBrand(page, BRAND);
    await waitForSlider(page);

    const slide = activeSlide(page);
    const brandName = (await page.getByRole('heading', { level: 1 }).textContent())?.trim() ?? '';
    await expect(slide.locator('.kwv-brand-slide__content p').first()).toHaveText(new RegExp(brandName, 'i'));
    await expect(slide.getByRole('heading', { level: 2 })).not.toBeEmpty();

    const price = slide.locator('.wc-block-components-product-price');
    await expect(price).toContainText(/R\s?\d/);

    const viewProduct = slide.getByRole('link', { name: /view product/i });
    await expect(viewProduct).toHaveAttribute('href', /\/product\//);

    const button = slide.locator('.wc-block-components-product-button .wp-block-button__link');
    if (await button.isVisible()) {
      const priceBox = await box(price);
      const buttonBox = await box(button);
      expect(priceBox.y + priceBox.height, 'price sits above Add to Cart').toBeLessThanOrEqual(buttonBox.y + 1);
    }
  });

  // BRAND-TC6 — pasted descriptions (e.g. a fixed-width table) stay inside their slide.
  test('BRAND-TC6 no product description overflows its slide', async ({ page }) => {
    await openBrand(page, BRAND);
    await waitForSlider(page);

    const overflowing = await page.locator('.kwv-brand-slide .wc-block-components-product-summary').evaluateAll((nodes) =>
      nodes
        .filter((n) => n.scrollWidth > n.clientWidth + 1 || n.querySelector('[style], [width]'))
        .map((n) => n.closest('.wc-block-product')?.querySelector('.wp-block-post-title')?.textContent?.trim()),
    );
    expect(overflowing, 'descriptions wider than their column or still carrying inline styles').toEqual([]);
  });

  // BRAND-TC7 — Add to Cart from a slide updates the mini-cart.
  test('BRAND-TC7 Add to Cart on a slide adds the product and updates the mini-cart count', async ({ page }) => {
    await openBrand(page, BRAND);
    await waitForSlider(page);

    // First slide that can be bought here (in stock, simple product).
    const index = await slides(page).evaluateAll((items) =>
      items.findIndex((i) => i.classList.contains('instock') && i.classList.contains('product-type-simple')),
    );
    test.skip(index < 0, `${BRAND} has no in-stock simple product`);
    if (index > 0) {
      await thumbs(page).nth(index).click();
      await expect.poll(() => activeIndex(page)).toBe(index);
    }

    const badge = page.locator('.wc-block-mini-cart__badge').first();
    const before = Number((await badge.textContent().catch(() => '0'))?.trim() || 0);

    await activeSlide(page).getByRole('button', { name: /add to cart/i }).click();
    await waitForStoreApi(page);
    await expect.poll(async () => Number((await badge.textContent())?.trim() || 0)).toBeGreaterThan(before);
  });

  // BRAND-TC8 — only out-of-stock / non-simple products lose Add to Cart.
  test('BRAND-TC8 out-of-stock products show "Out of stock" instead of Add to Cart; in-stock products show no stock text', async ({ page }) => {
    await openBrand(page, BRAND);
    await waitForSlider(page);

    const states = await slides(page).evaluateAll((items) =>
      items.map((i) => {
        const button = i.querySelector('.wc-block-components-product-button');
        const stock = i.querySelector('.wc-block-components-product-stock-indicator');
        return {
          outOfStock: i.classList.contains('outofstock'),
          simple: i.classList.contains('product-type-simple'),
          buttonShown: !!button && getComputedStyle(button).display !== 'none',
          stockShown: !!stock && getComputedStyle(stock).display !== 'none',
          stockText: stock?.textContent?.trim() ?? '',
        };
      }),
    );

    for (const s of states) {
      if (s.outOfStock) {
        expect(s.buttonShown).toBe(false);
        expect(s.stockText).toMatch(/out of stock/i);
      } else if (s.simple) {
        expect(s.buttonShown).toBe(true);
        expect(s.stockShown).toBe(false);
      } else {
        expect(s.buttonShown).toBe(false);
      }
    }
  });
});

test.describe('Brand page — phone @brand @responsive', () => {
  test.use({ viewport: { width: 390, height: 844 } });

  // BRAND-TC9 — single column, image above details, no sideways scroll.
  test('BRAND-TC9 stacks the slide (image above details) without horizontal scrolling', async ({ page }) => {
    await openBrand(page, BRAND);
    await waitForSlider(page);

    const slide = activeSlide(page);
    const media = await box(slide.locator('.kwv-brand-slide__media'));
    const content = await box(slide.locator('.kwv-brand-slide__content'));
    expect(media.y + media.height).toBeLessThanOrEqual(content.y + 1);

    const overflow = await page.evaluate(() => document.documentElement.scrollWidth - window.innerWidth);
    expect(overflow).toBeLessThanOrEqual(0);
  });
});

test.describe('Brand page — without JavaScript @brand', () => {
  test.use({ javaScriptEnabled: false });

  // BRAND-TC10 — progressive enhancement: every product is reachable as a list.
  test('BRAND-TC10 lists every product when the slider cannot start', async ({ page }) => {
    await openBrand(page, BRAND);

    const items = slides(page);
    const count = await items.count();
    expect(count).toBeGreaterThan(0);
    for (const item of await items.all()) {
      await expect(item).toBeVisible();
    }
    await expect(page.locator('.kwv-brand-slider__arrow')).toHaveCount(0);
  });
});
