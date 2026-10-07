@push('scripts')
    @include('partials.registration-form')
@endpush

<div class="auth-page" x-data="registrationForm">

    <x-auth.header
        rightLabel="Already registered?"
        rightText="Sign in"
        rightHref="{{ route('login') }}"
    />

    <main class="@stylex('registerMain')">

        <div class="@stylex('registerTitleBlock')">
            <h1 class="@stylex('authTitle', 'authTitleXl')">
                Create your <span class="accent-fg">account</span>
            </h1>
            <p class="@stylex('authSubtitle')">
                Enterprise registration &middot; Protected by multi-layer security
            </p>
        </div>

        <x-auth.register-progress />

        <form x-on:submit.prevent="handleStepSubmit()" novalidate
              class="@stylex('registerForm')">

            <div>
                <div class="@stylex('stepPanel')">
                    <div class="@stylex('stepHeader')">
                        <h2 class="@stylex('stepHeaderTitle')">
                            <span class="accent-fg @stylex('stepHeaderTitle')" x-text="('0' + step).slice(-2)"></span>
                            <span x-text="step === 1 ? ' Profile' : step === 2 ? ' Social & Address' : ' Review'"></span>
                        </h2>
                        <span class="@stylex('stepHeaderMeta')" x-text="'Step ' + step + ' of 3'"></span>
                    </div>

                    <div x-show="errorBanner" x-cloak x-transition:enter="{{ cls('fadeEnter') }}" x-transition:enter-start="{{ cls('fadeStart') }}" class="@stylex('errorBanner')">
                        <x-lucide-triangle-alert class="@stylex('errorBannerIcon', 'iconStroke')" aria-hidden="true" />
                        <p class="@stylex('errorBannerText')">Please fix the highlighted fields below, then submit again.</p>
                        <button type="button" @click="errorBanner = false" class="@stylex('errorBannerClose')" title="Dismiss">
                            <x-lucide-x class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                        </button>
                    </div>

                    <x-auth.step-profile :country-codes="$countryCodes ?? []" :country-options="$countryOptions ?? []" />
                    <x-auth.step-social />
                    <x-auth.step-review />
                </div>

                <x-auth.social />
            </div>

            <x-auth.security-sidebar />
        </form>

        <x-auth.security-footer />
    </main>
</div>