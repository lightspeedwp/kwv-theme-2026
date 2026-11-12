# Test pack — launch coverage (2026-10-22)

**Status:** awaiting review. No spec code is written until this pack is approved.
**Branch:** `test/e2e-launch-coverage` (off `develop` @ `b207491`).
**Target:** `https://kwv.lightspeedwp.dev` (dev). Never production.
**Linear:** [LS-1175 · 15. Testing & Launch](https://linear.app/lightspeedwp/issue/LS-1175/15-testing-and-launch)

This pack extends the existing suite (`full-site-2026-07-24`, `quality-gates-2026-07-31`) to cover what has shipped since July. It also records the baseline run that was taken before any spec changes.

---

## 1. Baseline run: existing suite against dev, unchanged

| Project | Result |
|---|---|
| Guest (`chromium`, 2 workers) | 58 passed / 10 failed (7.7 min) |
| Auth (`chromium-auth`, serial) | Setup failed at first; after the account fix: 3 passed / 1 failed |
| Login reruns (ACCT-TC1, EMAIL-TC1/2) | 3 passed |

**Email templates (EMAIL-TC1/2) passed for the first time.** This closes gap G4 / GAP-1, so the PRD's "verify email templates" now has evidence.
**The coupon `test` applies correctly.** The discount line appears, so gap G3 / GAP-2 is closed on the site side. The spec still needs the fix below.

### 1.1 Triage: every failure was in the tests, none on the site

| Test | Root cause | Fix |
|---|---|---|
| Auth setup, ACCT-TC1 | Customer 23387 no longer exists on dev. The admin `test-user` created on 2026-10-22 has the same email, so the customer login hit the admin account. | **Done:** created customer `kwv-e2e-customer` (ID 23395, `zared+kwv-e2e@…`, role customer) and updated `.env`. |
| EMAIL-TC1/2 | Wrong admin password in the first run. | **Done:** passing. |
| TC-001 add from Shop grid | Shop sorts by popularity, and the seeded product is no longer on page 1 (15 of 167). | Search the Shop archive for the product (`/shop/?s=…&post_type=product`) before picking the card. |
| TC-002 mini-cart links | "View my cart" was hidden on purpose (`blockVisibility:false` in DB part `mini-cart` 183128, 2026-08-04). The drawer offers only "Go to checkout". | Assert that "Go to checkout" is present and "View my cart" is absent. |
| TC-008 coupon | After applying, the coupon panel collapses. The loose `/coupon\|code/` locator then matched the **Postal code** field and typed the invalid code into it. | Reopen the panel and target the coupon input by its own label. |
| CONTACT-TC1 | `/email/` doesn't match the "E-mail" label, and the required "Reason for contact" field was never filled. | Fill fields by their exact labels and use `KWV_FORM_TEST_EMAIL`. |
| VISIT-TC1 | The venue h2 is now "Emporium" (was "KWV Emporium", renamed 2026-10-21). | Update the names. Folded into the new carousel cases (VIS-TC1). |
| VISIT-TC3 | Only Name was filled. Form 4 now requires E-mail, Date, Time (hour/minute) and Number of people. | Rewritten as BOOK-TC1, which covers all three venues. |
| CLUB-TC2 | The product is now "WINEMAKER’S Selection Edition 15" (183886, R999). Its curly apostrophe doesn't match `/winemakers selection/`. | Match `/winemaker.?s selection/i` instead. |
| CLUB-TC4 | `goto('/?kwv-join=current')` timed out at 45s. The 302 to `/checkout/?add-to-cart=183886` plus the checkout render is slow on dev. | Wait for the URL to commit (`waitUntil: 'commit'`), then wait for the checkout form, with a longer timeout. |

### 1.2 Observations to confirm (not failures)

- **O-1.** On `/visit/`, every carousel slide's h3 repeats the venue name ("Emporium" ×3, "Cathedral Cellar" ×2, "House of Fire" ×2). The 2026-10-15 CHANGELOG describes the later slides as tours and tastings, each with its own title. Is this content still to come, or intended?
- **O-2.** `/visit/kwv/` (booking page) shows "General / Tours and Tastings / Member Shops / Shop Online" contact sections under the form. The same block is on `/contact/`. Is that intended for the booking pages?

---

## 2. Environment and test-data contract

| Item | Value | Notes |
|---|---|---|
| `KWV_BASE_URL` | `https://kwv.lightspeedwp.dev` | |
| `KWV_SANDBOX` | `1` | PayFast TEST mode completes without a card. |
| Customer | `kwv-e2e-customer` (23395) | Created 2026-10-22. **Delete after testing,** together with `test-user`. |
| Admin | `test-user` (23393) | Used for the email preview only. Temporary. |
| Seeded product | 181708 KWV Classic Chardonnay 2025, R79 | Exists, purchasable. |
| Coupon | `test` (183087, 10%) | |
| Form submits | `KWV_ALLOW_FORM_SUBMIT=1`, submitter `KWV_FORM_TEST_EMAIL=zared@lightspeedwp.agency` | Dev has *Disable Emails* active, so notifications are not delivered. The client's per-form recipient addresses are being supplied by Neo and are added before launch. |
| Workers | max 2 (guest), 1 (auth) | CrowdSec bans the whole IP on bursts. |

**Data the suite creates on dev**, to clean up before launch:
- WooCommerce orders and a pending `shop_subscription` per run, for customer 23395 and guests
- Gravity Forms entries in forms 3, 4, 5, 6, 9 and careers
- Users 23393 and 23395

---

## 3. New and changed cases

Priorities: **P1** launch-critical, **P2** should have, **P3** nice to have. `S` means stateful (creates an order or entry).
All locators are accessible-first. Data-dependent cases discover their data at run time (from the menu, list or Store API) rather than hard-coding IDs that the catalogue resync can change.

### Shop and catalogue

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| CAT-TC1 | P1 | Shop › Category filter lists the current tree: Wine, Spirits (incl. Whiskey, Brandy, Rare and Limited) and Lifestyle. There is **no** "Brands" category. Each listed category page returns 200 with ≥1 product, or shows the empty state. | `kwv-product-cat-restructure-2026-10`, catalogue sync script |
| CAT-TC2 | P1 | `/brand/<slug>/` pages (brand = `product_brand` only) list that brand's products. Brand slugs are sampled from the Shop › Brand filter, up to 5. | same |
| CAT-TC3 | P2 | Shop filter sections are collapsed by default and toggle via `aria-expanded`. No empty filter section is rendered. | CHANGELOG (shop filters hide-empty) |
| CAT-TC4 | P2 | Product grid at 1440: 4 cards per row, and every "Add to cart" label sits on one line (button height ≈ icon height). Checked with bounding boxes, not pixels. | CHANGELOG 2026-10-21/22 |
| CAT-TC5 | P2 | Category, brand and Shop pages show the promo bar. The header stays at the top of the viewport after scrolling (sticky). | CHANGELOG 2026-10-12, Phase 2 audit |
| PDP-TC1 | P1 | Single product: the Payflex widget renders inside the add-to-cart form. Add to cart updates the cart count. | PRD (verify Payflex), `kwv-single-product-payflex-and-db-template` |
| PDP-TC2 | P1 | Single product: the Related products section renders with ≥1 product card. This guards against the WebToffee regression. | `kwv-related-products-empty` |

### Global navigation

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| NAV-TC1 (rewrite) | P1 | Shop mega menu: hovering opens the panel. Hovering a row shows its fold-out column, and that column **stays open** while the pointer moves down to it (`is-kwv-open`). Hovering another row swaps the column. Moving off closes the panel. | CHANGELOG 2026-10-12 (`mega-menu-hover.js`) |
| NAV-TC6 | P2 | The other header menus (About, Visit us) open, and their links resolve with status < 400. | `kwv-megamenu-nested-nav-bleed` |
| NAV-TC2 (rewrite) | P1 | Mobile drawer at 390: the hamburger opens it and Close shuts it. Submenus start collapsed, and each toggle expands only its own submenu. Checked on the home (transparent) header, a light header (Shop) and the dark header (single post). | `kwv-mobile-drawer-submenu-and-icon` |
| NAV-TC7 | P2 | The transparent header overlays the hero on `/`, `/visit/`, `/about-us/history/` and `/careers/`, with nav links visible. | CHANGELOG 2026-10-12 |
| SRCH-TC1 | P2 | Header search (Advanced Woo Search): typing shows the live results dropdown with products. Clicking a result opens the product. Enter goes to the results page. | `kwv-aws-search-integration` |
| FTR-TC1 | P2 | Footer shows the legal notice "NOT INTENDED FOR PERSONS UNDER THE LEGAL ALCOHOL AGE LIMIT". | CHANGELOG 2026-10-09 |
| LINK-TC1 | P2 | Internal-link crawl: every same-origin `<a href>` in the header, footer and the 15 smoke pages returns < 400 via HEAD/GET, deduplicated and throttled. External links are listed in the report but not failed. | Launch readiness |

### Visit us, events and bookings

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| VIS-TC1 (replaces VISIT-TC1) | P1 | Three venues (Emporium, Cathedral Cellar, House of Fire), each a carousel with 4 / 3 / 3 slides. "Next slide" advances the slide and enables "Previous slide". There are no dots and no autoplay: the slide is unchanged after 6s idle. Every slide has a Book link to `/visit/{kwv,cellar,fire}/`. | CHANGELOG 2026-10-15, `kwv-visit-venue-carousels` |
| VIS-TC2 (replaces VISIT-TC2) | P2 | Each venue's Menu button opens **its own** popup (Emporium 182953, Cellar 182954, Fire 182955). The popup content names the venue, and both Close and Esc dismiss it. | `kwv-popup-maker-mechanics` |
| VIS-TC3 | P1 | Upcoming Events: when there are no published events the whole section is absent (heading included). When there are events, each card links to its single event. The test adapts to the live data. | CHANGELOG 2026-10-21 (`kwv-hide-if-empty`) |
| EVT-TC1 | P2 | Single event (reached from the list; skipped when there are no published events): date and time shown, "Accommodation nearby" shown only when it has entries, exactly one Book now link. | `kwv-event-accommodation` |
| EVT-TC2 | P1 | Book now routing: a branded event links to its venue booking page and an unbranded one links to `/visit/event-enquiry/`. Both carry `?event=`, and the booking form pre-fills the event. Skipped when there are no published events. | `kwv-event-booking-routing` |
| BOOK-TC1 (replaces VISIT-TC3) | P1, S | `/visit/kwv/`, `/visit/cellar/`, `/visit/fire/`: the form validates required fields (empty submit shows errors). A valid submit (Name, E-mail, Date, Time hh:mm, Number of people) lands on the venue's thank-you page. | `kwv-booking-forms-state` |
| BOOK-TC2 | P2, S | Event Enquiry (form 9): a valid submit lands on its thank-you page. | `kwv-event-booking-routing` |

### Content pages

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| CONTACT-TC1 (fix) | P1, S | Empty submit shows validation errors. A valid submit (Name, E-mail, Reason, Message) lands on the Contact thank-you page. | `kwv-gravity-forms-contact-booking` |
| HIST-TC1 | P2 | History timeline: slides render newest first. Clicking a milestone image opens the core lightbox showing that image, and closing it returns to the timeline. | CHANGELOG (timeline lightbox), `kwv-history-timeline-dev-content` |
| HOME-TC1 | P2 | Homepage: the brand carousels render, and every card links to a `/brand/` or product URL that returns < 400. The "Our Legacy" video has a poster. Load timing is not asserted. | CHANGELOG 2026-10-09, `kwv-carousel-block-markup` |
| NEWS-TC1 | P3 | News list → category archive highlights the active category → single post renders. | CHANGELOG (category template) |

### Careers

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| CAR-TC4 | P2 | A job title link also opens the modal. LinkedIn Easy Apply shows only when the job has a LinkedIn URL. Skipped when there are no open jobs. | `kwv-careers-architecture` |
| CAR-TC5 | P2, S | Apply in the modal (with a tiny PDF CV fixture) shows the confirmation in place. | same |

### Wine club

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| CLUB-TC1 (extend) | P1 | The edition card shows the newest wine-club product (title, eyebrow, price), matching the Store API's newest in category 182. Sign up links to `/?kwv-join=current`. | `kwv-winemakers-club-join-flow` |
| CLUB-TC2 (fix) | P1 | Join lands on checkout with the current edition and "every 3 months" terms. | same |
| CLUB-TC5 | P2 | A single subscription product URL 301s to `/wineclub/`, while a simple product URL stays 200. | same |
| CLUB-TC4 (fix) | P1, S | Logged-in sign-up reaches the PayFast sandbox. | same |

### Checkout and order

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| TC-001, TC-002, TC-008 (fix) | P1 | As in §1.1. | |
| TC-010 (extend) | P1, S | After the PayFast sandbox completes, the order-received page shows the order number, line items and total. | `order-confirmation` template |

### Responsive (mobile and tablet adaptations of the desktop designs)

| ID | Pri | Behaviour | Source |
|---|---|---|---|
| RESP-TC1 (extend) | P2 | Add `/visit/`, `/wineclub/`, `/about-us/history/`, `/careers/`, a category page and a brand page to the 390/768 no-horizontal-overflow and footer-visible checks. | PRD (responsive adaptation) |
| RESP-TC2 | P2 | At 390: the Visit carousel arrows sit below the slide and work, the booking form fields are usable, the careers modal fits the viewport, and the Shop mobile filter overlay opens and closes. | CHANGELOG 2026-10-15, theme |
| config | P2 | Add a `mobile-chrome` (Pixel 7) project that runs `@responsive` and `@mobile` tagged cases only. | |

### Quality gate

| ID | Pri | Behaviour |
|---|---|---|
| TC-QG1 (re-baseline) | P2 | Re-record `CONSOLE_ERROR_BASELINE` from a clean run, since new JS has shipped since July. Extend the paths to `/visit/`, `/wineclub/` and `/about-us/history/`. |

**Totals:** about 30 new or rewritten cases (15 P1). Expected suite size afterwards is about 95 cases.

---

## 4. Out of scope, not built

These are excluded by Estimate 3168 and recorded as change-control candidates, not tests:
- axe/WCAG accessibility audits and focus-order audits (PRD L128/L383). The existing careers modal focus-return check stays as a functional check.
- SEO and metadata, schema (JobPosting, Event) and sitemaps (PRD L127/L614).
- Performance and timing (LCP, video weight, lazy-load timing).
- Pixel or Figma parity (site gutter, hero title offsets). Design sign-off is manual.
- Bespoke mobile or tablet designs, data migration, plugin admin config, and email deliverability (emails are disabled on dev).
- Careers expiry cron, Payflex capture (needs a gateway card), and the 60+ per-country Popup Maker popups.

## 5. Decisions for the reviewer

1. Approve the pack as-is, or adjust priorities or cut P3s.
2. O-1 and O-2 (§1.2): intended or not?
3. LINK-TC1 checks external links too, but only reports them without failing. Is that OK?
