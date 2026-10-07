<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.theme-preset')

        <style>
            /* Pre-paint critical CSS: prevents white flash + uninitialized-Alpine
               flash on hard reload. --pf-* vars are set synchronously above. */
            [x-cloak] { display: none !important; }
            html, body { background-color: var(--pf-bg, #030712) !important; }
            body { color: var(--pf-text, #ffffff); }
        </style>

        <title>{{ $title ?? config('app.name') }}</title>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @endif

        @livewireStyles
    </head>
    <body class="@stylex('appBody')">
        <div class="@stylex('adminShell')" x-data="{ mobileNavOpen: false }" @keydown.escape.window="mobileNavOpen = false">
            <x-admin.app.header />

            <div class="@stylex('adminLayout')">
                <aside class="@stylex('adminSidebar')">
                    <x-admin.app.sidebar />
                </aside>

                <main class="@stylex('adminMain')">
                    <div class="@stylex('appMain')">
                        {{ $slot }}
                    </div>
                </main>

                <aside
                    class="@stylex('adminDrawer')"
                    :class="mobileNavOpen && '{{ cls('adminDrawerOpen') }}'"
                    :aria-hidden="!mobileNavOpen"
                    :inert="!mobileNavOpen"
                >
                    <div class="@stylex('adminDrawerHead')">
                        <span class="@stylex('brandText')">{{ config('app.name', 'Laravel') }}</span>

                        <button type="button" class="@stylex('adminMenuBtn')" @click="mobileNavOpen = false" aria-label="Close navigation">
                            <x-lucide-x class="@stylex('adminNavIcon')" />
                        </button>
                    </div>

                    <x-admin.app.sidebar />
                </aside>

                <div
                    class="@stylex('adminOverlay')"
                    x-show="mobileNavOpen"
                    x-cloak
                    x-transition:enter="@stylex('fadeEnter')"
                    x-transition:enter-start="@stylex('fadeStart')"
                    x-transition:leave="@stylex('fadeEnter')"
                    x-transition:leave-end="@stylex('fadeStart')"
                    @click="mobileNavOpen = false"
                ></div>
            </div>
        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>