<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the admin header theme switcher and the sidebar for an admin', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertOk()
        ->assertSee('aria-label="Switch theme"', false)
        ->assertSee('role="listbox"', false)
        ->assertSee('aria-label="Theme picker"', false)
        ->assertSee('Manage')
        ->assertSee('Feature Requests')
        ->assertSee('Settings');
});
