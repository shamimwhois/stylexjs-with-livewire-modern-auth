<?php

use App\Models\LicenseTier;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('resolves the license tiers offered by a product', function () {
    $product = Product::factory()->create();
    $tier = LicenseTier::factory()->for($product)->create();

    expect($product->tiers->first()->is($tier))->toBeTrue();
    expect($tier->product->is($product))->toBeTrue();
});

it('enforces a unique product slug', function () {
    Product::factory()->create(['slug' => 'pro-one']);

    expect(fn () => Product::factory()->create(['slug' => 'pro-one']))
        ->toThrow(QueryException::class);
});
