---
paths:
  - 'resources/views/pages/auth/**'
  - resources/views/pages/auth/register.blade.php
  - resources/views/components/auth/step-*.blade.php
  - resources/views/components/auth/register-progress.blade.php
  - resources/views/components/auth/security-sidebar.blade.php
---

# Auth

## Use Livewire auth components, never Fortify POST forms
All auth GET pages are Livewire full-page components (app/Livewire/Pages/Auth) with wire:submit/wire:model. Do NOT write plain forms posting to Fortify routes in these views; the Livewire component is the sole login/registration path (login emits validation + throttle). Reuse the Blade components under resources/views/components/auth (input, password, button, social, etc.) — don't duplicate the input/status/logout markup. Fortify actions (e.g. CreateNewUser) remain the single validation source for registration.

## Register wizard: Alpine-owned inputs + $wire.set submit
The register wizard is an Alpine-owned single-page flow. Its `registrationForm` Alpine.data (steps, strength scoring, debounced server-side breach check via POST /api/v1/auth/check-breach — no client-side HIBP fallback, Enter-to-advance, error jump) lives INLINE in resources/views/partials/registration-form.blade.php, pushed via @push('scripts') and registered on document `alpine:init` — a classic inline script, never a Vite module (see js.md). The view is decomposed into anonymous components under resources/views/components/auth (register-progress, step-profile, step-security, step-review, security-sidebar) rendered inside the root x-data scope; the page wraps them in a <form> whose submit handler advances/submits (Enter-to-continue). These components are register-wizard-specific and coupled to the `registrationForm` Alpine state — do not reuse them outside the register page. Inputs intentionally use Alpine x-model, NOT wire:model — submitRegistration() awaits $wire.set('name'/'email'/'password'/'password_confirmation') then $wire.register(). Server validation errors map to Register::$errorFields and the JS jumps back to the failing step. Don't convert these inputs back to wire:model — it would break the live strength/breach/match indicators.

## shadcn-style semantic tokens — never hardcode per-theme colors
Auth styling is token-driven: `@theme inline` in resources/css/app.css defines `--color-background/card/foreground/primary/accent/c2/muted(-foreground)/secondary/border/input/ring/destructive/success/warning`, mapped to the runtime `--pf-*` vars the layout preset script sets synchronously. Light, dark and all 18 presets flip automatically. Rules:
- Use semantic utilities only: `bg-background`, `bg-card`, `text-foreground`, `text-muted-foreground`, `text-primary`, `text-accent`, `bg-primary`, `border-border`, `border-input`, `border-destructive/60`, `text-destructive/success/warning`, `bg-success/10`, `focus-visible:ring-ring/X`, `accent-primary` (checkboxes), gradients `from-primary to-c2`.
- NEVER reintroduce `text-white/X`, `bg-white/[..]`, `border-white/[..]`, `[var(--pf-*)]` arbitrary classes, or a `[data-theme="light"]` override <style> block — that hack was deleted and tokens are the single source of truth.
- shadcn-shaped primitives live in resources/views/components/ui (`ui/button` variants default/outline/ghost/secondary/destructive/accent + sizes sm/default/lg; `ui/input`, `ui/label`, `ui/card`, `ui/checkbox`, `ui/badge`, `ui/alert`, `ui/separator`). `x-auth.*` components delegate to them; login/forgot/reset use `x-auth.input/password/checkbox/button`, the wizard steps use inline token classes + `x-ui.button`.

## Component tags compile `:prop` as PHP — use `x-bind:` for Alpine getters
ON Blade component tags (`<x-ui.button :disabled="!canContinueProfile">`) the `:` prefix means PHP expression, so `!canContinueProfile` throws "Undefined constant". Bind Alpine getters on components with `x-bind:disabled="!canContinueProfile"` (rendered literally → Alpine binds). Also `@click="..."` on a component tag compiles to `x-on:click` and works. Plain HTML elements (inputs, svgs, labels) may still use Alpine `:class`/`x-model`/`x-show`/`x-bind:type` as normal — only component tags are affected.

## Register Alpine data before @livewireScripts
In Livewire 3 apps, Alpine data for auth pages must be registered inline via `document.addEventListener('alpine:init', ...)` in a `@stack('scripts')` block placed BEFORE `@livewireScripts`. Vite `type="module"` scripts are deferred and run AFTER Alpine starts, so `Alpine.data()` never registers in time — keep register/login-page Alpine components in `resources/views/partials/registration-form.blade.php` style inline scripts, not `resources/js/` modules.

## Registration wizard state lives in partials/registration-form.blade.php
The register wizard's `registrationForm` Alpine.data lives INLINE in resources/views/partials/registration-form.blade.php, pushed via @push('scripts') and registered on document 'alpine:init'. There is NO resources/js/auth/registration-form.js — do not reference it. The inline classic script is parsed synchronously at end of body, before the deferred livewire.js module boots Alpine, so alpine:init catches the registration. Keep every new Alpine.data registration inline here (or in resources/js as a classic, non-deferred script), NOT as a deferred Vite module.
