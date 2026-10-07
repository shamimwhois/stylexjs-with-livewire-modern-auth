<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\SocialAuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    public function __construct(private readonly SocialAuthService $socialAuth) {}

    /**
     * Redirect the user to the given OAuth provider.
     */
    public function redirect(Request $request, string $driver): RedirectResponse
    {
        abort_unless($this->socialAuth->isSupportedDriver($driver), 404);

        return $this->socialAuth->redirect($driver);
    }

    /**
     * Handle the OAuth provider callback.
     */
    public function callback(Request $request, string $driver): RedirectResponse
    {
        abort_unless($this->socialAuth->isSupportedDriver($driver), 404);

        try {
            $user = $this->socialAuth->callback($driver);
        } catch (\Throwable $e) {
            logger()->error('Social login failed.', ['driver' => $driver, 'exception' => $e]);

            return redirect()->route('login')
                ->withErrors(['email' => __('auth.social_login_failed')]);
        }

        return $this->socialAuth->login($user, $driver);
    }
}
