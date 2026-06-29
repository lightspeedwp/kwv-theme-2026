# Changelog

All notable changes to the **kwv-theme-2026** theme are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed (shop / product archive — Figma node 59:848)
- Rebuilt `patterns/woo-product-archive.php` (the `kwv/woo-product-archive` pattern behind `archive-product.html`) to match the Figma shop layout: a full-width **hero**, WooCommerce **breadcrumbs** (`Home / All Products`), a **filters sidebar** (Brands, Price Range, Type, Pack Size, Special Offers), a four-column **product grid** that uses the existing **Product Card** part (`kwv/woo-product-card`) in the Product Collection query loop, and a styled **pagination** (chevron prev/next + numbers). Token-only spacing/typography/colour, inferred from the design (gold `brand-500`, cream `brand-100`, `neutral-300` hairlines, `font-size|100` filter labels, `border-radius|200` cards).
- Added `patterns/shop-hero.php` (`kwv/shop-hero`) — a reusable, self-contained **Shop Hero** pattern: a cream (`brand-100`) panel carrying the dynamic **query title** ("All Products" on the shop, the category name on category archives) beside the uploaded **`shop-hero-image`** lifestyle photo (resolved by attachment slug at render — no hardcoded URL). The archive pattern inlines it via `require __DIR__ . '/shop-hero.php'` rather than a nested `wp:pattern` reference, because the block-template renderer does not resolve a `wp:pattern` nested inside another pattern's content (the inner reference silently drops). `kwv/shop-hero` stays an independently registered, Inserter-visible pattern and the single source of truth.
- Added `templates/taxonomy-product_cat.html` so product **category archives** use the same archive pattern (hero + filters + grid), reusing the Shop Hero with the category name as its title.
- Styled `core/query-pagination` in `theme.json`: transparent links, centred numbers with `neutral-700` text, gold (`brand-500`) bold current page, muted dots, gap from `spacing|10/20`. Added `woocommerce/product-filters` (hairline `neutral-300` dividers between filter sections, `font-size|100`) and `woocommerce/product-results-count` (`font-size|100`, `neutral-700`) block styles. Also corrected the archive no-results block to numeric radius tokens (`border-radius|200`) — the previous `sm`/`md` radius slugs were orphaned refs (this theme's radius scale is numeric).
- **Filter data (local dev):** created the `product_brand` terms (KWV, Imagin, Cruxland, Laborie, Roodeberg) and two global attributes — **Pack Size** (`pa_packsize`, 750 ml) and **Special Offers** (`pa_specialoffers`: On Promotion / Gift Set / New Release) — and assigned them across the 12 products so every filter facet has live counts. The "Type" filter reuses the existing `product_cat` taxonomy. Dev seed data, not theme code. Renamed the WooCommerce Shop page title to "All Products" (slug unchanged) so the archive title matches the design.
- **Reset the DB `archive-product` template override** (the old "Product Catalog" Site Editor customization) so the theme file `archive-product.html` is once again the source of truth; backed up before deletion.

### Changed (mini cart styling — Figma node 107:1691)
- Rebuilt the **Mini Cart** section of `assets/styles/woocommerce.css` to match the Figma "CART" drawer. The source frame has **no bound variables**, so tokens were inferred from the KWV ramp: centred uppercase **Jost-black** title with a `neutral-300` hairline (core makes the title a flex container with a scroll-fade mask + negative bottom margin — overridden so the title centres via `justify-content` and the hairline shows on a static, non-overlapping title); **Jost-bold uppercase** product name + price stacked tight; the quantity stepper restyled into a `neutral-200` pill with the count rendered as a **gold (`brand-500`) round badge** in `base` text; muted `font-size|100` / `neutral-700` subtotal labels with a `heading`/`semi-bold` grand-total emphasis; a white (`base`) footer surface (was a `neutral-200` band); and a full-width **black (`contrast`) CHECKOUT bar** (square corners, uppercase Jost-black, `brand-500` hover) with the "View cart" action demoted to a quiet text link. Token-only (no raw hex/font literals); CSS brace-balanced.
- **Scoping fix:** the drawer renders as a body-level portal whose root is `.wc-block-mini-cart__drawer` — **not** a descendant of `.wc-block-mini-cart` (the header button wrapper). All item-level rules (image, name/price, quantity stepper + gold badge, remove link) are therefore scoped under `.wc-block-mini-cart__drawer`; the footer/title/badge classes are drawer-internal element classes that match on their own. Verified by capturing the live drawer DOM + screenshot via headless Chromium against the local site.
- **Subtotal update is NOT a code bug:** clicking the stepper ＋/− does update the cart and subtotal — the change is driven by core's Interactivity API store firing a debounced `POST /wc/store/v1/batch`, after which the `state.formattedSubtotal`-bound value re-renders. On the local dev box this takes **~7 s** because the environment is severely slow (20–33 s page loads), so a quick glance reads as "not updating". Reproduced and confirmed working (R1 720,00 → R1 819,00 after a ＋ click). No console errors. The dev-server performance itself is a separate, out-of-scope environment concern.
- **Out of scope / flagged, not built:** the Figma shows separate **Subtotal + Tax (Incl) + TOTAL** lines, a literal "CART" title, and a single button — these are markup/content differences (extra totals rows, title text, button removal) beyond CSS styling. Left for the Change-Control Register rather than altering the Mini Cart block markup.

### Added (category mega menus — Figma nodes 1:1785 / 1:1818)
- Added `inc/mega-menu.php` (required from `functions.php`) — a `[kwv_mega_menu root="…"]` shortcode that renders the product-category fold-out menu from the live `product_cat` taxonomy (dummy categories on dev, real ones on launch — no markup change needed). Emits three columns (parents → children → grandchildren) wrapped in a WordPress **Interactivity API** region; the first parent and its first grandchild-bearing child are open by default (matches the Figma states). Resolves directives server-side via `wp_interactivity_process_directives()`. Shortcode injection point is Ollie Menu Designer's `do_shortcode()` pass over the menu template part. Also registers the `is-style-kwv-megamenu-search` block style for `core/search`.
- Added `assets/js/mega-menu.js` — the `kwv/megamenu` Interactivity store (registered as a script module depending on `@wordpress/interactivity`). Derived state getters (`isParentActive` / `isChildActive` / `isGrandchildVisible`) read the inherited context; `selectParent` / `selectChild` actions drive the side fold-out on hover/click.
- Added `parts/shop-mega-menu-dynamic.html` ("Shop Mega Menu (Dynamic)", `menu` area, registered in `theme.json`) — a flex group pairing the shortcode with a `core/search` block in the `is-style-kwv-megamenu-search` style. Both All Wines and All Spirits live in the menu's first column, so one part covers both ranges. (This is the dynamic/Interactivity option; see the nav option below.)
- Added `assets/styles/ollie-mega-menu.css` (enqueued only when an `ollie/mega-menu` block is present) — full-width white panel + `shadow-300`, the three fixed-width category columns with hairline `neutral-300` row dividers, `font-size|100` body text, gold (`brand-600`) hover/active rows, a CSS right-chevron on rows that expand, and a right-aligned search column. The child/grandchild columns grid-stack their per-parent lists in one cell so the fold-out can cross-fade (250ms opacity + side-slide, `prefers-reduced-motion` aware) instead of snapping via `display:none`. Colour rules are container-scoped so they beat the transparent header's white nav-link colour. Includes a `max-width:781px` wrap safeguard (responsive adaptation; the Ollie plugin owns the collapsed mobile menu).
- Added `assets/styles/core-search.css` — the `is-style-kwv-megamenu-search` underline search field (transparent, no border/radius, leading magnifier via `row-reverse`, `neutral-400` bottom rule). Suppresses the theme's global input `box-shadow` so only the single underline shows.
- **Dev data (local only):** reshaped the `product_cat` tree to the Figma structure — `All Wines` / `All Spirits` parents, their children (Red Wine, White wine, Rose, Cap Classique & Sparkling wine, Dessert wine / Brandy & Cognac, Gin, Liqueur, Ready to drink, Rum, Scotch, Tequila, Vodka) and grandchildren (White wine → White blends/Chardonnay/Chenin Blanc/Grenache blanc/Sauvignon blanc; Gin → Cruxland Gin/Imagin), with WooCommerce `order` term-meta set so the menu renders in design order. Dev seed data, not theme code.

### Added (category mega menu — native nav option)
- Added `parts/shop-mega-menu-nav.html` ("Shop Mega Menu (Nav)", `menu` area, registered in `theme.json`) — the same mega menu built from a native `core/navigation` block with `core/navigation-submenu` nesting (All Wines / All Spirits → children → grandchildren, linking to the real `product_cat` archives) plus the styled `core/search`. **Fully editable in the Site Editor** — no shortcode, no custom JS (core handles open/close). This is the editor-native alternative to the dynamic option; content is curated by hand rather than auto-pulled from the taxonomy. The live "Shop" nav trigger points here (`menuSlug:"shop-mega-menu-nav"`); switch the Dropdown block's menu to "Shop Mega Menu (Dynamic)" to use the other option.
- Extended `assets/styles/ollie-mega-menu.css` with a `.kwv-mega-menu-nav` section that re-skins core's nested submenus into the KWV mega menu: top-level list as column 1, submenu flyouts repositioned into co-planar columns (`.has-child` set `position:static` so columns anchor to the panel top, `height:auto`/`overflow:visible` to defeat core's JS height-collapse), the inherited uppercase/size-300 link styling reset, left-aligned labels, no hover underline, side-pointing carets, `neutral-300` row dividers pinned uniform across all levels/states, and hover-reveal with the first parent's column pre-opened. Note: core can't pre-open a *nested* (third) column, so column 3 reveals on hover only.

