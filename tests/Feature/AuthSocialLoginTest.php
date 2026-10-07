<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;

uses(RefreshDatabase::class);

beforeEach(function () {
    config()->set('socialite.enabled.github', true);
    config()->set('services.github.client_id', 'test-client');
    config()->set('services.github.client_secret', 'test-secret');

    Socialite::fake('github', socialiteUser([
        'id' => 'github-123',
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'avatar' => 'https://example.com/avatar.png',
    ]));
});

function socialiteUser(array $attributes): Laravel\Socialite\Two\User
{
    return (new Laravel\Socialite\Two\User)->map($attributes);
}

it('creates a user and linked social account on first login', function () {
    $response = $this->get(route('social.callback', 'github'));

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
    $this->assertDatabaseHas('social_accounts', [
        'provider' => 'github',
        'provider_id' => 'github-123',
    ]);
});

it('links an existing user by email without duplicating it', function () {
    $user = User::factory()->create(['email' => 'jane@example.com']);

    $this->get(route('social.callback', 'github'))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseCount('users', 1);
    $this->assertDatabaseHas('social_accounts', [
        'user_id' => $user->id,
        'provider' => 'github',
        'provider_id' => 'github-123',
    ]);
});

it('signs in an existing linked social account', function () {
    $user = User::factory()->create(['email' => 'jane@example.com']);
    $user->socialAccounts()->create([
        'provider' => 'github',
        'provider_id' => 'github-123',
    ]);

    $this->get(route('social.callback', 'github'))
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
    $this->assertDatabaseCount('users', 1);
});

it('creates a unique username when the derived one is already taken', function () {
    User::factory()->create(['username' => 'jane']);

    $this->get(route('social.callback', 'github'))
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'username' => 'jane1',
        'email' => 'jane@example.com',
    ]);
});

it('redirects back to login when the provider omits the email', function () {
    Socialite::fake('github', socialiteUser([
        'id' => 'github-456',
        'name' => 'No Email',
    ]));

    $this->get(route('social.callback', 'github'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('returns 404 for a disabled provider', function () {
    config()->set('socialite.enabled.github', false);

    $this->get(route('social.callback', 'github'))->assertNotFound();
});

it('redirects admins to the admin dashboard after social sign in', function () {
    $user = User::factory()->create(['email' => 'jane@example.com']);
    $user->assignRole('admin');

    Socialite::fake('github', socialiteUser([
        'id' => 'github-123',
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'avatar' => 'https://example.com/avatar.png',
    ]));

    $this->get(route('social.callback', 'github'))
        ->assertRedirect(route('admin.dashboard'));

    $this->assertAuthenticatedAs($user);
});
