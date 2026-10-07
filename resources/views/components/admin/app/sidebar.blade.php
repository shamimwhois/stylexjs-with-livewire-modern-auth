<div class="{{ cls('adminNav') }}">
    <span class="{{ cls('adminNavLabel') }}">Manage</span>

    <a href="{{ route('admin.dashboard') }}" class="{{ cls('adminNavLink', request()->routeIs('admin.dashboard') ? 'adminNavLinkActive' : null) }}">
        <x-lucide-layout-dashboard class="{{ cls('adminNavIcon') }}" />
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.users') }}" class="{{ cls('adminNavLink', request()->routeIs('admin.users*') ? 'adminNavLinkActive' : null) }}">
        <x-lucide-users class="{{ cls('adminNavIcon') }}" />
        <span>Users</span>
    </a>

    <a href="#" class="{{ cls('adminNavLink') }}">
        <x-lucide-blocks class="{{ cls('adminNavIcon') }}" />
        <span>Products</span>
    </a>

    <a href="#" class="{{ cls('adminNavLink') }}">
        <x-lucide-key-round class="{{ cls('adminNavIcon') }}" />
        <span>Licenses</span>
    </a>

    <a href="#" class="{{ cls('adminNavLink') }}">
        <x-lucide-wallet class="{{ cls('adminNavIcon') }}" />
        <span>Wallets</span>
    </a>

    <a href="#" class="{{ cls('adminNavLink') }}">
        <x-lucide-package class="{{ cls('adminNavIcon') }}" />
        <span>Add-ons</span>
    </a>

    <a href="#" class="{{ cls('adminNavLink') }}">
        <x-lucide-history class="{{ cls('adminNavIcon') }}" />
        <span>Changelogs</span>
    </a>

    <a href="#" class="{{ cls('adminNavLink') }}">
        <x-lucide-lightbulb class="{{ cls('adminNavIcon') }}" />
        <span>Feature Requests</span>
    </a>

    <span class="{{ cls('adminNavLabel', 'mt6') }}">System</span>

    <a href="#" class="{{ cls('adminNavLink') }}">
        <x-lucide-settings class="{{ cls('adminNavIcon') }}" />
        <span>Settings</span>
    </a>
</div>