<header class="@stylex('authHeader')">
    <div class="@stylex('authHeaderInner')">
        <div class="@stylex('brand')">
            <button type="button" class="@stylex('adminMenuBtn')" @click="mobileNavOpen = !mobileNavOpen" aria-label="Toggle navigation" :aria-expanded="mobileNavOpen.toString()">
                <x-lucide-menu class="{{ cls('adminNavIcon') }}" x-show="!mobileNavOpen" x-cloak />
                <x-lucide-x class="{{ cls('adminNavIcon') }}" x-show="mobileNavOpen" x-cloak />
            </button>

            <a href="{{ route('home') }}" class="@stylex('bannerMark')">
                <span class="@stylex('brandSlash')" aria-hidden="true">/</span>
                <span class="@stylex('brandName')">{{ config('app.name', 'GsmWhale') }}</span>
                <span class="@stylex('bannerVer')">v1.0</span>
            </a>
        </div>

        <span class="@stylex('bannerSpacer')" aria-hidden="true"></span>

        <nav class="@stylex('adminHeaderActions')">
            @include('partials.theme-switcher')

            <a href="{{ route('home') }}" class="@stylex('headerLink')">View site</a>
            <x-auth.logout class="{{ cls('headerLink') }}" />
        </nav>
    </div>
</header>