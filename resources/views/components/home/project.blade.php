<x-home.section
    alt
    eyebrow="Open source"
    title="Yours to fork, extend and ship"
    subtitle="No vendor lock-in, no usage fees — just a well-documented foundation and a community behind it."
>
    <div class="@stylex('homeSectionGrid')">
        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-square-code class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Open source</h3>
            <p class="@stylex('homeFeatureText')">Fully open source with MIT license. Fork it, extend it, ship it. No vendor lock-in, no usage fees.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-file-text class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Well documented</h3>
            <p class="@stylex('homeFeatureText')">Comprehensive docs covering architecture, deployment, customization, and every feature in the stack.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-users class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Community driven</h3>
            <p class="@stylex('homeFeatureText')">Join a growing community of developers building production apps with Laravel, Livewire, and StyleX.</p>
        </div>
    </div>
</x-home.section>
