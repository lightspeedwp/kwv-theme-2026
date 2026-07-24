import { test, expect } from '../fixtures';

/**
 * CONT-01 — About, About/History, FAQs render their content.
 * CONT-02 — Homepage renders with (placeholder) sliders and key sections.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 */

const CONTENT_PAGES = [
  { path: '/about-us/', label: 'About' },
  { path: '/about-us/history/', label: 'About/History' },
  { path: '/faq/', label: 'FAQs' },
];

test.describe('Content pages @content', () => {
  for (const { path, label } of CONTENT_PAGES) {
    // CONT-TC1
    test(`CONT-TC1 ${label} renders a heading and body content`, async ({ page }) => {
      await page.goto(path);
      await expect(page.getByRole('heading', { level: 1 }).first()).toBeVisible();
      // Body copy present: a paragraph of at least a few words of prose.
      await expect(
        page.locator('p').filter({ hasText: /\w+(\s+\w+){4,}/ }).first(),
      ).toBeVisible();
    });
  }

  // CONT-TC2 — Homepage template + sliders
  test('CONT-TC2 homepage renders hero/slider and key sections', async ({ page }) => {
    await page.goto('/');
    await expect(page.getByRole('heading', { name: /portfolio|discover/i }).first()).toBeVisible();
    // Newsletter subscribe section is part of the homepage/footer composition.
    await expect(page.getByRole('heading', { name: /newsletter/i })).toBeVisible();
  });
});
