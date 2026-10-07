<?php

namespace App\Livewire\Pages\Admin\UserList;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Form extends Component
{
    public ?User $user = null;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public ?string $phone = null;

    public ?string $country_code = null;

    public string $password = '';

    public string $password_confirmation = '';

    /** @var array<int, string> */
    public array $roles = [];

    /**
     * Hydrate the form with the user being edited, if any.
     */
    public function mount(?User $user = null): void
    {
        $this->user = $user;

        if ($user === null) {
            return;
        }

        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->country_code = $user->country_code;
        $this->roles = $user->roles()->pluck('slug')->all();
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
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($this->user?->getKey())],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user?->getKey())],
            'phone' => ['nullable', 'string', 'max:20'],
            'country_code' => ['nullable', 'string', 'max:8'],
            'password' => $this->user === null
                ? ['required', 'string', Password::default(), 'confirmed']
                : ['nullable', 'string', Password::default(), 'confirmed'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,slug'],
        ];
    }

    /**
     * Create or update the user and sync its roles.
     */
    public function save(): void
    {
        $validated = $this->validate();

        $data = array_intersect_key($validated, array_flip(['name', 'username', 'email', 'phone', 'country_code']));

        $roles = $validated['roles'] ?: ['user'];

        if ($this->user !== null && $this->user->hasRole('admin') && ! in_array('admin', $roles, true) && $this->isLastAdmin()) {
            $this->addError('roles', 'You cannot remove the admin role from the last administrator.');

            return;
        }

        if ($this->user === null) {
            $user = User::create([...$data, 'password' => Hash::make($validated['password'])]);

            $user->syncRoles($roles);

            session()->flash('status', "\"{$user->name}\" was created.");

            $this->redirectRoute('admin.users');

            return;
        }

        $this->user->forceFill($data);

        if (filled($validated['password'])) {
            $this->user->password = Hash::make($validated['password']);
        }

        $this->user->save();
        $this->user->syncRoles($roles);

        session()->flash('status', "\"{$this->user->name}\" was updated.");

        $this->redirectRoute('admin.users');
    }

    /**
     * The unique permissions granted by the currently selected roles.
     *
     * @return array<int, Permission>
     */
    private function effectivePermissions(): array
    {
        return Role::query()
            ->with('permissions')
            ->whereIn('slug', $this->roles)
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions)
            ->unique('slug')
            ->sortBy('name')
            ->values()
            ->all();
    }

    /**
     * Whether the edited user holding the admin role is the last admin.
     */
    private function isLastAdmin(): bool
    {
        return User::query()
            ->whereHas('roles', fn ($query) => $query->where('slug', 'admin'))
            ->count() === 1;
    }

    /**
     * Render the user form.
     */
    public function render()
    {
        return view('pages.admin.userlist.form', [
            'availableRoles' => Role::query()->orderBy('name')->get(),
            'effectivePermissions' => $this->effectivePermissions(),
        ])->layout('layouts.admin')
            ->title($this->user === null ? 'Create user' : "Edit \"{$this->user->name}\"");
    }
}
