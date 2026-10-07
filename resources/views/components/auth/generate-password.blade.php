@props([
    'label' => 'Generate password',
    'iconOnly' => false,
    'class' => '',
])

<button
    type="button"
    @click="generatePassword()"
    :aria-label="password ? '{{ __('Regenerate password') }}' : '{{ $label }}'"
    :title="password ? '{{ __('Regenerate password') }}' : '{{ $label }}'"
    class="{{ cls('btn', 'btnOutline', 'btnSm', 'shrink0', $iconOnly ? 'btnIcon' : '', $class) }}"
>
    <x-lucide-key x-show="!password.length" class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
    <x-lucide-rotate-ccw-key x-show="password.length" x-cloak class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
    @unless ($iconOnly)
        <span x-text="password ? '{{ __('Regenerate password') }}' : '{{ $label }}'">{{ $label }}</span>
    @endunless
</button>