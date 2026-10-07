<form wire:submit="save" class="@stylex('card', 'cardPad')">
    <div class="@stylex('formHeading')">{{ $user ? 'Edit user' : 'Create user' }}</div>
    <p class="@stylex('dashText')">{{ $user ? 'Update this user\'s details and roles below.' : 'Create an account with the details below.' }}</p>

    <div class="@stylex('mt6', 'formGrid2')">
        <div class="@stylex('fieldWrap')">
            <label for="name" class="@stylex('formLabel')">Name</label>
            <input id="name" type="text" class="@stylex('input', 'inputPad')" wire:model="name" autocomplete="name">
            @error('name')
                <p class="@stylex('errorText')">{{ $message }}</p>
            @enderror
        </div>

        <div class="@stylex('fieldWrap')">
            <label for="username" class="@stylex('formLabel')">Username</label>
            <input id="username" type="text" class="@stylex('input', 'inputPad')" wire:model="username" autocomplete="username">
            @error('username')
                <p class="@stylex('errorText')">{{ $message }}</p>
            @enderror
        </div>

        <div class="@stylex('fieldWrap', 'formColFull')">
            <label for="email" class="@stylex('formLabel')">Email</label>
            <input id="email" type="email" class="@stylex('input', 'inputPad')" wire:model="email" autocomplete="email">
            @error('email')
                <p class="@stylex('errorText')">{{ $message }}</p>
            @enderror
        </div>

        <div class="@stylex('fieldWrap')">
            <label for="phone" class="@stylex('formLabel')">Phone</label>
            <input id="phone" type="text" class="@stylex('input', 'inputPad')" wire:model="phone">
            @error('phone')
                <p class="@stylex('errorText')">{{ $message }}</p>
            @enderror
        </div>

        <div class="@stylex('fieldWrap')">
            <label for="country_code" class="@stylex('formLabel')">Country code</label>
            <input id="country_code" type="text" class="@stylex('input', 'inputPad')" wire:model="country_code">
            @error('country_code')
                <p class="@stylex('errorText')">{{ $message }}</p>
            @enderror
        </div>

        <div class="@stylex('fieldWrap')">
            <label for="password" class="@stylex('formLabel')">{{ $user ? 'New password' : 'Password' }}</label>
            <input id="password" type="password" class="@stylex('input', 'inputPad')" wire:model="password" autocomplete="new-password">
            @if ($user)
                <p class="@stylex('hint', 'mt1')">Leave blank to keep the current password.</p>
            @endif
            @error('password')
                <p class="@stylex('errorText')">{{ $message }}</p>
            @enderror
        </div>

        <div class="@stylex('fieldWrap')">
            <label for="password_confirmation" class="@stylex('formLabel')">Confirm password</label>
            <input id="password_confirmation" type="password" class="@stylex('input', 'inputPad')" wire:model="password_confirmation" autocomplete="new-password">
            @error('password_confirmation')
                <p class="@stylex('errorText')">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="@stylex('mt6')">
        <span class="@stylex('formLabel')">Roles</span>
        <div class="@stylex('roleGrid')">
            @foreach ($availableRoles as $role)
                <label class="@stylex('checkboxWrap')">
                    <input type="checkbox" class="@stylex('checkbox')" value="{{ $role->slug }}" wire:model="roles">
                    <span class="@stylex('checkboxLabel')">{{ $role->name }}</span>
                </label>
            @endforeach
        </div>
        @error('roles')
            <p class="@stylex('errorText')">{{ $message }}</p>
        @enderror

        @if (count($effectivePermissions) > 0)
            <p class="@stylex('hint', 'mt3')">Effective permissions (from selected roles)</p>
            <div class="@stylex('roleCell', 'mt2')">
                @foreach ($effectivePermissions as $permission)
                    <x-admin.table.role-badge :slug="$permission->slug" :name="$permission->name" />
                @endforeach
            </div>
        @endif
    </div>

    <div class="@stylex('formFooter')">
        <a href="{{ route('admin.users') }}" class="@stylex('btn', 'btnOutline', 'btnSm')">Cancel</a>
        <button type="submit" class="@stylex('btn', 'btnPrimary', 'btnSm')">
            <x-lucide-check class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            {{ $user ? 'Save changes' : 'Create user' }}
        </button>
    </div>
</form>