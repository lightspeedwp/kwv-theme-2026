import { test, expect, BRAND } from '../fixtures';
import type { Locator, Page } from '@playwright/test';

/**
 * KBD-01 — keyboard-only journeys through the site's interactive components:
 * header mega menu, header search, mobile drawer, shop filters, brand slider and
 * the Careers job modal. Each case drives the page with the keyboard alone and
 * asserts where focus lands and which ARIA state changes.
 *
 * Read-only: nothing is submitted. Cases that need content the environment may
 * not have (menu rows, jobs, the brand slider) skip with a reason.
 */

/**
 * The key a keyboard user presses to move to the next link. Safari (WebKit) only
 * tabs through form controls by default; Option+Tab reaches links too.
 */
function tabKey(page: Page): string {
  return page.context().browser()?.browserType().name() === 'webkit' ? 'Alt+Tab' : 'Tab';
}

/**
 * Assert `target` is a Tab stop reached from the element before it: put focus
 * on it, step back one stop with Shift+Tab, then press Tab and expect it back.
 * Walking from the top of the page instead depends on how many links sit in
 * the header's mega menus (over 100 on dev), not on the component under test.
 */
async function tabTo(page: Page, target: Locator): Promise<void> {
  const key = tabKey(page);
  await target.focus();
  await page.keyboard.press(`Shift+${key}`);
  await expect(target, 'Shift+Tab moved focus off the target').not.toBeFocused();
  await page.keyboard.press(key);
  await expect(target, 'Tab from the previous stop lands on the target').toBeFocused();
}

/** Press Tab (forward only) until `target` has focus; for the header toggles and the open mega menu panel. */
async function tabFromTop(page: Page, target: Locator, max = 15): Promise<void> {
  for (let i = 0; i < max; i++) {
    if (await target.evaluate((el) => el === document.activeElement).catch(() => false)) return;
    await page.keyboard.press(tabKey(page));
  }
  await expect(target, `reached by Tab within ${max} presses`).toBeFocused();
}

