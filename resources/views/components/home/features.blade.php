<x-home.section
    eyebrow="Core stack"
    title="Everything you need, out of the box"
    subtitle="A batteries-included foundation: authentication, reactive UI and deterministic styling working together from day one."
>
    <div class="@stylex('homeSectionGrid')">
        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-shield-check class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Authentication</h3>
            <p class="@stylex('homeFeatureText')">Login, registration, email verification and password reset out of the box with Laravel Fortify.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-lock class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Reactive UI</h3>
            <p class="@stylex('homeFeatureText')">Livewire 3 powers real-time forms, validation and interactions without writing a line of JavaScript.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-settings class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">StyleX styling</h3>
            <p class="@stylex('homeFeatureText')">Deterministic, type-safe styles compiled to atomic CSS — zero runtime, zero conflicts, tiny bundles.</p>
        </div>
    </div>
</x-home.section>
