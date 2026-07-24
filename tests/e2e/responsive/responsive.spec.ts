import { test, expect } from '../fixtures';

/**
 * RESP-01 — Key pages display and function on tablet and mobile viewports via
 *           responsive adaptation of the desktop designs (PRD Non-Goal: no bespoke
 *           breakpoint designs — so we assert usability, not pixel-specific layouts).
 * Pack: reports/test-packs/full-site-2026-07-24.md
 */
const VIEWPORTS = [
  { name: 'mobile', width: 390, height: 844 },
  { name: 'tablet', width: 768, height: 1024 },
];
const PAGES = ['/', '/shop/', '/product/kwv-classic-chardonnay-2025/', '/checkout/'];

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