### Changed (header nav + mega-menu nav → block style variations)
- Added two `core/navigation` block style variations under `styles/navigation/`: **`main-navigation.json`** ("Main Navigation") carries the header nav's resting state (uppercase, `font-size|300`, medium weight, centred); **`mega-menu-nav.json`** ("Mega Menu Nav") carries the Shop fold-out nav's resting appearance (vertical first column, label typography, `neutral-300` row dividers, side-pointing caret). Applied via `is-style-main-navigation` (header navs in `patterns/header-light|transparent|dark.php`) and `is-style-mega-menu-nav` (`parts/shop-mega-menu-nav.html`); DB copies (front-page template, header part, mega-menu-nav part) updated to match.
- **Fixed the brand-300 underline bleeding onto the Shop parent options.** The header nav's uppercase + sliding-underline rules were a descendant selector in `core-navigation.css` (`…__container > .navigation-item > …__content`) that also matched the *nested* mega-menu nav's items — dragging the uppercase, `justify-content:center` and the brand-300 line onto "All Wines"/"All Spirits". Re-homing the resting rule in the `main-navigation` variation (scoped with a child combinator at the `…__responsive-container-content > …__container` level) keeps it on the header nav's own top row only. Same root cause fixed the parent labels rendering centred on the front end — they are now left-aligned, matching the editor.
- Split rationale (documented in each file): a block style's `css` field is wrapped by WordPress in `:root :where()` (zero specificity) and sanitised in ways that **strip `:hover`/`:first-child`, mangle `content:""`, and drop all-but-one of a comma-separated selector list**. So the variations hold only single-selector, no-pseudo resting rules (with `!important` to clear core/Ollie), while the interactive bits stay in real CSS scoped to the `is-style-*` class: the brand-300 underline `::after` + `:hover` (`core-navigation.css`) and the mega-menu's co-planar column **layout** + `:hover`/pre-open reveal (`ollie-mega-menu.css`). Net behaviour is unchanged; the styling is now organised behind the two selectable block styles.