test.describe('Keyboard journeys @keyboard', () => {
  test('KBD-TC1 the Shop mega menu opens on focus and Tab moves into the panel', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/shop/');
    const shop = page
      .getByRole('navigation', { name: 'Navigation', exact: true })
      .locator('.wp-block-ollie-mega-menu__toggle')
      .filter({ hasText: /^\s*shop\s*$/i });

    await tabFromTop(page, shop);
    await expect(shop).toHaveAttribute('aria-expanded', 'true');

    await page.keyboard.press(tabKey(page));
    const panel = page.locator('.wp-block-ollie-mega-menu__menu-container').filter({ visible: true });
    await expect(panel.locator(':focus')).toHaveCount(1);

    // Rows in the panel follow focus (mega-menu-hover.js): the focused row opens.
    const rows = page.locator('.kwv-mega-menu-nav-wrap .is-style-mega-menu-nav .wp-block-navigation__container > li.has-child').filter({ visible: true });
    if ((await rows.count()) > 1) {
      const second = rows.nth(1);
      // Forward only: the panel closes if focus steps back out of it.
      await tabFromTop(page, second.locator('a').first(), 60);
      await expect(second).toHaveClass(/is-kwv-open/);
      await expect(rows.first()).not.toHaveClass(/is-kwv-open/);
    }

    await page.keyboard.press('Escape');
    await expect(shop).toHaveAttribute('aria-expanded', 'false');
  });

  test('KBD-TC2 Escape in the Shop mega menu returns focus to its toggle', async ({ page }) => {
    // FINDING (JS test report, A11Y-F1): Ollie Menu Designer closes the panel on
    // Escape but leaves focus on <body>, so a keyboard user loses their place.
    // Third-party plugin behaviour; this case flips to passing once it is fixed.
    test.fail();
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/shop/');
    const shop = page
      .getByRole('navigation', { name: 'Navigation', exact: true })
      .locator('.wp-block-ollie-mega-menu__toggle')
      .filter({ hasText: /^\s*shop\s*$/i });
    await tabFromTop(page, shop);
    await page.keyboard.press(tabKey(page));

    await page.keyboard.press('Escape');

    await expect(shop).toBeFocused({ timeout: 3_000 });
  });

  test('KBD-TC3 header search opens from the keyboard and Escape returns focus to its toggle', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/');
    // The toggle is hidden while the bar is open, so anchor on the visible container.
    const container = page.locator('.aws-container.is-style-kwv-header-search').filter({ visible: true }).first();
    const toggle = container.locator('.kwv-search-toggle');

    await tabTo(page, toggle);
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await page.keyboard.press('Enter');
    await expect(toggle).toHaveAttribute('aria-expanded', 'true');
    const field = container.locator('.aws-search-field');
    await expect(field).toBeFocused();

    await page.keyboard.type('wine');
    await page.keyboard.press('Escape');
    await expect(toggle).toHaveAttribute('aria-expanded', 'false');
    await expect(toggle).toBeFocused();
    await expect(field).toHaveValue('');
  });

  test('KBD-TC4 mobile drawer opens and toggles submenus from the keyboard, and Escape closes it @mobile', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 });
    await page.goto('/');
    const open = page.getByRole('button', { name: /open menu/i }).filter({ visible: true });

    await open.focus();
    await page.keyboard.press('Enter');
    const drawer = page.getByRole('dialog', { name: /menu/i }).filter({ visible: true });
    await expect(drawer).toBeVisible();
    await expect(drawer.locator(':focus')).toHaveCount(1);

    const toggles = drawer.getByRole('button', { name: /submenu$/i }).filter({ visible: true });
    if ((await toggles.count()) > 0) {
      const first = toggles.first();
      await tabTo(page, first);
      await page.keyboard.press('Enter');
      await expect(first).toHaveAttribute('aria-expanded', 'true');
      await page.keyboard.press('Space');
      await expect(first).toHaveAttribute('aria-expanded', 'false');
    }

    await page.keyboard.press('Escape');
    await expect(drawer).toBeHidden();
    await expect(open).toBeFocused();
  });

  test('KBD-TC5 shop filter sections expand and collapse with Enter and Space', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto('/shop/');
    const filters = page.locator('.kwv-shop-filters.kwv-filters-collapsible').filter({ visible: true });
    await expect(filters).toBeVisible();
    const heading = filters.getByRole('button').filter({ hasText: /\S/ }).first();

    await tabTo(page, heading);
    await expect(heading).toHaveAttribute('aria-expanded', 'false');
    await page.keyboard.press('Enter');
    await expect(heading).toHaveAttribute('aria-expanded', 'true');
    await page.keyboard.press('Space');
    await expect(heading).toHaveAttribute('aria-expanded', 'false');
  });

  test('KBD-TC6 the brand slider is operable from the keyboard and only the shown product is reachable', async ({ page }) => {
    await page.setViewportSize({ width: 1440, height: 900 });
    await page.goto(`/brand/${BRAND.slug}/`);
    const slider = page.locator('.kwv-brand-slider.swiper');
    test.skip((await slider.count()) === 0, 'No brand slider on this environment (assets/js/brand-slider.js not deployed, or one product).');

    const next = slider.locator('.kwv-brand-slider__arrow--next');
    const active = () => slider.locator('.wc-block-product:not([inert])');
    await expect(active()).toHaveCount(1);
    // textContent, not innerText: the title is uppercased by CSS.
    const first = ((await active().locator('.wp-block-post-title').textContent()) ?? '').trim();

    await tabTo(page, next);
    await page.keyboard.press('Enter');
    await expect(active().locator('.wp-block-post-title')).not.toHaveText(first);

    // Thumbnails are buttons; Enter moves the slider and marks the current one.
    const thumbs = page.locator('.kwv-brand-slider__thumb');
    if ((await thumbs.count()) > 1) {
      await thumbs.first().focus();
      await page.keyboard.press('Enter');
      await expect(thumbs.first()).toHaveAttribute('aria-current', 'true');
      await expect(active().locator('.wp-block-post-title')).toHaveText(first);
    }
  });

  test('KBD-TC7 Careers job modal: open from the keyboard, focus stays inside, Escape returns focus', async ({ page }) => {
    await page.goto('/careers/');
    const link = page.locator('[data-kwv-career-open]').first();
    test.skip((await link.count()) === 0, 'No jobs currently published on the Careers CPT.');

    await tabTo(page, link);
    await page.keyboard.press('Enter');
    const dialog = page.getByRole('dialog');
    await expect(dialog).toBeVisible();
    await expect(page).toHaveURL(/\/careers\/?(#.*)?$/);

    // A modal <dialog> keeps Tab inside it.
    for (let i = 0; i < 12; i++) {
      await page.keyboard.press(tabKey(page));
      const inside = await page.evaluate(() => {
        const d = document.getElementById('kwv-career-dialog');
        return !!d && d.contains(document.activeElement);
      });
      const onChrome = await page.evaluate(() => document.activeElement === document.body);
      expect(inside || onChrome, `focus stays in the modal (Tab ${i + 1})`).toBeTruthy();
    }

    await page.keyboard.press('Escape');
    await expect(dialog).toBeHidden();
    await expect(link).toBeFocused();
  });
});
