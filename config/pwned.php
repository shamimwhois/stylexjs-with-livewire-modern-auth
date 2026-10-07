<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Have I Been Pwned (HIBP) Password Breach Checks
    |--------------------------------------------------------------------------
    |
    | The pwned service uses the HIBP k-anonymity range API: only a five
    | character SHA-1 prefix leaves this server. Responses are cached per
    | prefix. When fail_open is true, network errors are reported as "not
    | pwned" so the registration flow never breaks if HIBP is unreachable.
    |
    */

    'hibp' => [
        'api_url' => env('HIBP_API_URL', 'https://api.pwnedpasswords.com/range/'),
        'cache_ttl' => env('HIBP_CACHE_TTL', 3600),
        'timeout' => env('HIBP_TIMEOUT', 5),
        'fail_open' => (bool) env('HIBP_FAIL_OPEN', true),
    ],

];
