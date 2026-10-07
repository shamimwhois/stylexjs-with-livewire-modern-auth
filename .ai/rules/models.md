---
paths:
  - 'app/Models/**'
---

# Models

## Custom roles trail: use HasRoles trait
Roles are custom (no Spatie): `roles` + `role_user` tables, `HasRoles` trait on User with assignRole/syncRoles/hasRole. Default role for new users is `user` (assigned in CreateNewUser and SocialAuthService). Use `assignRole('slug')`; missing roles are auto-created on assignment.

## Custom permissions trail: config catalog + HasRoles::hasPermission
Permissions are custom (no Spatie): `permissions` + `permission_role` tables. The catalog+role grants live in config/permissions.php and are seeded by RolePermissionSeeder (DatabaseSeeder runs it after RoleSeeder). HasRoles::hasPermission() resolves grants through roles; the admin role bypasses. Enforce via the `permission:` middleware alias (any-of) or Gate::define'd slugs registered at boot from the catalog. Add/revoke a permission by editing the config, then re-seed RolePermissionSeeder.

## Custom permissions trail: config seed + DB CRUD + Gate::before
Permissions are custom (no Spatie): `permissions` + `permission_role` tables. The seed catalog+role grants live in config/permissions.php (RolePermissionSeeder, run after RoleSeeder); the runtime source of truth is the DB, managed via admin CRUD at /admin/roles and /admin/permissions. HasRoles::hasPermission() resolves grants through roles; the admin role bypasses. Enforcement: AppServiceProvider registers a Gate::before that answers any ability matching a permission slug in the DB (admin CRUD-created slugs become gateable immediately), and the `permission:` middleware alias (any-of).

## Block/suspend: two mutex timestamps, enforcement triple
User account status uses two mutually-exclusive nullable timestamps: `suspended_at` (temporary) and `blocked_at` (permanent). Toggle via User::suspend()/block()/restoreAccount() (each clears the other state). Enforcement: Auth\Login::ensureUserMaySignIn() blocks the switch + 2FA path; the web-group middleware EnsureUserIsActive signs restricted users out on the next request; admin toggles also delete the user's `sessions` table rows (driver is database) to end live sessions. Admin can never change their own status (Gate::allowIf 403).
