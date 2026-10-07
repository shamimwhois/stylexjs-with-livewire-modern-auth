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
            <h1 class="@stylex('dashTitle')">Permissions</h1>
            <p class="@stylex('dashText')">Granular abilities granted through roles. The key is how code gates access.</p>
        </div>
    </div>

    <div class="@stylex('toolbar')">
        <div></div>

        <a href="{{ route('admin.permissions.create') }}" class="@stylex('btn', 'btnPrimary', 'btnSm')">
            <x-lucide-plus class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            New permission
        </a>
    </div>

    <x-admin.table.permissions.table :permissions="$permissions" />

    {{ $permissions->links('components.admin.table.pagination') }}
</div>