@props([
    'type' => 'submit',
    'wire' => null,
    'variant' => 'default',
    'size' => 'default',
    'class' => '',
])

@php
    $variantClass = match ($variant) {
        'outline' => 'btnOutline',
        'ghost' => 'btnGhost',
        'secondary' => 'btnSecondary',
        'destructive' => 'btnDestructive',
        'accent' => 'btnAccent',
        default => 'btnPrimary',
    };

    $sizeClass = match ($size) {
        'sm' => 'btnSm',
        'lg' => 'btnLg',
        'icon' => 'btnIcon',
        default => 'btnDefault',
    };
@endphp

<button
    {{ $attributes->merge(['type' => $type]) }}
    @if ($wire) wire:click="{{ $wire }}" @endif
    class="{{ cls('btn', $variantClass, $sizeClass, $class) }}"
>
    {{ $slot }}
</button>