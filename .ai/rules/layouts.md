---
paths:
  - 'resources/views/layouts/**'
  - resources/views/layouts/admin.blade.php
---

# Layouts

## Reload-flash guard: critical head CSS + x-cloak on hidden elements
Both layouts (auth/app) ship a critical inline <style> in <head> BEFORE @vite: [x-cloak]{display:none!important} plus a page background (auth: html,body background var(--pf-bg,#030712); app: #f9fafb with a @media (prefers-color-scheme:dark) rule). This prevents white/unstyled-background and uninitialized-Alpine flashes on hard reload. Any new x-show element that is hidden at initial state MUST carry x-cloak. The [x-cloak] rule also lives in resources/css/app.css @layer base. Do not remove these critical head styles.

## Semantic color tokens; do not re-add the light-mode override block
Auth styling is token-driven via `@theme inline` in resources/css/app.css (semantic utilities mapped to the runtime `--pf-*` vars). The old 145-line `[data-theme="light"] .auth-page [class*=...]` override <style> was DELETED from layouts/auth.blade.php — it was an escape-hatch hack. If a color doesn't flip for light/dark, fix the token mapping/utility, never re-add override CSS. The `--tw-*` aliases the auth layout script used to write were removed too; nothing references them. Keep the preset script writing the canonical `--pf-*` vars synchronously (home page still depends on them).

## Admin layout: header/sidebar components + theme switcher wiring
Admin layout renders x-admin.app.header (top bar) and x-admin.app.sidebar in BOTH the desktop adminSidebar aside and the mobile drawer (below adminDrawerHead). Drawer open state stays on the shell: x-data="{ mobileNavOpen: false }" — header toggle buttons and drawer buttons reference that parent scope; the components must not define their own x-data for the toggle. Theme switching lives in partials/theme-switcher.blade.php (included by header): the usehallmark-style banner indicator + numbered listbox dot grid, driven by window.themePreset from partials/theme-preset.blade.php (catalog + apply/choose API — see .ai/rules/partials.md). Only route admin.dashboard exists; other sidebar links are href="#" placeholders until those admin sections are built.
