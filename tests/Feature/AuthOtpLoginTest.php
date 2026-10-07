<?php

use App\Console\Commands\CleanupOtpCodes;
use App\Livewire\Pages\Auth\LoginOtp;
use App\Models\OtpCode;
use App\Models\User;
use App\Notifications\OtpLoginCode;
use App\Services\Auth\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

uses(RefreshDatabase::class);

function issueCode(string $email, string $code = '123456'): OtpCode
{
    return OtpCode::query()->create([
        'email' => $email,
        'code_hash' => Hash::make($code),
        'expires_at' => now()->addMinutes(10),
    ]);
}

it('renders the OTP login page when the feature is enabled', function () {
    config()->set('otp.enabled', true);

    $this->get(route('otp.login'))
        ->assertOk()
        ->assertSeeLivewire(LoginOtp::class);
});

it('returns 404 when OTP feature is disabled', function () {
    config()->set('otp.enabled', false);

    $this->get(route('otp.login'))
        ->assertStatus(404);
});

it('emails a code and shows the verify step', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'jane@example.com']);

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode')
        ->assertSet('codeSent', true)
        ->assertHasNoErrors();

    Notification::assertSentTo($user, OtpLoginCode::class);
    $this->assertDatabaseCount('otp_codes', 1);
});

it('signs in with a valid code', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'jane@example.com']);
    $code = '654321';

    $component = Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode');

    $otp = OtpCode::query()->latest('id')->first();
    $otp->forceFill(['code_hash' => Hash::make($code)])->save();

    $component->set('code', $code)
        ->call('verifyCode')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    expect($otp->fresh()->consumed_at)->not->toBeNull();
});

it('rejects a wrong code and burns it after the attempt budget', function () {
    config()->set('otp.max_attempts', 2);

    $user = User::factory()->create(['email' => 'jane@example.com']);
    issueCode($user->email, '123456');

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->set('code', '000001')
        ->call('verifyCode')
        ->assertHasErrors('code');

    $otp = OtpCode::query()->latest('id')->first();
    expect($otp->attempts)->toBe(1)
        ->and($otp->consumed_at)->toBeNull();

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->set('code', '000002')
        ->call('verifyCode')
        ->assertHasErrors('code');

    $otp->refresh();
    expect($otp->attempts)->toBe(2)
        ->and($otp->consumed_at)->not->toBeNull();

    $this->assertGuest();
});

it('rejects an expired code', function () {
    $user = User::factory()->create(['email' => 'jane@example.com']);
    issueCode($user->email)->forceFill(['expires_at' => now()->subMinute()])->save();

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->set('code', '123456')
        ->call('verifyCode')
        ->assertHasErrors('code');

    $this->assertGuest();
});

it('enforces the resend cooldown', function () {
    config()->set('otp.resend_cooldown', 60);

    $user = User::factory()->create(['email' => 'jane@example.com']);
    issueCode($user->email);

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode')
        ->assertHasErrors('email');

    $this->assertDatabaseCount('otp_codes', 1);
});

it('allows a new code after the cooldown passes', function () {
    config()->set('otp.resend_cooldown', 60);

    $user = User::factory()->create(['email' => 'jane@example.com']);
    issueCode($user->email)->forceFill(['created_at' => now()->subSeconds(61)])->save();

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('otp_codes', 2);
});

it('consumes the newest code, not an older one', function () {
    $user = User::factory()->create(['email' => 'jane@example.com']);

    issueCode($user->email, '111111');
    issueCode($user->email, '222222');

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->set('code', '111111')
        ->call('verifyCode')
        ->assertHasErrors('code');

    $this->assertGuest();
});

it('does not consume the code when user does not exist', function () {
    config()->set('otp.resend_cooldown', 0);

    $otp = issueCode('ghost@example.com', '999999');

    Livewire::test(LoginOtp::class)
        ->set('email', 'ghost@example.com')
        ->set('code', '999999')
        ->call('verifyCode')
        ->assertHasErrors('code');

    $otp->refresh();
    expect($otp->consumed_at)->toBeNull();
});

it('the otp service is the single source of configuration', function () {
    config()->set('otp.ttl', 7);
    config()->set('otp.max_attempts', 3);
    config()->set('otp.resend_cooldown', 90);

    $otp = app(OtpService::class);

    expect($otp->ttl())->toBe(7)
        ->and($otp->maxAttempts())->toBe(3)
        ->and($otp->resendCooldown())->toBe(90);
});

it('cleans up expired otp codes', function () {
    $user = User::factory()->create(['email' => 'jane@example.com']);

    issueCode($user->email);
    issueCode($user->email)->forceFill(['expires_at' => now()->subHour()])->save();

    $this->assertDatabaseCount('otp_codes', 2);

    $this->artisan(CleanupOtpCodes::class)
        ->assertExitCode(0);

    $this->assertDatabaseCount('otp_codes', 1);
});

it('rolls back the code when the email cannot be delivered', function () {
    Notification::extend('mail', function () {
        return new class
        {
            public function send($notifiable, $notification): void
            {
                throw new UnexpectedResponseException('550 5.7.0 too many emails per second');
            }
        };
    });

    $user = User::factory()->create(['email' => 'jane@example.com']);

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode')
        ->assertHasErrors('email');

    $otp = OtpCode::query()->latest('id')->first();
    expect($otp->consumed_at)->not->toBeNull();
});

it('ignores consumed codes for the resend cooldown', function () {
    config()->set('otp.resend_cooldown', 60);

    $user = User::factory()->create(['email' => 'jane@example.com']);

    issueCode($user->email)->forceFill(['consumed_at' => now()])->save();

    Notification::fake();

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode')
        ->assertHasNoErrors();

    $this->assertDatabaseCount('otp_codes', 2);
});

it('redirects admins to the admin dashboard after signing in', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'admin@example.com']);
    $user->assignRole('admin');

    $component = Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode');

    $otp = OtpCode::query()->latest('id')->first();
    $otp->forceFill(['code_hash' => Hash::make('654321')])->save();

    $component->set('code', '654321')
        ->call('verifyCode')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});
