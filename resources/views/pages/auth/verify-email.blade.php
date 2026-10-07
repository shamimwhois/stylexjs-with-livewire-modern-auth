<div class="auth-page">

    <x-auth.header />

    <main class="@stylex('authMain')">
        <div class="@stylex('authInner')">

            <div class="@stylex('titleBlock')">
                <h1 class="@stylex('authTitle')">
                    Verify your email address
                </h1>
                <p class="@stylex('authSubtitle')">
                    Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.
                </p>
            </div>

            <div class="@stylex('card', 'cardPad')">
                @if (session('status') == 'verification-link-sent')
                    <div class="@stylex('alert', 'alertSuccess', 'mb6')" role="alert">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <x-auth.button wire="resendNotification">
                    Resend verification email
                </x-auth.button>

                <div class="@stylex('flexRow', 'justifyCenter', 'mt6')">
                    @if (Route::has('logout'))
                        <x-auth.logout class="@stylex('hint', 'textMuted')" />
                    @endif
                </div>
            </div>

            <x-auth.security-footer />
        </div>
    </main>

    <x-auth.notice-modal
        :notice-modal="$noticeModal"
        :title="$noticeTitle"
        :message="$noticeMessage"
        variant="warning"
        kicker="Verification email"
        dialog-id="verify-notice"
    >
        <x-slot:icon>
            <x-lucide-hourglass class="{{ cls('noticeIcon', 'iconStroke') }}" aria-hidden="true" />
        </x-slot:icon>
    </x-auth.notice-modal>
</div>