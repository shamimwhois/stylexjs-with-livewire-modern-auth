<?php

namespace App\Services\Auth;

use App\Models\OtpCode;
use App\Models\User;
use App\Notifications\OtpLoginCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * Issues and verifies one-time email login codes.
 *
 * All OTP semantics (lifetime, length, attempt budget, resend cooldown)
 * live here so every consumer — the Livewire page, future API endpoints,
 * artisan tooling — shares one canonical implementation.
 */
class OtpService
{
    public function __construct(private readonly Request $request) {}

    /**
     * Send a fresh login code to the given email address.
     *
     * The request is throttled per email + IP, the previous live code's
     * resend cooldown is enforced, and a delivery failure rolls the code
     * back so the user can retry immediately.
     *
     * @throws ValidationException On throttle, cooldown, or delivery failure.
     */
    public function issue(string $email): void
    {
        $throttleKey = 'otp-send:'.Str::transliterate(Str::lower($email)).'|'.$this->request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => $seconds, 'minutes' => (int) ceil($seconds / 60)]),
            ]);
        }

        $latest = OtpCode::query()
            ->where('email', $email)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if ($latest && $latest->created_at->gt(now()->subSeconds($this->resendCooldown()))) {
            throw ValidationException::withMessages([
                'email' => __('auth.otp.throttle', [
                    'seconds' => (int) ceil($latest->created_at->addSeconds($this->resendCooldown())->diffInSeconds(now())),
                ]),
            ]);
        }

        $code = $this->generateCode();

        $otp = OtpCode::query()->create([
            'email' => $email,
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes($this->ttl()),
        ]);

        $user = User::query()->where('email', $email)->first();

        if ($user) {
            try {
                $user->notify(new OtpLoginCode($code, $this->ttl()));
            } catch (TransportExceptionInterface $e) {
                Log::warning('OTP email could not be delivered.', ['email' => $email, 'error' => $e->getMessage()]);

                $otp->forceFill(['consumed_at' => now()])->save();

                app(AuthAuditLogger::class)->log('otp.send_failed', $user);

                throw ValidationException::withMessages([
                    'email' => __('auth.otp.send_failed'),
                ]);
            }
        }

        RateLimiter::hit($throttleKey);

        app(AuthAuditLogger::class)->log('otp.sent', $user);
    }

    /**
     * Verify the supplied code against the newest active code for the email.
     *
     * On success the code is consumed. On failure the attempt budget is
     * decremented; once exhausted the code is burned.
     *
     * @throws ValidationException For unknown/expired codes and mismatches.
     */
    public function verify(string $email, string $code): void
    {
        $otp = OtpCode::query()
            ->where('email', $email)
            ->whereNull('consumed_at')
            ->latest('id')
            ->first();

        if (! $otp || $otp->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'code' => __('auth.otp.expired'),
            ]);
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->forceFill(['attempts' => $otp->attempts + 1])->save();

            if ($otp->attempts >= $this->maxAttempts()) {
                $otp->forceFill(['consumed_at' => now()])->save();
            }

            app(AuthAuditLogger::class)->log(
                'otp.failed',
                User::query()->where('email', $email)->first(),
                ['attempts' => $otp->attempts],
            );

            throw ValidationException::withMessages([
                'code' => __('auth.otp.invalid'),
            ]);
        }

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'code' => __('auth.otp.invalid'),
            ]);
        }

        $otp->forceFill(['consumed_at' => now()])->save();

        app(AuthAuditLogger::class)->log(
            'otp.verified',
            User::query()->where('email', $email)->first(),
        );
    }

    /**
     * Lifetime of a code in minutes.
     */
    public function ttl(): int
    {
        return (int) config('otp.ttl', 10);
    }

    /**
     * Seconds the user must wait between code requests.
     */
    public function resendCooldown(): int
    {
        return (int) config('otp.resend_cooldown', 60);
    }

    /**
     * Verification attempts allowed per code.
     */
    public function maxAttempts(): int
    {
        return (int) config('otp.max_attempts', 5);
    }

    /**
     * Whether the OTP login feature is switched on.
     */
    public function enabled(): bool
    {
        return (bool) config('otp.enabled', false);
    }

    /**
     * Generate a zero-padded numeric code of configured length.
     */
    private function generateCode(): string
    {
        $length = max(4, (int) config('otp.length', 6));
        $max = (10 ** $length) - 1;

        return str_pad((string) random_int(0, $max), $length, '0', STR_PAD_LEFT);
    }
}
