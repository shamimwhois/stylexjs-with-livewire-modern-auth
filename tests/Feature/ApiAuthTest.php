<?php

use App\Models\AuthLog;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

beforeEach(function () {
    config()->set('security.audit_log_enabled', true);
});

function issueApiOtp(string $email, string $code = '123456'): void
{
    OtpCode::query()->create([
        'email' => $email,
        'code_hash' => Hash::make($code),
        'expires_at' => now()->addMinutes(10),
    ]);
}

it('issues a token for valid credentials', function () {
    $user = User::factory()->create(['password' => 'secret-password']);

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'secret-password',
    ])->assertOk()
        ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email']]);

    expect($user->tokens()->count())->toBe(1);
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create(['password' => 'secret-password']);

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertUnprocessable();
});

it('throttles login attempts', function () {
    $user = User::factory()->create(['password' => 'secret-password']);

    foreach (range(1, 5) as $i) {
        $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ])->assertUnprocessable();
    }

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'secret-password',
    ])->assertStatus(429);
});

it('requires a two factor code for enrolled users', function () {
    $user = User::factory()->create(['password' => 'secret-password']);
    $user->forceFill([
        'two_factor_secret' => app('encrypter')->encrypt('secret-secret'),
        'two_factor_recovery_codes' => app('encrypter')->encrypt(json_encode(['abc'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'secret-password',
    ])->assertUnprocessable()
        ->assertJsonValidationErrors('code');
});

it('registers a user with the default role and returns a token', function () {
    $this->postJson('/api/v1/auth/register', [
        'name' => 'New User',
        'username' => 'newuser',
        'email' => 'newuser@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertOk()
        ->assertJsonStructure(['token', 'user']);

    $user = User::query()->where('email', 'newuser@example.com')->firstOrFail();

    expect($user->hasRole('user'))->toBeTrue();
});

it('revokes the current token on logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api')->plainTextToken;

    $this->withToken($token)
        ->postJson('/api/v1/auth/logout')
        ->assertOk();

    expect($user->tokens()->count())->toBe(0);
});

it('sends a password reset link', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->postJson('/api/v1/auth/forgot-password', ['email' => $user->email])
        ->assertOk();

    Notification::assertSentTo($user, ResetPasswordNotification::class);
});

it('resets a password and returns a fresh token', function () {
    $user = User::factory()->create(['password' => 'old-password']);

    $token = Password::broker()->createToken($user);

    $this->postJson('/api/v1/auth/reset-password', [
        'token' => $token,
        'email' => $user->email,
        'password' => 'brand-new-password',
        'password_confirmation' => 'brand-new-password',
    ])->assertOk()
        ->assertJsonStructure(['token']);

    expect(Hash::check('brand-new-password', $user->fresh()->password))->toBeTrue();
});

it('sends and verifies an OTP code', function () {
    Notification::fake();

    $user = User::factory()->create();
    issueApiOtp($user->email, '424242');

    $this->postJson('/api/v1/auth/otp/verify', [
        'email' => $user->email,
        'code' => '424242',
    ])->assertOk()
        ->assertJsonStructure(['token', 'user']);

    $this->postJson('/api/v1/auth/otp/verify', [
        'email' => $user->email,
        'code' => '000000',
    ])->assertUnprocessable();
});

it('rejects OTP verify for non-existent user', function () {
    $this->postJson('/api/v1/auth/otp/verify', [
        'email' => 'ghost@example.com',
        'code' => '123456',
    ])->assertUnprocessable();
});

it('audits api events', function () {
    $user = User::factory()->create(['password' => 'secret-password']);

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ])->assertUnprocessable();

    $this->postJson('/api/v1/auth/login', [
        'email' => $user->email,
        'password' => 'secret-password',
    ])->assertOk();

    $events = app(AuthLog::class)->newQuery()->orderBy('id')->pluck('event')->all();

    expect($events)->toBe(['api.login_failed', 'api.login']);
});
