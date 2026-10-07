<?php

use App\Livewire\Pages\Auth\VerifyEmail;
use App\Models\AuthLog;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\RawMessage;

uses(RefreshDatabase::class);

function usesThrowingMailer(): void
{
    Config::set('mail.mailers.throwing', ['transport' => 'throwing']);
    Config::set('mail.default', 'throwing');

    Mail::extend('throwing', fn () => new class implements TransportInterface
    {
        public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
        {
            throw new UnexpectedResponseException('550 5.7.0 Too many emails per second. Please upgrade your plan');
        }

        public function __toString(): string
        {
            return 'throwing';
        }
    });
}

it('renders the verify email page for unverified users', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)->get('/verify-email')->assertOk()->assertSeeLivewire(VerifyEmail::class);
});

it('sends a new verification email and confirms it on-screen', function () {
    Notification::fake();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    Livewire::test(VerifyEmail::class)
        ->call('resendNotification')
        ->assertHasNoErrors()
        ->assertSet('noticeModal', false)
        ->assertSee('A new verification link has been sent to the email address you provided during registration.');

    Notification::assertSentTo($user, VerifyEmailNotification::class);

    expect(AuthLog::query()->where('event', 'verification.sent')->where('user_id', $user->id)->exists())->toBeTrue();
});

it('shows a notice modal when the verification email cannot be sent', function () {
    usesThrowingMailer();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    Livewire::test(VerifyEmail::class)
        ->call('resendNotification')
        ->assertHasNoErrors()
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'send_failed')
        ->assertSet('noticeTitle', 'Verification email not sent')
        ->assertSee('Verification email not sent')
        ->assertDontSee('A new verification link has been sent to the email address you provided during registration.');

    expect(AuthLog::query()->where('event', 'verification.send_failed')->where('user_id', $user->id)->exists())->toBeTrue();
});

it('dismisses the resend failure modal', function () {
    usesThrowingMailer();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    Livewire::test(VerifyEmail::class)
        ->call('resendNotification')
        ->assertSet('noticeModal', true)
        ->call('dismissNotice')
        ->assertSet('noticeModal', false);
});
