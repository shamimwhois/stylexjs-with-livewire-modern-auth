<div>
    <h1 class="@stylex('dashTitle')">Seller dashboard</h1>
    <p class="@stylex('dashText')">Welcome, {{ auth()->user()->name }}. Manage your shop, products and payouts from here.</p>

    <div class="@stylex('dashGrid')">
        @can('products.manage')
            <div class="@stylex('dashCard')">
                <h2 class="@stylex('dashCardTitle')">Products</h2>
                <p class="@stylex('dashCardText')">Create and manage the products you sell.</p>
                <div class="@stylex('mt4')">
                    <a href="#" class="@stylex('btn', 'btnPrimary')">Manage products</a>
                </div>
            </div>
        @endcan

        @can('licenses.view')
            <div class="@stylex('dashCard')">
                <h2 class="@stylex('dashCardTitle')">Licenses</h2>
                <p class="@stylex('dashCardText')">Track issued licenses and their activations.</p>
                <div class="@stylex('mt4')">
                    <a href="#" class="@stylex('btn', 'btnOutline')">View licenses</a>
                </div>
            </div>
        @endcan

        @can('wallet.view')
            <div class="@stylex('dashCard')">
                <h2 class="@stylex('dashCardTitle')">Wallet</h2>
                <p class="@stylex('dashCardText')">Monitor your balance and payout history.</p>
                <div class="@stylex('mt4')">
                    <a href="#" class="@stylex('btn', 'btnOutline')">Open wallet</a>
                </div>
            </div>
        @endcan
    </div>
</div>