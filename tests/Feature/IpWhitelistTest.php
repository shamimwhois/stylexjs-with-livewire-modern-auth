<?php

use App\Models\AuthLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function whitelistedAdmin(): User
{
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    return $admin;
}

it('allows listed IPs and blocks everyone else', function () {
    config()->set('security.admin_ip_whitelist', '203.0.113.10, 203.0.113.11');

    $this->actingAs(whitelistedAdmin())
        ->withServerVariables(['REMOTE_ADDR' => '203.0.113.10'])
        ->get('/admin/dashboard')
        ->assertOk();

    $this->actingAs(whitelistedAdmin())
        ->withServerVariables(['REMOTE_ADDR' => '203.0.113.11'])
        ->get('/admin/dashboard')
        ->assertOk();

    $this->actingAs(whitelistedAdmin())
        ->withServerVariables(['REMOTE_ADDR' => '198.51.100.7'])
        ->get('/admin/dashboard')
        ->assertForbidden();
});

it('is a no-op when the whitelist is empty', function () {
    config()->set('security.admin_ip_whitelist', '');

    $this->actingAs(whitelistedAdmin())
        ->withServerVariables(['REMOTE_ADDR' => '198.51.100.7'])
        ->get('/admin/dashboard')
        ->assertOk();
});

it('audits blocked whitelist requests', function () {
    config()->set('security.admin_ip_whitelist', '203.0.113.10');

    $this->actingAs(whitelistedAdmin())
        ->withServerVariables(['REMOTE_ADDR' => '198.51.100.7'])
        ->get('/admin/dashboard')
        ->assertForbidden();

    expect(app(AuthLog::class)->newQuery()->where('event', 'whitelist.blocked')->exists())->toBeTrue();
});
