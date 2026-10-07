@props([
    'name',
    'label' => 'Password',
    'model' => null,
    'autocomplete' => 'current-password',
    'autofocus' => false,
    'required' => true,
    'hint' => null,
    'hintHref' => null,
])

@php $model = $model ?: $name; @endphp

<div>
    <div class="{{ cls('labelRow') }}">
        <label for="{{ $name }}" class="{{ cls('label') }}">{{ $label }}</label>
        @if ($hint)
            <a href="{{ $hintHref }}" class="accent-fg-70 {{ cls('hint') }}">{{ $hint }}</a>
        @endif
    </div>

    <div class="{{ cls('fieldWrap') }}" x-data="{ show: false }">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            x-bind:type="show ? 'text' : 'password'"
            wire:model="{{ $model }}"
            autocomplete="{{ $autocomplete }}"
            @if ($required) required @endif
            @if ($autofocus) autofocus @endif
            class="{{ cls($errors->has($name) ? 'inputInvalid' : 'input', 'inputPadPr') }}"
        >
        <button type="button" x-on:click="show = !show" class="{{ cls('iconBtn') }}" aria-label="Toggle password visibility">
            <x-lucide-eye x-show="!show" class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
            <x-lucide-eye-off x-show="show" x-cloak class="{{ cls('iconSm', 'iconStroke') }}" aria-hidden="true" />
        </button>
    </div>

    @error($name)
        <p class="{{ cls('errorText') }}">{{ $message }}</p>
    @enderror
</div>