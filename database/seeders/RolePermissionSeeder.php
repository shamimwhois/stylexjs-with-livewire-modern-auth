<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the permission catalog and the grants each role holds.
     */
    public function run(): void
    {
        foreach (config('permissions.permissions', []) as $slug => $name) {
            Permission::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            );
        }

        foreach (config('permissions.role_grants', []) as $slug => $grant) {
            $role = Role::query()->where('slug', $slug)->first();

            if ($role === null) {
                continue;
            }

            $slugs = $grant === '*' ? array_keys(config('permissions.permissions', [])) : $grant;

            $role->syncPermissions($slugs);
        }
    }
}
