@if (session('status'))
    <div class="{{ cls('alert', 'alertSuccess', 'mb6') }}" role="alert">
        {{ session('status') }}
    </div>
@endif