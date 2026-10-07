<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled Social Providers
    |--------------------------------------------------------------------------
    |
    | Each provider is only surfaced in the auth UI (and resolved by the
    | SocialAuthService) when its flag is enabled and valid credentials are
    | configured in config/services.php. Apple is intentionally absent: it
    | requires an additional community driver and Apple signing keys.
    |
    */

    'enabled' => [
        'google' => (bool) env('SOCIAL_GOOGLE_ENABLED', false),
        'github' => (bool) env('SOCIAL_GITHUB_ENABLED', false),
        'facebook' => (bool) env('SOCIAL_FACEBOOK_ENABLED', false),
        'apple' => (bool) env('SOCIAL_APPLE_ENABLED', false),
    ],

];
