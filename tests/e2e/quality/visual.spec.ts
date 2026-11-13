import { test, expect, PRODUCT, visualMasks } from '../fixtures';

/**
 * VIS-01 — visual regression of the key templates with toHaveScreenshot.
 *
 * Baselines live next to this file in visual.spec.ts-snapshots/, one per
 * template, project and OS. They are environment-specific: record them against
 * the environment you compare with (default: the local Studio site).
 *
 *   Update after an intended design change:
 *     KWV_BASE_URL=http://localhost:8901 npx playwright test quality/visual --update-snapshots
 *   then review the changed PNGs in the diff before committing them.
 *
 * Dynamic regions (carousels, prices, dates, cart count, video) are masked, and
 * animations are frozen. Desktop runs in `chromium`; the @mobile cases run in the
 * phone projects.
 */
const TEMPLATES = [
  { name: 'home', path: '/' },
  { name: 'shop', path: '/shop/' },
  { name: 'product', path: `/product/${PRODUCT.slug}/` },
  { name: 'about', path: '/about-us/' },
  { name: 'careers', path: '/careers/' },
  { name: 'news', path: '/news/' },
  { name: 'faq', path: '/faq/' },
  { name: 'contact', path: '/contact/' },
];

async function snapshot(page: import('@playwright/test').Page, path: string, name: string) {
  const res = await page.goto(path);
  test.skip(res?.status() === 404, `${path} does not exist on this environment; no baseline to compare.`);
  await page.waitForLoadState('load');
  // Lazy images below the fold: scroll through once, then back to the top.
  await page.evaluate(async () => {
    for (let y = 0; y < document.body.scrollHeight; y += 600) {
      window.scrollTo(0, y);
      await new Promise((r) => setTimeout(r, 50));
    }
    window.scrollTo(0, 0);
    // Load every image (lazy ones switched to eager) and wait for them to
    // decode, or the last rows below the fold are captured half-painted.
    // decode() never settles on an unfetched lazy image, so only loaded images
    // are decoded and the whole wait is capped at 15s.
    document.querySelectorAll('img[loading="lazy"]').forEach((img) => ((img as HTMLImageElement).loading = 'eager'));
    const settled = Promise.all(
      [...document.images].map(async (img) => {
        if (!img.complete) {
          await new Promise((r) => {
            img.addEventListener('load', r, { once: true });
            img.addEventListener('error', r, { once: true });
          });
        }
        if (img.naturalWidth > 0) await img.decode().catch(() => {});
      }),
    );
    await Promise.race([settled, new Promise((r) => setTimeout(r, 15_000))]);
  });
  await expect(page).toHaveScreenshot(`${name}.png`, {
    fullPage: true,
    animations: 'disabled',
    caret: 'hide',
    mask: visualMasks(page),
    maxDiffPixelRatio: 0.01,
    timeout: 30_000,
  });
}

test.describe('Visual regression @visual', () => {
  for (const t of TEMPLATES) {
    test(`VIS-TC1 ${t.name} matches its baseline (desktop)`, async ({ page }) => {
      await page.setViewportSize({ width: 1440, height: 900 });
      await snapshot(page, t.path, `${t.name}-desktop`);
    });
  }
});

test.describe('Visual regression @visual @mobile', () => {
  for (const t of TEMPLATES) {
    test(`VIS-TC2 ${t.name} matches its baseline (phone)`, async ({ page }) => {
      await snapshot(page, t.path, `${t.name}-phone`);
    });
  }
});
