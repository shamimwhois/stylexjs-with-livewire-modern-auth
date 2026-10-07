<?php

return [

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'two_factor_code_invalid' => 'The provided two factor authentication code was invalid.',
    'suspended' => 'This account is currently suspended. Contact support for assistance.',
    'blocked' => 'This account has been blocked.',
    'social_login_failed' => 'We could not sign you in with that account. Please try again.',
    'social_no_email' => 'We could not sign you in because the social provider did not return an email address.',
    'social_password_safe' => 'This password has not appeared in known data breaches.',
    'social_password_breach' => 'This password has appeared :count times in known data breaches.',
    'social_password_critical' => 'This password has been exposed in breaches. Choose a stronger, unique password.',

    'social_signed_in' => 'Signed in with :driver successfully.',

    'verify' => [
        'send_failed' => 'We could not send a new verification email right now. The mail service is busy — please try again in a moment.',
    ],

    'device' => [
        'unknown_browser' => 'Unknown browser',
        'unknown_ip' => 'Unknown IP',
        'session_expired' => 'Your session was opened from a different device or network, so it was closed for your security. Please sign in again.',
        'mail_subject' => 'New device sign-in alert',
        'mail_greeting' => 'Hello!',
        'mail_line_intro' => 'Your account was just signed in to from a new device.',
        'mail_line_time' => 'Time: :time',
        'mail_line_ip' => 'IP address: :ip',
        'mail_line_browser' => 'Browser: :browser',
        'mail_line_help' => 'If this was you, no action is needed. If you do not recognize this sign-in, change your password immediately and sign out of all sessions.',
    ],

    'otp' => [
        'throttle' => 'Please wait :seconds seconds before requesting another code.',
        'send_failed' => 'We could not email your code right now. Please wait a moment and try again.',
        'expired' => 'That code has expired. Request a new one to continue.',
        'invalid' => 'That code is incorrect. Please check and try again.',
        'mail_subject' => 'Your sign-in code',
        'mail_greeting' => 'Hello!',
        'mail_line_code' => 'Use this one-time code to sign in:',
        'mail_line_expiry' => 'The code expires in :minutes minutes. If you did not request it you can safely ignore this email.',
        'mail_line_ignore' => 'If you did not attempt to sign in, no action is needed — your account is safe.',
    ],

    'magic' => [
        'sent' => 'If an account exists for that address, a sign-in link is on its way.',
        'send_failed' => 'We could not email your sign-in link right now. Please wait a moment and try again.',
        'invalid' => 'That sign-in link is invalid or has expired. Request a new one.',
        'mail_subject' => 'Your sign-in link',
        'mail_greeting' => 'Hello!',
        'mail_line_intro' => 'Click the button below to sign in to your account. This link works once and expires in :minutes minutes.',
        'mail_button' => 'Sign in',
        'mail_line_ignore' => 'If you did not request this link, no action is needed — your account is safe.',
    ],

];
