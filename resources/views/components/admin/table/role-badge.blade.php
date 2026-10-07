@props([
    'slug' => null,
    'name' => null,
])

@php
    $variant = match ($slug) {
        'admin' => 'badgePrimary',
        'developer', 'seller' => 'badgeAccent',
        default => 'badgeDefault',
    };
@endphp

<span class="@stylex('badge', $variant)">{{ $name ?? $slug }}</span>