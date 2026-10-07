<?php

use App\Livewire\Pages\Admin\Permissions\Form as AdminPermissionForm;
use App\Livewire\Pages\Admin\Permissions\Index as AdminPermissionList;
use App\Livewire\Pages\Admin\Roles\Form as AdminRoleForm;
use App\Livewire\Pages\Admin\Roles\Index as AdminRoleList;
use App\Livewire\Pages\Admin\UserList\Form as AdminUserForm;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        RolePermissionSeeder::class,
    ]);
});

function rolePermissionAdmin(): User
{
    $admin = User::factory()->create();

    $admin->assignRole('admin');

    return $admin->refresh();
}

it('redirects guests away from role and permission management', function () {
    $this->get('/admin/roles')->assertRedirect(route('login'));
    $this->get('/admin/permissions')->assertRedirect(route('login'));
});

it('forbids non-admins from role management', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user->refresh())
        ->get('/admin/roles')
        ->assertForbidden();

    $this->actingAs($user->refresh())
        ->get('/admin/permissions')
        ->assertForbidden();
});

it('renders the role list for an admin', function () {
    $this->actingAs(rolePermissionAdmin())
        ->get('/admin/roles')
        ->assertOk()
        ->assertSee('Roles')
        ->assertSee('Support Agent')
        ->assertSee('admin');
});

it('renders the permission list for an admin', function () {
    $this->actingAs(rolePermissionAdmin())
        ->get('/admin/permissions')
        ->assertOk()
        ->assertSee('Permissions')
        ->assertSee('products.manage')
        ->assertSee('Manage products');
});

it('creates a role and grants it permissions', function () {
    $admin = rolePermissionAdmin();

    Livewire::actingAs($admin)
        ->test(AdminRoleForm::class)
        ->set('name', 'Moderator')
        ->set('slug', 'moderator')
        ->set('permissions', ['changelogs.view', 'feature_requests.vote'])
        ->call('save')
        ->assertRedirect(route('admin.roles'))
        ->assertSessionHas('status');

    $role = Role::query()->where('slug', 'moderator')->firstOrFail();

    expect($role->permissions()->pluck('slug')->all())->toEqualCanonicalizing(['changelogs.view', 'feature_requests.vote']);

    $user = User::factory()->create();
    $user->assignRole('moderator');

    expect($user->refresh()->hasPermission('changelogs.view'))->toBeTrue();
});

it('edits a role and resyncs its permissions', function () {
    $role = Role::query()->where('slug', 'support')->firstOrFail();

    $role->syncPermissions(['dashboard.access']);

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminRoleForm::class, ['role' => $role])
        ->set('name', 'Hero Support')
        ->set('slug', 'hero_support')
        ->set('permissions', ['dashboard.access', 'changelogs.view'])
        ->call('save')
        ->assertRedirect(route('admin.roles'))
        ->assertSessionHas('status');

    $role->refresh();

    expect($role->name)->toBe('Hero Support');
    expect($role->slug)->toBe('hero_support');
    expect($role->permissions()->pluck('slug')->all())->toEqualCanonicalizing(['dashboard.access', 'changelogs.view']);
});

it('blocks deleting the admin role', function () {
    $cache = Role::query()->where('slug', 'admin')->firstOrFail();

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminRoleList::class)
        ->call('delete', $cache->id)
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(Role::query()->where('slug', 'admin')->exists())->toBeTrue();
});

it('blocks deleting a role that is still assigned to users', function () {
    $role = Role::query()->where('slug', 'support')->firstOrFail();

    $user = User::factory()->create();
    $user->assignRole('support');

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminRoleList::class)
        ->call('delete', $role->id)
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(Role::query()->find($role->id))->not->toBeNull();
});

it('deletes an unused role and clears its grants', function () {
    $role = Role::create(['name' => 'Trial', 'slug' => 'trial']);
    $role->syncPermissions(['dashboard.access']);

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminRoleList::class)
        ->call('delete', $role->id)
        ->assertRedirect()
        ->assertSessionHas('status');

    expect(Role::query()->find($role->id))->toBeNull();
});

