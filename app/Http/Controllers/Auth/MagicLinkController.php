<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthAuditLogger;
use App\Services\Auth\DeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MagicLinkController extends Controller
{
    /**
     * Sign the user in from a valid magic link signature.
     */
    public function verify(Request $request, string $email): RedirectResponse
    {
        if (! $request->hasValidSignature()) {
            return redirect()->route('login')
                ->withErrors(['email' => __('auth.magic.invalid')]);
        }

        $user = User::query()->where('email', $email)->first();

        if (! $user) {
            return redirect()->route('login')
                ->withErrors(['email' => __('auth.magic.invalid')]);
        }

        Auth::login($user);

        $request->session()->regenerate();

        app(DeviceService::class)->recordLogin($user);

        app(AuthAuditLogger::class)->log('magic.link_verified', $user);

        return redirect()->intended(route($user->homeRouteName()));
    }
}
