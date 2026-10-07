<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's roles.
     */
    public function run(): void
    {
        foreach (['admin' => 'Admin', 'user' => 'User', 'seller' => 'Seller', 'support' => 'Support Agent'] as $slug => $name) {
            Role::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            );
        }
    }
}
