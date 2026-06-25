# Changelog

All notable changes to the **kwv-theme-2026** theme are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
