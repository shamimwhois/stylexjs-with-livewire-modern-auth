@props([
    'placeholder' => '',
    'required' => false,
    'class' => '',
])

<input
    type="tel"
    id="phone"
    name="phone"
    @if ($required) required @endif
    placeholder="{{ $placeholder }}"
    class="{{ cls('input', 'inputPad', $class) }}"
    {{ $attributes }}
>