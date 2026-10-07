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
            <h1 class="@stylex('dashTitle')">{{ $permission ? 'Edit permission' : 'Create permission' }}</h1>
            <p class="@stylex('dashText')">The key is used in code via <code>@@can('key')</code> or the <code>permission:key</code> middleware.</p>
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
                <input id="slug" type="text" class="@stylex('input', 'inputPad')" wire:model="slug">
                <p class="@stylex('hint', 'mt1')">Lowercase, dots allowed — for example <code>products.manage</code>.</p>
                @error('slug')
                    <p class="@stylex('errorText')">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="@stylex('formFooter')">
            <a href="{{ route('admin.permissions') }}" class="@stylex('btn', 'btnOutline', 'btnSm')">Cancel</a>
            <button type="submit" class="@stylex('btn', 'btnPrimary', 'btnSm')">
                <x-lucide-check class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                {{ $permission ? 'Save changes' : 'Create permission' }}
            </button>
        </div>
    </form>
</div>