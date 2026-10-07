<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 */
#[Fillable(['name', 'slug'])]
class Role extends Model
{
    /**
     * The users holding this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * The permissions granted to this role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Replace the role's permissions with the given slugs, creating any
     * permissions that do not exist yet.
     *
     * @param  string|array<int, string>  $permissions
     */
    public function syncPermissions(string|array $permissions): self
    {
        $this->permissions()->sync($this->resolvePermissionIds($permissions));

        $this->unsetRelation('permissions');

        return $this;
    }

    /**
     * Resolve permission slugs to ids, creating missing permissions.
     *
     * @param  string|array<int, string>  $permissions
     * @return array<int, int>
     */
    private function resolvePermissionIds(string|array $permissions): array
    {
        return collect((array) $permissions)
            ->map(fn (string $slug) => Permission::query()->firstOrCreate(
                ['slug' => $slug],
                ['name' => ucwords(str_replace(['_', '.'], ' ', $slug))],
            ))
            ->map->getKey()
            ->values()
            ->all();
    }
}
