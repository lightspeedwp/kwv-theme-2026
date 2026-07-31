# Test Pack — Quality Gates (a11y / SEO / console / performance)

**Flow:** cross-cutting quality gates over the existing KWV E2E suite
**Date:** 2026-07-31
**Environment:** `https://kwv.lightspeedwp.dev` (dev)
**Agent:** Playwright Testing Agent v2.0.0 (`lightspeedwp/.github@develop`, incl. PR #1392)
**Pack form:** **condensed** — 3 confirmed requirements (≤ 4), so the standalone
Sources / Confirmed Requirements / Assumptions / Cases sections are merged. Two
further candidate areas resolved to change-control items rather than requirements.

---

## Integration Pre-flight

| Capability | Status |
|---|---|
| Playwright MCP | **available** — used for live locator grounding and per-page console capture |
| Chrome DevTools MCP | **available** — used for `lighthouse_audit` (accessibility / SEO / best-practices) on `/` and `/shop/` |
| WordPress MCP (`kwv-lightspeedwp-dev`) | **available** — used to attribute the logo-alt defect to attachment 182341 |
| GitHub (`gh`) | **available** — authenticated as `ZaredRogers`; writes remain approval-gated |
| Figma | **unavailable → degraded path:** grounded from PRD + repo + live site only. No visual/layout requirement in this pack depends on design evidence, so nothing is marked design-unverified. |
| BugHerd | **unavailable → degraded path:** findings below are recorded here for manual logging. No ticket has been raised. |

---

## Scope Summary

**Request:** add accessibility, SEO/metadata, console-error and performance gates
to the KWV E2E suite (22 spec files, 62 tests, none of which currently assert any
of the four).

**Scope ruling.** Estimate 3168 / the PRD explicitly excludes three of the four as
deliverables. Per the scope-exclusion discipline, an explicit exclusion is
*evidence*, not a gap, and LightSpeed's house a11y baseline (AGENTS.md working
agreement #4) does not authorise building what the project did not buy:

| Area | Source | Ruling |
|---|---|---|
| Formal accessibility audit | PRD L128, L383 — "Formal accessibility audit is excluded unless separately scoped." | **Excluded.** No `@a11y` axe suite. → CC-1 |
| Formal performance audit | PRD L128, L384; L490 "does not commit to performance… guarantees" | **Excluded as audit**; extracted as a performance rule and routed → **pagespeed-agent** (QG-R3) |
| Ongoing SEO support | PRD L127, L614. Yoast SEO Premium appears only as a *licence dependency* (L385, L408) — a licence is not a requirement for coverage. | **Excluded.** No SEO/metadata suite. → CC-2 |
| Console errors on in-scope flows | PRD L312 "must preserve core WooCommerce flows through testing"; L354 testing must include checkout, add to cart, account | **In scope as coverage expansion** — an assertion added inside existing in-scope cases, not a new deliverable (QG-R1) |

Two live findings are **defects in already-delivered, in-scope components** (the
header template part the estimate covers). A defect in a paid-for deliverable is
not new scope — it is a bug. They are reported as findings, with one cheap
regression assertion proposed (QG-R2) and one held pending a design decision.

**This agent does not measure performance.** **pagespeed-agent** owns measurement,
waterfall analysis and reporting. No `@perf` project, timing assertion or
performance budget appears in this pack.

---

## Environment & Test-Data Contract

| Field | Value | Status |
|---|---|---|
| Base URL / environment | `https://kwv.lightspeedwp.dev` (dev) | ✅ |
| Payment / sandbox mode | PayFast **TEST mode**, completes card-free. Gateways live at checkout: PayFast (default, pre-selected), EFT / Bank Transfer, Payflex. | ✅ verified live 2026-07-31 |
| Test card(s) | Not required for PayFast sandbox. Payflex/BNPL capture would need a gateway sandbox card. | ⚠️ n/a for this pack |
| Test customer | ID 23387 · `zared@lightspeed.dev` · role `customer` | ✅ (password in local `.env`, never committed) |
| Seeded product(s) | ID 181708; `/product/cruxland-gin-kalahari-truffle/` (R349,00, SKU `700027235\|6002323015970`) | ✅ |
| Known coupon(s) | `test` (id 183087, 10%). `PINOTAGE100` advertised in the promo bar — rule values unconfirmed. | ✅ / ⚠️ |
| Shipping / tax / discount rule source | Live config only: Flat Delivery Rate R110,00 (default) · Local pickup — Kohler Street, Paarl, 7620 (Free). No document supplies rule *values*. | ⚠️ **gap** — mechanics-only assertions; do not assert amounts |
| Subscriptions test data | Wine Club sign-up creates `shop_subscription` rows in HPOS `wp_wc_orders`. Billing-interval expectations undocumented. | ⚠️ partial |
| Checkout implementation | **WooCommerce Blocks** — `/checkout/` has `.wc-block-checkout` and 162 `wc-block-components-*` nodes; no `form.checkout`, no `#billing_*`. Locators target the Blocks fieldsets (as the existing specs already do). | ✅ verified live via DOM query |
| **Accessibility baseline** | **Captured 2026-07-31** via `lighthouse_audit` (desktop, navigation) — see below | ✅ captured, not invented |
| **Console-error baseline** | **Captured 2026-07-31** via Playwright MCP, per page — see below | ✅ captured, not invented |

### Accessibility baseline (captured — Lighthouse desktop, `navigation` mode)

| Page | Score | Existing violations |
|---|---|---|
| `/` | 97 | `aria-hidden-focus` ×1 — `.wc-block-mini-cart__drawer` is `aria-hidden="true"` while its descendants stay focusable (WooCommerce Blocks mini-cart, third-party) |
| `/shop/` | 94 | `color-contrast` ×1 — promo bar (see FND-1) · `link-name` ×1 — header site logo (see FND-2) |

> Automated audits catch only a minority of real barriers. A green Lighthouse run
> is not a clean bill of accessibility health, and this baseline is not an audit.

### Console-error baseline (captured — Playwright MCP, one navigation per page)

| Page | Errors | Detail |
|---|---|---|
| `/` | 0 | — |
| `/product/cruxland-gin-kalahari-truffle/` | 0 | — |
| `/cart/` | 0 | — |
| `/?add-to-cart=181708` | 0 | — |
| `/shop/` | **1** | `TypeError: Cannot read properties of null (reading 'addEventListener')` at `trackEvents` (`woocommerce-google-analytics-pro-js-after:5:58`) → **FND-3** |
| `/checkout/` | **1** | `TypeError: Cannot read properties of undefined (reading 'ajax')` (`woocommerce-google-analytics-pro-js-after:24:5`) → **FND-3** |

`JQMIGRATE: Migrate is installed, version 3.4.1` appears on every page as a
`console.log`, not an error — excluded from the budget by level, not by allowlist.

### SEO baseline — **not usable as a gate on dev**

`/` and `/shop/` both score 58, dominated by `is-crawlable: <meta name="robots"
content="noindex, nofollow">` — correct and deliberate on a dev environment. A
robots/indexability assertion written against dev would encode the opposite of the
production requirement. `/shop/` additionally has **no meta description** (`/`
has one). Recorded as evidence for CC-2, not as a gate.

---

## Requirements + Test Cases (merged)

### QG-R1 — No new console errors on in-scope WooCommerce flows
**Type:** error or empty state · **Evidence:** PRD L312, L354 · **Label:** coverage
expansion on existing in-scope cases (not a new deliverable)

| Field | Detail |
|---|---|
| **TC-QG1** | Console-error budget across the in-scope flow pages |
| Actor | Guest |
| Preconditions | Age-gate cookie `age_gate=18` seeded (fixtures already do this); baseline table above committed alongside the spec |
| Steps | For each of `/`, `/shop/`, product, `/cart/`, `/checkout/`: attach a `page.on('console')` collector, navigate, wait for network idle, filter to `type === 'error'` |
| Expected result | Zero errors **beyond the recorded baseline**. `/shop/` and `/checkout/` each permit exactly the one `woocommerce-google-analytics-pro` error named above, each with a comment pointing at FND-3. |
| Assertions | `expect(newErrors).toEqual([])` where `newErrors = observed − baseline` |
| Notes | Never widen the baseline to make a run pass — a new error is a finding. Baseline entries carry the owning plugin so they can be removed when FND-3 is fixed. Implemented as a shared fixture (`watchConsoleErrors` / `newConsoleErrors` / `CONSOLE_ERROR_BASELINE`) plus one focused spec covering the in-scope pages, tagged `@console`. The existing 22 specs can adopt the fixture incrementally — deliberately not edited here, to avoid destabilising a green suite for a coverage-expansion gate. |

### QG-R2 — Header site logo exposes an accessible name
**Type:** accessibility rule · **Evidence:** delivered header template part
(`parts/header.html` → `patterns/header-light.php:21`), in scope per Estimate 3168
Template Parts · **Label:** regression assertion for FND-2, **after** the fix

| Field | Detail |
|---|---|
| **TC-QG2** | Header logo link has a discernible accessible name |
| Actor | Guest |
| Preconditions | FND-2 fixed (alt text set on attachment 182341) |
| Steps | Navigate to `/shop/` (inner-page header); query the header logo link by role |
| Expected result | `getByRole('banner').getByRole('link', { name: /KWV/i })` resolves |
| Assertions | Link is present and has a non-empty accessible name (WCAG 2.4.4, 4.1.2) |
| Notes | **Fails today** — see FND-2. Scoped as fix-then-assert, not landed red. One assertion inside existing global/navigation coverage; this is not the excluded a11y suite. |

### QG-R3 — Performance expectations
**Type:** performance rule · **Evidence:** PRD L128, L384, L490
**Planned output:** `deferred → pagespeed-agent`

No Playwright output. Not converted to a timing assertion: wall-clock timings from
a functional suite are noisy, environment-bound and produce gates that get
disabled — and the source states no threshold to assert. **Owner: pagespeed-agent.**

Environment-bound observation only (not a metric, not a baseline): Lighthouse
`navigation` runs against dev totalled ~20–25 s and page loads were 8–12 s to first
console entry. Dev is a slow, unoptimised environment; these figures say nothing
about production Core Web Vitals.

---

## Findings (candidate — logging is approval-gated, no ticket raised)

| ID | Finding | Attribution | Severity |
|---|---|---|---|
| **FND-1** | Promo bar fails WCAG 1.4.3: **2.98:1** (`#FFFFFF` on `#B29143`, 15.72px/500). Needs 4.5:1. | **Theme-owned, token-level** — `styles/sections/header-promo-bar.json` pairs `background: brand-500` with `text: base`. | Medium — **design decision required**, see CC-3 |
| **FND-2** | Inner-page header site logo renders `<a href="/">` with **no accessible name**; element is in tab order. | **Content-side on dev** — `patterns/header-light.php:21` uses `wp:site-logo`, and attachment **182341** ("Site Logo - White") has `_wp_attachment_image_alt` = NULL. The homepage passes because its baked-in DB template uses `wp:image` with `alt="KWV"`. | Medium — cheap fix |
| **FND-3** | `woocommerce-google-analytics-pro` throws on `/shop/` (`addEventListener` of null) and `/checkout/` (`ajax` of undefined). | **Third-party plugin**, not theme code. Checkout is the more serious of the two — the error fires inside conversion tracking on the order path. | Medium — plugin owner |
| **FND-4** | `parts/promo-bar.html` is declared `area: header` in `theme.json`, so it renders as a **second `<header>`/`banner` landmark** alongside the real header. | Theme-owned — one-line `theme.json` change (`area: uncategorized`) or nest it inside the header part. | Low — one-line fix |
| **FND-5** | `/shop/` has **no meta description**; `/` has one. | Yoast archive config, content-side. | Low — evidence for CC-2 |
| **FND-6** | Single product page renders **no related-products section**, and no Payflex widget appeared in the a11y tree on a R349 product. | Pre-existing open bug (related products); Payflex presence needs confirming against gateway thresholds before it is called a defect. | Medium — **unverified**, needs focused check |

**Correction to a previously recorded finding.** An earlier note framed "three
gateways live (PayFast, EFT, Payflex)" as conflicting with a PRD
consolidate-to-PayFast rule. That is not supported: PRD L209 says *"Consolidate
PayFast plugins to one instance"* — duplicate **plugins**, not gateways — and the
PRD requires PayFlex to be verified in testing (L166, L312, L354, L456). Three
gateways is consistent with the PRD. Withdrawn.

---

## Change-Control Register items (out of scope — not built)

| ID | Item | Collides with | What coverage would cost |
|---|---|---|---|
| **CC-1** | `@a11y` axe-core suite: per-page + per-widget scans, keyboard traversal for the mega-menu, mini-cart drawer, filter overlay, age gate and popups, WCAG 2.2 AA criteria cited per case | PRD L128, L383 — formal accessibility audit excluded | New suite (~12–18 cases), a maintained baseline per page/widget, and remediation capacity for what it finds |
| **CC-2** | SEO/metadata suite: `<title>`, meta description, canonical, robots, OG/Twitter, structured data, enumerated from the site inventory rather than hand-listed | PRD L127, L614 — ongoing SEO support excluded; Yoast is a licence, not a requirement | New suite + a production environment to assert indexability against (dev is `noindex` by design) |
| **CC-3** | FND-1 remediation: the promo bar's white-on-`brand-500` pair is the supplied design. Fixing contrast means changing a design token pairing (e.g. text to a dark neutral, or background to a darker brand step). | Supplied desktop designs / KWV design system | Design decision + sign-off, then a one-line style change. Held pending that decision. |

I think CC-1 is worth buying before launch — a consumer alcohol brand carries real
accessibility exposure, and the two defects found in ten minutes of automated
scanning suggest there is more. That is still a scope decision, and it is not
mine: the exclusion stands until whoever owns scope changes it.

---

## Traceability Matrix

| Req | Type | Evidence | Test case | Planned output |
|---|---|---|---|---|
| QG-R1 | error or empty state | PRD L312, L354 | TC-QG1 | `fixtures.ts` console collector + assertions in existing smoke/shop/cart/checkout specs |
| QG-R2 | accessibility rule | `patterns/header-light.php:21`; live `/shop/` | TC-QG2 | assertion in `global/navigation.spec.ts` — **after FND-2 fix** |
| QG-R3 | performance rule | PRD L128, L384, L490 | — | **deferred → pagespeed-agent** |
| CC-1 | accessibility rule (excluded) | PRD L128, L383 | — | **not built** — change-control |
| CC-2 | content rule (excluded) | PRD L127, L614 | — | **not built** — change-control |

No requirement is left unmapped: every row resolves to a spec, a routing, or a
change-control item.

---

## Review Gate / Next Step

**Persisted to:** `tests/e2e/test-packs/quality-gates-2026-07-31.md` (the theme
repo has no `.github/` control plane, so the agent's portability-aware fallback
applies — repo-local `test-packs/` beside the suite it covers).

**Decisions needed before code:**

1. **QG-R1** — approve the console-error gate as coverage expansion, with the
   two-error baseline above committed alongside it.
2. **FND-2** — set alt text on attachment 182341 on dev, then land TC-QG2. (Fix is
   content-side; it also needs applying wherever production's logo attachment
   lives.)
3. **FND-4** — approve the one-line `theme.json` fix for the duplicate `banner`.
4. **CC-1 / CC-2 / CC-3** — route to the Change-Control Register. No work started.
5. **FND-3, FND-6** — assign to plugin owner / open bug respectively.

Nothing in section QG has been generated yet. Awaiting approval.
