import AxeBuilder from '@axe-core/playwright';
import { test, expect, A11Y_TARGETS, newA11yViolations } from '../fixtures';

/**
 * A11Y-01 — automated accessibility scan of the main templates (WCAG 2.x A/AA
 * rules via axe-core), at desktop and phone width. Fails on serious or critical
 * violations that the reviewed baseline in fixtures.ts (A11Y_BASELINE) does not
 * already list, so new violations fail while known ones are worked off.
 *
 * Record a baseline: KWV_A11Y_RECORD=1 npx playwright test quality/a11y --project=chromium
 * prints every serious/critical rule per page as a ready-to-paste entry.
 *
 * Read-only: scans rendered pages and opens the Careers modal; never submits.
 */
const WIDTHS = [
  { name: 'desktop' as const, width: 1440, height: 900 },
  { name: 'phone' as const, width: 390, height: 844 },
];
const RECORD = process.env.KWV_A11Y_RECORD === '1';

test.describe('Accessibility @a11y', () => {
  for (const target of A11Y_TARGETS) {
    for (const vp of WIDTHS) {
      test(`A11Y-TC1 ${target.label} has no new serious/critical violations (${vp.name})`, async ({ page, request }) => {
        await page.setViewportSize({ width: vp.width, height: vp.height });
        const path = typeof target.path === 'string' ? target.path : await target.path(request);
        test.skip(!path, `No ${target.label} content on this environment.`);

        const res = await page.goto(path!);
        // Page existence is SMK-TC1's job; a page missing here is a content gap, not an a11y result.
        test.skip(res?.status() === 404, `${path} does not exist on this environment.`);
        expect(res?.status(), `${path} status`).toBeLessThan(400);
        await page.waitForLoadState('load');
        if (target.prepare) {
          test.skip(!(await target.prepare(page)), `Nothing to open for ${target.label} on this environment.`);
        }

        const results = await new AxeBuilder({ page })
          .withTags(['wcag2a', 'wcag2aa', 'wcag21a', 'wcag21aa', 'wcag22aa'])
          .analyze();
        const serious = results.violations.filter((v) => v.impact === 'serious' || v.impact === 'critical');
        const ids = [...new Set(serious.map((v) => v.id))].sort();

        if (RECORD) {
          console.log(`A11Y_BASELINE ${target.key}.${vp.name}: ${JSON.stringify(ids)}`);
          for (const v of serious) {
            console.log(`  ${v.id} (${v.impact}, ${v.nodes.length} nodes): ${v.nodes[0]?.target.join(' ')}`);
          }
          return;
        }

        const fresh = newA11yViolations(ids, target.key, vp.name);
        const detail = serious
          .filter((v) => fresh.includes(v.id))
          .map((v) => `${v.id} (${v.impact}) ${v.help}: ${v.nodes.slice(0, 3).map((n) => n.target.join(' ')).join(' | ')}`);
        expect(detail, `New serious/critical a11y violations on ${path}`).toEqual([]);
      });
    }
  }
});
