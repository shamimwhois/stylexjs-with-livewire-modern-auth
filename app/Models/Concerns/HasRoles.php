<?php

namespace App\Models\Concerns;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Role assignment helpers for the User model.
 */
trait HasRoles
{
    /**
     * The roles held by this user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Whether the user holds any (or all) of the given role slugs.
     */
    public function hasRole(string|array $roles, bool $requireAll = false): bool
    {
        $held = $this->roles()->pluck('slug')->all();

        $matches = array_intersect($held, (array) $roles);

        return $requireAll ? count($matches) === count((array) $roles) : $matches !== [];
    }

    /**
     * The unique permission slugs granted to the user through their roles.
     *
     * @return array<int, string>
     */
    public function permissionSlugs(): array
    {
        return $this->roles()
            ->with('permissions')
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions->pluck('slug'))
            ->unique()
            ->values()
            ->all();
    }

    /**
     * Whether the user holds any (or all) of the given permission slugs.
     *
     * The admin role bypasses permission checks.
     */
    public function hasPermission(string|array $permissions, bool $requireAll = false): bool
    {
        if ($this->hasRole('admin')) {
            return true;
        }

        $held = $this->permissionSlugs();

        $matches = array_intersect($held, (array) $permissions);

        return $requireAll ? count($matches) === count((array) $permissions) : $matches !== [];
    }

    /**
     * Named route to land on after login, derived from the first matching
     * role in the security.role_home_routes map.
     *
     * Roles that are not mapped (or role-less users) fall back to 'dashboard'.
     */
    public function homeRouteName(): string
    {
        foreach ((array) config('security.role_home_routes', []) as $slug => $route) {
            if ($this->hasRole($slug)) {
                return $route;
            }
        }

        return 'dashboard';
    }

    /**
     * Assign the given role slugs without removing existing ones.
     *
     * @param  string|array<int, string>  $roles
     */
    public function assignRole(string|array $roles): self
    {
        $this->roles()->syncWithoutDetaching($this->resolveRoleIds($roles));

        $this->unsetRelation('roles');

        return $this;
    }

    /**
     * Replace the user's roles with the given slugs.
     *
     * @param  string|array<int, string>  $roles
     */
    public function syncRoles(string|array $roles): self
    {
        $this->roles()->sync($this->resolveRoleIds($roles));

        $this->unsetRelation('roles');

        return $this;
    }

    /**
     * Resolve role slugs to ids, creating missing roles.
     *
     * @param  string|array<int, string>  $roles
     * @return array<int, int>
     */
    private function resolveRoleIds(string|array $roles): array
    {
        return collect((array) $roles)
            ->map(fn (string $slug) => Role::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => ucfirst($slug)],
            ))
            ->map->getKey()
            ->values()
            ->all();
    }
}
