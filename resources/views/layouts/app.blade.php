<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @include('partials.theme-preset')

        <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

        <!-- Pre-paint critical CSS: prevents white flash + uninitialized-Alpine
             flash on hard reload. --pf-* vars are set synchronously above. -->
        <style>
            [x-cloak] { display: none !important; }
            html { background-color: #030712; }
        </style>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @endif

        @livewireStyles
    </head>
    <body class="@stylex('appBody')">
        <div class="@stylex('appShell')" x-data="{ mobileNavOpen: false }" @keydown.escape.window="mobileNavOpen = false">
            <x-app.header />

            <div class="@stylex('appLayout')">
                <aside class="@stylex('appSidebar')">
                    <x-app.sidebar />
                </aside>

                <main class="@stylex('appMainFlex')">
                    <div class="@stylex('appMain')">
                        {{ $slot }}
                    </div>

                    <x-app.footer />
                </main>

                <aside
                    class="@stylex('appDrawer')"
                    :class="mobileNavOpen && '{{ cls('appDrawerOpen') }}'"
                    :aria-hidden="!mobileNavOpen"
                    :inert="!mobileNavOpen"
                >
                    <div class="@stylex('appDrawerHead')">
                        <span class="@stylex('brandText')">{{ config('app.name', 'Laravel') }}</span>

                        <button type="button" class="@stylex('adminMenuBtn')" @click="mobileNavOpen = false" aria-label="Close navigation">
                            <x-lucide-x class="{{ cls('adminNavIcon') }}" />
                        </button>
                    </div>

                    <x-app.sidebar />
                </aside>

                <div
                    class="@stylex('appOverlay')"
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