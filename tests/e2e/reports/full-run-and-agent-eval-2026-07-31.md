# E2E Full Run + Playwright Testing Agent Evaluation — 2026-07-31

**Target:** `https://kwv.lightspeedwp.dev` (dev)
**Theme:** `kwv-theme-2026` @ `develop` (`7e4efbc`)
**Agent under test:** Playwright Testing Agent v2.0.0 — `lightspeedwp/.github@develop`
(`e6cd1eb0`), agent tree last changed by `849a0cdf` (PR #1392: performance routing,
a11y/SEO/console gates, scope-exclusion discipline)
**Not tested:** the unmerged `feat/playwright-testing-agent-update-fix` branch (4 commits, PR #1422)

---

## Verdict

| | Result |
|---|---|
| **Suite vs dev** | **66 passed · 0 failed · 5 skipped** (guest 57, new gate 5, auth 4) |
| **Agent workflow** | Ran end to end. All three PR #1392 behaviours fired correctly under live MCPs. |
| **Agent issues found** | **5** — one material (AG-1), four minor — plus one housekeeping item |
| **Project findings** | **6** new/confirmed, plus 2 coverage gaps against PRD-required testing |
| **Scope creep avoided** | ~30+ test cases the estimate does not cover, correctly refused |

The single most valuable thing this run demonstrated: asked for a11y, SEO, console
and performance gates, the agent **declined three of the four on scope grounds**
and cited the PRD lines that exclude them, rather than generating suites nobody
bought. That behaviour is new in PR #1392 and it worked.

The single material problem: **the agent's a11y baseline is captured with a
different rule set than the gate it feeds** (AG-1). That produces a baseline that
silently under-reports, and we hit a live instance of it.

---

## What was run

The agent's full default workflow, not a prototype path:

1. Integration pre-flight (live, all five named integrations)
2. Grounding — PRD v1.1, Estimate 3168 exclusions, 7 OpenSpec specs, theme repo, live site
3. Environment & Test-Data Contract, including **live capture** of the a11y and console baselines
4. Requirement extraction + classification (8 types, incl. performance rule)
5. Right-sizing → condensed pack
6. Persistence → repo-local path (portability fallback)
7. Review gate — **held**
8. Spec generation for the one requirement that needed no prior fix or decision
9. Execution vs dev + triage
10. This report

**Artefacts:**
- Pack — [`tests/e2e/test-packs/quality-gates-2026-07-31.md`](../test-packs/quality-gates-2026-07-31.md)
- Gate — [`tests/e2e/quality/console-errors.spec.ts`](../quality/console-errors.spec.ts)
- Fixture — `CONSOLE_ERROR_BASELINE` / `watchConsoleErrors` / `newConsoleErrors` in [`fixtures.ts`](../fixtures.ts)

---

## Part A — Project results

### A.1 Suite execution

| Project | Command | Result | Time |
|---|---|---|---|
| `chromium` (guest) | `npx playwright test --project=chromium` | 57 passed, 5 skipped | 5.1m |
| `chromium` (new gate) | `npx playwright test --project=chromium quality/` | 5 passed | 2.1m |
| `chromium-auth` | `npx playwright test --project=chromium-auth --workers=1` | 4 passed (incl. `setup`) | 3.1m |

Stateful cases genuinely transacted: **TC-010** placed a guest order via the PayFast
sandbox, **TC-011** completed a logged-in checkout, **CLUB-TC4** generated a Wine Club
subscription. Real orders/subscriptions now exist on dev, as designed (`KWV_SANDBOX=1`).

**No flakes.** Zero retries were needed; the slow-staging timeouts in
`playwright.config.ts` held across all three runs.

### A.2 Coverage gaps — both against PRD-required testing

The 5 skips are all environment guards, not defects. Two of them cover work the PRD
explicitly requires to be verified in testing, so they are gaps, not noise:

| Gap | Skipped cases | PRD requirement | To close |
|---|---|---|---|
| **GAP-1 — email templates unverified** | `EMAIL-TC1`, `EMAIL-TC2` (`@emails @admin`) | L166, L312, L354, L456 — "email templates … verified in testing" | Set `KWV_ADMIN_EMAIL` / `KWV_ADMIN_PASSWORD`. Dev has the *Disable Emails* plugin active, so templates are checked via the WooCommerce admin email preview, which needs an administrator. **The admin-UI selectors in this spec have never been executed** — expect to adjust them on first real run. |
| **GAP-2 — discount rules unverified** | `TC-008` (coupon) | L312, L354, L456 — "discount/role rules … verified in testing" | Set `KWV_COUPON_CODE=test` (coupon id 183087, 10%). Confirm the cart threshold first. `PINOTAGE100` is advertised in the promo bar but its rule values are still unconfirmed. |

The remaining 3 skips (`CONTACT-TC1`, `VISIT-TC3` behind `KWV_ALLOW_FORM_SUBMIT`) are
correctly off by default — they create form entries and fire notifications.

### A.3 Findings

Full detail, evidence and attribution in the pack. Summary:

| ID | Finding | Owner | Severity |
|---|---|---|---|
| **FND-1** | Promo bar fails WCAG 1.4.3 — **2.98:1** (`#FFFFFF` on `#B29143` @ 15.72px/500; needs 4.5:1) | **Theme** — `styles/sections/header-promo-bar.json` pairs `background: brand-500` with `text: base` | Medium — needs a **design decision** (CC-3) |
| **FND-2** | Inner-page header logo is `<a href="/">` with **no accessible name**, and it is in tab order | **Content on dev** — `patterns/header-light.php:21` uses `wp:site-logo`; attachment **182341** has `_wp_attachment_image_alt` = NULL. Homepage passes only because its DB template bakes in `wp:image alt="KWV"`. | Medium — cheap fix, also needed on production's logo attachment |
| **FND-3** | `woocommerce-google-analytics-pro` throws on `/shop/` (`addEventListener` of null) and `/checkout/` (`ajax` of undefined) | **Third-party plugin** | Medium — the checkout one sits inside conversion tracking on the order path |
| **FND-4** | `parts/promo-bar.html` is declared `area: header`, so it renders a **second `<header>`/`banner` landmark** next to the real header | **Theme** — one-line `theme.json` change (`area: uncategorized`) or nest it in the header part | Low |
| **FND-5** | `/shop/` has **no meta description** (`/` has one) | Yoast archive config | Low |
| **FND-6** | Single product renders **no related-products section**; no Payflex widget appeared in the a11y tree on a R349 product | Open bug / **unverified** | Medium — needs a focused check |

**A previously recorded finding is withdrawn.** "Three gateways live (PayFast, EFT,
Payflex) conflicts with the PRD's consolidate-to-PayFast rule" is not supported by
the source. PRD L209 says *"Consolidate PayFast **plugins** to one instance"* —
duplicate plugins, not gateways — and the PRD requires PayFlex to be verified in
testing (L166, L312, L354, L456). Three gateways is consistent with the PRD.

