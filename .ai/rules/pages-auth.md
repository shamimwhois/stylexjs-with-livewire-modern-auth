---
paths:
  - 'app/Livewire/Pages/Auth/**'
---

# Pages Auth

## Audit all auth events via AuthAuditLogger
Every authentication event must funnel through App\Services\Auth\AuthAuditLogger::log() (events like login.success, login.failed, magic.link_verified, otp.sent). OTP/magic/device flows use their canonical service (OtpService, MagicLink controller, DeviceService::recordLogin) rather than duplicating logic.
