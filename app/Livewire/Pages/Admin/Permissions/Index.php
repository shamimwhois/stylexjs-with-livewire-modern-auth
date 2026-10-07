<?php

namespace App\Livewire\Pages\Admin\Permissions;

use App\Models\Permission;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * Delete the given permission, removing it from every role grant.
     */
    public function delete(int $permissionId): void
    {
        $permission = Permission::findOrFail($permissionId);

        $permission->delete();

        session()->flash('status', "\"{$permission->name}\" was deleted.");

        $this->redirect(self::class);
    }

    /**
     * Render the permission list.
     */
    public function render()
    {
        return view('pages.admin.permissions.index', [
            'permissions' => Permission::query()
                ->with('roles')
                ->orderBy('name')
                ->paginate(15),
        ])->layout('layouts.admin')
            ->title('Permissions');
    }
}
