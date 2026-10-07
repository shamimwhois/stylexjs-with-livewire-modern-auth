---
paths:
  - 'app/Http/Controllers/Api/**'
---

# Api

## Sanctum token API auth conventions
API auth lives in AuthController under /api/v1/auth, token-based via Sanctum (not JWT): login/register/forgot-password/reset-password/otp return `{token, user}` from createToken('api')->plainTextToken. 2FA-enrolled users must supply a TOTP `code` at login. Route group stays behind `throttle:api` plus tighter per-endpoint limiters.
