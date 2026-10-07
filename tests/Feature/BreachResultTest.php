<?php

use App\Services\Auth\Breach\BreachResult;
use App\Services\Auth\Breach\RiskLevel;

it('labels a breached result as compromised', function () {
    $result = new BreachResult(pwned: true, count: 1500, riskLevel: RiskLevel::High);

    expect($result->label())->toBe('Compromised');
});

it('labels a clean result as clear', function () {
    $result = new BreachResult(pwned: false, count: 0, riskLevel: RiskLevel::Safe);

    expect($result->label())->toBe('Clear');
});

it('serializes to the canonical api shape', function () {
    $result = new BreachResult(pwned: true, count: 1234, riskLevel: RiskLevel::Medium);

    expect($result->toArray())->toBe([
        'pwned' => true,
        'count' => 1234,
        'risk_level' => 'medium',
        'label' => 'Compromised',
        'message' => __('auth.social_password_breach', ['count' => number_format(1234)]),
    ]);
});

it('uses the safe translation for clean results', function () {
    $result = new BreachResult(pwned: false, count: 0, riskLevel: RiskLevel::Safe);

    expect($result->message())->toBe(__('auth.social_password_safe'));
});
