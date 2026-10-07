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
                    {{ $linkSent ? 'Check your inbox' : 'Passwordless sign in' }}
                </h1>
                <p class="@stylex('authSubtitle')">
                    @if ($linkSent)
                        We emailed a sign-in link to <strong>{{ $email }}</strong>. It expires in {{ $ttl }} minutes. Click it to sign in instantly.
                    @else
                        Enter your email and we'll send you a secure, expiring sign-in link. No password needed.
                    @endif
                </p>
            </div>

            <div class="@stylex('card', 'cardPad')">
                <x-auth.status />

                @if (! $linkSent)
                    <form wire:submit="sendLink" class="@stylex('flexCol', 'gap5')">
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
                            Email me a sign-in link
                        </x-auth.button>
                    </form>
                @else
                    <p class="@stylex('authSubtitle')">
                        Didn't get it? <a href="{{ route('login.magic') }}" class="accent-fg-70 {{ cls('headerLinkStrong') }}">Request another link</a>.
                    </p>
                @endif
            </div>

            <x-auth.security-footer />
        </div>
    </main>
</div>