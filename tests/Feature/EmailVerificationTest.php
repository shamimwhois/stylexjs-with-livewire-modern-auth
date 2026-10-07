<?php

use App\Livewire\Pages\Auth\VerifyEmail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('redirects unverified users to the verification notice', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('verification.notice'));
});

it('lets verified users through to the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get('/dashboard')
        ->assertOk();
});

it('blocks unverified users from the admin dashboard before the role check', function () {
    $user = User::factory()->unverified()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertRedirect(route('verification.notice'));
});

it('renders the verification notice page for authenticated unverified users', function () {
    $user = User::factory()->unverified()->create();

    $this->actingAs($user)
        ->get(route('verification.notice'))
        ->assertOk()
        ->assertSeeLivewire(VerifyEmail::class);
});
