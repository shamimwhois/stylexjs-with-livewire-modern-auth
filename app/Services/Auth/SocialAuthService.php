<?php

namespace App\Services\Auth;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User as SocialUser;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthService
{
    /**
     * The social drivers currently surfaced in the auth UI.
     *
     * @return array<int, string>
     */
    public function supportedDrivers(): array
    {
        return collect(Socialite::driverNames())->filter(function ($driver) {
            return $this->isDriverEnabled($driver) && $this->isDriverConfigured($driver);
        })->values()->all();
    }

    /**
     * Determine whether the given driver is enabled and configured.
     */
    public function isSupportedDriver(string $driver): bool
    {
        return $this->isDriverEnabled($driver) && $this->isDriverConfigured($driver);
    }

    /**
     * Redirect the user to the given OAuth provider.
     *
     * @return RedirectResponse
     */
    public function redirect(string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /**
     * Resolve a local user from the OAuth provider callback.
     *
     * @throws \RuntimeException When the provider response cannot be matched
     *                           to a user (e.g. missing email).
     */
    public function callback(string $driver): User
    {
        $socialUser = Socialite::driver($driver)->user();

        $account = SocialAccount::query()
            ->where('provider', $driver)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        }

        $user = $socialUser->getEmail() ? User::where('email', $socialUser->getEmail())->first() : null;

        if ($user) {
            $this->storeAccount($user, $driver, $socialUser);

            return $user;
        }

        if (! $socialUser->getEmail()) {
            throw new \RuntimeException(__('auth.social_no_email'));
        }

        $user = User::create([
            'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'User',
            'username' => $this->uniqueUsername($socialUser->getEmail()),
            'email' => $socialUser->getEmail(),
            'password' => Str::random(40),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('user');

        $this->storeAccount($user, $driver, $socialUser);

        return $user;
    }

    /**
     * Sign the user in, record the login device and return them to the
     * application with a provider success flash message.
     *
     * @return RedirectResponse
     */
    public function login(User $user, string $driver)
    {
        Auth::login($user);

        request()->session()->regenerate();

        app(DeviceService::class)->recordLogin($user);

        app(AuthAuditLogger::class)->log('social.login', $user, ['driver' => $driver]);

        return redirect()->intended(route($user->homeRouteName()))
            ->with('status', __('auth.social_signed_in', ['driver' => ucfirst($driver)]));
    }

    private function storeAccount(User $user, string $driver, SocialUser $socialUser): void
    {
        $user->socialAccounts()->firstOrCreate([
            'provider' => $driver,
            'provider_id' => $socialUser->getId(),
        ], [
            'avatar' => $socialUser->getAvatar(),
        ]);
    }

    /**
     * Derive a username that does not collide with an existing user.
     */
    private function uniqueUsername(string $email): string
    {
        $base = Str::lower(Str::before($email, '@')) ?: 'user';
        $username = $base;
        $suffix = 1;

        while (User::where('username', $username)->exists()) {
            $username = $base.$suffix;
            $suffix++;
        }

        return $username;
    }

    private function isDriverEnabled(string $driver): bool
    {
        return (bool) config("socialite.enabled.{$driver}", false);
    }

    private function isDriverConfigured(string $driver): bool
    {
        return (bool) config("services.{$driver}.client_id");
    }
}
