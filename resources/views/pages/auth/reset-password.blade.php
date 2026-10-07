<div class="auth-page">

    <x-auth.header
        rightLabel="Remembered it?"
        rightText="Back to sign in"
        rightHref="{{ route('login') }}"
    />

    <main class="@stylex('authMain')">
        <div class="@stylex('authInner')">

            <div class="@stylex('titleBlock')">
                <h1 class="@stylex('authTitle')">
                    Reset your password
                </h1>
                <p class="@stylex('authSubtitle')">Choose a new password for your account.</p>
            </div>

            <div class="@stylex('card', 'cardPad')">
                <x-auth.status />

                <form wire:submit="resetPassword" class="@stylex('flexCol', 'gap5')">
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

                    <x-auth.password
                        name="password"
                        label="Password"
                        model="password"
                        autocomplete="new-password"
                    />

                    <x-auth.password
                        name="password_confirmation"
                        label="Confirm password"
                        model="password_confirmation"
                        autocomplete="new-password"
                    />

                    <x-auth.button>
                        Reset password
                    </x-auth.button>
                </form>
            </div>

            <x-auth.security-footer />
        </div>
    </main>
</div>