<?php

namespace App\Livewire\Pages\Admin\Permissions;

use App\Models\Permission;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Form extends Component
{
    public ?Permission $permission = null;

    public string $name = '';

    public string $slug = '';

    /**
     * Hydrate the form with the permission being edited, if any.
     */
    public function mount(?Permission $permission = null): void
    {
        $this->permission = $permission;

        if ($permission === null) {
            return;
        }

        $this->name = $permission->name;
        $this->slug = $permission->slug;
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
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:\.[a-z0-9]+)*$/',
                Rule::unique('permissions', 'slug')->ignore($this->permission?->getKey()),
            ],
        ];
    }

    /**
     * Create or update the permission.
     */
    public function save(): void
    {
        $validated = $this->validate();

        if ($this->permission === null) {
            $permission = Permission::create($validated);

            session()->flash('status', "\"{$permission->name}\" was created. Assign it to a role to start granting it.");
        } else {
            $this->permission->fill($validated)->save();

            session()->flash('status', "\"{$this->permission->name}\" was updated.");
        }

        $this->redirectRoute('admin.permissions');
    }

    /**
     * Render the permission form.
     */
    public function render()
    {
        return view('pages.admin.permissions.form')
            ->layout('layouts.admin')
            ->title($this->permission === null ? 'Create permission' : "Edit permission \"{$this->permission->name}\"");
    }
}
