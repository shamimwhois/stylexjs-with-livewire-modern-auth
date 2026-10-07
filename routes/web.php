<?php

use App\Livewire\Pages\Account\Security as AccountSecurity;
use App\Livewire\Pages\Admin\Dashboard\Index as AdminDashboard;
use App\Livewire\Pages\Admin\Permissions\Form as AdminPermissionForm;
use App\Livewire\Pages\Admin\Permissions\Index as AdminPermissionList;
use App\Livewire\Pages\Admin\Roles\Form as AdminRoleForm;
use App\Livewire\Pages\Admin\Roles\Index as AdminRoleList;
use App\Livewire\Pages\Admin\UserList\Form as AdminUserForm;
use App\Livewire\Pages\Admin\UserList\Index as AdminUserList;
use App\Livewire\Pages\Dashboard\Index as Dashboard;
use App\Livewire\Pages\Seller\Dashboard\Index as SellerDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.home.index');
})->name('home');

Route::get('/dashboard', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/account/security', AccountSecurity::class)
    ->middleware(['auth', 'verified'])
    ->name('account.security');

Route::get('/seller', SellerDashboard::class)
    ->middleware(['auth', 'verified', 'permission:products.view'])
    ->name('seller.dashboard');

Route::get('/admin/dashboard', AdminDashboard::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.dashboard');

Route::get('/admin/users', AdminUserList::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.users');

Route::get('/admin/users/create', AdminUserForm::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.users.create');

Route::get('/admin/users/{user}/edit', AdminUserForm::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.users.edit');

Route::get('/admin/roles', AdminRoleList::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.roles');

Route::get('/admin/roles/create', AdminRoleForm::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.roles.create');

Route::get('/admin/roles/{role}/edit', AdminRoleForm::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.roles.edit');

Route::get('/admin/permissions', AdminPermissionList::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.permissions');

Route::get('/admin/permissions/create', AdminPermissionForm::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.permissions.create');

Route::get('/admin/permissions/{permission}/edit', AdminPermissionForm::class)
    ->middleware(['auth', 'verified', 'role:admin', 'whitelist:admin'])
    ->name('admin.permissions.edit');