### Added (product card pattern — Figma node 31:4290)
- Added `patterns/woo-product-card-bordered.php` (`kwv/woo-product-card-bordered`, "Product Card — Bordered") — a WooCommerce product-collection loop card matching the Figma "Card / Product" Default state: gold-bordered white frame (`is-style-product-card`), centred packshot (`woocommerce/product-image`), `core/separator` divider, linked `core/post-title` (woo namespace), a flex meta row pairing the **Bottle Size** attribute (`core/post-terms` → `pa_bottlesize`) with `woocommerce/product-price`, and an always-visible `woocommerce/product-button` styled `is-style-add-to-cart`. Block-validated and render-tested against a live product (no fatals; attribute, price, button and divider all render).
- Added a "Product Card — Bordered" section to `assets/styles/woocommerce.css` — reapplies the `is-style-add-to-cart` look to `woocommerce/product-button` (a different block from `core/button`, so the low-specificity `:where()` from the JSON variation can't beat WooCommerce's own button rules), keeps the button full-width, and suppresses the attribute link's hover underline. Includes a NOTE flagging the deferred Figma "Add Items" quantity-stepper state + gold ＋ affordance as stateful Store-API/Interactivity work (intentionally out of this card).
- **Dev data (local only):** created a global product attribute **Bottle Size** (`pa_bottlesize`) with a `750 ml` term, assigned to a few 750ml products, and enabled its archives so `core/post-terms` treats the taxonomy as viewable. This is local dev seed data, not theme code.

