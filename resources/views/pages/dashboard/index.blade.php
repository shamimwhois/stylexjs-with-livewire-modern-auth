<div>
    @if (session('status'))
        <div class="@stylex('alert', 'alertSuccess', 'mb6')" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <h1 class="@stylex('dashTitle')">Welcome, {{ auth()->user()->name }}!</h1>
    <p class="@stylex('dashText')">You're signed in. This dashboard is a starting point for your application.</p>

    <div class="@stylex('dashGrid')">
        <div class="@stylex('dashCard')">
            <h2 class="@stylex('dashCardTitle')">StyleX button</h2>
            <p class="@stylex('dashCardText')">Compiled by StyleX into <code class="@stylex('codeTag')">@layer priority*</code> rules in the built CSS.</p>
            <div class="@stylex('mt4')">
                <button type="button" class="@stylex('btn', 'btnPrimary')">
                    Hello from StyleX
                </button>
            </div>
        </div>

        <div class="@stylex('dashCard')">
            <h2 class="@stylex('dashCardTitle')">Sign out</h2>
            <p class="@stylex('dashCardText')">End your session securely.</p>

            <div class="@stylex('mt4')">
                <x-auth.logout class="@stylex('btn', 'btnOutline')" />
            </div>
        </div>
    </div>
</div>