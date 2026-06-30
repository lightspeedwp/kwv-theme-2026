# AGENTS.md — kwv-theme-2026

> Theme-specific guidance. Read the project root [`../../../AGENTS.md`](../../../AGENTS.md),
> [`../../../DESIGN.md`](../../../DESIGN.md), and [`../../../CONTRIBUTING.md`](../../../CONTRIBUTING.md) first.

The KWV block theme — a WordPress Full Site Editing (FSE) + WooCommerce theme. This directory is its own git repository; commit theme work here.

---

## State: re-skinned to KWV

Scaffolded from the **Ollie** theme and **fully re-skinned to KWV**. Ollie identity, namespace, text domain, slugs, CSS classes, and demo patterns have all been removed:

- `style.css` → `Theme Name: KWV 2026`, author LightSpeed, `Text Domain: kwv`, `Version: 1.0.0`.
- `functions.php` / `inc/` → PHP namespace `Kwv`, text domain `kwv`, block-style handles `kwv-block-*`.
- `theme.json` → real KWV tokens from Figma (gold brand ramp `brand-500 #B29143`, Jost + Figtree fonts, numeric token scales). Schema `wp/6.9`.

The current `theme.json` **is** the KWV design system — see [`../../../DESIGN.md`](../../../DESIGN.md). Remaining work: rebuild deleted pattern references (footer + template patterns), WooCommerce styling, and the in-scope patterns/blocks listed in DESIGN.md §9.

---

## Layout

```
kwv-theme-2026/
├── theme.json            # Global Styles + settings (KWV design tokens). Generated from Figma. Schema wp/6.9.
├── style.css             # Theme header (KWV 2026) + CSS reset/base.
├── functions.php         # Setup, block styles, pattern categories. PHP namespace `Kwv`, text domain `kwv`.
├── inc/woocommerce.php    # WooCommerce-specific theme functions.
├── templates/            # Page/post/Woo templates (.html).
├── parts/                # Template parts: header, footer (empty placeholder), sidebar, product-card, add-to-cart parts.
├── patterns/             # ~20 block patterns (.php): headers, post loops, team, WooCommerce set.
├── styles/               # Style variations (none currently; no dark.json — see DESIGN.md §4.3, §8).
└── assets/
    ├── fonts/            # Self-hosted WOFF2: jost/ (headings), figtree/ (body).
    └── styles/           # Per-block CSS, lazy-enqueued by filename (core-*.css → core/*).
```

---

## Conventions

### theme.json & tokens
- `theme.json` is **generated from Figma** by the `.agents/skills` extractors — don't hand-edit token values; re-extract. See DESIGN.md §2.
- Colour is referenced **directly by palette slug** (`var:preset|color|brand-500`, `…|contrast`, `…|neutral-700`). The optional semantic-token layer (`settings.custom.color`) + `styles/dark.json` mirror is **not adopted** — only introduce it (via `theme-color-token-enforcer`) if dark mode / semantic indirection is approved. See DESIGN.md §4.3.
- Schema is `https://schemas.wp.org/wp/6.9/theme.json`, `version: 3`. Tokens use **numeric slugs** (`100`/`200`/…), not named sizes.

### Authored files (patterns / templates / parts / styles)
- Reference **preset tokens by numeric slug**: `var:preset|color|<slug>`, `var:preset|spacing|<slug>`, `var:preset|font-size|<slug>`, `var:preset|font-family|heading|body`, `var:custom|font-weight|…`, `var:custom|line-height|…`.
- **No** raw `#hex` / `rgb()` / font-family / raw font-weight literals. In raw `css` strings use `var(--wp--preset--…)` / `var(--wp--custom--…)`.
- Semantic HTML `tagName`s; correct heading hierarchy; keep templates/parts lean (no inline styles).

### Styling lives in JSON (block & section styles)
**Rule:** every style belongs in a block-style or section-style JSON partial under `styles/**` — including its `css` field for selector-level rules. Author CSS in `assets/styles/*.css` **only** for the parts a JSON style genuinely cannot express. This keeps one source of truth, renders the style in the editor, and keeps the cascade clean. (We audit `assets/styles/*.css` against this rule.)

