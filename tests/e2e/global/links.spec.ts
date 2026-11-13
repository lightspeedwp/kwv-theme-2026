import { test, expect, SCOPED_PAGES } from '../fixtures';

/**
 * LINK-TC1 — No broken internal links on the scoped pages.
 * Pack: test-packs/launch-coverage-2026-10-22.md
 *
 * Collects every <a href> on the scoped pages (header, content, footer), dedupes,
 * then requests each same-origin URL once, serially (dev bans bursts). Internal
 * links must answer < 400. The site should have no external links, so any found
 * are reported as an annotation for review rather than failed.
 */
const SKIP = /^(mailto:|tel:|javascript:|#)|\/wp-admin\/|\/wp-login\.php|[?&](add-to-cart|kwv-join|remove_item|customer-logout)=|\/my-account\/customer-logout/;

test.describe('Links @links', () => {
  test.setTimeout(15 * 60_000);

  test('LINK-TC1 internal links on the scoped pages resolve', async ({ page, request, baseURL }) => {
    const origin = new URL(baseURL!).origin;
    const found = new Map<string, string>(); // url -> first page it was seen on

    for (const { path } of SCOPED_PAGES) {
      await page.goto(path);
      const hrefs = await page
        .locator('a[href]')
        .evaluateAll((as) => as.map((a) => (a as HTMLAnchorElement).getAttribute('href') ?? ''));
      for (const raw of hrefs) {
        if (!raw || SKIP.test(raw)) continue;
        const url = new URL(raw, origin + path);
        url.hash = '';
        if (!found.has(url.href)) found.set(url.href, path);
      }
    }

    const internal = [...found.keys()].filter((u) => new URL(u).origin === origin);
    const external = [...found.keys()].filter((u) => new URL(u).origin !== origin && /^https?:/.test(u));

    const broken: string[] = [];
    for (const url of internal) {
      const res = await request.get(url, { maxRedirects: 5, failOnStatusCode: false });
      if (res.status() >= 400) broken.push(`${res.status()} ${url} (on ${found.get(url)})`);
    }

    if (external.length) {
      test.info().annotations.push({
        type: 'external-links',
        description: external.map((u) => `${u} (on ${found.get(u)})`).join('\n'),
      });
    }
    expect(internal.length, 'links collected').toBeGreaterThan(50);
    expect(broken, `${broken.length} broken of ${internal.length} internal links`).toEqual([]);
  });
});
