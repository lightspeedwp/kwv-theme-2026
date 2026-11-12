import { test as base, expect, type APIRequestContext, type Page } from '@playwright/test';

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
  // A search term that matches this product AND others: WooCommerce redirects a
  // single-result search straight to the product page, skipping the grid.
  search: process.env.KWV_PRODUCT_SEARCH ?? 'Chardonnay',
};

/** A product_brand term with published products (Single Brand template). */
export const BRAND = {
  slug: process.env.KWV_BRAND_SLUG ?? 'the-mentors',
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

/** Every scoped page/template (SMK-01); also the seed set for the link crawl. */
export const SCOPED_PAGES: { path: string; label: string }[] = [
  { path: '/', label: 'Homepage' },
  { path: '/shop/', label: 'Shop' },
  { path: `/product/${PRODUCT.slug}/`, label: 'Single product' },
  { path: '/cart/', label: 'Cart' },
  { path: '/checkout/', label: 'Checkout' },
  { path: '/my-account/', label: 'Account/Login' },
  { path: '/about-us/', label: 'About' },
  { path: '/about-us/history/', label: 'About/History' },
  { path: '/faq/', label: 'FAQs' },
  { path: '/contact/', label: 'Contact Us' },
  { path: '/wineclub/', label: 'Wine Club' },
  { path: '/visit/', label: 'Visit Us' },
  { path: '/careers/', label: 'Careers' },
  { path: '/news/', label: 'News' },
  { path: `/brand/${BRAND.slug}/`, label: 'Single Brand' },
];

/** Submitter address for form-submitting cases (a team inbox, never a client's). */
export const FORM_TEST_EMAIL = process.env.KWV_FORM_TEST_EMAIL ?? '';

/** Marker text on every test submission, so entries are easy to find and purge. */
export const TEST_MESSAGE = 'E2E test submission (Playwright) - please ignore.';

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
 * Empty the cart. Logged-in customers have a persistent, account-bound cart that
 * survives across tests, so authenticated checkout specs must start from a clean
 * cart or items (incl. leftover subscriptions) accumulate.
 */
export async function emptyCart(page: Page): Promise<void> {
  await page.goto('/cart/');
  // The Cart block renders client-side: wait until it shows either line items or
  // the empty state, otherwise the first count() sees nothing and stops early.
  // Each removal re-renders the block, so a button counted a moment ago can be
  // gone by the time it is clicked. Keep removing until the empty state shows.
  const removeBtn = page
    .getByRole('button', { name: /remove (item|.*from cart)/i })
    .filter({ visible: true })
    .first();
  const emptyState = page.locator('.wp-block-woocommerce-empty-cart-block').filter({ visible: true });
  await expect(removeBtn.or(emptyState).first()).toBeVisible({ timeout: 30_000 });
  for (let i = 0; i < 15 && !(await emptyState.isVisible()); i++) {
    await removeBtn.click({ timeout: 10_000 }).catch(() => {});
    await waitForStoreApi(page);
  }
  await expect(emptyState).toBeVisible({ timeout: 20_000 });
}

/**
 * Fill the checkout shipping address. For South Africa the State/County field is a
 * province <select> (not a text input), so select it as a combobox.
 */
export async function fillCheckoutAddress(page: Page): Promise<void> {
  const shipping = page.getByRole('group', { name: /shipping address/i });
  const country = shipping.getByLabel(/country\/region/i);
  const editBtn = shipping.getByRole('button', { name: /edit shipping address/i });
  // Wait for the address block to settle into one of its two states: editable fields
  // (guest) or a collapsed saved address behind an "Edit" button (logged-in).
  await Promise.race([
    country.waitFor({ state: 'visible', timeout: 15_000 }).catch(() => {}),
    editBtn.waitFor({ state: 'visible', timeout: 15_000 }).catch(() => {}),
  ]);
  // A collapsed saved address hides the fields — expand it so they're editable.
  if (await editBtn.isVisible().catch(() => false)) {
    await editBtn.click();
    await country.waitFor({ state: 'visible', timeout: 10_000 }).catch(() => {});
  }
  // Fill only when the country selector is actually editable/visible.
  if (await country.isVisible().catch(() => false)) {
    await country.selectOption({ label: 'South Africa' });
    await shipping.getByRole('textbox', { name: /first name/i }).fill('Zared');
    await shipping.getByRole('textbox', { name: /last name/i }).fill('Test');
    await shipping.getByRole('textbox', { name: /^address/i }).fill('123 Test Avenue');
    await shipping.getByRole('textbox', { name: /city/i }).fill('Cape Town');
    await shipping.getByRole('combobox', { name: /state|province|county/i }).selectOption({ label: 'Western Cape' });
    await shipping.getByRole('textbox', { name: /postal code/i }).fill('7300');
    await shipping.getByRole('textbox', { name: /phone/i }).fill('0210000000');
  }
  // Logged-in checkout may show a separate (empty) billing form; ensure billing
  // reuses the shipping address so it doesn't block submission.
  const sameBilling = page.getByRole('checkbox', { name: /use same address for billing/i });
  if ((await sameBilling.count()) > 0 && !(await sameBilling.isChecked().catch(() => true))) {
    await sameBilling.check();
  }
  await waitForStoreApi(page);
  // Shipping rates recalculate async on the slow staging site — wait for a
  // selectable rate before the caller submits, or Place Order fires with no
  // shipping method and the order never completes.
  await expect(
    page.getByRole('group', { name: /shipping options/i }).getByRole('radio').first(),
  ).toBeVisible({ timeout: 45_000 });
  // On slow dev a late Store API response (from an update sent before the street
  // was typed) can reset the street field to empty, and Place Order then fails with
  // "Please enter a valid address". Re-check once everything has settled.
  const street = shipping.getByRole('textbox', { name: /^address/i });
  if (await street.isVisible().catch(() => false)) {
    await expect(async () => {
      if ((await street.inputValue()) === '') {
        await street.fill('123 Test Avenue');
        await waitForStoreApi(page);
      }
      await expect(street).not.toHaveValue('', { timeout: 3_000 });
    }).toPass({ timeout: 30_000 });
  }
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

/**
 * The page's main Gravity Form. Every KWV enquiry/booking form submits with
 * "SEND MESSAGE"; the footer newsletter is also a Gravity Form, so scope by the
 * submit button rather than taking the first `form[id^=gform]`.
 */
export function enquiryForm(page: Page) {
  return page.locator('form').filter({ has: page.getByRole('button', { name: /send message/i }) });
}

/** A date `days` from today as dd/mm/yyyy, the format the booking date pickers use. */
export function futureDate(days: number): string {
  const d = new Date(Date.now() + days * 86_400_000);
  const pad = (n: number) => String(n).padStart(2, '0');
  return `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()}`;
}

type PublishedEvent = { id: number; link: string };

/**
 * Published `kwv_event` posts, from the public REST API. Event cases adapt to the
 * live data: with none published, the Visit page must hide its events section and
 * the single-event cases skip.
 */
export async function publishedEvents(request: APIRequestContext): Promise<PublishedEvent[]> {
  const res = await request.get('/wp-json/wp/v2/kwv_event?per_page=20&_fields=id,link');
  expect(res.ok(), 'kwv_event REST endpoint').toBeTruthy();
  return res.json();
}

type StoreCategory = { id: number; name: string; slug: string; parent: number; count: number; permalink: string };

/** Product categories from the public Store API (empty categories are omitted by the API). */
export async function storeCategories(request: APIRequestContext): Promise<StoreCategory[]> {
  const res = await request.get('/wp-json/wc/store/v1/products/categories?per_page=100');
  expect(res.ok(), 'Store API categories').toBeTruthy();
  return res.json();
}

/**
 * Console-error budget (QG-R1, test pack test-packs/quality-gates-2026-07-31.md).
 *
 * Errors already present on the target environment, captured 2026-07-31 against
 * https://kwv.lightspeedwp.dev — NOT invented. The gate asserts "no NEW errors
 * against this baseline", never zero outright, so it can land while the known
 * errors are still open.
 *
 * Never widen this list to make a run pass. A new error is a finding: report it.
 * Each entry names the owner so it can be deleted once the owner fixes it.
 */
export const CONSOLE_ERROR_BASELINE: Record<string, RegExp[]> = {
  '/': [],
  '/cart/': [],
  '/shop/': [
    // FND-3 — woocommerce-google-analytics-pro (third-party plugin), not theme code.
    // trackEvents() binds to an element that isn't on the shop template.
    /Cannot read properties of null \(reading 'addEventListener'\)/,
  ],
  '/checkout/': [
    // FND-3 — same plugin; fires inside conversion tracking on the order path.
    /Cannot read properties of undefined \(reading 'ajax'\)/,
  ],
};

/** JQMIGRATE announces itself as a console.log on every page — excluded by level, not by allowlist. */

export type ConsoleWatcher = { errors: () => string[] };

/**
 * Start collecting console errors and uncaught page exceptions. Attach BEFORE
 * navigating — errors thrown during load are missed otherwise. Uncaught
 * TypeErrors arrive via `pageerror`, explicit console.error via `console`.
 */
export function watchConsoleErrors(page: Page): ConsoleWatcher {
  const errors: string[] = [];
  page.on('console', (msg) => {
    if (msg.type() === 'error') errors.push(msg.text());
  });
  page.on('pageerror', (err) => errors.push(err.message));
  return { errors: () => [...errors] };
}

/** Errors observed that the recorded baseline for `pageKey` does not already account for. */
export function newConsoleErrors(observed: string[], pageKey: string): string[] {
  const allowed = CONSOLE_ERROR_BASELINE[pageKey] ?? [];
  return observed.filter((text) => !allowed.some((re) => re.test(text)));
}

export { expect };
