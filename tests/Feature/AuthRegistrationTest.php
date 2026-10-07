<?php

use App\Livewire\Pages\Auth\Register;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('renders the registration page', function () {
    $this->get('/register')
        ->assertOk()
        ->assertSeeLivewire(Register::class)
        ->assertSee('Generate password');
});

it('can register new users', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('username', 'testuser')
        ->set('email', 'test@example.com')
        ->set('password', 'valid-password-123')
        ->set('password_confirmation', 'valid-password-123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
    ]);

    $this->assertAuthenticated();
});

it('validates the registration form through the CreateNewUser action', function () {
    Livewire::test(Register::class)
        ->set('name', '')
        ->set('email', 'not-an-email')
        ->set('password', 'short')
        ->set('password_confirmation', 'different')
        ->call('register')
        ->assertHasErrors(['name', 'email', 'password']);
});

it('does not allow registering with a duplicate email', function () {
    $existing = User::factory()->create();

    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('email', $existing->email)
        ->set('password', 'valid-password-123')
        ->set('password_confirmation', 'valid-password-123')
        ->call('register')
        ->assertHasErrors(['email']);
});

it('persists phone, country code, social profiles and address on registration', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('username', 'testuser')
        ->set('email', 'test@example.com')
        ->set('country_code', '+1')
        ->set('phone', '(555) 123-4567')
        ->set('facebook', 'https://facebook.com/testuser')
        ->set('whatsapp', 'https://wa.me/15551234567')
        ->set('website', 'https://example.com')
        ->set('street_address', '123 Main St')
        ->set('city', 'Springfield')
        ->set('state', 'IL')
        ->set('postal_code', '62701')
        ->set('country', 'United States')
        ->set('password', 'valid-password-123')
        ->set('password_confirmation', 'valid-password-123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $user = User::where('email', 'test@example.com')->firstOrFail();

    $this->assertSame('+1', $user->country_code);
    $this->assertSame('(555) 123-4567', $user->phone);
    $this->assertSame('United States', $user->addresses()->first()->country);
    $this->assertSame('123 Main St', $user->addresses()->first()->street);

    $this->assertDatabaseHas('social_profiles', [
        'user_id' => $user->id,
        'input_type' => 'facebook',
        'url' => 'https://facebook.com/testuser',
    ]);
    $this->assertDatabaseHas('social_profiles', [
        'user_id' => $user->id,
        'input_type' => 'whatsapp',
        'url' => 'https://wa.me/15551234567',
    ]);
    $this->assertDatabaseHas('social_profiles', [
        'user_id' => $user->id,
        'input_type' => 'website',
        'url' => 'https://example.com',
    ]);
    $this->assertDatabaseMissing('social_profiles', [
        'user_id' => $user->id,
        'input_type' => 'telegram',
    ]);
});

it('does not create address rows when no address fields are provided', function () {
    Livewire::test(Register::class)
        ->set('name', 'Test User')
        ->set('username', 'testuser')
        ->set('email', 'test@example.com')
        ->set('password', 'valid-password-123')
        ->set('password_confirmation', 'valid-password-123')
        ->call('register')
        ->assertHasNoErrors();

    $user = User::where('email', 'test@example.com')->firstOrFail();

    $this->assertDatabaseCount('social_profiles', 0);
    $this->assertCount(0, $user->addresses);
});
