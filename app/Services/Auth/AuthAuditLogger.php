<?php

namespace App\Services\Auth;

use App\Models\AuthLog;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Appends authentication events to the auth_logs audit trail.
 *
 * Login, verification, API and fraud events should funnel through this one
 * class so audits record the same fields (ip, user agent, metadata) and can
 * be switched off globally with the security.audit_log_enabled flag.
 */
class AuthAuditLogger
{
    public function __construct(private readonly Request $request) {}

    /**
     * Persist one audit entry.
     *
     * @param  array<string, mixed>  $metadata
     */
    public function log(string $event, ?User $user = null, array $metadata = []): void
    {
        if (! config('security.audit_log_enabled', true)) {
            return;
        }

        AuthLog::query()->create([
            'user_id' => $user?->getKey(),
            'event' => $event,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'metadata' => $metadata === [] ? null : $metadata,
        ]);
    }
}
