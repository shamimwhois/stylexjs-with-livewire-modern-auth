<div>
    @if (session('status'))
        <div class="@stylex('alert', 'alertSuccess', 'mb6')" role="alert">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="@stylex('alert', 'alertDestructive', 'mb6')" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <div class="@stylex('dashHead')">
        <div>
            <h1 class="@stylex('dashTitle')">{{ $role ? 'Edit role' : 'Create role' }}</h1>
            <p class="@stylex('dashText')">Roles control which permissions their members can use.</p>
        </div>
    </div>

    <form wire:submit="save" class="@stylex('formCard')">
        <div class="@stylex('formGrid')">
            <div class="@stylex('fieldWrap')">
                <label for="name" class="@stylex('formLabel')">Name</label>
                <input id="name" type="text" class="@stylex('input', 'inputPad')" wire:model="name">
                @error('name')
                    <p class="@stylex('errorText')">{{ $message }}</p>
                @enderror
            </div>

            <div class="@stylex('fieldWrap')">
                <label for="slug" class="@stylex('formLabel')">Key</label>
                @if ($isAdminRole)
                    <input id="slug" type="text" class="@stylex('input', 'inputPad')" value="{{ $slug }}" disabled>
                    <p class="@stylex('hint', 'mt1')">The admin role key is locked.</p>
                @else
                    <input id="slug" type="text" class="@stylex('input', 'inputPad')" wire:model="slug">
                    @error('slug')
                        <p class="@stylex('errorText')">{{ $message }}</p>
                    @enderror
                @endif
            </div>
        </div>

        <div class="@stylex('mt6')">
            @if ($isAdminRole)
                <span class="@stylex('formLabel')">Permissions</span>
                <p class="@stylex('hint', 'mt1')">The admin role bypasses permission checks and cannot be edited here.</p>
            @else
                <span class="@stylex('formLabel')">Permissions</span>
                <div class="@stylex('roleGrid')">
                    @foreach ($availablePermissions as $permission)
                        <label class="@stylex('checkboxWrap')">
                            <input type="checkbox" class="@stylex('checkbox')" value="{{ $permission->slug }}" wire:model="permissions">
                            <span class="@stylex('checkboxLabel')">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions')
                    <p class="@stylex('errorText')">{{ $message }}</p>
                @enderror
            @endif
        </div>

        <div class="@stylex('formFooter')">
            <a href="{{ route('admin.roles') }}" class="@stylex('btn', 'btnOutline', 'btnSm')">Cancel</a>
            <button type="submit" class="@stylex('btn', 'btnPrimary', 'btnSm')">
                <x-lucide-check class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                {{ $role ? 'Save changes' : 'Create role' }}
            </button>
        </div>
    </form>
</div>