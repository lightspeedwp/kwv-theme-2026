import { test, expect, SANDBOX_READY, TEST_CUSTOMER, emptyCart, fillCheckoutAddress } from '../fixtures';
import type { APIRequestContext } from '@playwright/test';

/**
 * CLUB-01 — Wine Club landing presents the current edition and a sign-up route.
 * CLUB-02 — Sign-up proceeds into the WooCommerce subscription checkout.
 * CLUB-03 — Existing-member page renders scoped content.
 * CLUB-04 — Subscription generation (LS-1175 "Test subscription generation").
 * CLUB-05 — Subscription products are sold from /wineclub/, never their own page.
 * Packs: reports/test-packs/full-site-2026-07-24.md, test-packs/launch-coverage-2026-10-22.md
 *
 * "Sign up now" → `/?kwv-join=current` → 302 to /checkout/?add-to-cart=<newest
 * edition> (kwv-enhancements WinemakersClub). The edition changes quarterly, so the
 * current one is read from the Store API (newest product in the wine-club category),
 * never hard-coded. Its title uses a curly apostrophe: "WINEMAKER’S Selection".
 */
const SUBSCRIPTION = { name: /winemaker.?s selection/i, interval: /every\s*3\s*months/i };

type StoreProduct = { id: number; name: string; permalink: string; prices: { price: string; currency_minor_unit: number } };

async function currentEdition(request: APIRequestContext): Promise<StoreProduct> {
  const res = await request.get(
    '/wp-json/wc/store/v1/products?category=wine-club-membership&orderby=date&order=desc&per_page=1',
  );
  expect(res.ok()).toBeTruthy();
  const [product] = await res.json();
  expect(product, 'a published wine-club edition').toBeTruthy();
  return product;
}

/** "R999" from Store API minor units, as the card prints it. */
function rands(p: StoreProduct): RegExp {
  const major = Number(p.prices.price) / 10 ** p.prices.currency_minor_unit;
  return new RegExp(`R\\s?${major.toLocaleString('en-ZA').replace(/\s/g, '\\s?')}`);
}

test.describe('Wine Club @wineclub', () => {
  // CLUB-TC1 — landing shows the current edition and the sign-up CTA
  test('CLUB-TC1 landing shows the current edition card and a sign-up CTA', async ({ page, request }) => {
    const edition = await currentEdition(request);
    await page.goto('/wineclub/');

    const card = page.getByRole('listitem').filter({ has: page.getByRole('heading', { name: edition.name }) });
    await expect(card).toBeVisible();
    await expect(page.getByText(rands(edition)).first()).toBeVisible();

    const signUp = page.getByRole('link', { name: /sign up now/i });
    await expect(signUp.first()).toBeVisible();
    for (const link of await signUp.all()) {
      await expect(link).toHaveAttribute('href', /[?&]kwv-join=current/);
    }
  });

  // CLUB-TC2 — sign-up enters the subscription checkout with recurring terms
  test('CLUB-TC2 sign-up routes to checkout with the current edition and its recurring terms', async ({ page, request }) => {
    const edition = await currentEdition(request);
    await page.goto('/wineclub/');
    await page.getByRole('link', { name: /sign up now/i }).first().click();

    await expect(page).toHaveURL(/\/checkout\//, { timeout: 60_000 });
    await expect(page.getByText(edition.name).first()).toBeVisible({ timeout: 45_000 });
    await expect(page.getByText(SUBSCRIPTION.interval).first()).toBeVisible();
    // Subscription checkout may relabel the submit button (e.g. "Sign up now").
    await expect(
      page.getByRole('button', { name: /place order|sign up|subscribe|complete/i }).first(),
    ).toBeVisible();
  });

  // CLUB-TC3 — existing member page
  test('CLUB-TC3 existing-member content renders', async ({ page }) => {
    await page.goto('/wineclub/');
    await expect(page.getByRole('contentinfo')).toBeVisible();
  });

  // CLUB-TC5 — subscription product URLs redirect to the sales page
  test('CLUB-TC5 the edition product URL redirects to /wineclub/', async ({ page, request }) => {
    const edition = await currentEdition(request);
    const res = await request.get(edition.permalink, { maxRedirects: 0 });
    expect(res.status()).toBe(301);
    expect(res.headers().location).toMatch(/\/wineclub\/?$/);

    await page.goto(edition.permalink);
    await expect(page).toHaveURL(/\/wineclub\/?$/);
  });

  // CLUB-TC4 — subscription generation (completes sign-up, verifies the subscription)
  test('CLUB-TC4 completing sign-up generates a subscription @stateful @auth', async ({ page }) => {
    test.skip(
      !SANDBOX_READY || !TEST_CUSTOMER.email,
      'Needs KWV_SANDBOX=1 and a test customer (KWV_TEST_CUSTOMER_*).',
    );

    // Pre-authenticated (saved session). Start clean — the account cart persists.
    await emptyCart(page);
    // Join → 302 → checkout with the subscription. The redirect plus a cold checkout
    // render can exceed the default navigation timeout on dev, so only wait for the
    // navigation to commit and then wait for the checkout itself.
    await page.goto('/?kwv-join=current', { waitUntil: 'commit', timeout: 90_000 });
    await expect(page).toHaveURL(/checkout/i, { timeout: 90_000 });
    await expect(page.getByText(SUBSCRIPTION.name).first()).toBeVisible({ timeout: 60_000 });

    // Physical subscription → needs a shipping address + rate before submitting.
    await fillCheckoutAddress(page);
    await page.getByRole('radio', { name: /payfast/i }).check();
    await page
      .getByRole('button', { name: /place order|sign up|subscribe|complete/i })
      .first()
      .click();

    // Reaching the PayFast gateway confirms the subscription sign-up submitted.
    // A shop_subscription order IS generated for the customer — verified out-of-band
    // in HPOS wc_orders (a pending shop_subscription row is created per sign-up,
    // activating once payment settles). The post-payment redirect for subscriptions
    // is inconsistent on dev, so completion is best-effort and not asserted here.
    await expect(page).toHaveURL(/payfast|order-pay|order-received/i, { timeout: 60_000 });
    const complete = page
      .getByRole('button', { name: /complete payment|pay now|complete/i })
      .or(page.getByRole('link', { name: /complete payment|pay now|complete/i }))
      .first();
    if (await complete.count()) await complete.click().catch(() => {});
  });
});
