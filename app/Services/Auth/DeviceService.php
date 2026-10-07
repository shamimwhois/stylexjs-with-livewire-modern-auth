<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Notifications\NewDeviceLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Device binding and new-device alerts.
 *
 * Every login path (password, two factor, OTP, social) funnels through
 * {@see DeviceService::recordLogin()} so fingerprinting, first-device
 * detection and alerting have exactly one canonical implementation.
 */
class DeviceService
{
    public function __construct(private readonly Request $request) {}

    /**
     * Record the login device: bind the fingerprint, update last-seen and
     * email the user on the first login from an unknown device.
     */
    public function recordLogin(User $user): void
    {
        if (! $this->bindingEnabled()) {
            return;
        }

        $fingerprint = $this->fingerprint();
        $known = $user->loginDevice()->first();

        if (! $known) {
            $user->loginDevice()->create([
                'fingerprint' => $fingerprint,
                'ip_address' => $this->request->ip(),
                'user_agent' => $this->userAgent(),
                'last_seen_at' => now(),
            ]);

            $this->sendNewDeviceAlert($user);

            app(AuthAuditLogger::class)->log('device.registered', $user);

            return;
        }

        $rebound = ! hash_equals($known->fingerprint, $fingerprint);

        $known->forceFill([
            'fingerprint' => $fingerprint,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->userAgent(),
            'last_seen_at' => now(),
        ])->save();

        if ($rebound) {
            app(AuthAuditLogger::class)->log('device.rebound', $user);
        }
    }

    /**
     * Whether the current request matches the user's bound device.
     *
     * Unknown users and users without a bound device always match (there is
     * nothing to compare against yet).
     */
    public function matchesBoundDevice(?User $user): bool
    {
        if (! $user) {
            return true;
        }

        $known = $user->loginDevice()->first();

        if (! $known) {
            return true;
        }

        return hash_equals($known->fingerprint, $this->fingerprint());
    }

    /**
     * Whether the fingerprint has been seen for this user before.
     */
    public function hasKnownDevice(User $user): bool
    {
        return $user->loginDevice()->exists();
    }

    /**
     * Whether new-device alert emails are enabled.
     */
    public function alertsEnabled(): bool
    {
        return (bool) config('security.new_device_alert', true);
    }

    /**
     * Whether device binding (session destroy on fingerprint change) is on.
     */
    public function bindingEnabled(): bool
    {
        return (bool) config('security.bind_device', true);
    }

    /**
     * Stable fingerprint of the current browser: IP + user agent.
     */
    public function fingerprint(): string
    {
        return hash('sha256', $this->request->ip().'|'.$this->userAgent());
    }

    /**
     * Truncated user agent (DB column comfort, fingerprints dominate).
     */
    private function userAgent(): ?string
    {
        return mb_substr((string) $this->request->userAgent(), 0, 500) ?: null;
    }

    /**
     * Email the account owner about a first-time device sign-in.
     */
    private function sendNewDeviceAlert(User $user): void
    {
        if (! $this->alertsEnabled()) {
            return;
        }

        try {
            $user->notify(new NewDeviceLogin(
                ip: $this->request->ip(),
                userAgent: $this->userAgent() ?? __('auth.device.unknown_browser'),
                time: now(),
            ));
        } catch (TransportExceptionInterface $e) {
            Log::warning('New device alert email could not be delivered.', [
                'user_id' => $user->getKey(),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
