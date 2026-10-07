<div class="auth-page">

    <x-auth.header
        rightLabel="Prefer passwords?"
        rightText="Sign in"
        rightHref="{{ route('login') }}"
    />

    <main class="@stylex('authMain')">
        <div class="@stylex('authInner')">

            <div class="@stylex('titleBlock')">
                <h1 class="@stylex('authTitle')">
                    {{ $codeSent ? 'Enter your code' : 'Login with a code' }}
                </h1>
                <p class="@stylex('authSubtitle')">
                    @if ($codeSent)
                        We emailed a {{ str_repeat('•', config('otp.length', 6)) }}-digit code to <strong>{{ $email }}</strong>. It expires in {{ $ttl }} minutes.
                    @else
                        Tell us your email and we'll send you a one-time sign-in code. No password needed.
                    @endif
                </p>
            </div>

            <div class="@stylex('card', 'cardPad')">
                <x-auth.status />

                @if (! $codeSent)
                    <form wire:submit="sendCode" class="@stylex('flexCol', 'gap5')">
                        <x-auth.input
                            name="email"
                            label="Email"
                            model="email"
                            type="email"
                            autocomplete="username"
                            autofocus
                            required
                            placeholder="you@example.com"
                        />

                        <x-auth.button>
                            Email me a code
                        </x-auth.button>
                    </form>
                @else
                    <form wire:submit="verifyCode" class="@stylex('flexCol', 'gap5')">
                        <div>
                            <div class="@stylex('labelRow')">
                                <label for="code" class="@stylex('label')">One-time code</label>
                            </div>

                            <x-auth.otp-code name="code" model="code" />
                        </div>

                        <x-auth.button>
                            Verify and sign in
                        </x-auth.button>

                        <div class="@stylex('flexRow', 'justifyBetween')">
                            <button type="button" wire:click="resetFlow" class="@stylex('btn', 'btnOutline', 'btnSm')">
                                Use a different email
                            </button>

                            <button type="button" wire:click="sendCode" wire:loading.attr="disabled" class="@stylex('btn', 'btnOutline', 'btnSm')">
                                Resend code
                            </button>
                        </div>
                    </form>
                @endif
            </div>

            <p class="@stylex('authFooter')">
                <span>Don't have an account? <a href="{{ route('register') }}" class="accent-fg-70 {{ cls('headerLinkStrong') }}">Register</a></span>
            </p>

            <x-auth.security-footer />
        </div>
    </main>
</div>
