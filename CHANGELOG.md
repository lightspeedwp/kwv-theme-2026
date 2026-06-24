# Changelog

All notable changes to the **kwv-theme-2026** theme are documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

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
