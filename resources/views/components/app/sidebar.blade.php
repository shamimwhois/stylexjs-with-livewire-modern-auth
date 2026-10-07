<div class="{{ cls('appNav') }}">
    {{-- Main navigation group (collapsible, expanded by default) --}}
    <div class="{{ cls('appNavGroup') }}" x-data="{ open: true }">
        <button
            type="button"
            class="{{ cls('appNavGroupToggle') }}"
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-controls="app-nav-main"
        >
            <span class="{{ cls('appNavGroupIconWrap') }}" :class="open && '{{ cls('appNavGroupIconOpen') }}'">
                <x-lucide-chevron-right class="{{ cls('appNavGroupIcon') }}" aria-hidden="true" />
            </span>
            <span class="{{ cls('appNavLabel') }}">Main Navigation</span>
        </button>

        <div id="app-nav-main" class="{{ cls('appNavGroupBody') }}" x-show="open" x-cloak x-transition>
            <a href="{{ route('dashboard') }}" class="{{ cls('appNavLink', request()->routeIs('dashboard') ? 'appNavLinkActive' : null) }}">
                <x-lucide-layout-dashboard class="{{ cls('appNavIcon') }}" />
                <span>Dashboard</span>
            </a>

            <a href="{{ route('account.security') }}" class="{{ cls('appNavLink', request()->routeIs('account.security') ? 'appNavLinkActive' : null) }}">
                <x-lucide-shield-check class="{{ cls('appNavIcon') }}" />
                <span>Security</span>
            </a>
        </div>
    </div>

    {{-- Account group (collapsible, collapsed by default) --}}
    <div class="{{ cls('appNavGroup') }}" x-data="{ open: false }">
        <button
            type="button"
            class="{{ cls('appNavGroupToggle') }}"
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-controls="app-nav-account"
        >
            <span class="{{ cls('appNavGroupIconWrap') }}" :class="open && '{{ cls('appNavGroupIconOpen') }}'">
                <x-lucide-chevron-right class="{{ cls('appNavGroupIcon') }}" aria-hidden="true" />
            </span>
            <span class="{{ cls('appNavLabel') }}">Account</span>
        </button>

        <div id="app-nav-account" class="{{ cls('appNavGroupBody') }}" x-show="open" x-cloak x-transition>
            <a href="#" class="{{ cls('appNavLink') }}">
                <x-lucide-user-round class="{{ cls('appNavIcon') }}" />
                <span>Profile</span>
            </a>

            <a href="#" class="{{ cls('appNavLink') }}">
                <x-lucide-settings class="{{ cls('appNavIcon') }}" />
                <span>Settings</span>
            </a>

            <x-auth.logout class="{{ cls('appNavLink') }}" label="Log out" />
        </div>
    </div>
</div>