### A.4 Change-control items (not built)

| ID | Item | Excluded by |
|---|---|---|
| **CC-1** | `@a11y` axe suite: per-page + per-widget scans, keyboard traversal for mega-menu / mini-cart / filter overlay / age gate / popups, WCAG 2.2 AA criteria per case | PRD L128, L383 — formal accessibility audit excluded |
| **CC-2** | SEO/metadata suite: title, description, canonical, robots, OG/Twitter, structured data | PRD L127, L614 — ongoing SEO support excluded; Yoast is a licence, not a requirement |
| **CC-3** | FND-1 remediation — changes a supplied design-token pairing | Supplied desktop designs |

Worth saying plainly: two real a11y defects surfaced from roughly ten minutes of
automated scanning of two pages. For a consumer alcohol brand that is a live
exposure, and CC-1 looks worth buying before launch. It remains a scope decision
that belongs to whoever owns scope — the exclusion stands until they change it.

---

## Part B — Agent evaluation

### B.1 What fired correctly

| Behaviour | Evidence |
|---|---|
| **Integration pre-flight** | All five named integrations reported one-line with degraded paths. Figma (not wired this session) and BugHerd (unauthenticated) correctly reported `unavailable → <degraded path>`; no ticket was claimed. |
| **Scope-exclusion discipline** (PR #1392) | Refused the a11y suite (PRD L128/L383), the SEO suite (PRD L127/L614), and the perf audit (PRD L128/L384) — each with the citation. Recognised that Yoast Premium in a licence list is not an SEO requirement, exactly as the contract's own example states. Routed all three to change-control **before** writing cases, not after. |
| **Performance recognise-and-route** | Extracted a performance rule with an ID, refused to emit a timing assertion or a `@perf` project, recorded `deferred → pagespeed-agent`, and labelled the one observed timing as an environment-bound observation rather than a metric. |
| **Baseline discipline** | Captured both baselines live rather than guessing an allowlist. The gate asserts *no new* errors, so it lands green against known debt. |
| **Right-sizing** | 3 confirmed requirements → condensed pack, stated with a reason. |
| **Portability-aware persistence** | Theme repo has no `.github/`, so the pack landed at the repo-local `tests/e2e/test-packs/` fallback instead of assuming a control plane. |
| **Review gate** | Held. No spec was generated for the requirements that depend on an unmade decision (QG-R2, CC-1/2/3). |
| **Gate actually works** | Negative control: removing the `/shop/` baseline entry made `TC-QG1` **fail** with the real error text; restoring it passed. The gate is not vacuous. |

### B.2 Issues found in the agent

#### AG-1 — Material: the a11y baseline is captured with a different rule set than the gate it feeds

The contract splits a11y into **explore** (`lighthouse_audit`, Chrome DevTools MCP)
and **gate** (`@axe-core/playwright`, assert no new violations vs the recorded
baseline). Those are different rule sets. Lighthouse runs a *subset* of axe and
omits axe's `best-practice` tag entirely.

We hit this live. **FND-4** (duplicate `banner` landmarks) is axe's `landmark-unique`
rule, which sits in `best-practice` — Lighthouse never ran it, and scored the pages
94–97 while a full axe run would have flagged it. A baseline captured this way
records fewer violations than the gate will find, so the first real axe run fails on
"new" violations that were pre-existing all along — and the usual response to that is
to widen the baseline, which the contract explicitly forbids.

**Recommended fix:** in `shared/core-prompt.md` § Accessibility Rules, state that the
recorded baseline **must be captured with the same tool and rule set as the gate**
(`@axe-core/playwright`, with the tags the gate asserts). Keep `lighthouse_audit` for
discovery only, and say so — it currently reads as an acceptable baseline source.

#### AG-2 — The contract has no notion of an *environment-invalid* gate

The Environment & Test-Data Contract treats a missing baseline as "unmeasured → mark
as a gap". But some gates are not merely unmeasured on a staging environment, they are
**invalid** there. `/` and `/shop/` both score SEO 58 on dev, dominated by
`<meta name="robots" content="noindex, nofollow">` — correct and deliberate on dev. A
robots/indexability assertion baselined against dev would encode the exact opposite of
the production requirement, and it would pass.

**Recommended fix:** add an environment-validity note to § SEO & Metadata Rules —
indexability, canonical and robots assertions are production-only; never baseline them
against a `noindex` staging site. Consider a third state for baseline fields:
`captured` / `gap` / **`not valid in this environment`**.

#### AG-3 — Minor: right-sizing is driven by a count that scope discipline suppresses

Right-sizing keys off confirmed-requirement count. Under strict scope discipline that
count trends towards zero while the *output* grows — this run produced 3 confirmed
requirements (→ condensed) but 6 findings and 3 change-control items, so the condensed
pack is longer than a full pack for an in-scope flow would be. The heuristic doesn't
model findings/CC volume.

**Recommended fix:** one line — findings and change-control items don't count towards
right-sizing, and a pack dominated by them should say so up front rather than reading
as an oversized condensed pack.

#### AG-4 — Minor: no guidance on landing a gate into an existing suite

§ Console Error Budget says "capture console messages per page during functional and
smoke runs", which implies editing the specs. Here that meant touching 22 green spec
files for a coverage-expansion gate. We landed a shared fixture plus one focused
`@console` spec instead, and left the existing specs to adopt the fixture
incrementally.

**Recommended fix:** name fixture-plus-focused-spec as the acceptable landing pattern
when a suite already exists, so the agent doesn't propose a 22-file diff for a gate.

Related, smaller: nothing in the contract says *don't land a red assertion for an
unfixed defect*. QG-R2 (logo accessible name) was correctly scoped as fix-then-assert,
but that came from judgement, not the prompt.

#### AG-5 — Minor: "Blocks vs classic" detection has no prescribed method, and the obvious one is wrong

The WooCommerce rules say to detect Blocks vs classic checkout *before* writing
locators, and name the class-name tells (`.wc-block-checkout` vs `#customer_details`).
They don't say **how** to look. Detecting from a Playwright accessibility snapshot —
the natural artefact when grounding via Playwright MCP — silently gives the wrong
answer, because an a11y snapshot carries roles and accessible names and **no CSS
classes at all**.

This run made exactly that mistake: an initial pass concluded "classic (zero
`wc-block-*` nodes)" from an a11y snapshot. A DOM query then showed
`.wc-block-checkout` present with 162 `wc-block-components-*` nodes — the site is
**Blocks**, as the existing specs and `tests/e2e/README.md` already had right. Had it
gone unchecked, the pack would have told the next run to write `#billing_*` locators
against a Blocks checkout.

