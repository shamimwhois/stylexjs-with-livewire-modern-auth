<?php

namespace App\Http\Middleware;

use App\Services\Auth\AuthAuditLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Rejects requests whose IP is not on the allow-list for a group of routes.
 *
 * When the allow-list is empty the middleware is a no-op so local/CI setups
 * are unaffected until an explicit list is configured.
 */
class EnsureIpWhitelisted
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $group = 'admin'): Response
    {
        $allowed = $this->allowedIps($group);

        if ($allowed === [] || in_array($request->ip(), $allowed, true)) {
            return $next($request);
        }

        app(AuthAuditLogger::class)->log('whitelist.blocked', null, [
            'group' => $group,
            'ip' => $request->ip(),
        ]);

        abort(403);
    }

    /**
     * The allow-list for the given group, parsed from the config.
     *
     * @return array<int, string>
     */
    private function allowedIps(string $group): array
    {
        $raw = (string) config("security.{$group}_ip_whitelist", '');

        return array_values(array_filter(array_map(
            fn (string $ip) => trim($ip),
            explode(',', $raw),
        )));
    }
}
