---
paths:
  - 'resources/views/components/**'
---

# Components

## Icons: x-lucide-* everywhere, only brand marks stay inline
All auth/home icons use x-lucide-* Blade components (see vendor/mallardduck/blade-lucide-icons/resources/svg/icons for the available set); verify a name exists there before using it. Google is the one exception — lucide has no brand icon, so social.blade keeps its inline multi-color Google SVG. For x-show icon toggle pairs (mini-card, register-progress, sidebar checklist) render TWO x-lucide components toggled with x-show + x-cloak; never hand-write inline path/circle SVGs. When a toggle needs a dynamic color, bind it with x-bind:class (never :class on a component tag — the : prefix compiles as a PHP expression, see auth.md).
