<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('reports a password exposed in breaches', function () {
    $hash = strtoupper(sha1('password123'));
    $prefix = substr($hash, 0, 5);
    $suffix = substr($hash, 5);

    Http::fake([
        'https://api.pwnedpasswords.com/range/*' => Http::response($suffix.":12345\n".'OTHERHASH:3'),
    ]);

    $this->postJson('/api/v1/auth/check-breach', ['password' => 'password123'])
        ->assertOk()
        ->assertJson([
            'pwned' => true,
            'count' => 12345,
            'risk_level' => 'critical',
            'label' => 'Compromised',
            'message' => __('auth.social_password_breach', ['count' => number_format(12345)]),
        ]);
});

it('reports a clean password', function () {
    $hash = strtoupper(sha1('unique-password'));
    $suffix = substr($hash, 5);

    Http::fake([
        'https://api.pwnedpasswords.com/range/*' => Http::response('OTHERHASH:1'."\n".'ANOTHERTH:2'),
    ]);

    $this->postJson('/api/v1/auth/check-breach', ['password' => 'unique-password'])
        ->assertOk()
        ->assertJson([
            'pwned' => false,
            'count' => 0,
            'risk_level' => 'safe',
            'label' => 'Clear',
            'message' => __('auth.social_password_safe'),
        ]);
});

it('validates the password length', function () {
    $this->postJson('/api/v1/auth/check-breach', ['password' => 'short'])
        ->assertUnprocessable();
});
