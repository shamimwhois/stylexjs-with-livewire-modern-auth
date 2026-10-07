<?php

namespace App\Http\Controllers\Api;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\BreachCheckResource;
use App\Models\User;
use App\Services\Auth\AuthAuditLogger;
use App\Services\Auth\Breach\BreachService;
use App\Services\Auth\DeviceService;
use App\Services\Auth\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Fortify;

class AuthController extends Controller
{
    public function __construct(
        private readonly CreateNewUser $createUser,
        private readonly BreachService $breach,
    ) {}

    /**
     * Check whether the submitted password is exposed in known breaches.
     */
    public function checkBreach(Request $request): BreachCheckResource
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:128'],
        ]);

        return new BreachCheckResource($this->breach->check($data['password']));
    }

    /**
     * Issue a personal access token for valid email/password credentials.
     *
     * Users enrolled in two factor authentication must also supply a valid
     * authenticator code.
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email'],
            'password' => ['required', 'string'],
            'code' => ['nullable', 'string'],
        ]);

        $user = $this->verifyCredentials($data['email'], $data['password']);

        if ($user->two_factor_secret) {
            $code = $data['code'] ?? null;
            $valid = $code !== null && app(TwoFactorAuthenticationProvider::class)->verify(
                Fortify::currentEncrypter()->decrypt($user->two_factor_secret),
                $code,
            );

            if (! $valid) {
                throw ValidationException::withMessages([
                    'code' => __('auth.two_factor_code_invalid'),
                ]);
            }
        }

        $token = $user->createToken('api')->plainTextToken;

        app(AuthAuditLogger::class)->log('api.login', $user);

        return $this->tokenResponse($user, $token);
    }

    /**
     * Register a new user and return a personal access token.
     */
    public function register(Request $request): JsonResponse
    {
        $user = $this->createUser->create($request->all());

        $token = $user->createToken('api')->plainTextToken;

        app(AuthAuditLogger::class)->log('api.register', $user);

        return $this->tokenResponse($user, $token);
    }

    /**
     * Revoke the current personal access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        $request->user()->currentAccessToken()->delete();

        app(AuthAuditLogger::class)->log('api.logout', $user);

        return response()->json(['message' => 'Signed out.']);
    }

    /**
     * Email a password reset link.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email'],
        ]);

        Password::broker()->sendResetLink($request->only('email'));

        app(AuthAuditLogger::class)->log('api.password_reset_sent', null, ['email' => $request->input('email')]);

        return response()->json(['message' => 'If that address exists, a reset link has been sent.']);
    }

    /**
     * Reset a password with a broker token and return a fresh token.
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $status = Password::broker()->reset(
            $data,
            fn (User $user, string $password) => $this->resetPasswordForUser($user, $password),
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        $user = User::query()->where('email', $data['email'])->firstOrFail();

        $token = $user->createToken('api')->plainTextToken;

        app(AuthAuditLogger::class)->log('api.password_reset', $user);

        return $this->tokenResponse($user, $token);
    }

    /**
     * Email a one-time sign-in code.
     */
    public function sendOtp(Request $request, OtpService $otp): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email'],
        ]);

        $otp->issue($data['email']);

        return response()->json(['message' => 'If that address exists, a code has been sent.']);
    }

    /**
     * Verify a one-time sign-in code and return a token.
     */
    public function verifyOtp(Request $request, OtpService $otp): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email'],
            'code' => ['required', 'string'],
        ]);

        $otp->verify($data['email'], $data['code']);

        $user = User::query()->where('email', $data['email'])->firstOrFail();

        app(DeviceService::class)->recordLogin($user);

        $token = $user->createToken('api')->plainTextToken;

        app(AuthAuditLogger::class)->log('api.otp_verified', $user);

        return $this->tokenResponse($user, $token);
    }

    /**
     * Validate credentials and return the matching user.
     */
    private function verifyCredentials(string $email, string $password): User
    {
        $provider = Auth::guard('web')->getProvider();

        $user = $provider->retrieveByCredentials(['email' => $email]);

        if (! $user || ! $provider->validateCredentials($user, ['password' => $password])) {
            app(AuthAuditLogger::class)->log('api.login_failed', null, ['email' => $email]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return $user;
    }

    /**
     * Persist a reset password callback.
     */
    private function resetPasswordForUser(User $user, string $password): void
    {
        $user->password = $password;
        $user->setRememberToken(Str::random(60));
        $user->save();
    }

    /**
     * Build the standard token response payload.
     */
    private function tokenResponse(User $user, string $token): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'user' => $user->only(['id', 'name', 'username', 'email']),
        ]);
    }
}
