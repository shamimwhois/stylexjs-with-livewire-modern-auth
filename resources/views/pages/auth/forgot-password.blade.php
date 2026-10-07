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
                    Forgot your password?
                </h1>
                <p class="@stylex('authSubtitle')">No problem. Just let us know your email address and we will email you a password reset link.</p>
            </div>

            <div class="@stylex('card', 'cardPad')">
                <x-auth.status />

                <form wire:submit="sendPasswordResetLink" class="@stylex('flexCol', 'gap5')">
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
                        Email password reset link
                    </x-auth.button>
                </form>
            </div>

            <x-auth.security-footer />
        </div>
    </main>
</div>