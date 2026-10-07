<?php

namespace App\Livewire\Pages\Account;

use App\Services\Auth\AuthAuditLogger;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\RecoveryCode;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Security settings')]
class Security extends Component
{
    public bool $pendingConfirm = false;

    public bool $passwordPrompt = false;

    public bool $showRecoveryCodes = false;

    public string $password = '';

    public string $code = '';

    public ?string $action = null;

    /**
     * Begin enabling two factor authentication.
     */
    public function prepareEnable(): void
    {
        $this->requirePasswordFor('enable');
    }

    /**
     * Begin disabling two factor authentication.
     */
    public function prepareDisable(): void
    {
        $this->requirePasswordFor('disable');
    }

    /**
     * Begin regenerating the recovery codes.
     */
    public function prepareRegenerate(): void
    {
        $this->requirePasswordFor('regenerate');
    }

    /**
     * Confirm the current password and run the pending action.
     */
    public function submitPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($this->password, auth()->user()->password)) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session()->put('auth.password_confirmed_at', now()->timestamp);

        $this->passwordPrompt = false;
        $this->password = '';

        $this->runAction($this->action);
    }

    /**
     * Discard the pending password confirmation prompt.
     */
    public function cancelPrompt(): void
    {
        $this->passwordPrompt = false;
        $this->action = null;
        $this->password = '';
    }

    /**
     * Abandon the two factor setup and clear any stored secret.
     */
    public function cancelEnable(): void
    {
        $this->pendingConfirm = false;

        if (auth()->user()->two_factor_secret) {
            $this->disableNow();
        }
    }

    /**
     * Confirm the two factor setup code from the authenticator app.
     */
    public function confirmTwoFactor(): void
    {
        $this->validate([
            'code' => ['required', 'string'],
        ]);

        $user = auth()->user();

        $valid = app(TwoFactorAuthenticationProvider::class)->verify(
            Fortify::currentEncrypter()->decrypt($user->two_factor_secret),
            $this->code,
        );

        if (! $valid) {
            throw ValidationException::withMessages([
                'code' => __('auth.two_factor_code_invalid'),
            ]);
        }

        $user->forceFill(['two_factor_confirmed_at' => now()])->save();

        $this->pendingConfirm = false;
        $this->showRecoveryCodes = true;
        $this->code = '';

        app(AuthAuditLogger::class)->log('two_factor.enabled', $user);
    }

    /**
     * The recovery codes currently stored for the user.
     *
     * @return array<int, string>
     */
    public function recoveryCodes(): array
    {
        $user = auth()->user();

        if (! $user->hasEnabledTwoFactorAuthentication() || ! $user->two_factor_recovery_codes) {
            return [];
        }

        return $user->recoveryCodes();
    }

    /**
     * The QR code to scan while setting up two factor authentication.
     */
    public function qrSvg(): string
    {
        return auth()->user()->twoFactorQrCodeSvg();
    }

    /**
     * The base32 secret for manual authenticator entry.
     */
    public function secretKey(): string
    {
        $user = auth()->user();

        return Fortify::currentEncrypter()->decrypt($user->two_factor_secret);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('pages.account.security');
    }

    /**
     * Require a recent password confirmation before a sensitive action.
     */
    private function requirePasswordFor(string $action): void
    {
        if ($this->passwordIsFresh()) {
            $this->runAction($action);

            return;
        }

        $this->action = $action;
        $this->passwordPrompt = true;
        $this->password = '';
    }

    /**
     * Whether the current password was confirmed within the timeout window.
     */
    private function passwordIsFresh(): bool
    {
        $confirmedAt = (int) session('auth.password_confirmed_at', 0);

        return time() - $confirmedAt <= (int) config('auth.password_timeout', 10800);
    }

    /**
     * Execute a confirmed sensitive action.
     */
    private function runAction(string $action): void
    {
        match ($action) {
            'enable' => $this->enableNow(),
            'disable' => $this->disableNow(),
            'regenerate' => $this->regenerateNow(),
            default => null,
        };
    }

    /**
     * Store a fresh two factor secret and surface the confirm step.
     */
    private function enableNow(): void
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            return;
        }

        $secret = app(TwoFactorAuthenticationProvider::class)->generateSecretKey();

        $user->forceFill([
            'two_factor_secret' => Fortify::currentEncrypter()->encrypt($secret),
            'two_factor_recovery_codes' => Fortify::currentEncrypter()->encrypt(json_encode($this->freshRecoveryCodes())),
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->pendingConfirm = true;
        $this->showRecoveryCodes = false;
        $this->code = '';

        app(AuthAuditLogger::class)->log('two_factor.setup_started', $user);
    }

    /**
     * Clear the two factor secret, codes and confirmation.
     */
    private function disableNow(): void
    {
        $user = auth()->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->pendingConfirm = false;
        $this->showRecoveryCodes = false;

        app(AuthAuditLogger::class)->log('two_factor.disabled', $user);
    }

    /**
     * Issue a fresh set of recovery codes.
     */
    private function regenerateNow(): void
    {
        $user = auth()->user();

        $user->forceFill([
            'two_factor_recovery_codes' => Fortify::currentEncrypter()->encrypt(json_encode($this->freshRecoveryCodes())),
        ])->save();

        $this->showRecoveryCodes = true;

        app(AuthAuditLogger::class)->log('two_factor.recovery_codes_regenerated', $user);
    }

    /**
     * A fresh set of nine single-use recovery codes.
     *
     * @return array<int, string>
     */
    private function freshRecoveryCodes(): array
    {
        return collect(range(0, 8))
            ->map(fn () => RecoveryCode::generate())
            ->all();
    }
}
