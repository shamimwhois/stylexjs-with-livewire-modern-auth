<section class="@stylex('homeHero')">
    <span class="@stylex('homeHeroBadge')">
        <x-lucide-shield-check class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
        {{ $badge ?? 'Secure by design' }}
    </span>

    <h1 class="@stylex('homeHeroTitle')">
        {!! $title ?? 'Build faster with <span class="@stylex(\'homeHeroTitleAccent\')">Laravel &amp; StyleX</span>' !!}
    </h1>

    <p class="@stylex('homeHeroSub')">
        {{ $subtitle ?? 'A modern application starter — Livewire 3 for reactive interfaces, Fortify for bulletproof authentication, and StyleX for deterministic, maintainable styling.' }}
    </p>

    <div class="@stylex('homeCtaRow')">
        @auth
            <a href="{{ route('dashboard') }}" class="@stylex('homeCtaPrimary')">
                Go to dashboard
                <x-lucide-arrow-right class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            </a>
        @else
            <a href="{{ route('register') }}" class="@stylex('homeCtaPrimary')">
                Get started free
                <x-lucide-arrow-right class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            </a>
            <a href="{{ route('login') }}" class="@stylex('homeCtaGhost')">Sign in</a>
        @endauth
    </div>
</section>
