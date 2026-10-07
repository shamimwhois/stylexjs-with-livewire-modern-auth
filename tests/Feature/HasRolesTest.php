<?php

use App\Models\Role;
use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('assigns roles by slug and checks membership', function () {
    $user = User::factory()->create();

    $user->assignRole('admin');

    expect($user->hasRole('admin'))->toBeTrue()
        ->and($user->hasRole('user'))->toBeFalse()
        ->and($user->hasRole(['user', 'admin'], requireAll: false))->toBeTrue()
        ->and($user->hasRole(['user', 'support'], requireAll: true))->toBeFalse();
});

it('creates missing roles on assignment', function () {
    $user = User::factory()->create();

    $user->assignRole('seller');

    expect(Role::query()->where('slug', 'seller')->exists())->toBeTrue()
        ->and($user->hasRole('seller'))->toBeTrue();
});

it('syncs roles replacing existing ones', function () {
    $user = User::factory()->create();
    $user->assignRole('user');
    $user->syncRoles(['seller', 'support']);

    expect($user->hasRole('seller'))->toBeTrue()
        ->and($user->hasRole('support'))->toBeTrue()
        ->and($user->hasRole('user'))->toBeFalse();
});

it('seeds the standard roles', function () {
    $this->seed(RoleSeeder::class);

    expect(Role::query()->orderBy('id')->pluck('slug')->all())
        ->toBe(['admin', 'user', 'seller', 'support']);
});