**Recommended fix:** name the detection method, not just the tells — query the DOM
(`browser_evaluate` / `page.locator`) for the marker classes. Add a one-line warning
that a11y snapshots cannot answer class-name questions.

#### AG-6 — Housekeeping: pack persistence has drifted across two locations

Prior packs live in the workspace at `reports/test-packs/`; this one is in the theme
repo at `tests/e2e/test-packs/`. Both are defensible under "prefer a project-configured
location", but the project hasn't configured one, so the fallback picks per-repo.

**Recommended fix (project side, not agent):** record the configured path in
`tests/e2e/README.md` so future runs are deterministic. Test packs that describe specs
in this repo belong beside them.

---

## Evidence & reproduction

```bash
cd wp-content/themes/kwv-theme-2026/tests/e2e
npm ci && npx playwright install chromium
cp .env.example .env      # fill KWV_BASE_URL, KWV_SANDBOX=1, customer creds

npx playwright test --project=chromium                     # 57 passed, 5 skipped
npx playwright test --project=chromium quality/            # 5 passed  (new @console gate)
npx playwright test --project=chromium-auth --workers=1    # 4 passed  (serial — dev locks out on repeat logins)
```

Baselines were captured live on 2026-07-31: `lighthouse_audit` (desktop, `navigation`)
on `/` and `/shop/` via Chrome DevTools MCP; per-page console capture on `/`, `/shop/`,
product, `/cart/`, `/?add-to-cart=181708` and `/checkout/` via Playwright MCP. Both are
recorded in the pack's Environment & Test-Data Contract. FND-2 was attributed by
querying `_wp_attachment_image_alt` for attachment 182341 through the WordPress MCP.

