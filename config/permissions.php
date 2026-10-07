<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Permission Catalog
    |--------------------------------------------------------------------------
    |
    | Every permission the application can grant, keyed by slug. Gates are
    | registered from this list at boot (AppServiceProvider), the seeder
    | persists them to the `permissions` table, and roles grant them through
    | the `permission_role` pivot. Slugs follow the `<module>.<action>` form.
    |
    */

    'permissions' => [
        'dashboard.access' => 'Access dashboard',
        'changelogs.view' => 'View changelogs',
        'feature_requests.view' => 'View feature requests',
        'feature_requests.vote' => 'Vote on feature requests',
        'products.view' => 'View products',
        'products.manage' => 'Manage products',
        'licenses.view' => 'View licenses',
        'wallet.view' => 'View wallet',
        'users.manage' => 'Manage users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Grants
    |--------------------------------------------------------------------------
    |
    | Slugs each role is granted by RolePermissionSeeder. `*` grants every
    | permission in the catalog. The admin role also bypasses permission
    | checks via the HasRoles::hasPermission() superuser shortcut.
    |
    */

    'role_grants' => [
        'admin' => '*',
        'user' => [
            'dashboard.access',
            'changelogs.view',
            'feature_requests.view',
            'feature_requests.vote',
        ],
        'seller' => [
            'dashboard.access',
            'changelogs.view',
            'feature_requests.view',
            'feature_requests.vote',
            'products.view',
            'products.manage',
            'licenses.view',
            'wallet.view',
        ],
        'support' => [
            'dashboard.access',
        ],
    ],

];
