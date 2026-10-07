<?php

use App\Models\FeatureRequest;
use App\Models\Licence;
use App\Models\Seller;
use App\Models\SocialProfile;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('resolves the wallet owned by the user', function () {
    $user = User::factory()->create();
    $wallet = Wallet::factory()->for($user)->create();

    expect($user->wallet->is($wallet))->toBeTrue();
    expect($wallet->user->is($user))->toBeTrue();
});

it('allows only one wallet per user', function () {
    $user = User::factory()->create();

    Wallet::factory()->for($user)->create();

    expect(fn () => Wallet::factory()->for($user)->create())
        ->toThrow(QueryException::class);
});

it('resolves the licences owned by the user', function () {
    $user = User::factory()->create();

    Licence::factory()->count(2)->for($user)->create();

    expect($user->licences)->toHaveCount(2);
});

it('resolves the feature requests created by the user', function () {
    $user = User::factory()->create();

    $request = FeatureRequest::factory()->for($user)->create();

    expect($user->featureRequests->first()->is($request))->toBeTrue();
});

it('resolves the social profile for a seller', function () {
    $user = User::factory()->create();
    $profile = SocialProfile::factory()->for($user)->create();
    $seller = Seller::factory()->for($user)->create(['social_profile_id' => $profile->id]);

    expect($seller->socialProfile->is($profile))->toBeTrue();
});
