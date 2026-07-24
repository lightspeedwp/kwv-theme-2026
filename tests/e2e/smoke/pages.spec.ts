import { test, expect } from '../fixtures';

/**
 * SMK-01 — Every scoped template/page returns 200 and renders its primary content.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 */
const PAGES: { path: string; label: string }[] = [
  { path: '/', label: 'Homepage' },
  { path: '/shop/', label: 'Shop' },
  { path: '/product/kwv-classic-chardonnay-2025/', label: 'Single product' },
  { path: '/cart/', label: 'Cart' },
  { path: '/checkout/', label: 'Checkout' },
  { path: '/my-account/', label: 'Account/Login' },
  { path: '/about-us/', label: 'About' },
  { path: '/about-us/history/', label: 'About/History' },
  { path: '/faq/', label: 'FAQs' },
  { path: '/contact/', label: 'Contact Us' },
  { path: '/wineclub/', label: 'Wine Club' },
  { path: '/visit/', label: 'Visit Us' },
  { path: '/careers/', label: 'Careers' },
  { path: '/news/', label: 'News' },
  { path: '/brand/the-mentors/', label: 'Single Brand' },
];

test.describe('Smoke — scoped pages render @smoke', () => {
  for (const { path, label } of PAGES) {
    // SMK-TC1
    test(`SMK-TC1 ${label} (${path}) returns 200 and renders`, async ({ page }) => {
      const response = await page.goto(path);
      expect(response, `no response for ${path}`).not.toBeNull();
      expect(response!.status(), `${path} status`).toBeLessThan(400);

      // Page rendered fully: a heading and the footer chrome are present.
      // (Some pages render from DB templates without a <main> landmark, so we
      // assert on heading + footer rather than requiring `main`.)
      await expect(page.getByRole('heading').first()).toBeVisible();
      await expect(page.getByRole('contentinfo')).toBeVisible();
    });
  }
});
