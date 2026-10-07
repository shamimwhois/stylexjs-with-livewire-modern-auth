---
paths:
  - routes/web.php
---

# Routes

## Gate /admin routes behind role + IP whitelist
Admin routes must be gated with `['auth', 'verified', 'role:admin', 'whitelist:admin']`. `role` requires the user to hold a `roles` row slug; `whitelist` is a no-op unless `security.admin_ip_whitelist` config is set (comma-separated IPs).
