<div>
    @if (session('status'))
        <div class="@stylex('alert', 'alertSuccess', 'mb6')" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <h1 class="@stylex('dashTitle')">Admin dashboard</h1>
    <p class="@stylex('dashText')">Welcome, {{ auth()->user()->name }}. This is the admin area of your application.</p>

    <div class="@stylex('dashGrid')">
        <div class="@stylex('dashCard')">
            <h2 class="@stylex('dashCardTitle')">Admin area</h2>
            <p class="@stylex('dashCardText')">Manage your application's data and settings from here.</p>
            <div class="@stylex('mt4')">
                <button type="button" class="@stylex('btn', 'btnPrimary')">
                    Getting started
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