<?php

use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\LoginOtp;
use App\Models\LoginDevice;
use App\Models\OtpCode;
use App\Models\User;
use App\Notifications\NewDeviceLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    config()->set('security.bind_device', true);
    config()->set('security.new_device_alert', true);
});

function issueOtp(string $email, string $code = '654321'): void
{
    OtpCode::query()->create([
        'email' => $email,
        'code_hash' => Hash::make($code),
        'expires_at' => now()->addMinutes(10),
    ]);
}

it('binds a device and alerts on password login', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors();

    expect($user->loginDevice()->exists())->toBeTrue();

    Notification::assertSentTo($user, NewDeviceLogin::class);
});

it('does not re-alert when the same device signs in again', function () {
    Notification::fake();

    $user = User::factory()->create();

    foreach (range(1, 2) as $i) {
        Livewire::test(Login::class)
            ->set('email', $user->email)
            ->set('password', 'password')
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->post(route('logout'));
    }

    Notification::assertSentToTimes($user, NewDeviceLogin::class, 1);
});

it('binds a device and alerts on OTP login', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'otp@example.com']);
    issueOtp($user->email);

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->set('code', '654321')
        ->call('verifyCode')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    expect($user->loginDevice()->exists())->toBeTrue();

    Notification::assertSentTo($user, NewDeviceLogin::class);
});

it('binds a device and alerts on social login', function () {
    Notification::fake();
    Socialite::fake('github', (new Laravel\Socialite\Two\User)->map([
        'id' => 'gh-1',
        'name' => 'Jane Doe',
        'email' => 'social@example.com',
    ]));

    config()->set('socialite.enabled.github', true);
    config()->set('services.github.client_id', 'test-client');
    config()->set('services.github.client_secret', 'test-secret');

    $this->get(route('social.callback', 'github'))
        ->assertRedirect(route('dashboard'));

    $user = User::query()->where('email', 'social@example.com')->firstOrFail();

    expect($user->loginDevice()->exists())->toBeTrue();

    Notification::assertSentTo($user, NewDeviceLogin::class);
});

it('shows a provider success flash after social login', function () {
    Notification::fake();
    Socialite::fake('github', (new Laravel\Socialite\Two\User)->map([
        'id' => 'gh-2',
        'name' => 'Flash Doe',
        'email' => 'flash@example.com',
    ]));

    config()->set('socialite.enabled.github', true);
    config()->set('services.github.client_id', 'test-client');
    config()->set('services.github.client_secret', 'test-secret');

    $this->get(route('social.callback', 'github'))
        ->assertRedirect(route('dashboard'))
        ->assertSessionHas('status', 'Signed in with Github successfully.');
});

it('destroys the session when the IP changes mid-session', function () {
    $user = User::factory()->create();

    LoginDevice::query()->create([
        'user_id' => $user->id,
        'fingerprint' => hash('sha256', '10.0.0.1|Test-Agent'),
        'ip_address' => '10.0.0.1',
        'user_agent' => 'Test-Agent',
        'last_seen_at' => now(),
    ]);

    $this->actingAs($user)
        ->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
        ->withHeaders(['User-Agent' => 'Test-Agent'])
        ->get(route('dashboard'))
        ->assertOk();

    // Same browser, different IP → cookie replay → session destroyed.
    $this->actingAs($user)
        ->withServerVariables(['REMOTE_ADDR' => '10.9.9.9'])
        ->withHeaders(['User-Agent' => 'Test-Agent'])
        ->get(route('dashboard'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('destroys the session when the user agent changes mid-session', function () {
    $user = User::factory()->create();

    LoginDevice::query()->create([
        'user_id' => $user->id,
        'fingerprint' => hash('sha256', '10.0.0.1|Chrome'),
        'ip_address' => '10.0.0.1',
        'user_agent' => 'Chrome',
        'last_seen_at' => now(),
    ]);

    $this->actingAs($user)
        ->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
        ->withHeaders(['User-Agent' => 'Firefox'])
        ->get(route('dashboard'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('allows the bound device to keep browsing', function () {
    $user = User::factory()->create();

    LoginDevice::query()->create([
        'user_id' => $user->id,
        'fingerprint' => hash('sha256', '10.0.0.1|Chrome'),
        'ip_address' => '10.0.0.1',
        'user_agent' => 'Chrome',
        'last_seen_at' => now(),
    ]);

    $this->actingAs($user)
        ->withServerVariables(['REMOTE_ADDR' => '10.0.0.1'])
        ->withHeaders(['User-Agent' => 'Chrome'])
        ->get(route('dashboard'))
        ->assertOk();

    $this->assertAuthenticatedAs($user);
});

it('does not bind or alert when the feature is disabled', function () {
    Notification::fake();
    config()->set('security.bind_device', false);
    config()->set('security.new_device_alert', false);

    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors();

    expect($user->loginDevice()->exists())->toBeFalse();

    Notification::assertNothingSentTo($user);
});