What goes where:
- **Structured props** (color, border, radius, typography, spacing) and **native pseudo-states** (`:hover`/`:focus` as `styles` keys) → the JSON `styles` object. These render in the editor.
- **Selector-level rules** that aren't structured props (descendant selectors, layout/`display:flex` on generated wrappers, resting overrides that must out-specify a plugin) → the JSON style's **`css` field** (`&` = variation root). The `css` field loads in the editor too.
- **`assets/styles/*.css`** → last resort, only for what the `css` field provably can't hold. The `css` field is **`:where()`-zero-specificity** (use `!important` to win), **strips `:hover`/`:first-child`**, **mangles `content:""`**, and **drops comma `&` selectors**. So `:hover`/`:focus` flips and `::after`/`::before` icons (which need `content`) belong in enqueued CSS — nothing else should. Front-end-only sheets like `woocommerce.css` never reach the editor, which is the whole reason to prefer JSON.
- **WooCommerce caveat (tested):** Woo blocks that declare `__experimentalSelector` **and** `__experimentalSkipSerialization` (e.g. `woocommerce/product-button`) **cannot** be driven by a block-style JSON. Adding the block to `blockTypes` makes WP compile the variation's `&` against the *inner* experimental selector (so `& .wp-block-button__link` mis-targets), emit everything at `:where()`-zero-specificity, and serialize a stray inline border — and the `!important`-in-preset trick (`var:preset|color|x !important`) compiles to invalid CSS (`var(--…--x !important)`). For these blocks, style the control in **enqueued CSS** scoped to its `is-style-*` class (natural specificity wins cleanly, no `!important`). Keep the JSON style for the serializable blocks (`core/button`, `woocommerce/add-to-cart-with-options`).

When you must put something in a `.css` file, add a comment saying which limit forced it, and link back to this rule. Worked example: `styles/blocks/button/add-to-cart.json` (canonical, for core/button) + the full `woocommerce/product-button` bridge in `assets/styles/woocommerce.css` (the tested WooCommerce exception).

### PHP (`functions.php`, `inc/`, `patterns/*.php`)
- Escape **all** output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`) and include the **text domain** in every translation call.
- Keep `functions.php`/`inc/` minimal — no plugin-like features in the theme.
- Block styles are registered in `functions.php`; per-block CSS in `assets/styles/<core-block>.css` is auto-enqueued only when the block is used (don't break that filename→block convention).

### Patterns
- Register categories in `functions.php` (now `kwv/*`). Use the `pattern-extractor` skill to turn Figma sections into patterns.
- Build only patterns on the DESIGN.md §9 / PRD list. Some templates still embed `wp:pattern` slugs whose patterns were deleted in the de-Ollie cleanup (footer + template patterns) — rebuild these as KWV patterns; `footer.html` is an intentional empty placeholder.

---

## Commands

```bash
npm run dev               # watch patterns, auto-escape for translations
npm run translate:patterns
composer run lint         # PHP syntax
composer run wpcs:scan    # WordPress Coding Standards
composer run wpcs:fix     # auto-fix
```

Recommended skill passes: `block-theme-audit` (before milestones), `themejson-completion` (coverage gaps), `theme-orphaned-refs` (after token changes), `theme-color-token-enforcer` (only if the semantic-token/dark layer is adopted — see DESIGN.md §4.3).

---

## Do / Don't

**Do:** prefer `theme.json` over PHP for styling; keep diffs small; update `CHANGELOG.md`; validate JSON against the WP schema; keep RTL-safe logical properties.

**Don't:** add Webpack/Vite/Docker/Storybook or npm/Composer deps without justification; leave `{{PLACEHOLDER}}` tokens in shipped files; reintroduce Ollie naming/branding or named-size token slugs; build out-of-scope templates/patterns (→ Change-Control Register).
