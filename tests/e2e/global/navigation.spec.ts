import { test, expect } from '../fixtures';
import type { Page } from '@playwright/test';

/**
 * NAV-01 — Header nav with Shop/About mega menus; mobile menu on small viewports.
 * NAV-02 — Footer sections + newsletter + PDF/policy links + legal notice.
 * Packs: reports/test-packs/full-site-2026-07-24.md, test-packs/launch-coverage-2026-10-22.md
 *
 * Shop and About are Ollie mega menus that open on hover. Inside the Shop panel,
 * `assets/js/mega-menu-hover.js` marks the nav `has-hover-intent` and keeps the
 * hovered row `is-kwv-open` until another row in the same column is hovered, so a
 * fold-out column stays reachable when the pointer leaves its row.
 */
function megaMenuToggle(page: Page, name: RegExp) {
  return page
    .getByRole('navigation', { name: 'Navigation', exact: true })
    .locator('.wp-block-ollie-mega-menu__toggle')
    .filter({ hasText: name });
}

function openPanelNav(page: Page) {
  // The Shop panel holds several mega-menu navs (nested ones have no rows of their
  // own), so pick the visible nav whose container has the expandable rows.
  return page
    .locator('.is-style-mega-menu-nav')
    .filter({ visible: true })
    .filter({ has: page.locator('.wp-block-navigation__container > li.has-child') })
    .first();
}

