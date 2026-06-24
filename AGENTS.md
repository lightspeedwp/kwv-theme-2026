# AGENTS.md — kwv-theme-2026

> Theme-specific guidance. Read the project root [`../../../AGENTS.md`](../../../AGENTS.md),
> [`../../../DESIGN.md`](../../../DESIGN.md), and [`../../../CONTRIBUTING.md`](../../../CONTRIBUTING.md) first.

The KWV block theme — a WordPress Full Site Editing (FSE) + WooCommerce theme. This directory is its own git repository; commit theme work here.

---

## ⚠️ This theme is still Ollie under the hood

Scaffolded from the **Ollie** theme and **not yet re-skinned to KWV**:

- `style.css` header → `Theme Name: Ollie`; `functions.php` → `namespace Ollie`, text domain `ollie`.
- `theme.json` → Ollie's default tokens (Mona Sans, purple `#5344F4`).
- `README.md` is Ollie's; `screenshot.png` is Ollie's.

Re-skinning is **in-scope** build work (identity strings, namespace/text-domain, palette/typography via the extractor pipeline, pruning Ollie patterns/variations to KWV's set). Until then, **do not treat current `theme.json` tokens as the KWV brand** — see [`../../../DESIGN.md`](../../../DESIGN.md).

Housekeeping: there are stray duplicates (`LICENSE copy`) — clean these up when touching the area, don't add more.

---

## Layout

```
kwv-theme-2026/
├── theme.json            # Global Styles + settings (design tokens). Generated from Figma.
├── style.css             # Theme header + CSS reset/base. ⚠️ still says "Ollie".
├── functions.php         # Setup, block styles, pattern categories. ⚠️ "Ollie" namespace.
├── inc/woocommerce.php    # WooCommerce-specific theme functions.
├── templates/            # Page/post/Woo templates (.html).
├── parts/                # Template parts: header, footer, shop header, mini-cart, product-card.
├── patterns/             # ~116 block patterns (.php). Mostly Ollie — prune to KWV's set.
├── styles/               # Style variations + colors/ + typography/ + blocks/.
│   └── (no dark.json yet — DESIGN.md §7 expects one)
└── assets/
    ├── fonts/            # WOFF2 (currently Mona Sans).
    └── styles/           # Per-block CSS, lazy-enqueued by filename (core-*.css → core/*).
```

---

## Conventions

### theme.json & tokens
- `theme.json` is **generated from Figma** by the `.agents/skills` extractors — don't hand-edit token values; re-extract. See DESIGN.md §3.
- Three colour layers stay separate: `settings.color.palette` (raw values) → `settings.custom.color` (semantic tokens, *what files reference*) → `styles/dark.json` (dark mirror). The semantic + dark layers don't exist yet; introduce them via the extractors + `theme-color-token-enforcer`.
- Schema target moving to `6.9` per the typography extractor; current file is `version: 3` / trunk schema. Reconcile during extraction.

### Authored files (patterns / templates / parts / styles)
- Reference **semantic tokens only**: `var:custom|color|…`, `var:preset|spacing|…`, `var:preset|font-size|…`, `var:custom|font-weight|…`.
- **No** raw `#hex` / `rgb()` / font-family / raw font-weight literals. In raw `css` strings use `var(--wp--custom--…)` / `var(--wp--preset--…)`.
- Semantic HTML `tagName`s; correct heading hierarchy; keep templates/parts lean (no inline styles).

### PHP (`functions.php`, `inc/`, `patterns/*.php`)
- Escape **all** output (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`) and include the **text domain** in every translation call.
- Keep `functions.php`/`inc/` minimal — no plugin-like features in the theme.
- Block styles are registered in `functions.php`; per-block CSS in `assets/styles/<core-block>.css` is auto-enqueued only when the block is used (don't break that filename→block convention).

### Patterns
- Register categories in `functions.php`. Use the `pattern-extractor` skill to turn Figma sections into patterns.
- Build only patterns on the DESIGN.md §8 / PRD list. The bundled Ollie patterns are reference, not deliverables.

---

## Commands

```bash
npm run dev               # watch patterns, auto-escape for translations
npm run translate:patterns
composer run lint         # PHP syntax
composer run wpcs:scan    # WordPress Coding Standards
composer run wpcs:fix     # auto-fix
```

Recommended skill passes: `block-theme-audit` (before milestones), `themejson-completion` (coverage gaps), `theme-orphaned-refs` (after token changes), `theme-color-token-enforcer` (semantic + dark parity).

---

## Do / Don't

**Do:** prefer `theme.json` over PHP for styling; keep diffs small; update `CHANGELOG.md`; validate JSON against the WP schema; keep RTL-safe logical properties.

**Don't:** add Webpack/Vite/Docker/Storybook or npm/Composer deps without justification; leave `{{PLACEHOLDER}}` tokens in shipped files; ship Ollie branding; build out-of-scope templates/patterns (→ Change-Control Register).