### Added (product card styles — Figma node 31:4290)
- Added `styles/sections/cards/product-card.json` — the `is-style-product-card` section style for `core/group`: white (`base`) frame, 1px `brand-500` border, `border-radius|200` (8px), `spacing|20`/`30` padding. A `css` block styles the BEM child elements (`.product-card__media`, `__divider`, `__title`, `__attribute`, `__price`) using the `heading` font family (Jost — interim stand-in for Trenda) and the closest preset font sizes: title/attribute → `font-size|200` (14px→16px), price → `font-size|300` (20px→19.2px); divider is a `neutral-300` hairline.
- Added `styles/blocks/button/add-to-cart.json` — the `is-style-add-to-cart` style for `core/button`: solid `contrast` (black) fill, `base` (white) label, `heading` font at `font-size|200` weight 600, uppercase, square (radius 0); hover/focus invert to a `brand-500` gold fill with `contrast` text. Auto-registered (file-based variation, no `functions.php` change).
- Font family uses the `heading` slug deliberately so that if KWV later licenses **Trenda/Avenir Pro**, swapping the font-family preset updates both styles with no edits here.

### Added (header navigation hover interaction)
- `assets/styles/core-navigation.css` — styled top-level desktop header nav links: uppercase, 1px letter-spacing, font-size `300`, `medium` (500) weight. On hover/focus tracking opens to 3px, weight eases to `regular` (400), and a 2px `brand-500` line slides out from the centre (`scaleX`), 3px under the text. Styling lands on the `.wp-block-navigation-item__content` link element so it works for both real navigation links and the Page List fallback (which has no inner `__label` span); covers the direct and `.wp-block-page-list`-wrapped top-level structures, excludes submenu links. Trailing letter-spacing is trimmed so the cluster stays centred; honours `prefers-reduced-motion`. All values reference preset/custom tokens.

### Added (homepage / front-page template)
- Added `templates/front-page.html` — the KWV homepage built from the Figma desktop design (node `99:307`): full-bleed hero cover (heading + CTA), centred "Discover Our Globally Acclaimed Portfolio" intro, three brand sliders (Wine / Spirits / Agency), an "Our Legacy" full-bleed cover CTA, a "Latest News" post query loop, and a newsletter section. Wraps the existing `header` and `footer` template parts.
- Brand sliders use the **Carousel Block** plugin (`cb/carousel-v2` + `cb/slide-v2`); slides are linked brand images (Wine → `/product-category/wine/`, Spirits & Agency → `/product-category/spirits/`) pulled from the media library by attachment ID. Slider nav/pagination left at plugin defaults (to be styled later).
- Added `patterns/blog-card.php` (`kwv/blog-card`) — a post card (featured image with `is-style-image-hover-zoom` + title) used inside the Latest News `core/post-template`.
- Added `styles/sections/cards/blog-card.json` — the `is-style-blog-card` section style (rounded, clipped media well) accompanying the blog-card pattern.
- Newsletter section uses an empty `gravityforms/form` block (form to be assigned later).
- Section headings reuse the existing `is-style-section-header` gold-underline style; all spacing/type reference numeric preset tokens.
- The front page uses the `header-transparent` template part so the nav overlays the hero.

