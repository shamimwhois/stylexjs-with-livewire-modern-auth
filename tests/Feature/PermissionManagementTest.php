<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        RolePermissionSeeder::class,
    ]);
});

/**
 * Create a user holding a single seeded role.
 */
function createRolePermissionUser(string $role): User
{
    $user = User::factory()->create();

    $user->assignRole($role);

    return $user->refresh();
}

it('grants the admin role every permission in the catalog', function () {
    $admin = createRolePermissionUser('admin');

    expect($admin->permissionSlugs())->toHaveCount(count(config('permissions.permissions')));
    expect($admin->hasPermission('users.manage'))->toBeTrue();
    expect($admin->hasPermission('products.manage'))->toBeTrue();
});

it('grants the user role its catalog permissions only', function () {
    $user = createRolePermissionUser('user');

    expect($user->hasPermission('dashboard.access'))->toBeTrue();
    expect($user->hasPermission('changelogs.view'))->toBeTrue();
    expect($user->hasPermission('feature_requests.vote'))->toBeTrue();
    expect($user->hasPermission('products.manage'))->toBeFalse();
    expect($user->hasPermission('users.manage'))->toBeFalse();
});

it('grants the seller role the selling permissions', function () {
    $seller = createRolePermissionUser('seller');

    expect($seller->hasPermission('dashboard.access'))->toBeTrue();
    expect($seller->hasPermission('products.view'))->toBeTrue();
    expect($seller->hasPermission('products.manage'))->toBeTrue();
    expect($seller->hasPermission('licenses.view'))->toBeTrue();
    expect($seller->hasPermission('wallet.view'))->toBeTrue();
    expect($seller->hasPermission('users.manage'))->toBeFalse();
});

it('grants the support role only dashboard access', function () {
    $support = createRolePermissionUser('support');

    expect($support->hasPermission('dashboard.access'))->toBeTrue();
    expect($support->hasPermission('products.manage'))->toBeFalse();
});

it('supports require-all permission checks', function () {
    $seller = createRolePermissionUser('seller');

    expect($seller->hasPermission(['dashboard.access', 'products.manage'], requireAll: true))->toBeTrue();
    expect($seller->hasPermission(['products.manage', 'users.manage'], requireAll: true))->toBeFalse();
});

it('returns unique permission slugs across multiple roles', function () {
    $user = createRolePermissionUser('user');

    $user->assignRole('seller');

    $slugs = $user->permissionSlugs();

    expect(count($slugs))->toBe(count(array_unique($slugs)));
    expect($user->hasPermission('wallet.view'))->toBeTrue();
});

it('syncs permissions onto a role through the pivot', function () {
    $role = Role::query()->where('slug', 'user')->firstOrFail();

    $role->syncPermissions(['products.view']);

    $user = createRolePermissionUser('user');

    expect($user->hasPermission('products.view'))->toBeTrue();
    expect($user->hasPermission('dashboard.access'))->toBeFalse();
});

it('seeds the catalog and grants idempotently', function () {
    $this->seed(RolePermissionSeeder::class);

    expect(Permission::count())->toBe(count(config('permissions.permissions')));
    expect(Role::query()->where('slug', 'seller')->firstOrFail()->permissions()->count())->toBeGreaterThan(0);
});

it('redirects guests away from the seller dashboard', function () {
    $this->get('/seller')
        ->assertRedirect(route('login'));
});

it('forbids a plain user from the seller dashboard', function () {
    $this->actingAs(createRolePermissionUser('user'))
        ->get('/seller')
        ->assertForbidden();
});

it('renders the seller dashboard for a seller', function () {
    $this->actingAs(createRolePermissionUser('seller'))
        ->get('/seller')
        ->assertOk()
        ->assertSee('Seller dashboard')
        ->assertSee('Products')
        ->assertSee('Licenses')
        ->assertSee('Wallet');
});

it('lets the admin reach the seller dashboard', function () {
    $this->actingAs(createRolePermissionUser('admin'))
        ->get('/seller')
        ->assertOk()
        ->assertSee('Seller dashboard');
});

it('maps each role to its post-login home route', function () {
    expect(createRolePermissionUser('user')->homeRouteName())->toBe('dashboard');
    expect(createRolePermissionUser('seller')->homeRouteName())->toBe('seller.dashboard');
    expect(createRolePermissionUser('admin')->homeRouteName())->toBe('admin.dashboard');
});

it('registers catalog permissions as gates', function () {
    $seller = createRolePermissionUser('seller');
    $user = createRolePermissionUser('user');

    $this->actingAs($seller);
    expect(Gate::allows('products.manage'))->toBeTrue();
    expect(Gate::allows('users.manage'))->toBeFalse();

    $this->actingAs($user);
    expect(Gate::allows('dashboard.access'))->toBeTrue();
    expect(Gate::allows('products.manage'))->toBeFalse();
});
