<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects guests to login', function () {
    $this->get('/admin/dashboard')
        ->assertRedirect(route('login'));
});

it('renders the admin dashboard for an admin user', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin)
        ->get('/admin/dashboard')
        ->assertOk()
        ->assertSee('Admin dashboard')
        ->assertSee('This is the admin area of your application.')
        ->assertSee('Users')
        ->assertSee('Products')
        ->assertSee('Licenses')
        ->assertSee('Feature Requests');
});

it('forbids non-admin users from the admin dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/dashboard')
        ->assertForbidden();
});