### Changed (transparent header overlay)
- `styles/sections/header-transparent.json` now positions the header group out of flow (`position:absolute; z-index:100; inset-block-start:0; inset-inline:0`) via its `css` so the transparent nav overlays the section beneath it (its wrapping `<header>` collapses, letting the hero start at the page top). Applies everywhere the `is-style-header-transparent` style / `header-transparent` part is used. White text + links were already set.

### Added (header & footer section styles)
- Extracted the inline section styling on the header and footer patterns into reusable section styles under `styles/sections/` (`blockTypes: core/group`), all referencing numeric preset tokens:
  - `header-dark` — `contrast` background, `base` text + links, `20` padding all sides.
  - `header-transparent` — `base` text + links, `20` padding all sides, no background (sits over heroes).
  - `header-promo-bar` — `brand-500` background, `base` text, `10`/`20` padding.
  - `header-row` — `base` background, `contrast` text + links, `20` padding, 1px solid `neutral-300` bottom border.
  - `site-footer` — `contrast` background, `base` text, `neutral-400` links, `80`/`40`/`20` padding, `blockGap` `60`.
- Applied each via `is-style-*` className on the wrapping group in `patterns/header-dark.php`, `patterns/header-transparent.php`, `patterns/header-light.php` (promo bar + header row), and `patterns/footer.php`, removing the now-redundant inline `style`/`backgroundColor`/`textColor`/`border` attributes. Layout attributes (`align`, `layout`, the outer light-header `blockGap:0`) stay inline as they're structural, not reusable theme styling.

### Changed (Image Hover Zoom block style)
- Generalised the cover-only hover-zoom variation in `styles/blocks/cover/image-hover-zoom.json` to also cover **Image**, **Featured Image** and **WooCommerce Product Image** (`blockTypes`: `core/cover`, `core/image`, `core/post-featured-image`, `woocommerce/product-image`). Renamed slug/title `ati-cover-hover-zoom`/"ATI Cover Hover Zoom" → `image-hover-zoom`/"Image Hover Zoom" (no references existed) and dropped the leftover "ATI" branding.
- The selector now targets the cover background image (`.wp-block-cover__image-background`) **plus** the direct-child media of the other blocks (`> img`, `> a > img`) so deeply-nested content images aren't caught. Each selector is authored as its own single-`&` rule because WordPress's `process_blocks_custom_css()` does `explode('&', …)` — comma-grouped `&`-prefixed selectors in one rule get mis-parsed as root CSS and break.

### Added (page section styles)
- Added three section styles under `styles/sections/` (`blockTypes: core/group`), all referencing numeric preset tokens:
  - `light-page-section` — `base` background, `contrast` text; padding `50` top/bottom, `20` left/right; `blockGap` `40` between items.
  - `dark-page-section` — same spacing as light, colours inverted (`contrast` background, `base` text).
  - `section-header` — padding-left `30`, padding-bottom `10`, 1px solid `brand-500` bottom border.
  - `tinted-page-section` — same spacing as light/dark; `brand-100` background, `contrast` text.
  - `inner-page-section` — padding-left `30` only.
- All section styles (the five above + `cards/team-member-card`) set `margin.top: 0`.

### Added (About page)
- Added `patterns/template-page-full.php` (`kwv/template-page-full`, `Inserter: false`) — the page-body pattern the **Page (Full Width, No Title)** template (`templates/page-no-title.html`) referenced but that no pattern declared, so the template rendered nothing. It composes the `header` template part, a full-width `main` with `core/post-content`, and the `footer` template part. (The sibling refs `kwv/template-page-centered`, `kwv/template-post-centered` and the 404/search/sidebar variants are still dangling — separate work.)

### Fixed (Team Member Card hover)
- Moved the **entire overlay reveal animation** (resting state, transition, hover + keyboard `:focus-within`) into `assets/styles/core-group.css`, leaving `team-member-card.json` as a clean, editor-applicable structural variation (card/media/overlay layout + background only). WordPress's block-style-variation CSS compiler drops the `&:`-prefixed hover rule authored in the section JSON; with the resting state in JSON but the hover override gone, the overlay's delayed `visibility` switch never flipped early, so it sat invisible for ~300ms and then snapped in. With both states now plain CSS keyed to the stable `.is-style-team-member-card` class, the overlay **fades in and slides up** (`opacity` + `transform: translateY(24px → 0)`, 300ms `ease`) and `visibility` switches immediately on reveal. Keyboard focus reveals the bio too; `prefers-reduced-motion` disables the slide and transition.

