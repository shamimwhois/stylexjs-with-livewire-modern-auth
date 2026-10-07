<x-home.section
    eyebrow="Under the hood"
    title="The boring parts, solved"
    subtitle="Dashboards, authorization and background processing wired up before you write your first line of code."
>
    <div class="@stylex('homeSectionGrid')">
        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-chart-no-axes-column class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Dashboard</h3>
            <p class="@stylex('homeFeatureText')">Pre-built admin dashboard with stats, charts, and management panels. Extend it or replace it.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-sliders-horizontal class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Role-based access</h3>
            <p class="@stylex('homeFeatureText')">Gate and policy based authorization with role management. Control who sees what with fine-grained permissions.</p>
        </div>

        <div class="@stylex('homeFeatureCard')">
            <div class="@stylex('homeFeatureIcon')">
                <x-lucide-clock class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
            </div>
            <h3 class="@stylex('homeFeatureTitle')">Queues &amp; jobs</h3>
            <p class="@stylex('homeFeatureText')">Database-backed queue system with job batching, retries, and failure handling. Process tasks in the background.</p>
        </div>
    </div>
</x-home.section>
