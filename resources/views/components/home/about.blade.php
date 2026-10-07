<x-home.section
    alt
    eyebrow="Why this stack"
    title="Built for speed, themed for you"
    subtitle="Ship in hours instead of weeks — with a color system that adapts to your brand, not the other way around."
>
    <div class="@stylex('homeSectionGrid')">
        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-zap class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Built for speed</h3>
            <p class="@stylex('homeFeatureText')">From zero to production-ready with batteries-included tooling, sensible defaults, and zero configuration overhead.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-globe class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Theme presets</h3>
            <p class="@stylex('homeFeatureText')">23 built-in theme presets from Dracula to Tokyo Night. Switch the entire color scheme with a single toggle.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-shield-check class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Security first</h3>
            <p class="@stylex('homeFeatureText')">Real-time password breach checks, strength meters, passkey support, and two-factor authentication built in.</p>
        </div>
    </div>
</x-home.section>
