---
paths:
  - config/fortify.php
  - config/livewire.php
---

# Fortify

## Keep 'views' => false — Livewire owns the auth GET pages
Fortify must not register its own view routes. When `config('fortify.views')` is `true`, Fortify registers GET `/login`, `/forgot-password`, `/reset-password/{token}`, `/verify-email` and `/confirm-password` routes that render `auth.*` views; those routes shadow the Livewire full-page components in `app/Livewire/Pages/Auth` (registered in `routes/web.php`) and fail because no `auth.*` views exist. Fortify's POST endpoints (login, register, logout, password reset, etc.) are unaffected by this setting and remain active.

## Livewire class namespace must stay App\\Livewire
livewire.class_namespace MUST be 'App\\Livewire'. If it ever points elsewhere (it was once corrupted to a 'gsmwhale' value), component name derivation (getName) can't strip the prefix: memo.name gets a doubled prefix, reverse lookup (getClass) fails, and EVERY Livewire update/register POST returns 419/ComponentNotFoundException. Component classes live under app/Livewire (Pages/Auth, Pages/Dashboard, Home). Verify with: php -r "echo config('livewire.class_namespace');".
