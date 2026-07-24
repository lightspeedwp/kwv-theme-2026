import { test, expect } from '../fixtures';

/**
 * ERR-01 — 404 template renders for an unknown URL.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 */
test.describe('Errors — 404 @errors', () => {
  // ERR-TC1
  test('ERR-TC1 unknown URL returns 404 and renders the template', async ({ page }) => {
    const response = await page.goto('/this-page-does-not-exist-e2e-check/');
    expect(response?.status()).toBe(404);

    // A styled 404 template renders (message present, site chrome intact).
    await expect(page.getByText(/404|not found|can’t be found|cannot be found/i).first()).toBeVisible();
    await expect(page.getByRole('contentinfo')).toBeVisible(); // footer still renders
  });
});
