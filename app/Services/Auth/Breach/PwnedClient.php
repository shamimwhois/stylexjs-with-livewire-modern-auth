<?php

namespace App\Services\Auth\Breach;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

final readonly class PwnedClient
{
    /**
     * Return how many times the password has appeared in known breaches.
     *
     * Uses the HIBP k-anonymity model: only the first five characters of the
     * SHA-1 hash are sent to the API. Falls back to zero on network errors
     * (fail-open) so the auth flow never breaks. The prefix range responses
     * are cached per prefix.
     */
    public function rangeCount(string $password): int
    {
        $hash = strtoupper(sha1($password));
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        $body = $this->fetchRangeBody($prefix);

        if ($body === null) {
            return 0;
        }

        $match = collect(explode("\n", $body))
            ->first(fn (string $line): bool => str_starts_with($line, $suffix));

        if ($match === null) {
            return 0;
        }

        $count = (int) trim(substr($match, strlen($suffix) + 1));

        return max($count, 0);
    }

    /**
     * Fetch the HIBP range response body, cached by prefix.
     *
     * Returns null on failure (fail-open) so callers can degrade gracefully.
     */
    private function fetchRangeBody(string $prefix): ?string
    {
        $cacheKey = 'hibp:range:'.$prefix;
        $cacheTtl = (int) config('pwned.hibp.cache_ttl', 3600);
        $timeout = (int) config('pwned.hibp.timeout', 5);
        $failOpen = (bool) config('pwned.hibp.fail_open', true);
        $apiUrl = (string) config('pwned.hibp.api_url', 'https://api.pwnedpasswords.com/range/');

        try {
            return Cache::remember($cacheKey, $cacheTtl, function () use ($prefix, $timeout, $apiUrl) {
                $response = Http::timeout($timeout)
                    ->withHeaders(['Add-Padding' => 'true'])
                    ->get($apiUrl.$prefix);

                if ($response->serverError() || $response->failed()) {
                    throw new \RuntimeException('HIBP request failed');
                }

                return $response->body();
            });
        } catch (\Throwable $e) {
            if (! $failOpen) {
                throw $e;
            }

            return null;
        }
    }
}
