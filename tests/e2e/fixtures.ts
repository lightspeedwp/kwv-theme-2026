import { test as base, expect, type Page } from '@playwright/test';

/**
 * KWV E2E fixtures & helpers.
 *
 * Derived from the approved test pack (reports/test-packs/checkout-2026-07-24.md)
 * and the Environment & Test-Data Contract. No credentials or product data are
 * invented here — everything comes from the environment (.env).
 */

export const PRODUCT = {
  slug: process.env.KWV_PRODUCT_SLUG ?? 'kwv-classic-chardonnay-2025',
  name: process.env.KWV_PRODUCT_NAME ?? 'KWV Classic Chardonnay 2025',
};

export const COUPON = process.env.KWV_COUPON_CODE ?? '';

/**
 * True only when the environment is safe for order-placing / account-mutating
 * cases: sandbox mode on AND a test card available. @stateful cases skip otherwise.
 */
export const STATEFUL_READY =
  process.env.KWV_SANDBOX === '1' && !!process.env.KWV_TEST_CARD_NUMBER;

export const TEST_CUSTOMER = {
  email: process.env.KWV_TEST_CUSTOMER_EMAIL ?? '',
  password: process.env.KWV_TEST_CUSTOMER_PASSWORD ?? '',
};

/**
 * Wait for a WooCommerce Store API mutation to settle. Cart/checkout totals,
 * shipping and coupons recalculate via async Store API calls after an interaction;
 * asserting before recalculation is a common false failure.
 */
export async function waitForStoreApi(page: Page): Promise<void> {
  await page
    .waitForResponse(
      (r) => r.url().includes('/wp-json/wc/store/') && r.request().method() !== 'GET',
      { timeout: 20_000 },
    )
    .catch(() => {
      /* some interactions resolve from cache; fall through to element-level waits */
    });
}

/** The header cart button, whose accessible name carries the live item count. */
export function cartButton(page: Page) {
  return page.getByRole('button', { name: /items in the cart/i });
}

/** The mini-cart drawer is a body-level portal — scope drawer locators here, not the trigger. */
export function miniCartDrawer(page: Page) {
  return page.locator('.wc-block-mini-cart__drawer');
}

/** Add the seeded product to the cart from the Shop grid and wait for confirmation. */
export async function addSeededProductToCart(page: Page): Promise<void> {
  await page.goto('/shop/');
  const card = page
    .getByRole('listitem')
    .filter({ hasText: PRODUCT.name })
    .first();
  await card.getByRole('button', { name: /add to cart/i }).click();
  // Item count in the header updates via the Store API.
  await expect(cartButton(page)).toHaveAccessibleName(/:\s*[1-9]/, { timeout: 20_000 });
}

export const test = base;
export { expect };