### Added (Team post type)
- Registered a `kwv_team` custom post type (`inc/team.php`, loaded from `functions.php`): public, REST-enabled, archive at `/team`, `dashicons-groups` menu icon. A team member uses the **title** for their name, the **featured image** for their photo, and the **content** for their short bio.
- Added a `kwv_team_role` post meta field for the member's **role** — REST-exposed, `sanitize_text_field`, `edit_posts` auth — with a nonce-protected, capability-checked **Role** meta box in the editor sidebar.
- Removed the static `kwv/team-members` pattern (`patterns/team-members.php`) — superseded by the Team post type; it had hardcoded members and no template referenced it.
- Added the `kwv/team-member-card` pattern (`patterns/team-member-card.php`): a `kwv_team` query-loop grid. Each card shows the featured image, the title (name, uppercase Jost) and the role; role is bound to the `kwv_team_role` meta via the **core/post-meta** Block Bindings source. On hover/focus an **80% `contrast` overlay dissolves in (opacity, 300ms `ease-in`)** over the photo to reveal the member's bio (`post-excerpt`).
- Added the `team-member-card` **section style** (`styles/sections/cards/team-member-card.json`, `blockTypes: core/group`): carries the overlay positioning + the hover/focus dissolve transition. Overlay colour uses `color-mix(… var(--wp--preset--color--contrast) 80% …)`; honours `prefers-reduced-motion`.
- Note: this CPT may be out of Estimate 3168 scope → flag for the Change-Control Register.

### Added (header & footer patterns)
- Built three KWV header patterns from the supplied designs, each with logo (`site-logo`), an empty navigation block (menu to be assigned in the editor), and account + cart icons, in a three-child `space-between` flex so the nav centres; inner content group is `alignwide`:
  - `kwv/header-light` — white background **+ gold (`brand-500`) promo bar** ("15% Off All Wine Orders Of R500+ | Use Code: PINOTAGE100"); wired to `parts/header.html` for shop/category/product.
  - `kwv/header-dark` — `contrast` background, base text, `brand-500` icons → new `parts/header-dark.html`.
  - `kwv/header-transparent` — no background, base text, for templates with heroes behind the header → new `parts/header-transparent.html`.
- Built `kwv/footer` pattern (replaces the empty footer placeholder): `contrast` background, logo, outline SHOP NOW / CONTACT buttons, four link columns (Corporate Links, Featured Brands, Noteworthy, Contact Us) with supplied labels, centred "PLEASE DRINK RESPONSIBLY" notice, divider, and a dynamic-year copyright line. Wired to `parts/footer.html`.
- Registered `header-dark` and `header-transparent` template parts (area `header`) in `theme.json`.
- All values reference numeric preset tokens; orphaned-reference scan remains at 0.

### Changed (buttons)
- Restyled the button element and `core/button` **outline** variation in `theme.json` to the KWV button spec:
  - Both use the custom button spacing tokens (`var:custom|spacing|button|paddingVertical/-Horizontal`), `0` radius, uppercase text, base size (`font-size|200`), medium weight (`custom|font-weight|medium`), and a `1px` base border.
  - **Default** — base text, transparent background; background → contrast on hover.
  - **Outline** — base text + base border, contrast background; on hover text → contrast and background → base (border stays base).
- Removed the conflicting hardcoded `border: none` + `outline: 2px solid currentColor` rules from `assets/styles/core-button.css`; the outline style is now token-driven in `theme.json`.

### Added
- Agent documentation: theme-level `AGENTS.md`, project `DESIGN.md`, `CONTRIBUTING.md`, and root `AGENTS.md`/`CLAUDE.md`.

