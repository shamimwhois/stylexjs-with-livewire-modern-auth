---
paths:
  - 'resources/views/errors/**'
---

# Errors

## Error views: token-driven glassmorphism, self-contained
All HTTP error pages live in resources/views/errors/ as {status}.blade.php extending errors.layout (no code in components/ or layouts/). The layout is token-driven glassmorphism (--pf-* vars, backdrop-filter, gradient glow blobs) and must stay self-contained: theme-preset + critical head CSS + @fonts + @vite guarded by file_exists — never depend on Livewire/Alpine or a build for the page to render (503/500 contexts). Per-page copy overrides @section('code'/'title'/'message'); actions default to Home + Back in the layout.

## Error layout: mono statement card, tactile surfaces
Error pages use a tactile 'statement' card (not glass): solid color-mix panel over --pf-surface, dotted radial texture (6px), hairline border, hard 3px accent foot matching --pf-accent, radius 0.875rem. Error code + HTTP chip are IBM Plex Mono in accent color; primary CTA is solid accent with dark ink (#04201f) + 3px hard foot shadow; :active translateY(1px). All colors resolve to --pf-* so presets recolor the pages.
