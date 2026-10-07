<?php

namespace App\Livewire\Pages\Admin\Roles;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?Role $role = null;

    public string $name = '';

    public string $slug = '';

    /** @var array<int, string> */
    public array $permissions = [];

    public bool $isAdminRole = false;

    /**
     * Hydrate the form with the role being edited, if any.
     */
    public function mount(?Role $role = null): void
    {
        $this->role = $role;
        $this->isAdminRole = $role?->slug === 'admin';

        if ($role === null) {
            return;
        }

        $this->name = $role->name;
        $this->slug = $role->slug;
        $this->permissions = $role->permissions()->pluck('slug')->all();
    }

    /**
     * Validation rules for the form.
     *
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'alpha_dash', 'max:255', Rule::unique('roles', 'slug')->ignore($this->role?->getKey())],
            'permissions' => ['array'],
            'permissions.*' => ['exists:permissions,slug'],
        ];
    }

    /**
     * Create or update the role and sync its permissions.
     */
    public function save(): void
    {
        if ($this->isAdminRole) {
            session()->flash('error', 'The admin role is locked and always bypasses permission checks.');

            return;
        }

        $validated = $this->validate();

        $permissions = $validated['permissions'] ?? [];

        if ($this->role === null) {
            $role = Role::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
            ]);

            $role->syncPermissions($permissions);

            session()->flash('status', "\"{$role->name}\" was created.");
        } else {
            $this->role->fill($validated)->save();
            $this->role->syncPermissions($permissions);

            session()->flash('status', "\"{$this->role->name}\" was updated.");
        }

        $this->redirectRoute('admin.roles');
    }

    /**
     * Render the role form.
     */
    public function render()
    {
        return view('pages.admin.roles.form', [
            'availablePermissions' => Permission::query()->orderBy('name')->get(),
        ])->layout('layouts.admin')
            ->title($this->role === null ? 'Create role' : "Edit role \"{$this->role->name}\"");
    }
}
