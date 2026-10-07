<?php

use App\Services\Auth\Breach\BreachService;
use App\Services\Auth\Breach\RiskLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

it('reports the exposure count and risk level', function () {
    $hash = strtoupper(sha1('password123'));
    $suffix = substr($hash, 5);

    Http::fake([
        'https://api.pwnedpasswords.com/range/*' => Http::response($suffix.":12345\n".'OTHERHASH:2'),
    ]);

    $result = app(BreachService::class)->check('password123');

    expect($result->pwned)->toBeTrue();
    expect($result->count)->toBe(12345);
    expect($result->riskLevel)->toBe(RiskLevel::Critical);
});

it('maps smaller exposure counts to lower risk levels', function () {
    $hash = strtoupper(sha1('password123'));
    $suffix = substr($hash, 5);

    Http::fake([
        'https://api.pwnedpasswords.com/range/*' => Http::response($suffix.':150'),
    ]);

    $result = app(BreachService::class)->check('password123');

    expect($result->pwned)->toBeTrue();
    expect($result->riskLevel)->toBe(RiskLevel::Medium);
});

it('reports a password as safe when absent from breaches', function () {
    $hash = strtoupper(sha1('unique-password'));
    $suffix = substr($hash, 5);

    Http::fake([
        'https://api.pwnedpasswords.com/range/*' => Http::response('OTHERHASH:1'),
    ]);

    $result = app(BreachService::class)->check('unique-password');

    expect($result->pwned)->toBeFalse();
    expect($result->count)->toBe(0);
    expect($result->riskLevel)->toBe(RiskLevel::Safe);
});

it('exposes an isPwned convenience check', function () {
    $hash = strtoupper(sha1('password123'));
    $suffix = substr($hash, 5);

    Http::fake([
        'https://api.pwnedpasswords.com/range/*' => Http::response($suffix.':1'),
    ]);

    expect(app(BreachService::class)->isPwned('password123'))->toBeTrue();
});

it('fails open when HIBP is unreachable', function () {
    Http::fake([
        'https://api.pwnedpasswords.com/range/*' => Http::response('', 500),
    ]);

    $result = app(BreachService::class)->check('password123');

    expect($result->pwned)->toBeFalse();
    expect($result->riskLevel)->toBe(RiskLevel::Safe);
});
