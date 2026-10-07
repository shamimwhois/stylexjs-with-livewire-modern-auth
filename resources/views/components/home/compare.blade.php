<x-home.section
    alt
    eyebrow="Comparison"
    title="Why not just..."
    subtitle="The honest trade-offs — and why this stack wins on simplicity, not hype."
>
    <div class="@stylex('homeSectionGrid')">
        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-chart-no-axes-column class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">StyleX vs Tailwind</h3>
            <p class="@stylex('homeFeatureText')">StyleX compiles to atomic CSS at build time with zero runtime. No purge step, no config file, no arbitrary class strings.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-arrow-left-right class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Livewire vs React</h3>
            <p class="@stylex('homeFeatureText')">Server-side rendering with reactive updates. No build step for components, no API layer, no hydration mismatch.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-clock class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Faster setup</h3>
            <p class="@stylex('homeFeatureText')">From clone to running app in under a minute. No complex toolchain, no config files, no boilerplate to wade through.</p>
        </div>
    </div>
</x-home.section>
