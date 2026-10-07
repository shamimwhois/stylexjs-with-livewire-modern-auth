<div>
    <div class="@stylex('titleBlock')">
        <h1 class="@stylex('authTitle')">Security settings</h1>
        <p class="@stylex('authSubtitle')">Manage two factor authentication and recovery options for your account.</p>
    </div>

    @if ($passwordPrompt)
        <div class="@stylex('card', 'cardPad', 'mb6')">
            <h2 class="@stylex('dashCardTitle')">Confirm your password</h2>
            <p class="@stylex('dashCardText')">For your security, confirm your password to continue.</p>

            <form wire:submit="submitPassword" class="@stylex('flexCol', 'gap4', 'mt6')">
                <x-auth.input
                    name="password"
                    label="Password"
                    model="password"
                    type="password"
                    autocomplete="current-password"
                    autofocus
                    required
                />

                <div class="@stylex('flexRow', 'gap3')">
                    <x-auth.button>Confirm</x-auth.button>
                    <x-auth.button wire="cancelPrompt" type="button" variant="ghost">Cancel</x-auth.button>
                </div>
            </form>
        </div>
    @endif

    <div class="@stylex('card', 'cardPad')">
        <h2 class="@stylex('dashCardTitle')">Two factor authentication</h2>
        <p class="@stylex('dashCardText')">
            When enabled, sign in requires a code from your authenticator app in addition to your password.
        </p>

        @if (auth()->user()->hasEnabledTwoFactorAuthentication())
            <p class="@stylex('dashText', 'mt4')">
                <span class="@stylex('alert', 'alertSuccess')">Two factor authentication is enabled.</span>
            </p>

            @if ($showRecoveryCodes)
                <div class="@stylex('mt4')">
                    <p class="@stylex('dashCardText')">Store these recovery codes somewhere safe. Each code can be used once.</p>
                    <ul class="@stylex('flexCol', 'gap3', 'mt4')">
                        @foreach ($this->recoveryCodes() as $code)
                            <li class="@stylex('codeTag')">{{ $code }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="@stylex('flexRow', 'gap3', 'mt6')">
                <x-auth.button wire="prepareRegenerate" type="button" variant="outline">Regenerate recovery codes</x-auth.button>
                <x-auth.button wire="prepareDisable" type="button" variant="destructive">Disable two factor authentication</x-auth.button>
            </div>
        @elseif ($pendingConfirm)
            <div class="@stylex('mt4')">
                <p class="@stylex('dashCardText')">Scan the QR code with your authenticator app, or enter the setup key manually.</p>

                <div class="@stylex('mt4')">
                    {!! $this->qrSvg() !!}
                </div>

                <p class="@stylex('codeTag', 'mt4')">{{ $this->secretKey() }}</p>

                <form wire:submit="confirmTwoFactor" class="@stylex('flexCol', 'gap4', 'mt6')">
                    <x-auth.input
                        name="code"
                        label="Authenticator code"
                        model="code"
                        autocomplete="one-time-code"
                        inputmode="numeric"
                        autofocus
                        required
                        placeholder="123456"
                    />

                    <div class="@stylex('flexRow', 'gap3')">
                        <x-auth.button>Confirm and enable</x-auth.button>
                        <x-auth.button wire="cancelEnable" type="button" variant="ghost">Cancel</x-auth.button>
                    </div>
                </form>
            </div>
        @else
            <div class="@stylex('mt6')">
                <x-auth.button wire="prepareEnable" type="button">
                    Enable two factor authentication
                </x-auth.button>
            </div>
        @endif
    </div>
</div>