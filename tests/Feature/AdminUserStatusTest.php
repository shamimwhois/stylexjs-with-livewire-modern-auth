<?php

use App\Livewire\Pages\Admin\UserList\Index as UserListIndex;
use App\Livewire\Pages\Auth\Login;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Livewire;

uses(RefreshDatabase::class)->beforeEach(function () {
    $this->seed([
        RoleSeeder::class,
        RolePermissionSeeder::class,
    ]);
});

function adminUserStatusOperator(): User
{
    $admin = User::factory()->create();

    $admin->assignRole('admin');

    return $admin->refresh();
}

function statusSessionRow(User $user): void
{
    DB::table('sessions')->insert([
        'id' => Str::random(40),
        'user_id' => $user->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'pest',
        'payload' => base64_encode(''),
        'last_activity' => now()->timestamp,
    ]);
}

it('suspends a user, clears any block and ends their active sessions', function () {
    $target = User::factory()->create();
    $target->block();
    statusSessionRow($target);

    Livewire::actingAs(adminUserStatusOperator())
        ->test(UserListIndex::class)
        ->call('suspend', $target->id)
        ->assertRedirect()
        ->assertSessionHas('status');

    $target->refresh();

    expect($target->isSuspended())->toBeTrue();
    expect($target->isBlocked())->toBeFalse();
    expect(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(0);
});

it('blocks a user, clearing any suspension and ending their active sessions', function () {
    $target = User::factory()->create();
    $target->suspend();
    statusSessionRow($target);

    Livewire::actingAs(adminUserStatusOperator())
        ->test(UserListIndex::class)
        ->call('block', $target->id)
        ->assertRedirect()
        ->assertSessionHas('status');

    $target->refresh();

    expect($target->isBlocked())->toBeTrue();
    expect($target->isSuspended())->toBeFalse();
    expect(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(0);
});

it('restores a restricted user to an active account', function () {
    $target = User::factory()->create();
    $target->block();

    Livewire::actingAs(adminUserStatusOperator())
        ->test(UserListIndex::class)
        ->call('restore', $target->id)
        ->assertRedirect()
        ->assertSessionHas('status');

    expect($target->refresh()->isRestricted())->toBeFalse();
});

it('forbids an admin from changing their own account status', function () {
    $admin = adminUserStatusOperator();

    Livewire::actingAs($admin)
        ->test(UserListIndex::class)
        ->call('suspend', $admin->id)
        ->assertForbidden();

    Livewire::actingAs($admin)
        ->test(UserListIndex::class)
        ->call('block', $admin->id)
        ->assertForbidden();

    Livewire::actingAs($admin)
        ->test(UserListIndex::class)
        ->call('restore', $admin->id)
        ->assertForbidden();

    expect($admin->refresh()->isRestricted())->toBeFalse();
});

it('denies sign in for suspended and blocked accounts', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $user->suspend();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'suspended')
        ->assertHasNoErrors();

    $this->assertGuest();

    $user->restoreAccount();
    $user->block();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('authenticate')
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'blocked')
        ->assertHasNoErrors();

    $this->assertGuest();
});

it('signs restricted users out on the next request and leaves active users alone', function () {
    $active = User::factory()->create();
    $active->assignRole('user');

    $this->actingAs($active);

    $this->get('/dashboard')->assertOk();

    $suspended = User::factory()->create();
    $suspended->assignRole('user');

    $this->actingAs($suspended);
    $suspended->suspend();

    $this->get('/dashboard')
        ->assertRedirect(route('login'))
        ->assertSessionHas('login.restriction', 'suspended');

    Livewire::test(Login::class)
        ->assertSet('noticeModal', true)
        ->assertSet('noticeType', 'suspended');

    expect(auth()->check())->toBeFalse();
});

it('shows the account status badge on the user list', function () {
    $suspended = User::factory()->create();
    $suspended->suspend();

    $blocked = User::factory()->create();
    $blocked->block();

    $this->actingAs(adminUserStatusOperator())
        ->get('/admin/users')
        ->assertOk()
        ->assertSee('Suspended')
        ->assertSee('Blocked');
});
