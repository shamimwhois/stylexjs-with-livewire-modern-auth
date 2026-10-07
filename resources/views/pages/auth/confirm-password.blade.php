<div class="auth-page">

    <x-auth.header />

    <main class="@stylex('authMain')">
        <div class="@stylex('authInner')">

            <div class="@stylex('titleBlock')">
                <h1 class="@stylex('authTitle')">
                    Confirm your password
                </h1>
                <p class="@stylex('authSubtitle')">For your security, please confirm your password to continue.</p>
            </div>

            <div class="@stylex('card', 'cardPad')">
                <form wire:submit="confirm" class="@stylex('flexCol', 'gap5')">
                    <x-auth.password
                        name="password"
                        label="Password"
                        model="password"
                        autocomplete="current-password"
                        autofocus
                    />

                    <x-auth.button>
                        Confirm
                    </x-auth.button>
                </form>
            </div>

            <x-auth.security-footer />
        </div>
    </main>
</div>