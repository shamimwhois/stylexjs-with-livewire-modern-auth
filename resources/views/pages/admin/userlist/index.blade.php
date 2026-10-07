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

    <h1 class="@stylex('dashTitle')">User management</h1>
    <p class="@stylex('dashText')">Manage registered users, login details, roles and verification status.</p>

    <div class="@stylex('toolbar')">
        <div class="@stylex('searchBox')">
            <x-lucide-search class="@stylex('searchIcon', 'iconStroke')" aria-hidden="true" />
            <input
                type="search"
                class="@stylex('input', 'inputPad', 'searchPad')"
                placeholder="Search name, username or email…"
                wire:model.live.debounce.250ms="search"
                aria-label="Search users"
            >
        </div>

        <div class="@stylex('rowActions')">
            <a href="{{ route('admin.roles') }}" class="@stylex('btn', 'btnOutline', 'btnSm')">
                <x-lucide-shield class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                Roles
            </a>

            <a href="{{ route('admin.permissions') }}" class="@stylex('btn', 'btnOutline', 'btnSm')">
                <x-lucide-lock-keyhole class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                Permissions
            </a>

            <a href="{{ route('admin.users.create') }}" class="@stylex('btn', 'btnPrimary', 'btnSm')">
                <x-lucide-plus class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                New user
            </a>
        </div>
    </div>

    <x-admin.table.userlist.table :users="$users" />

    {{ $users->links('components.admin.table.pagination') }}
</div>