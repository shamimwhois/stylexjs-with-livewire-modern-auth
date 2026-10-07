@props([
    'route' => 'logout',
    'label' => 'Log out',
    'class' => null,
])

@php $class = $class ?: cls('headerLink'); @endphp

<form method="POST" action="{{ route($route) }}">
    @csrf

    <button type="submit" class="{{ $class }}">
        {{ $label }}
    </button>
</form>