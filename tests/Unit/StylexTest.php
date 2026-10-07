<?php

use App\Support\Stylex;
use Tests\TestCase;

uses(TestCase::class);

it('resolves style keys to their compiled class hashes', function () {
    expect(Stylex::cls('brandText'))->toBe(Stylex::map()['brandText'])
        ->and(Stylex::has('brandText'))->toBeTrue();
});

it('passes raw single-token classes through instead of dropping them', function () {
    expect(Stylex::cls('shrink0'))->toBe('shrink0')
        ->and(Stylex::has('shrink0'))->toBeFalse();
});

it('passes multi-class raw values through as-is', function () {
    expect(Stylex::cls('accent-fg-70 mt-2'))->toBe('accent-fg-70 mt-2');
});

it('flattens nested arrays preserving order', function () {
    expect(Stylex::cls('brandText', ['shrink0', ['mt-2']]))->toBe(Stylex::map()['brandText'].' shrink0 mt-2');
});

it('skips false, null and empty strings', function () {
    expect(Stylex::cls(false, null, '', 'shrink0'))->toBe('shrink0');
});

it('deduplicates classes across arguments', function () {
    expect(Stylex::cls('shrink0', 'shrink0', ['shrink0']))->toBe('shrink0');
});

it('mixes resolved keys with raw classes in a single call', function () {
    expect(Stylex::cls('btn', 'shrink0', 'btn'))->toBe(Stylex::map()['btn'].' shrink0');
});

it('returns an empty string when no classes are given', function () {
    expect(Stylex::cls())->toBe('');
});
