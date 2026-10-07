<?php

use App\Models\User;
use Database\Seeders\UserDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds a test admin plus more than twenty users', function () {
    $this->seed(UserDataSeeder::class);

    $admin = User::query()->where('email', 'test@example.com')->firstOrFail();

    expect(User::count())->toBe(31);
    expect($admin->hasRole('admin'))->toBeTrue();
    expect(User::query()->whereHas('roles', fn ($query) => $query->where('slug', 'seller'))->count())->toBeGreaterThanOrEqual(1);
    expect(User::query()->whereHas('roles', fn ($query) => $query->where('slug', 'support'))->count())->toBeGreaterThanOrEqual(1);
    expect(User::query()->whereNull('email_verified_at')->count())->toBeGreaterThanOrEqual(1);
});