test.describe('Global navigation @global', () => {
  // NAV-TC1 — Shop mega menu
  test('NAV-TC1 the Shop mega menu opens on hover and keeps a hovered column open', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/shop/');
    const shop = megaMenuToggle(page, /^\s*shop\s*$/i);
    await shop.hover();
    await expect(shop).toHaveAttribute('aria-expanded', 'true');

    const panel = openPanelNav(page);
    await expect(panel).toBeVisible();
    await expect(panel).toHaveClass(/has-hover-intent/);
    for (const name of [/^all wines$/i, /^all spirits$/i, /^lifestyle$/i, /^rare and limited$/i]) {
      await expect(panel.getByRole('link', { name }).first()).toBeVisible();
    }

    // Hovering a row opens its column and closes its sibling's.
    const rows = panel.locator('.wp-block-navigation__container > li.has-child');
    const wines = rows.filter({ has: page.getByRole('link', { name: /^all wines$/i }) });
    const spirits = rows.filter({ has: page.getByRole('link', { name: /^all spirits$/i }) });
    await spirits.getByRole('link', { name: /^all spirits$/i }).hover();
    await expect(spirits).toHaveClass(/is-kwv-open/);
    await expect(wines).not.toHaveClass(/is-kwv-open/);
    const spiritsColumn = spirits.locator(':scope > ul').first();
    await expect(spiritsColumn).toBeVisible();

    // Leaving the row for its column keeps the column open. Move sideways first, so
    // the pointer does not cross a sibling row (hovering a sibling closes the column
    // by design), then down into the column.
    const row = (await spirits.boundingBox())!;
    const col = (await spiritsColumn.boundingBox())!;
    await page.mouse.move(col.x + 8, row.y + row.height / 2, { steps: 5 });
    await page.mouse.move(col.x + 8, col.y + col.height - 4, { steps: 5 });
    await expect(spirits).toHaveClass(/is-kwv-open/);
    await expect(spiritsColumn).toBeVisible();

    // A link in the open column is reachable and navigates.
    const target = spiritsColumn.getByRole('link').first();
    const href = await target.getAttribute('href');
    await target.click();
    await expect(page).toHaveURL(href!);
  });

  // NAV-TC6 — About mega menu links resolve
  test('NAV-TC6 the About menu opens and its links resolve', async ({ page, request }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/');
    const about = megaMenuToggle(page, /^\s*about\s*$/i);
    await about.hover();
    await expect(about).toHaveAttribute('aria-expanded', 'true');

    // `has` is resolved inside each mega menu, so it must not start from the page's
    // header nav (which a mega menu never contains) — match the toggle alone.
    const panelLinks = page
      .locator('.wp-block-ollie-mega-menu')
      .filter({ has: page.locator('.wp-block-ollie-mega-menu__toggle').filter({ hasText: /^\s*about\s*$/i }) })
      .getByRole('link')
      .filter({ visible: true });
    await expect(panelLinks.first()).toBeVisible();
    const hrefs = [...new Set(await panelLinks.evaluateAll((as) => as.map((a) => (a as HTMLAnchorElement).href)))];
    for (const href of hrefs.filter((h) => h.startsWith('http'))) {
      expect((await request.get(href)).status(), href).toBeLessThan(400);
    }
  });

  // NAV-TC2 — mobile drawer on each header type
  const MOBILE_HEADERS = [
    { label: 'transparent header (home)', path: async () => '/' },
    { label: 'light header (shop)', path: async () => '/shop/' },
    {
      label: 'dark header (single post)',
      path: async (request: import('@playwright/test').APIRequestContext) => {
        const [post] = await (await request.get('/wp-json/wp/v2/posts?per_page=1&_fields=link')).json();
        return new URL(post.link).pathname;
      },
    },
  ];
  for (const h of MOBILE_HEADERS) {
    test(`NAV-TC2 mobile drawer opens, toggles submenus one at a time, and closes — ${h.label}`, async ({ page, request }) => {
      await page.setViewportSize({ width: 390, height: 844 });
      await page.goto(await h.path(request));

      const open = page.getByRole('button', { name: /open menu/i }).filter({ visible: true });
      await expect(open).toHaveCount(1);
      await open.click();

      const drawer = page.getByRole('dialog', { name: /menu/i }).filter({ visible: true });
      await expect(drawer).toBeVisible();
      const close = drawer.getByRole('button', { name: /close menu/i });
      await expect(close).toBeVisible();

      // Submenus start collapsed.
      const toggles = drawer.getByRole('button', { name: /submenu$/i }).filter({ visible: true });
      expect(await toggles.count()).toBeGreaterThan(1);
      for (const t of await toggles.all()) await expect(t).toHaveAttribute('aria-expanded', 'false');

      // Each toggle expands only its own submenu.
      const first = toggles.first();
      const second = toggles.nth(1);
      await first.click();
      await expect(first).toHaveAttribute('aria-expanded', 'true');
      await expect(second).toHaveAttribute('aria-expanded', 'false');
      const firstItem = first.locator('xpath=ancestor::li[1]');
      await expect(firstItem.getByRole('list').getByRole('link').first()).toBeVisible();

      await close.click();
      await expect(drawer).toBeHidden();
    });
  }

  // NAV-TC7 — transparent header overlays the hero
  for (const path of ['/', '/visit/', '/about-us/history/', '/careers/']) {
    test(`NAV-TC7 the transparent header overlays the hero on ${path}`, async ({ page }) => {
      await page.setViewportSize({ width: 1440, height: 900 });
      await page.goto(path);
      const header = page.locator('.is-style-header-transparent').filter({ visible: true }).first();
      await expect(header).toBeVisible();
      await expect(header.getByRole('link', { name: /^visit us$/i }).first()).toBeVisible();

      const hero = page.locator('.kwv-hero-carousel, .wp-block-cover').filter({ visible: true }).first();
      const hb = (await header.boundingBox())!;
      const cb = (await hero.boundingBox())!;
      expect(hb.y, 'header sits inside the hero, not above it').toBeGreaterThanOrEqual(cb.y - 1);
      expect(hb.y + hb.height).toBeLessThanOrEqual(cb.y + cb.height);
    });
  }

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

  // FTR-TC1 — legal drinking-age notice
  test('FTR-TC1 footer carries the legal drinking-age notice', async ({ page }) => {
    await page.goto('/');
    await expect(
      page.getByRole('contentinfo').getByText(/not intended for persons under the legal alcohol age limit/i),
    ).toBeVisible();
  });
});