### Changed
- Extracted KWV design tokens from Figma into `theme.json`:
  - **Border radius** — replaced Ollie defaults with 6 KWV `radiusSizes` (slugs `0/100/200/300/400/500`).
  - **Shadow** — replaced Ollie presets with 6 KWV `shadow` presets (`100`–`600`); ramp tuned to scale monotonically (offset/blur/opacity) per client request, deviating from raw Figma values.
  - **Custom tokens** — imported button padding as fluid clamps: `--wp--custom--spacing--button--padding-horizontal/-vertical`.
  - **Layout** — `contentSize` 800px, `wideSize` 1440px; `fullWidth` 1920px preserved as `--wp--custom--layout--full-width` (no native field).
- Remapped 1023 orphaned preset references (Ollie slugs → KWV slugs) across 47 files (patterns, `assets/styles`, `theme.json`, `parts`): colour (`main→contrast`, `primary→brand-500`, etc.), spacing (`small→20`…`xx-large→60`), font-size (`x-small→100`…`x-large→600`), shadow (`small-light→300`). Orphan scan now reports 0.

### Removed
- Inherited Ollie style variations (`styles/agency|creator|ecommerce|startup|studio.json`) and typography presets (`styles/typography/*`) — not part of the KWV scope.

### Changed (theme identity)
- Re-skinned theme identity from Ollie to **KWV 2026**:
  - `style.css` header (name, author LightSpeed, description, tags, URIs, `Version: 1.0.0`, `Text Domain: kwv`, copyright).
  - `functions.php` + `inc/woocommerce.php` docblocks; PHP namespace `Ollie` → `Kwv`; block-style handle `ollie-block-*` → `kwv-block-*`.
  - `index.php` `@package`.
  - `README.md` and `readme.txt` rewritten for KWV (provenance noted; fonts Jost + Figtree, both OFL).
  - New `screenshot.png` (1200×900) generated from the KWV logo.
- Theme-wide rename: text domain `'ollie'` → `'kwv'` (all PHP), and pattern/category slug prefix `ollie/` → `kwv/` across `functions.php`, patterns, templates, and parts (cross-references kept consistent). Removed a stale `"theme":"ollie"` template-part attribute.
- Renamed internal function `unregister_ollie_woocommerce_patterns` → `unregister_kwv_woocommerce_patterns`.
- Fixed legacy bare block-attribute slugs (uncaught by the `var:`/`--wp--` orphan scan) across patterns/parts/templates: `backgroundColor`/`textColor`/`overlayColor` (`tertiary→neutral-200`, `secondary→neutral-700`, `main→contrast`, `primary→brand-500`, etc.) and `fontSize` (`small→300`, `x-small→100`, `base→200`, `medium→400`, `large→500`, `x-large→600`) — 134 fixes, restoring consistency with the already-remapped `has-*` classes.
- Full `ollie-*` → `kwv-*` CSS utility-class rename (style.css, assets/styles, woo patterns) — 60 replacements; removed Ollie-specific `ollieCustomClasses` block metadata.
- Removed legacy `ollie_pattern` post type from `customTemplates`; updated orphan `package-lock.json` name.

### Removed (cleanup)
- Deleted Ollie-branded patterns: `footer-dark`, `footer-light`, `single-testimonial`, `pricing-table`, `pricing-table-3-column`, `pricing-table-with-testimonials` (KWV patterns to be built fresh). `parts/footer.html` reset to an empty footer placeholder.
- Deleted stray `LICENSE copy` file.
- Genericised Ollie placeholder copy in `parts/sidebar.html`.

### Notes
- The theme is currently scaffolded on the **Ollie** base theme and not yet re-skinned to KWV.
  Re-skinning (identity strings, namespace/text domain, KWV palette/typography via the Figma
  extractor pipeline, pruning Ollie patterns/variations) is tracked in the OpenSpec change
  `openspec/changes/kwv-website-redesign-2026/`.

### To do (tracked, not yet done)
- Replace Ollie theme identity in `style.css`, `functions.php` (namespace + text domain), and `README.md`.
- Extract the real KWV design tokens from Figma into `theme.json` (palette, typography, spacing, radius, shadow).
- Introduce the `settings.custom.color` semantic token layer and `styles/dark.json` dark mirror.
- Resolve the Avenir LT Pro / Trenda font licensing decision before swapping font families.
- Prune bundled Ollie patterns and style variations down to the KWV deliverable set.
