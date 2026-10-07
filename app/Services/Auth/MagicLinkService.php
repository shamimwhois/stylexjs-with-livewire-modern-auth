<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Notifications\MagicLinkLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Issues passwordless sign-in links.
 *
 * Owns the temporary signed URL, per-email/IP throttling, notification
 * delivery and audit so the Livewire page and any future consumer share one
 * canonical implementation. Never reveals whether the address is a known
 * account.
 */
class MagicLinkService
{
    public function __construct(private readonly Request $request) {}

    /**
     * Whether the magic link login feature is switched on.
     */
    public function enabled(): bool
    {
        return (bool) config('magic-link.enabled', false);
    }

    /**
     * Lifetime of an issued link in minutes.
     */
    public function ttl(): int
    {
        return (int) config('magic-link.ttl', 15);
    }

    /**
     * Email a temporary signed sign-in link to the given address.
     *
     * @throws ValidationException On throttle or delivery failure.
     */
    public function send(string $email): void
    {
        $throttleKey = 'magic-link:'.Str::transliterate(Str::lower($email)).'|'.$this->request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => (int) ceil($seconds / 60)]),
            ]);
        }

        $user = User::query()->where('email', $email)->first();

        if ($user) {
            $url = URL::temporarySignedRoute(
                'auth.magic.verify',
                now()->addMinutes($this->ttl()),
                ['email' => $user->email],
            );

            try {
                $user->notify(new MagicLinkLogin($url, $this->ttl()));
            } catch (TransportExceptionInterface $e) {
                Log::warning('Magic link email could not be delivered.', ['email' => $email, 'error' => $e->getMessage()]);

                app(AuthAuditLogger::class)->log('magic.send_failed', $user);

                throw ValidationException::withMessages([
                    'email' => __('auth.magic.send_failed'),
                ]);
            }

            app(AuthAuditLogger::class)->log('magic.link_sent', $user);
        }

        RateLimiter::hit($throttleKey);
    }
}
