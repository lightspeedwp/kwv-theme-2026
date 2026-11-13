import { test, expect } from '../fixtures';

/**
 * CONT-01 — About, About/History, FAQs render their content.
 * CONT-02 — Homepage renders with (placeholder) sliders and key sections.
 * HIST-TC1, HOME-TC1, NEWS-TC1 — History timeline, homepage brand carousels, News.
 * Packs: reports/test-packs/full-site-2026-07-24.md, test-packs/launch-coverage-2026-10-22.md
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

  // HIST-TC1 — History timeline: newest first, milestone image opens the core lightbox
  test('HIST-TC1 the History timeline runs newest first and images open in the lightbox', async ({ page }) => {
    await page.goto('/about-us/history/');
    const slides = page.locator('.swiper-slide').filter({ has: page.getByRole('heading', { level: 3 }) });
    await expect(slides.first()).toBeVisible();

    const years = (await slides.getByRole('heading', { level: 3 }).allInnerTexts())
      .map((t) => parseInt(t, 10))
      .filter((n) => !Number.isNaN(n));
    expect(years.length).toBeGreaterThan(5);
    for (let i = 1; i < years.length; i++) {
      expect(years[i], `slide ${i + 1} (${years[i]}) is not newer than slide ${i}`).toBeLessThanOrEqual(years[i - 1]);
    }

    // A plain <img> locator: milestone images may have alt="" (role "presentation"),
    // which getByRole('img') skips, and then picked up an element with no src.
    const firstImage = slides.first().locator('img').first();
    const src = await firstImage.getAttribute('src');
    await slides.first().getByRole('button', { name: /enlarge/i }).click();
    const lightbox = page.getByRole('dialog').filter({ visible: true });
    await expect(lightbox).toBeVisible();
    const file = src!.split('/').pop()!.replace(/(-\d+x\d+)?\.\w+$/, '');
    await expect(lightbox.locator(`img[src*="${file}"]`).first()).toBeVisible();
    await lightbox.getByRole('button', { name: /close/i }).click();
    await expect(lightbox).toBeHidden();
  });

  // HOME-TC1 — homepage brand carousels link to live brand pages; legacy video has a poster
  test('HOME-TC1 homepage brand carousels link to brand pages that resolve', async ({ page, request }) => {
    await page.goto('/');
    for (const heading of [/^wine brands$/i, /^spirits brands$/i, /^agency brands$/i]) {
      await expect(page.getByRole('heading', { name: heading })).toBeVisible();
    }
    const hrefs = [
      ...new Set(
        await page
          .locator('.kwv-brand-marquee a[href], .swiper-slide a[href*="/brand/"]')
          .evaluateAll((as) => as.map((a) => (a as HTMLAnchorElement).href)),
      ),
    ];
    expect(hrefs.length, 'brand links in the carousels').toBeGreaterThan(10);
    const broken: string[] = [];
    for (const href of hrefs) {
      const status = (await request.get(href)).status();
      if (status >= 400) broken.push(`${status} ${href}`);
    }
    expect(broken).toEqual([]);

    // The "Our Legacy" video is lazy: no src until near the viewport, but a poster.
    const video = page.locator('video[data-kwv-lazy-video]');
    await expect(video).toHaveAttribute('poster', /\.(png|jpe?g|webp)/);
  });

  // NEWS-TC1 — News list → single post → category archive
  test('NEWS-TC1 news list opens a post and its category archive', async ({ page }) => {
    await page.goto('/news/');
    const first = page.getByRole('listitem').getByRole('heading', { level: 2 }).getByRole('link').first();
    const title = (await first.innerText()).trim();
    await first.click();
    await expect(page.getByRole('heading', { level: 1 })).toContainText(title, { ignoreCase: true });

    // Categories (news years) are reached from the year filter on /news/: neither the
    // single-post template nor the list cards show category links, so this used to skip.
    await page.goto('/news/');
    const category = page.locator('a[href*="/category/"]').filter({ visible: true }).first();
    await expect(category, 'year filter link on /news/').toBeVisible();
    await category.click();
    await expect(page).toHaveURL(/\/category\//);
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    await expect(page.getByRole('listitem').getByRole('heading', { level: 2 }).first()).toBeVisible();
  });
});
