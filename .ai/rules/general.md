---
paths:
  - 'vite.config.*'
  - vite.config.js
---

# General

## StyleX Vite plugin must precede Tailwind/Laravel plugins
Register `stylex.vite()` BEFORE `tailwindcss()` and `laravel-vite-plugin` in the plugins array. Ordering matters: if StyleX runs after the Laravel/Vite plugins, HMR and CSS-layer emission can conflict. This applies to all Vite configs in this repo.

## Vite input: CSS must not be a separate entry (Vite 8 + @stylexjs/unplugin)
`laravel-vite-plugin` input must be `['resources/js/app.js']` only — `resources/css/app.css` is imported from `app.js`, never listed as its own input entry. The unplugin appends aggregated StyleX CSS to the first CSS asset and renames/deletes it during `generateBundle`; with Vite 8 (rolldown) that breaks `vite:css-post` ("Cannot read properties of undefined (reading 'viteMetadata')") when the CSS is an input entry. Do not re-add `resources/css/app.css` to `input`.

## StyleX styles module
Author `stylex.create()` in plain JS (`resources/js/stylex.js`, no JSX). Import it from `resources/js/app.js` so the unplugin transforms it. Use `import * as stylex from '@stylexjs/stylex'` — v0.19 has no default export. Avoid the `border` shorthand (silently dropped by `@stylexjs/babel-plugin` 0.19); use `borderWidth`/`borderStyle`/`borderColor` longhands. Class names are deterministic content hashes — reference them in Blade views, and update them when styles change by reading the `@layer priority*` blocks in the built CSS.

## StyleX dev-HMR tags are REMOVED (do not re-add)
The layouts previously injected `<link href="/virtual:stylex.css">` + `<script type="module">import('virtual:stylex:runtime')</script>` when `Vite::isRunningHot()`. Under Vite 8 (Rolldown) the `@stylexjs/unplugin` dev server does NOT serve those virtual modules - both 404 and the import logs an unhandled promise rejection on every dev load. They were removed from `layouts/auth.blade.php` and `layouts/app.blade.php`. StyleX CSS still compiles into the production build via the unplugin; in `npm run dev` the StyleX-only dashboard button styles are simply absent.

## Web fonts are self-hosted through @fonts (bunny), never Google Fonts
All font families ship through `laravel-vite-plugin/fonts`: Instrument Sans (400/500/600), Teko (400-700), Plus Jakarta Sans (400-800), declared in `vite.config.js` `fonts` and emitted by the `@fonts` Blade directive. Do NOT add external `<link rel="stylesheet" href="https://fonts.googleapis.com/...">` tags - they caused reflow/CLS and the metrics differ from the self-hosted woff2s. Add new families to the vite config `fonts` array instead.

## Semantic color tokens live in app.css `@theme inline`
resources/css/app.css registers semantic colors (`--color-background/card/foreground/primary/accent/muted-foreground/border/input/ring/destructive/success/warning`, fonts `sans/heading/jakarta`) that map to the runtime `--pf-*` vars set by the auth layout's preset script, plus fixed success/warning/destructive. Use semantic utilities (`bg-primary`, `text-muted-foreground`, `border-input`, `ring-ring/X`) instead of arbitrary `[var(--pf-*)]` / `white/X` values.

## `@source inline(...)` forcing block removed — verify with a build grep
The auth-page forcing list in app.css (fractional white-opacity, `[var(--pf-*,...)]` arbitrary utilities) was deleted when the auth UI moved to token utilities. Tailwind v4 discovers the remaining utilities (including those inside Alpine `:class="'text-success' : 'text-destructive'"` strings and `lg:grid-cols-[1fr_320px]`) from the Blade sources. If a UI class seems missing after changes, check `public/build/assets/app-*.css` for it and only reinstate a minimal `@source inline(...)` entry for that exact class — don't restore the whole block.

## Pin Vite server.host to avoid [::] hot file
Vite dev server must use an explicit server.host (127.0.0.1). Without it on Windows, the laravel-vite-plugin hot file is written as http://[::]:5173; browsers can't reliably load the assets, so app.js never runs (Alpine forms look dead). Keep public/hot normalized to http://localhost:5173 too.
