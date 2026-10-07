<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Magic Link Login
    |--------------------------------------------------------------------------
    |
    | When 'enabled' is true the auth pages surface a "Magic link" entry
    | point and the magic link routes become active.
    |
    */

    'enabled' => env('MAGIC_LINK_ENABLED', true),

    /*
    | Lifetime in minutes for the temporary signed URL sent by the
    | passwordless sign-in flow. The link is single-use in intent (the
    | signature is one-way) and expires after this window.
    |
    */

    'ttl' => (int) env('MAGIC_LINK_TTL', 15),

];
