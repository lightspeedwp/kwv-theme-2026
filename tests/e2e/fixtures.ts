import { test as base, expect, type Page } from '@playwright/test';

/**
 * KWV E2E fixtures & helpers.
 *
 * Derived from the approved test packs (reports/test-packs/checkout-2026-07-24.md,
 * full-site-2026-07-24.md) and the Environment & Test-Data Contract. No credentials
 * or product data are invented here — everything comes from the environment (.env).
 */

export const PRODUCT = {
  id: process.env.KWV_PRODUCT_ID ?? '181708',
  slug: process.env.KWV_PRODUCT_SLUG ?? 'kwv-classic-chardonnay-2025',
  name: process.env.KWV_PRODUCT_NAME ?? 'KWV Classic Chardonnay 2025',
};

export const COUPON = process.env.KWV_COUPON_CODE ?? '';

/**
 * Sandbox is on when gateways are in test mode. PayFast is in TEST mode on dev and
 * its sandbox completes WITHOUT a real card, so this flag alone enables end-to-end
 * order placement via PayFast. Set KWV_SANDBOX=1 to opt in.
 */
export const SANDBOX_READY = process.env.KWV_SANDBOX === '1';

/**
 * Only needed for the Payflex (BNPL) capture path, which requires a gateway
 * sandbox test card. PayFast sandbox does not need this.
 */
export const STATEFUL_READY = SANDBOX_READY && !!process.env.KWV_TEST_CARD_NUMBER;

/**
 * Opt-in guard for cases that submit real forms (Contact, Visit Us) on the target
 * environment — they create entries and fire notifications. Off by default so specs
 * never spam dev accidentally. Set KWV_ALLOW_FORM_SUBMIT=1 to enable.
 */
export const ALLOW_FORM_SUBMIT = process.env.KWV_ALLOW_FORM_SUBMIT === '1';

export const TEST_CUSTOMER = {
  email: process.env.KWV_TEST_CUSTOMER_EMAIL ?? '',
  password: process.env.KWV_TEST_CUSTOMER_PASSWORD ?? '',
};

/**
 * The site shows an Age Gate to visitors without the `age_gate` cookie. A fresh
 * browser context hits it on every page, so the default `test` seeds the cookie
 * (value "18" = age confirmed) for the target host before any navigation.
 *
 * The dedicated age-gate test imports `freshTest` (raw context, no cookie) to
 * observe and assert the gate itself.
 */
export const test = base.extend({
  context: async ({ context, baseURL }, use) => {
    if (baseURL) {
      const host = new URL(baseURL).hostname;
      await context.addCookies([
        { name: 'age_gate', value: '18', domain: host, path: '/' },
      ]);
    }
    await use(context);
  },
});

/** Raw test with no age-gate bypass — for the age-gate spec only. */
export const freshTest = base;

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

/**
 * Seed the cart with the known product. Uses the server-side add-to-cart URL: the
 * block "Add to cart" button only updates client state and is LOST on a full-page
 * navigation to /cart or /checkout, whereas `?add-to-cart=<id>` persists in the
 * WooCommerce session so the cart/checkout render populated. (TC-001 still exercises
 * the shop button itself.)
 */
export async function addSeededProductToCart(page: Page): Promise<void> {
  await page.goto(`/?add-to-cart=${PRODUCT.id}`);
  await expect(cartButton(page)).toHaveAccessibleName(/:\s*[1-9]/, { timeout: 20_000 });
}

/**
 * Fill the checkout shipping address. For South Africa the State/County field is a
 * province <select> (not a text input), so select it as a combobox.
 */
export async function fillCheckoutAddress(page: Page): Promise<void> {
  const shipping = page.getByRole('group', { name: /shipping address/i });
  await shipping.getByLabel(/country\/region/i).selectOption({ label: 'South Africa' });
  await shipping.getByRole('textbox', { name: /first name/i }).fill('Zared');
  await shipping.getByRole('textbox', { name: /last name/i }).fill('Test');
  await shipping.getByRole('textbox', { name: /^address/i }).fill('123 Test Avenue');
  await shipping.getByRole('textbox', { name: /city/i }).fill('Cape Town');
  await shipping.getByRole('combobox', { name: /state|province|county/i }).selectOption({ label: 'Western Cape' });
  await shipping.getByRole('textbox', { name: /postal code/i }).fill('7300');
  await shipping.getByRole('textbox', { name: /phone/i }).fill('0210000000');
  await waitForStoreApi(page);
}

/**
 * Log in a WooCommerce customer via My Account. Requires TEST_CUSTOMER (skips otherwise).
 * The My Account page shows both a login AND a register form, so target the login
 * fields by their stable ids (#username / #password; register uses #reg_*).
 */
export async function loginCustomer(page: Page): Promise<void> {
  await page.goto('/my-account/');
  await page.locator('#username').fill(TEST_CUSTOMER.email);
  await page.locator('#password').fill(TEST_CUSTOMER.password);
  await page.getByRole('button', { name: /^log in$/i }).click();
  // Fail fast with a clear message if the credentials don't match the account.
  await expect(
    page.getByText(/username or password you entered is incorrect|unknown email|is not registered/i),
    'Login failed — KWV_TEST_CUSTOMER_PASSWORD does not match the account for KWV_TEST_CUSTOMER_EMAIL',
  ).toHaveCount(0, { timeout: 10_000 });
  await expect(page.getByRole('link', { name: /log out/i }).first()).toBeVisible({ timeout: 20_000 });
}

export { expect };
