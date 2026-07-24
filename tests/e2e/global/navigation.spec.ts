import { test, expect } from '../fixtures';

/**
 * NAV-01 — Header nav with Shop/Visit Us/About mega menus; mobile menu on small viewports.
 * NAV-02 — Footer sections + newsletter + PDF/policy links.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 */
test.describe('Global navigation @global', () => {
  // NAV-TC1 — header nav + mega menus
  test('NAV-TC1 header shows primary nav and mega-menu triggers', async ({ page }) => {
    await page.goto('/');
    const nav = page.getByRole('navigation', { name: /navigation/i }).first();
    await expect(nav).toBeVisible();

    // Primary items present with resolvable links.
    await expect(nav.getByRole('link', { name: /^shop$/i })).toHaveAttribute('href', /shop/);
    await expect(nav.getByRole('link', { name: /about/i }).first()).toBeVisible();
    await expect(nav.getByRole('link', { name: /visit/i }).first()).toBeVisible();

    // Opening the Shop mega menu reveals additional links.
    await nav.getByRole('link', { name: /^shop$/i }).hover();
    await expect(page.getByRole('link', { name: /brand|wine|spirits|shop all/i }).first()).toBeVisible();
  });

  // NAV-TC2 — mobile menu toggles
  test('NAV-TC2 mobile menu opens on a small viewport', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    const toggle = page.getByRole('button', { name: /menu|open menu|navigation/i }).first();
    await expect(toggle).toBeVisible();
    await toggle.click();
    await expect(page.getByRole('link', { name: /shop/i }).first()).toBeVisible();
  });

  // NAV-TC3 — footer
  test('NAV-TC3 footer renders sections, newsletter, and policy/PDF links', async ({ page }) => {
    await page.goto('/');
    const footer = page.getByRole('contentinfo');
    await expect(footer).toBeVisible();

    await expect(footer.getByRole('heading', { name: /corporate links/i })).toBeVisible();
    await expect(footer.getByRole('heading', { name: /contact us/i })).toBeVisible();
    await expect(footer.getByRole('textbox', { name: /email/i })).toBeVisible();
    await expect(footer.getByRole('link', { name: /privacy policy/i })).toBeVisible();
    await expect(footer.getByRole('link', { name: /paia/i })).toBeVisible();
  });
});
