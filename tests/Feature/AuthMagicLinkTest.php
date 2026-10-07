<?php

use App\Livewire\Pages\Auth\MagicLink;
use App\Models\User;
use App\Notifications\MagicLinkLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Symfony\Component\Mailer\Exception\UnexpectedResponseException;

uses(RefreshDatabase::class);

it('renders the magic link login page', function () {
    $this->get(route('login.magic'))
        ->assertOk()
        ->assertSeeLivewire(MagicLink::class);
});

it('returns 404 when magic link feature is disabled', function () {
    config()->set('magic-link.enabled', false);

    $this->get(route('login.magic'))
        ->assertStatus(404);
});

it('emails a temporary signed link to an existing user', function () {
    Notification::fake();

    $user = User::factory()->create();

    Livewire::test(MagicLink::class)
        ->set('email', $user->email)
        ->call('sendLink')
        ->assertSet('linkSent', true)
        ->assertHasNoErrors();

    $sent = null;
    Notification::assertSentTo(
        $user,
        MagicLinkLogin::class,
        function (MagicLinkLogin $notification) use (&$sent) {
            $sent = $notification->url;

            return true;
        },
    );

    expect($sent)->toContain('auth/magic');
});

it('does not reveal whether an email exists', function () {
    Notification::fake();

    Livewire::test(MagicLink::class)
        ->set('email', 'nobody@example.com')
        ->call('sendLink')
        ->assertSet('linkSent', true)
        ->assertHasNoErrors();

    Notification::assertNothingSent();
});

it('throttles magic link requests', function () {
    config()->set('magic-link.ttl', 15);

    $user = User::factory()->create();

    $component = Livewire::test(MagicLink::class);

    foreach (range(1, 5) as $i) {
        $component->set('email', $user->email)->call('sendLink');
    }

    Livewire::test(MagicLink::class)
        ->set('email', $user->email)
        ->call('sendLink')
        ->assertHasErrors('email');
});

it('signs the user in via a valid magic link', function () {
    $user = User::factory()->create();

    Notification::fake();

    $url = URL::temporarySignedRoute('auth.magic.verify', now()->addMinutes(15), [
        'email' => $user->email,
    ]);

    $this->get($url)
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('rejects an expired or tampered magic link', function () {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('auth.magic.verify', now()->subMinutes(5), [
        'email' => $user->email,
    ]);

    $this->get($url)
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('rejects a magic link for an unknown email', function () {
    $url = URL::temporarySignedRoute('auth.magic.verify', now()->addMinutes(15), [
        'email' => 'ghost@example.com',
    ]);

    $this->get($url)
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');
});

it('surfaces a friendly error when the link email cannot be delivered', function () {
    Notification::extend('mail', function () {
        return new class
        {
            public function send($notifiable, $notification): void
            {
                throw new UnexpectedResponseException('550 5.7.0 too many emails per second');
            }
        };
    });

    $user = User::factory()->create();

    Livewire::test(MagicLink::class)
        ->set('email', $user->email)
        ->call('sendLink')
        ->assertSet('linkSent', false)
        ->assertHasErrors('email');
});

it('redirects admins to the admin dashboard via a magic link', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    Notification::fake();

    $url = URL::temporarySignedRoute('auth.magic.verify', now()->addMinutes(15), [
        'email' => $user->email,
    ]);

    $this->get($url)
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});
