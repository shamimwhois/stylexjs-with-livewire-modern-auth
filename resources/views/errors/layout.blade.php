<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.theme-preset')

    <style>
        /* Pre-paint critical CSS: prevents white flash on hard reload.
           --pf-* vars are set synchronously by the theme preset above. */
        [x-cloak] { display: none !important; }
        html, body { background-color: var(--pf-bg, #030712) !important; }
        body { color: var(--pf-text, #ffffff); }
    </style>

    <title>@yield('title', 'Something went wrong') &mdash; {{ config('app.name') }}</title>

    @fonts

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
</head>

<style>
    .error-page {
        box-sizing: border-box;
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow-x: hidden;
        padding: 1.5rem;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    .error-page *,
    .error-page *::before,
    .error-page *::after {
        box-sizing: border-box;
    }

    .error-glows {
        position: fixed;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        pointer-events: none;
    }

    .error-glow {
        position: absolute;
        width: 42rem;
        height: 42rem;
        border-radius: 9999px;
        filter: blur(120px);
        opacity: 0.22;
    }

    .error-glow-primary {
        top: -12rem;
        left: -12rem;
        background: radial-gradient(circle, var(--pf-c1, #6429ed) 0%, transparent 70%);
    }

    .error-glow-accent {
        right: -14rem;
        bottom: -14rem;
        background: radial-gradient(circle, var(--pf-accent, #00e5fd) 0%, transparent 70%);
    }

    .error-main {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 42rem;
    }

    .error-card {
        position: relative;
        overflow: hidden;
        border-radius: 0.875rem;
        border: 1px solid color-mix(in srgb, var(--pf-text, #fff) 12%, transparent);
        background-color: color-mix(in srgb, var(--pf-surface, #111827) 88%, var(--pf-bg, #030909));
        background-image: radial-gradient(color-mix(in srgb, var(--pf-text, #fff) 6%, transparent) 1px, transparent 1px);
        background-size: 6px 6px;
        box-shadow:
            inset 0 1px 0 0 color-mix(in srgb, var(--pf-text, #fff) 7%, transparent),
            0 16px 40px -22px rgba(0, 0, 0, 0.8),
            0 3px 0 -1px color-mix(in srgb, var(--pf-accent, #00e5fd) 55%, transparent);
        padding: clamp(2rem, 6vw, 3.5rem);
        text-align: center;
    }

    .error-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 10%;
        right: 10%;
        height: 3px;
        border-radius: 9999px;
        background: linear-gradient(to right, var(--pf-c1, #6429ed), var(--pf-accent, #00e5fd));
        opacity: 0.85;
    }

    .error-chip {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        border-radius: 9999px;
        border: 1px solid color-mix(in srgb, var(--pf-accent, #00e5fd) 45%, transparent);
        background: color-mix(in srgb, var(--pf-accent, #00e5fd) 8%, transparent);
        padding: 0.3125rem 0.75rem;
        font-family: 'IBM Plex Mono', ui-monospace, monospace;
        font-size: 0.6875rem;
        font-weight: 500;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--pf-accent, #00e5fd);
    }

    .error-code {
        margin: 1.25rem 0 0;
        font-family: 'IBM Plex Mono', ui-monospace, monospace;
        font-size: clamp(4.5rem, 15vw, 7.5rem);
        font-weight: 600;
        line-height: 0.9;
        letter-spacing: -0.02em;
        color: var(--pf-accent, #00e5fd);
    }

    .error-title {
        margin: 1.25rem 0 0;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, sans-serif;
        font-size: 1.375rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--pf-text, #fff);
    }

    .error-message {
        margin: 0.75rem auto 0;
        max-width: 32rem;
        font-size: 0.9375rem;
        line-height: 1.7;
        color: var(--pf-text-muted, rgba(255, 255, 255, 0.6));
    }

    .error-actions {
        margin-top: 2rem;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }

    .error-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 0.625rem;
        padding: 0.6875rem 1.5rem;
        font-family: 'Plus Jakarta Sans', ui-sans-serif, sans-serif;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: color 200ms ease, background-color 200ms ease, border-color 200ms ease, box-shadow 200ms ease, transform 200ms ease;
    }

    .error-btn:hover {
        transform: translateY(-1px);
    }

    .error-btn:active {
        transform: translateY(1px);
    }

    .error-btn-primary {
        background-color: var(--pf-accent, #00e5fd);
        color: #04201f;
        box-shadow: 0 3px 0 -1px rgba(0, 0, 0, 0.55);
    }

    .error-btn-primary:hover {
        background-color: color-mix(in srgb, var(--pf-accent, #00e5fd) 85%, #ffffff);
    }

    .error-btn-ghost {
        border: 1px solid color-mix(in srgb, var(--pf-text, #fff) 14%, transparent);
        background: transparent;
        color: var(--pf-text, #fff);
    }

    .error-btn-ghost:hover {
        background-color: color-mix(in srgb, var(--pf-text, #fff) 6%, transparent);
    }

    @media (prefers-reduced-motion: reduce) {
        .error-btn {
            transition: none;
        }

        .error-btn:hover {
            transform: none;
        }
    }
</style>

<body class="error-page">
    <div class="error-glows" aria-hidden="true">
        <div class="error-glow error-glow-primary"></div>
        <div class="error-glow error-glow-accent"></div>
    </div>

    <main class="error-main">
        <section class="error-card">
            <span class="error-chip">HTTP @yield('code', '500')</span>
            <h1 class="error-code">@yield('code', '500')</h1>
            <h2 class="error-title">@yield('title', 'Something went wrong')</h2>
            <p class="error-message">@yield('message', 'An unexpected error has occurred. Please try again.')</p>

            <div class="error-actions">
                @hasSection('actions')
                    @yield('actions')
                @else
                    <a href="{{ route('home') }}" class="error-btn error-btn-primary">Go back home</a>
                    <button type="button" class="error-btn error-btn-ghost" onclick="history.back()">Go back</button>
                @endif
            </div>
        </section>
    </main>
</body>
</html>