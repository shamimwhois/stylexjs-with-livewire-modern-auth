<?php

use App\Livewire\Pages\Auth\Login;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Fortify;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;

uses(RefreshDatabase::class);

it('renders the login page', function () {
    $this->get('/login')->assertOk()->assertSeeLivewire(Login::class);
});

it('can authenticate users using the login page', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('cannot authenticate with invalid credentials', function () {
    User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', 'does-not-exist@example.com')
        ->set('password', 'wrong-password')
        ->call('authenticate')
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'failed')
        ->assertHasNoErrors();

    $this->assertGuest();
});

it('renders the sign in notice modal with the failure reason', function () {
    User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', 'does-not-exist@example.com')
        ->set('password', 'wrong-password')
        ->call('authenticate')
        ->assertSee('Sign-in failed')
        ->assertSee(__('auth.failed'))
        ->assertSee('Invalid credentials')
        ->assertSee('Got it');
});

it('dismisses the sign in notice modal', function () {
    User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', 'does-not-exist@example.com')
        ->set('password', 'wrong-password')
        ->call('authenticate')
        ->assertSet('noticeModal', true)
        ->call('dismissNotice')
        ->assertSet('noticeModal', false);
});

it('can authenticate using a username', function () {
    $user = User::factory()->create(['username' => 'john_doe']);

    Livewire::test(Login::class)
        ->set('email', 'john_doe')
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('can authenticate using a username with mixed casing', function () {
    $user = User::factory()->create(['username' => 'john_doe']);

    Livewire::test(Login::class)
        ->set('email', 'JOHN_Doe')
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('cannot authenticate with an unknown username', function () {
    User::factory()->create(['username' => 'john_doe']);

    Livewire::test(Login::class)
        ->set('email', 'unknown_user')
        ->set('password', 'password')
        ->call('authenticate')
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'failed')
        ->assertHasNoErrors();

    $this->assertGuest();
});

it('throttles login attempts after five failed attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'wrong-password')
            ->call('authenticate')
            ->assertSet('noticeModal', true)
            ->assertSet('noticeType', 'failed');
    }

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'throttled');

    $this->assertGuest();
});

it('redirects admins to the admin dashboard after signing in', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('asks a user with two factor enabled for an authentication code', function () {
    [$user, $secret] = twoFactorUser();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertSet('twoFactorChallenge', true);

    $this->assertGuest();
    $this->assertNotNull(session('login.id'));
});

it('rejects an invalid two factor authentication code', function () {
    [$user] = twoFactorUser();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate');

    Livewire::test(Login::class)
        ->set('code', '000000')
        ->call('confirmTwoFactorAuthentication')
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'two_factor_invalid')
        ->assertHasNoErrors();

    $this->assertGuest();
});

it('signs the user in with a valid two factor authentication code', function () {
    [$user, $secret] = twoFactorUser();
    $code = app(Google2FA::class)->getCurrentOtp($secret);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate');

    Livewire::test(Login::class)
        ->set('code', $code)
        ->call('confirmTwoFactorAuthentication')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->assertNull(session('login.id'));
});

function twoFactorUser(): array
{
    $google2fa = app(Google2FA::class);
    $secret = $google2fa->generateSecretKey();

    $user = User::factory()->create();
    $user->forceFill([
        'two_factor_secret' => Fortify::currentEncrypter()->encrypt($secret),
        'two_factor_recovery_codes' => Fortify::currentEncrypter()->encrypt(json_encode([])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    return [$user, $secret];
}
