<?php

use App\Support\Stylex;

if (! function_exists('cls')) {
    /**
     * Resolve StyleX style keys (and pass through raw classes) to a class list.
     *
     * @param  array<int|string, mixed>|string|bool|null  ...$args
     */
    function cls(string|array|bool|null ...$args): string
    {
        return Stylex::cls(...$args);
    }
}
