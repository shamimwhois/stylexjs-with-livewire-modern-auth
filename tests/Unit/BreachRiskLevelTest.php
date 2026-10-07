<?php

use App\Services\Auth\Breach\RiskLevel;

it('maps exposure counts onto risk levels', function (int $count, RiskLevel $level) {
    expect(RiskLevel::fromCount($count))->toBe($level);
})->with([
    [0, RiskLevel::Safe],
    [1, RiskLevel::Low],
    [99, RiskLevel::Low],
    [100, RiskLevel::Medium],
    [999, RiskLevel::Medium],
    [1000, RiskLevel::High],
    [9999, RiskLevel::High],
    [10000, RiskLevel::Critical],
]);

it('serializes to a lowercase key for api responses', function () {
    expect(RiskLevel::Critical->value)->toBe('critical');
    expect(RiskLevel::Safe->value)->toBe('safe');
    expect(RiskLevel::Low->value)->toBe('low');
    expect(RiskLevel::Medium->value)->toBe('medium');
    expect(RiskLevel::High->value)->toBe('high');
});

it('renders a human label for each risk level', function (RiskLevel $level, string $label) {
    expect($level->label())->toBe($label);
})->with([
    [RiskLevel::Safe, 'Safe'],
    [RiskLevel::Low, 'Low'],
    [RiskLevel::Medium, 'Medium'],
    [RiskLevel::High, 'High'],
    [RiskLevel::Critical, 'Critical'],
]);
