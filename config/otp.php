<?php

return [

    /*
    |--------------------------------------------------------------------------
    | One-Time Password Login
    |--------------------------------------------------------------------------
    |
    | This block configures the email one-time-password sign-in flow. When
    | 'enabled' is true the auth pages surface a "Login with OTP" entry
    | point and the /otp-login routes become active.
    |
    */

    'enabled' => env('OTP_LOGIN_ENABLED', true),

    /*
    | Code lifetime in minutes. A code older than this is rejected even if
    | it has not been consumed or attempted.
    */
    'ttl' => (int) env('OTP_LOGIN_TTL', 10),

    /*
    | Number of digits in the generated code. Six is the industry norm.
    */
    'length' => (int) env('OTP_LOGIN_LENGTH', 6),

    /*
    | Maximum verification attempts allowed per issued code before it is
    | burned and the user must request a new one.
    */
    'max_attempts' => (int) env('OTP_LOGIN_MAX_ATTEMPTS', 5),

    /*
    | Seconds a user must wait between consecutive code requests for the
    | same email address.
    */
    'resend_cooldown' => (int) env('OTP_LOGIN_RESEND_COOLDOWN', 60),

];
