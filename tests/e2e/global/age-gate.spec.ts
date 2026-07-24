import { freshTest as test, expect } from '../fixtures';

/**
 * AGE-01 — Age-gate popup appears for a new visitor and gates content until
 *          acknowledged; the choice persists via the `age_gate` cookie.
 * Pack: reports/test-packs/full-site-2026-07-24.md
 *
 * Uses `freshTest` (no age-gate cookie seeded) so the gate is observable. All other
 * suites seed `age_gate=18` in fixtures to bypass it.
 */
test.describe('Age gate @global @age', () => {
  test.beforeEach(async ({ context }) => {
    await context.clearCookies(); // ensure a genuine first-visit state
  });

  // AGE-TC1 — gate appears, then acknowledging reveals content
  test('AGE-TC1 shows the age gate to a new visitor and lets an adult through', async ({ page }) => {
    await page.goto('/');

    // The Age Gate overlay is present for a cookieless visitor.
    const gate = page.locator('.age-gate, .age_gate, [id*="age-gate"], [class*="age-gate"]').first();
    await expect(gate).toBeVisible({ timeout: 20_000 });

    // Confirm being of age (button/link labelled yes / enter / 18+).
    await page
      .getByRole('button', { name: /yes|enter|i am|18|over/i })
      .or(page.getByRole('link', { name: /yes|enter|i am|18|over/i }))
      .first()
      .click();

    // The gate is dismissed and content is accessible.
    await expect(gate).toBeHidden();
    await expect(page.getByRole('navigation', { name: /navigation/i }).first()).toBeVisible();
  });

  // AGE-TC2 — the acknowledgement persists (no gate on next navigation)
  test('AGE-TC2 does not re-prompt once acknowledged', async ({ page }) => {
    await page.goto('/');
    await page
      .getByRole('button', { name: /yes|enter|i am|18|over/i })
      .or(page.getByRole('link', { name: /yes|enter|i am|18|over/i }))
      .first()
      .click();

    await page.goto('/shop/');
    const gate = page.locator('.age-gate, .age_gate, [id*="age-gate"], [class*="age-gate"]').first();
    await expect(gate).toBeHidden();
  });
});
