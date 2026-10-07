<?php

use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\LoginOtp;
use App\Models\AuthLog;
use App\Models\User;
use App\Notifications\OtpLoginCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    config()->set('security.audit_log_enabled', true);
});

function auditEvents(): array
{
    return AuthLog::query()->orderBy('id')->pluck('event')->all();
}

it('logs successful and failed password logins', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('authenticate')
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'failed');

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors();

    expect(auditEvents())->toContain('login.failed', 'login.success');
});

it('records ip and user agent on audit rows', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors();

    $log = AuthLog::query()->where('event', 'login.success')->firstOrFail();

    expect($log->user_id)->toBe($user->id)
        ->and($log->ip_address)->not->toBeNull()
        ->and($log->user_agent)->not->toBeNull();
});

it('logs otp issues and verifications', function () {
    Notification::fake();

    $user = User::factory()->create(['email' => 'otp@example.com']);

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->call('sendCode')
        ->assertHasNoErrors();

    $code = null;
    Notification::assertSentTo(
        $user,
        OtpLoginCode::class,
        function (OtpLoginCode $notification) use (&$code) {
            $code = $notification->code;

            return true;
        },
    );

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->set('code', '999999')
        ->call('verifyCode')
        ->assertHasErrors('code');

    Livewire::test(LoginOtp::class)
        ->set('email', $user->email)
        ->set('code', $code)
        ->call('verifyCode')
        ->assertHasNoErrors();

    expect(auditEvents())->toContain('otp.sent', 'otp.failed', 'otp.verified');
});

it('skips logging when the audit flag is disabled', function () {
    config()->set('security.audit_log_enabled', false);

    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertHasNoErrors();

    expect(AuthLog::query()->count())->toBe(0);
});
