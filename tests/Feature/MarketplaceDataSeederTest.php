<?php

use App\Models\Addon;
use App\Models\Changelog;
use App\Models\User;
use Database\Seeders\MarketplaceDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('creates a wallet, licences and a feature request for every user', function () {
    User::factory()->count(3)->create();

    $this->seed(MarketplaceDataSeeder::class);

    User::each(function (User $user) {
        $user->load('wallet', 'licences', 'featureRequests');

        expect($user->wallet)->not->toBeNull();
        expect($user->licences)->not->toBeEmpty();
        expect($user->featureRequests)->toHaveCount(1);
    });
});

it('seeds standalone changelogs and addons', function () {
    User::factory()->create();

    $this->seed(MarketplaceDataSeeder::class);

    expect(Changelog::count())->toBe(5);
    expect(Addon::count())->toBe(5);
});
