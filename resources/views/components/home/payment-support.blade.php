<x-home.section
    center
    eyebrow="Get started"
>
    <div class="@stylex('homeCtaBanner')">
        <h2 class="@stylex('homeCtaBannerTitle')">Ready to build something great?</h2>
        <p class="@stylex('homeCtaBannerText')">Clone the repo, pick a theme, and deploy your first feature today. Everything else is already wired up.</p>

        <div class="@stylex('homeCtaRow')">
            @auth
                <a href="{{ route('dashboard') }}" class="@stylex('homeCtaBannerBtn')">
                    Go to dashboard
                    <x-lucide-arrow-right class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                </a>
            @else
                <a href="{{ route('register') }}" class="@stylex('homeCtaBannerBtn')">
                    Create your account
                    <x-lucide-arrow-right class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                </a>
                <a href="{{ route('login') }}" class="@stylex('homeCtaGhost')">Sign in</a>
            @endauth
        </div>
    </div>
</x-home.section>
