---
paths:
  - 'resources/js/**'
---

# Js

## Register Alpine.data via classic inline scripts on alpine:init
Register Alpine.data on the 'alpine:init' event from classic (non-deferred) inline scripts pushed before @livewireScripts — e.g. resources/views/partials/registration-form.blade.php. Alpine boots via livewire.js, a classic blocking script at the end of <body>; an inline classic script parses before it, so its alpine:init listener is installed before Alpine's init fires. Deferred Vite `type="module"` scripts race Alpine and miss the event, leaving the component inert. Do NOT register Alpine.data in resources/js modules, and do NOT reference resources/js/auth/registration-form.js (it does not exist).
