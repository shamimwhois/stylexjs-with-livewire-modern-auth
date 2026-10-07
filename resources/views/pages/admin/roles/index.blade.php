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
            <h1 class="@stylex('dashTitle')">Roles</h1>
            <p class="@stylex('dashText')">Roles bundle permissions; users are granted roles, not permissions directly.</p>
        </div>
    </div>

    <div class="@stylex('toolbar')">
        <div></div>

        <a href="{{ route('admin.roles.create') }}" class="@stylex('btn', 'btnPrimary', 'btnSm')">
            <x-lucide-plus class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            New role
        </a>
    </div>

    <x-admin.table.roles.table :roles="$roles" />

    {{ $roles->links('components.admin.table.pagination') }}
</div>