<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Device Binding & Login Alerts
    |--------------------------------------------------------------------------
    |
    | When 'bind_device' is true every successful login fingerprints the
    | browser (IP + user agent) and binds the account to that device. If a
    | later request arrives from a different fingerprint the session cookie
    | is destroyed and the user must sign in again.
    |
    | When 'new_device_alert' is true the first login from a device emails
    | the account owner a "new device signed in" notice.
    |
    */

    'bind_device' => env('SECURITY_BIND_DEVICE', true),

    'new_device_alert' => env('SECURITY_NEW_DEVICE_ALERT', true),

    /*
    |--------------------------------------------------------------------------
    | Auth Audit Log
    |--------------------------------------------------------------------------
    |
    | When 'audit_log_enabled' is true every authentication event (login,
    | verification, API usage, whitelist blocks) is appended to the
    | auth_logs table for fraud detection and forensics.
    |
    */

    'audit_log_enabled' => env('SECURITY_AUDIT_LOG', true),

    /*
    |--------------------------------------------------------------------------
    | Admin IP Whitelist
    |--------------------------------------------------------------------------
    |
    | Comma-separated list of IP addresses allowed to reach the /admin routes
    | when 'whitelist:admin' middleware is applied. Empty means no restriction.
    |
    */

    'admin_ip_whitelist' => env('ADMIN_IP_WHITELIST', ''),

    /*
    |--------------------------------------------------------------------------
    | Post-login Home Route Per Role
    |--------------------------------------------------------------------------
    |
    | Named route a user is redirected to after sign in. The first role whose
    | slug matches a key wins; unmapped or role-less users fall back to the
    | 'dashboard' route. Keys are role slugs, values are named routes.
    |
    */

    'role_home_routes' => [
        'admin' => 'admin.dashboard',
        'seller' => 'seller.dashboard',
    ],

];
