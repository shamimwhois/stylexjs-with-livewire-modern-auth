<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use App\Services\Auth\AuthAuditLogger;
use App\Services\Auth\DeviceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.auth')]
#[Title('Sign in')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public string $code = '';

    public bool $remember = false;

    public bool $twoFactorChallenge = false;

    public bool $noticeModal = false;

    public ?string $noticeType = null;

    public ?string $noticeTitle = null;

    public ?string $noticeMessage = null;

    /**
     * Open the notice modal when redirected here by the active-user
     * middleware after a suspended or blocked session was closed.
     */
    public function mount(): void
    {
        $type = (string) session()->pull('login.restriction', '');

        if (in_array($type, ['suspended', 'blocked'], true)) {
            $this->openAlertModal($type, __("auth.$type"));
        }
    }

    /**
     * Attempt to authenticate the user's session.
     */
    public function authenticate(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $identifier = Str::lower(trim($this->email));

        $throttleKey = $this->throttleKey($identifier);

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            $this->openAlertModal('throttled', __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]));

            return;
        }

        $provider = Auth::guard()->getProvider();

        $user = User::query()
            ->where('email', $identifier)
            ->orWhereRaw('lower(username) = ?', [$identifier])
            ->first();

        if (! $user || ! $provider->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($throttleKey);

            app(AuthAuditLogger::class)->log('login.failed', null, ['identifier' => $identifier]);

            $this->openAlertModal('failed', __('auth.failed'));

            return;
        }

        RateLimiter::clear($throttleKey);

        if (! $this->ensureUserMaySignIn($user)) {
            return;
        }

        if ($this->shouldAskForTwoFactorChallenge($user)) {
            session()->put('login.id', $user->getKey());
            session()->put('login.remember', $this->remember);

            $this->twoFactorChallenge = true;

            return;
        }

        $this->loginUser($user);
    }

    /**
     * Complete the sign in with a valid two factor authentication code.
     */
    public function confirmTwoFactorAuthentication(): void
    {
        $this->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $this->challengedUser();

        if (! $user) {
            return;
        }

        $throttleKey = 'two-factor:'.$user->getKey();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            $this->openAlertModal('throttled', __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]));

            return;
        }

        $ok = app(TwoFactorAuthenticationProvider::class)->verify(
            Fortify::currentEncrypter()->decrypt($user->two_factor_secret),
            $this->code
        );

        if (! $ok) {
            RateLimiter::hit($throttleKey);

            $this->openAlertModal('two_factor_invalid', __('auth.two_factor_code_invalid'));

            return;
        }

        RateLimiter::clear($throttleKey);

        app(AuthAuditLogger::class)->log('two_factor.verified', $user);

        $this->loginUser($user);
    }

    /**
     * Discard the in-flight two factor challenge and restart sign in.
     */
    public function resetChallenge(): void
    {
        session()->forget(['login.id', 'login.remember']);

        $this->twoFactorChallenge = false;
        $this->code = '';
    }

    /**
     * Dismiss the sign-in notice modal.
     */
    public function dismissNotice(): void
    {
        $this->noticeModal = false;
    }

    /**
     * Deny sign in for blocked or suspended accounts.
     */
    private function ensureUserMaySignIn(User $user): bool
    {
        if ($user->isBlocked()) {
            $this->openAlertModal('blocked', __('auth.blocked'));

            return false;
        }

        if ($user->isSuspended()) {
            $this->openAlertModal('suspended', __('auth.suspended'));

            return false;
        }

        return true;
    }

    /**
     * Present the sign-in notice modal for the given outcome.
     */
    private function openAlertModal(string $type, string $message): void
    {
        $this->noticeType = $type;

        $this->noticeTitle = match ($type) {
            'blocked' => 'Account blocked',
            'suspended' => 'Account suspended',
            'throttled' => 'Too many attempts',
            'two_factor_invalid' => 'Invalid code',
            default => 'Sign-in failed',
        };

        $this->noticeMessage = $message;
        $this->noticeModal = true;
    }

    /**
     * Determine whether the user must confirm a two factor authentication code.
     */
    private function shouldAskForTwoFactorChallenge(mixed $user): bool
    {
        return $user instanceof User
            && in_array(TwoFactorAuthenticatable::class, class_uses_recursive($user), true)
            && $user->hasEnabledTwoFactorAuthentication();
    }

    /**
     * Resolve the user currently in the two factor challenge.
     */
    private function challengedUser(): ?User
    {
        $user = User::query()->find(session('login.id'));

        if (! $user) {
            $this->openAlertModal('two_factor_invalid', __('auth.two_factor_code_invalid'));

            return null;
        }

        return $user;
    }

    /**
     * Authenticate the given user, record the login device and redirect.
     */
    private function loginUser(User $user): void
    {
        if (! $this->ensureUserMaySignIn($user)) {
            return;
        }

        $remember = $this->remember || (bool) session()->pull('login.remember', false);

        session()->forget('login.id');

        Auth::login($user, $remember);

        session()->regenerate();

        app(DeviceService::class)->recordLogin($user);

        app(AuthAuditLogger::class)->log('login.success', $user);

        $this->redirectIntended(default: route($user->homeRouteName(), absolute: false), navigate: true);
    }

    /**
     * Shared throttle key matching the "login" limiter in FortifyServiceProvider.
     */
    private function throttleKey(string $identifier): string
    {
        return Str::transliterate($identifier.'|'.request()->ip());
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('pages.auth.login');
    }
}
