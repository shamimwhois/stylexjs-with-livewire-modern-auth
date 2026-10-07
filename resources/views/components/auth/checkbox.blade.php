@props([
    'name',
    'model' => null,
    'label',
    'class' => '',
])

@php $model = $model ?: $name; @endphp

<label class="{{ cls('checkboxWrap', $class) }}">
    <input
        type="checkbox"
        name="{{ $name }}"
        wire:model="{{ $model }}"
        class="{{ cls('checkbox') }}"
    >
    <span class="{{ cls('checkboxLabel') }}">{{ $label }}</span>
</label>