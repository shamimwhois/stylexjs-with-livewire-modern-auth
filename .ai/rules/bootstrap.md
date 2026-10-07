---
paths:
  - bootstrap/app.php
---

# Bootstrap

## Use web(append:) not web()->append()
Registering web-group middleware must use `$middleware->web(append: X::class)`. The old `$middleware->web()->append(...)` form registers X globally (runs before StartSession), which silently breaks anything needing the session (e.g. VerifySessionSecurity never saw the session). VerifySessionSecurity depends on being appended AFTER StartSession in the web group.
