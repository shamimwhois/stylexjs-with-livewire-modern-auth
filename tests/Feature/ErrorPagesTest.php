<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

$httpStatusCodes = [
    300, 301, 302, 303, 304, 305, 306, 307, 308,
    400, 401, 402, 403, 404, 405, 406, 407, 408, 409, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419,
    421, 422, 423, 424, 425, 426, 428, 429, 431, 451,
    500, 501, 502, 503, 504, 505, 506, 507, 508, 510, 511,
];

it('provides a renderable error page for every supported status code', function (int $code) {
    $html = view('errors.'.$code)->render();

    expect($html)
        ->toContain((string) $code)
        ->toContain('error-card')
        ->toContain('Go back home');
})->with($httpStatusCodes);

it('serves the custom 404 page for missing routes', function () {
    $this->get('/this-route-does-not-exist')
        ->assertNotFound()
        ->assertSee('Page Not Found');
});

it('serves the custom 403 page for unauthorised admin access', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $this->actingAs($user)
        ->get('/admin/dashboard')
        ->assertForbidden()
        ->assertSee('Forbidden');
});
