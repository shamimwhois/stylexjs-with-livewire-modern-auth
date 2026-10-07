<x-home.section
    eyebrow="Platform"
    title="Production-grade from the start"
    subtitle="Multi-tenancy, a versioned API and payments — the unglamorous work is already done."
>
    <div class="@stylex('homeSectionGrid')">
        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-database class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Multi-tenant ready</h3>
            <p class="@stylex('homeFeatureText')">Database-per-tenant architecture with seamless context switching and isolated data boundaries.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-square-code class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">API-first</h3>
            <p class="@stylex('homeFeatureText')">RESTful API with Eloquent Resources, Sanctum tokens, and versioned endpoints for mobile and third-party integrations.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-globe class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Payment ready</h3>
            <p class="@stylex('homeFeatureText')">Stripe integration with subscriptions, invoices, and webhook handling. Accept payments from day one.</p>
        </div>
    </div>
</x-home.section>
