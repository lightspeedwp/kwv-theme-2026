import { test, expect } from '../fixtures';

/**
 * NAV-03 — Search dropdown opens and submits to a results page.
 * NAV-04 — No-results search shows the styled message; filters remain available.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * Search is Advanced Woo Search (body-portal results dropdown) per project memory.
 */
test.describe('Global search @global', () => {
  // NAV-TC4 — search returns results
  test('NAV-TC4 search opens and returns matching products', async ({ page }) => {
    await page.goto('/');
    await page.getByRole('button', { name: /search/i }).first().click();

    const input = page.getByRole('searchbox').or(page.getByRole('textbox', { name: /search/i })).first();
    await expect(input).toBeVisible();
    await input.fill('brandy');
    await page.keyboard.press('Enter');

    // Either the AWS dropdown or a results page shows matches.
    await expect(
      page.getByRole('link', { name: /brandy/i }).first(),
    ).toBeVisible({ timeout: 20_000 });
  });

  // NAV-TC5 — no results
  test('NAV-TC5 a no-match query shows the no-results message', async ({ page }) => {
    await page.goto('/?s=zzzqqxnotaproduct&post_type=product');
    await expect(
      page.getByText(/no results|nothing found|no products|couldn’t find|could not find/i).first(),
    ).toBeVisible({ timeout: 20_000 });
  });
});
