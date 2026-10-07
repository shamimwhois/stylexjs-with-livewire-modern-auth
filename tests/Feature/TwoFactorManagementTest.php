<?php

use App\Livewire\Pages\Account\Security;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;

uses(RefreshDatabase::class);

function otpForSecret(string $secret): string
{
    return app(Google2FA::class)->getCurrentOtp($secret);
}

it('renders the security page for authenticated users', function () {
    $this->actingAs(User::factory()->create())
        ->get('/account/security')
        ->assertOk()
        ->assertSeeLivewire(Security::class);
});

it('requires the current password before enabling two factor', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Livewire::actingAs($user)
        ->test(Security::class)
        ->call('prepareEnable')
        ->assertSet('passwordPrompt', true);

    Livewire::actingAs($user)
        ->test(Security::class)
        ->set('password', 'wrong-password')
        ->call('submitPassword')
        ->assertHasErrors('password');
});

it('enables two factor and confirms with a valid code', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Security::class)
        ->call('prepareEnable')
        ->set('password', 'password')
        ->call('submitPassword')
        ->assertSet('pendingConfirm', true)
        ->assertHasNoErrors();

    $secret = app('encrypter')->decrypt($user->fresh()->two_factor_secret);

    $valid = otpForSecret($secret);

    Livewire::actingAs($user)
        ->test(Security::class)
        ->set('code', '000000')
        ->call('confirmTwoFactor')
        ->assertHasErrors('code');

    Livewire::actingAs($user)
        ->test(Security::class)
        ->set('code', $valid)
        ->call('confirmTwoFactor')
        ->assertHasNoErrors()
        ->assertSet('showRecoveryCodes', true);

    expect($user->fresh()->hasEnabledTwoFactorAuthentication())->toBeTrue();
});

it('regenerates the recovery codes', function () {
    $user = User::factory()->create();
    $user->forceFill([
        'two_factor_secret' => app('encrypter')->encrypt('supersecret1'),
        'two_factor_recovery_codes' => app('encrypter')->encrypt(json_encode(['old-code'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    Livewire::actingAs($user)
        ->test(Security::class)
        ->call('prepareRegenerate')
        ->set('password', 'password')
        ->call('submitPassword')
        ->assertSet('showRecoveryCodes', true)
        ->assertHasNoErrors();

    $codes = $user->fresh()->recoveryCodes();

    expect($codes)->not->toBe(['old-code'])
        ->and($codes)->not->toBeEmpty();
});

it('disables two factor after password confirmation', function () {
    $user = User::factory()->create();
    $user->forceFill([
        'two_factor_secret' => app('encrypter')->encrypt('supersecret1'),
        'two_factor_recovery_codes' => app('encrypter')->encrypt(json_encode(['code'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    Livewire::actingAs($user)
        ->test(Security::class)
        ->call('prepareDisable')
        ->set('password', 'password')
        ->call('submitPassword')
        ->assertHasNoErrors();

    $fresh = $user->fresh();

    expect($fresh->two_factor_secret)->toBeNull()
        ->and($fresh->hasEnabledTwoFactorAuthentication())->toBeFalse();
});
