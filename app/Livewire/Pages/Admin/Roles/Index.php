<?php

namespace App\Livewire\Pages\Admin\Roles;

use App\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * Delete the given role, guarding the admin role and roles in use.
     */
    public function delete(int $roleId): void
    {
        $role = Role::withCount('users')->findOrFail($roleId);

        if ($role->slug === 'admin') {
            session()->flash('error', 'The admin role cannot be deleted.');

            $this->redirect(self::class);

            return;
        }

        if ($role->users_count > 0) {
            session()->flash('error', 'Cannot delete a role that is still assigned to users.');

            $this->redirect(self::class);

            return;
        }

        $role->delete();

        session()->flash('status', "\"{$role->name}\" was deleted.");

        $this->redirect(self::class);
    }

    /**
     * Render the role list.
     */
    public function render()
    {
        return view('pages.admin.roles.index', [
            'roles' => Role::query()
                ->withCount(['users', 'permissions'])
                ->orderBy('name')
                ->paginate(15),
        ])->layout('layouts.admin')
            ->title('Roles');
    }
}
