<?php

use App\Livewire\Pages\Admin\UserList\Form as UserListForm;
use App\Livewire\Pages\Admin\UserList\Index as UserListIndex;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('redirects guests to login', function () {
    $this->get('/admin/users')
        ->assertRedirect(route('login'));
});

it('forbids non-admin users from the user list', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/users')
        ->assertForbidden();
});

it('renders the user list for an admin', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $user = User::factory()->create(['name' => 'Listed User']);

    $this->actingAs($admin)
        ->get('/admin/users')
        ->assertOk()
        ->assertSee('User management')
        ->assertSee('New user')
        ->assertSee('Listed User')
        ->assertSee($user->email)
        ->assertSee('Verified')
        ->assertSee('role="dialog"', false)
        ->assertSee('/admin/roles', false)
        ->assertSee('/admin/permissions', false);
});

it('renders the create form for an admin', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/users/create')
        ->assertOk()
        ->assertSee('Create user')
        ->assertSee('Roles')
        ->assertSee('Confirm password');
});

it('renders the edit form with the user prefilled', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $target = User::factory()->create(['name' => 'Prefilled Person']);
    $target->assignRole('user');

    $this->actingAs($admin)
        ->get("/admin/users/{$target->id}/edit")
        ->assertOk()
        ->assertSee('Edit user')
        ->assertSee('Prefilled Person');
});

it('filters the list by search term', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    User::factory()->create(['name' => 'Alice Administrator', 'email' => 'alice@example.com']);
    User::factory()->create(['name' => 'Bob Builder', 'email' => 'bob@example.com']);

    Livewire::actingAs($admin)
        ->test(UserListIndex::class)
        ->set('search', 'Alice')
        ->assertSee('Alice Administrator')
        ->assertDontSee('Bob Builder');
});

it('creates a user with the default user role', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(UserListForm::class)
        ->set('name', 'New Person')
        ->set('username', 'newperson')
        ->set('email', 'newperson@example.com')
        ->set('password', 'secret-password')
        ->set('password_confirmation', 'secret-password')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.users'));

    $created = User::query()->where('email', 'newperson@example.com')->firstOrFail();

    expect($created->hasRole('user'))->toBeTrue();
    expect(Hash::check('secret-password', $created->password))->toBeTrue();
});

it('creates a user with the selected roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Role::create(['name' => 'Seller', 'slug' => 'seller']);

    Livewire::actingAs($admin)
        ->test(UserListForm::class)
        ->set('name', 'Special Person')
        ->set('username', 'specialperson')
        ->set('email', 'special@example.com')
        ->set('password', 'secret-password')
        ->set('password_confirmation', 'secret-password')
        ->set('roles', ['admin', 'seller'])
        ->call('save')
        ->assertHasNoErrors();

    $created = User::query()->where('email', 'special@example.com')->firstOrFail();

    expect($created->hasRole('admin'))->toBeTrue();
    expect($created->hasRole('seller'))->toBeTrue();
    expect($created->hasRole('user'))->toBeFalse();
});

it('requires a password when creating a user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(UserListForm::class)
        ->set('name', 'No Password')
        ->set('username', 'nopassword')
        ->set('email', 'nopassword@example.com')
        ->call('save')
        ->assertHasErrors(['password']);
});

it('rejects a duplicate email when creating a user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    User::factory()->create(['email' => 'taken@example.com']);

    Livewire::actingAs($admin)
        ->test(UserListForm::class)
        ->set('name', 'Taker')
        ->set('username', 'taker')
        ->set('email', 'taken@example.com')
        ->set('password', 'secret-password')
        ->set('password_confirmation', 'secret-password')
        ->call('save')
        ->assertHasErrors(['email']);
});

it('updates an existing user and syncs roles', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $target = User::factory()->create(['name' => 'Old Name', 'email' => 'old@example.com']);
    $target->assignRole('user');

    Livewire::actingAs($admin)
        ->test(UserListForm::class, ['user' => $target])
        ->assertSet('name', 'Old Name')
        ->set('name', 'New Name')
        ->set('roles', ['admin'])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.users'));

    $target->refresh();

    expect($target->name)->toBe('New Name');
    expect($target->hasRole('admin'))->toBeTrue();
    expect($target->hasRole('user'))->toBeFalse();
});

it('keeps the existing password when editing with a blank password', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $target = User::factory()->create();
    $target->assignRole('user');

    $original = Hash::make('original-password');
    $target->update(['password' => $original]);

    Livewire::actingAs($admin)
        ->test(UserListForm::class, ['user' => $target])
        ->call('save')
        ->assertHasNoErrors();

    expect(Hash::check('original-password', $target->refresh()->password))->toBeTrue();
});

it('allows a user to keep their own email when editing', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $target = User::factory()->create(['email' => 'keep@example.com']);
    $target->assignRole('user');

    Livewire::actingAs($admin)
        ->test(UserListForm::class, ['user' => $target])
        ->call('save')
        ->assertHasNoErrors();
});

it('deletes a user from the list', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $target = User::factory()->create(['name' => 'Doomed User']);

    Livewire::actingAs($admin)
        ->test(UserListIndex::class)
        ->call('delete', $target->id)
        ->assertRedirect()
        ->assertSessionHas('status');

    expect(User::find($target->id))->toBeNull();
});

it('forbids an admin from deleting their own account', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Livewire::actingAs($admin)
        ->test(UserListIndex::class)
        ->call('delete', $admin->id)
        ->assertForbidden();

    expect(User::find($admin->id))->not->toBeNull();
});
