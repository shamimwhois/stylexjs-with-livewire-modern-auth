<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @include('partials.theme-preset')

        <style>
            [x-cloak] { display: none !important; }
            html, body { background-color: var(--pf-bg, #030712) !important; }
            body { color: var(--pf-text, #ffffff); }
        </style>

        @fonts

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/js/app.js'])
        @endif

        @livewireStyles
    </head>
    <body class="@stylex('homeBody')">
        <header class="@stylex('homeHeader')">
            <a href="{{ route('home') }}" class="@stylex('homeBrand')">
                <span class="@stylex('homeBrandMark')">{{ strtoupper(mb_substr(config('app.name', 'Laravel'), 0, 1)) }}</span>
                <span class="@stylex('homeBrandText')">{{ config('app.name', 'Laravel') }}</span>
            </a>

            <nav class="@stylex('homeNav')">
                @auth
                    <a href="{{ route('dashboard') }}" class="@stylex('homeNavBtnPrimary', 'homeNavBtn')">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="@stylex('homeNavBtn')">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="@stylex('homeNavBtnPrimary', 'homeNavBtn')">Create account</a>
                    @endif
                @endauth
            </nav>
        </header>

        <livewire:home.hero />

        <livewire:home.features />

        <livewire:home.about />

        <livewire:home.service />

        <livewire:home.project />

        <livewire:home.panel />

        <livewire:home.compare />

        <livewire:home.payment-support />

        <footer class="@stylex('homeFooter')">
            {{ config('app.name', 'Laravel') }} &mdash; Built with Laravel, Livewire &amp; StyleX.
        </footer>

        @livewireScripts
    </body>
</html>
