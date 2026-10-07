<?php

namespace App\Http\Middleware;

use App\Services\Auth\DeviceService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Destroys the session when a signed-in request arrives from a device
 * fingerprint (IP + user agent) different from the one the account is
 * bound to — a stolen-cookie replay from elsewhere is logged out.
 */
class VerifySessionSecurity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $device = app(DeviceService::class);
        $user = $request->user();

        if ($user
            && $request->hasSession()
            && $device->bindingEnabled()
            && ! $device->matchesBoundDevice($user)) {
            Auth::guard()->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withInput()
                ->withErrors(['email' => __('auth.device.session_expired')]);
        }

        return $next($request);
    }
}