it('creates a permission that is immediately gateable', function () {
    $admin = rolePermissionAdmin();

    Livewire::actingAs($admin)
        ->test(AdminPermissionForm::class)
        ->set('name', 'View reports')
        ->set('slug', 'reports.view')
        ->call('save')
        ->assertRedirect(route('admin.permissions'))
        ->assertSessionHas('status');

    expect(Permission::query()->where('slug', 'reports.view')->exists())->toBeTrue();

    $seller = User::factory()->create();
    $seller->assignRole('seller');
    $sellerRoles = Role::query()->where('slug', 'seller')->firstOrFail();
    $sellerRoles->syncPermissions([...$sellerRoles->permissions()->pluck('slug'), 'reports.view']);

    $this->actingAs($seller->refresh());
    expect(Gate::allows('reports.view'))->toBeTrue();

    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user->refresh());
    expect(Gate::allows('reports.view'))->toBeFalse();
});

it('edits a permission', function () {
    $permission = Permission::query()->where('slug', 'changelogs.view')->firstOrFail();

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminPermissionForm::class, ['permission' => $permission])
        ->set('name', 'Read changelogs')
        ->set('slug', 'changelogs.read')
        ->call('save')
        ->assertRedirect(route('admin.permissions'))
        ->assertSessionHas('status');

    $permission->refresh();

    expect($permission->name)->toBe('Read changelogs');
    expect($permission->slug)->toBe('changelogs.read');
});

it('deletes a permission and removes it from every role', function () {
    $permission = Permission::query()->where('slug', 'wallet.view')->firstOrFail();

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminPermissionList::class)
        ->call('delete', $permission->id)
        ->assertRedirect()
        ->assertSessionHas('status');

    expect(Permission::query()->find($permission->id))->toBeNull();
    expect(Role::query()->where('slug', 'seller')->firstOrFail()->permissions()->where('slug', 'wallet.view')->exists())->toBeFalse();
});

it('shows the effective permissions of the selected roles on the user form', function () {
    $this->actingAs(rolePermissionAdmin());

    Livewire::test(AdminUserForm::class)
        ->set('roles', ['seller'])
        ->assertSee('View wallet')
        ->assertSee('Manage products');

    Livewire::test(AdminUserForm::class)
        ->set('roles', ['user'])
        ->assertDontSee('View wallet')
        ->assertSee('Vote on feature requests');
});

it('paginates the roles list', function () {
    foreach (range(1, 16) as $i) {
        Role::create(['name' => "Role $i", 'slug' => "role_$i"]);
    }

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminRoleList::class)
        ->assertSee('Showing 1 to 15 of 20 results', false)
        ->call('gotoPage', 2)
        ->assertSee('Showing 16 to 20 of 20 results', false);
});

it('paginates the permission list', function () {
    foreach (range(1, 16) as $i) {
        Permission::create(['name' => "Permission $i", 'slug' => "permission_$i"]);
    }

    Livewire::actingAs(rolePermissionAdmin())
        ->test(AdminPermissionList::class)
        ->assertSee('Showing 1 to 15 of 25 results', false)
        ->call('gotoPage', 2)
        ->assertSee('Showing 16 to 25 of 25 results', false);
});

it('shows the permissions column on the user list', function () {
    $seller = User::factory()->create();
    $seller->assignRole('seller');

    $this->actingAs(rolePermissionAdmin())
        ->get('/admin/users')
        ->assertOk()
        ->assertSee('Permissions')
        ->assertSee('Manage products');
});

it('blocks removing the admin role from the last admin via the user form', function () {
    $admin = rolePermissionAdmin();

    Livewire::actingAs($admin)
        ->test(AdminUserForm::class, ['user' => $admin])
        ->set('roles', ['user'])
        ->call('save')
        ->assertHasErrors(['roles' => 'You cannot remove the admin role from the last administrator.']);

    expect($admin->refresh()->hasRole('admin'))->toBeTrue();
});
