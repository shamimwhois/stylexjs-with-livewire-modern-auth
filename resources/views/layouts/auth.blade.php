<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
<body class="auth-page @stylex('authBody')">
    {{ $slot }}

    @stack('scripts')
    @livewireScripts
</body>
</html>