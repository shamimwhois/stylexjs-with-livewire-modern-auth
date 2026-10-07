---
paths:
  - 'resources/views/partials/**'
  - resources/views/partials/theme-preset.blade.php
---

# Partials

## Alpine + Blade component traps (inline x-data over Alpine.data)
Two Alpine/Blade traps: (1) Blade does NOT compile @stylex(...)/{{ ... }} inside COMPONENT tag attributes (x-lucide-*, x-*): they are emitted literally (or break PHP quoting if they contain single quotes) — use them only on plain HTML elements, or wrap the component in a stamped element. (2) Alpine.data('name', factory) registration via document.addEventListener('alpine:init', ...) is unreliable on Livewire full-page layouts that render it early in the body ('themeSwitch is not defined'; Alpine.data() is a no-op after Livewire boots). Prefer inline x-data object literals (established pattern, e.g. admin shell x-data="{ mobileNavOpen: false }") for small presentational components like theme-switcher.

## Theme switcher: banner indicator + numbered listbox dots + shuffle
Theme switcher (partials/theme-switcher.blade.php) mirrors usehallmark.com's banner: a text indicator button (`aria-haspopup="listbox"`, `aria-controls="theme-dropdown"`, `aria-label="Switch theme"`) showing `<ordinal> / 21 — <name>` + caret, opening a `role="listbox" aria-label="Theme picker"` 7-col StyleX dot grid (themeDotGrid/themeDot/themeDotNum/themeDotActive). Each dot is a numbered keycap: inline CSS vars `--dot` (paper) / `--dot-edge` (accent ring) / `--num` (fig color) set from the theme's hex b/a/n; `aria-label="<NN> · <Name>"`; selected = edge ring + glow + spring overshoot; reduced-motion forced off. A shuffle button (T hint) randomizes; document `T` cycles the catalog (does NOT toggle the menu), `R` randomizes; both guarded to ignore typing (input/textarea/select/contenteditable) and modifier/repeat keys.

## Catalog theme presets: real hallmark 21, authentic OKLCH values
Theme presets are the exact 21 hallmark catalog themes in catalog order: hum(01) specimen(02) midnight(03) brutal(04) garden(05) atelier(06) newsprint(07) terminal(08) manifesto(09) almanac(10) sport(11) studio(12) riso(13) bloom(14) coral(15) aurora(16) editorial(17) carnival(18) lumen(19) cobalt(20) grid(21). 'terminal' is the default identity (green-black paper #020602, accent #75d350). Hex values are converted from the site's real OKLCH triples (--dot paper, --dot-edge accent, --num ink) with derived b/t/tm/s/f1/f2/c1/c2/a/h/n fields; papers are genuinely mixed light/dark so every theme must keep its own ink (t) and surface (s). No dark/light base toggle exists anymore — current()/outlet() default to 'terminal' and prefTheme persists the active key; the `n` field feeds the dot's `--num`. Keep the schema + apply()/current()/outlet()/choose()/ordinal()/label() API, x-data 'apply' (no menu toggle) vs 'choose' (closes menu).