---

## Action items

**Agent (`lightspeedwp/.github`)**
1. **AG-1** — baseline must use the same rule set as the gate; Lighthouse is discovery only. *(material)*
2. **AG-2** — add environment-validity handling for SEO/indexability gates.
3. **AG-3, AG-4, AG-5** — right-sizing note; fixture-plus-spec landing pattern; prescribe a DOM query for Blocks-vs-classic detection.

**Project (`kwv-theme-2026` / dev)**
4. **FND-2** — set alt text on logo attachment 182341 (dev **and** production), then land TC-QG2.
5. **FND-4** — change `promo-bar` to `area: uncategorized` in `theme.json`.
6. **FND-1 / CC-3** — design decision on the promo-bar colour pair.
7. **GAP-1** — fill `KWV_ADMIN_*` and run the `@emails` cases; expect selector adjustment on first run.
8. **GAP-2** — set `KWV_COUPON_CODE=test` after confirming the cart threshold.
9. **FND-3** — raise with the `woocommerce-google-analytics-pro` owner; keep the baseline comment until fixed.
10. **FND-6** — confirm related products and Payflex widget behaviour on single product.
11. **CC-1 / CC-2** — route to the Change-Control Register. Recommend pricing CC-1 before launch.

Nothing in QG-R2, CC-1, CC-2 or CC-3 has been built. No BugHerd ticket was raised
(integration unavailable). No GitHub write has been made beyond this branch.
