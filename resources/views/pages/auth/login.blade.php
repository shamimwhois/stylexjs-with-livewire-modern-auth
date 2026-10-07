<div class="auth-page">

    <x-auth.header
        rightLabel="New here?"
        rightText="Create account"
        rightHref="{{ route('register') }}"
    />

    <main class="@stylex('authMain')">
        <div class="@stylex('authInner')">

            <div class="@stylex('titleBlock')">
                <h1 class="@stylex('authTitle')">
                    {{ $twoFactorChallenge ? 'Two factor authentication' : 'Sign in to your account' }}
                </h1>
                <p class="@stylex('authSubtitle')">
                    {{ $twoFactorChallenge ? 'Enter the code from your authenticator app to continue' : 'Enter your credentials to continue' }}
                </p>
            </div>

            <div class="@stylex('card', 'cardPad')">
                <x-auth.status />

                @if ($twoFactorChallenge)
                    <form wire:submit="confirmTwoFactorAuthentication" class="@stylex('flexCol', 'gap5')">
                        <x-auth.input
                            name="code"
                            label="Authentication code"
                            model="code"
                            autocomplete="one-time-code"
                            inputmode="numeric"
                            autofocus
                            required
                            placeholder="123456"
                        />

                        <x-auth.button>
                            Confirm sign in
                        </x-auth.button>

                        <x-auth.button type="button" wire="resetChallenge" variant="ghost">
                            Use a different account
                        </x-auth.button>
                    </form>
                @else
                    <form wire:submit="authenticate" class="@stylex('flexCol', 'gap5')">
                        <x-auth.input
                            name="email"
                            label="Email or username"
                            model="email"
                            autocomplete="username"
                            autofocus
                            required
                            placeholder="you@example.com or username"
                        />

                        <x-auth.password
                            name="password"
                            label="Password"
                            model="password"
                            hint="Forgot password?"
                            hintHref="{{ route('password.request') }}"
                        />

                        <x-auth.checkbox name="remember" model="remember" label="Remember me" />

                        <x-auth.button>
                            Sign in
                        </x-auth.button>

                         @if (! $twoFactorChallenge)
                

                <p class="@stylex('authFooter')">
                    <span>Don't have an account? <a href="{{ route('register') }}" class="accent-fg-70 {{ cls('headerLinkStrong') }}">Register</a></span>
                    @if (config('otp.enabled') && Route::has('otp.login'))
                        <span>&middot;</span>
                        <span>Prefer a code? <a href="{{ route('otp.login') }}" class="accent-fg-70 {{ cls('headerLinkStrong') }}">Login with OTP</a></span>
                    @endif
                    @if (config('magic-link.enabled') && Route::has('login.magic'))
                        <span>&middot;</span>
                        <span>No password? <a href="{{ route('login.magic') }}" class="accent-fg-70 {{ cls('headerLinkStrong') }}">Magic link</a></span>
                    @endif
                </p>
                
            @endif
                    </form>
                    
                @endif
            </div>
<x-auth.social />
           

            <x-auth.security-footer />
        </div>
    </main>

@php
        $noticeVariant = in_array($noticeType, ['suspended', 'throttled'], true) ? 'warning' : 'danger';

        $noticeKicker = match ($noticeType) {
            'blocked' => 'Restricted access',
            'suspended' => 'Account hold',
            'throttled' => 'Rate limit',
            'two_factor_invalid' => '2FA check',
            default => 'Invalid credentials',
        };
    @endphp

    <x-auth.notice-modal
        :notice-modal="$noticeModal"
        :title="$noticeTitle"
        :message="$noticeMessage"
        :variant="$noticeVariant"
        :kicker="$noticeKicker"
        dialog-id="sign-in-notice"
    >
        <x-slot:icon>
            @if ($noticeType === 'blocked')
                <x-lucide-ban class="{{ cls('noticeIcon', 'iconStroke') }}" aria-hidden="true" />
            @elseif ($noticeType === 'suspended')
                <x-lucide-pause class="{{ cls('noticeIcon', 'iconStroke') }}" aria-hidden="true" />
            @elseif ($noticeType === 'throttled')
                <x-lucide-clock class="{{ cls('noticeIcon', 'iconStroke') }}" aria-hidden="true" />
            @elseif ($noticeType === 'two_factor_invalid')
                <x-lucide-key-round class="{{ cls('noticeIcon', 'iconStroke') }}" aria-hidden="true" />
            @else
                <x-lucide-octagon-alert class="{{ cls('noticeIcon', 'iconStroke') }}" aria-hidden="true" />
            @endif
        </x-slot:icon>
    </x-auth.notice-modal>
</div>