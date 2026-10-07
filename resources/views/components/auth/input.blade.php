@props([
    'name',
    'label' => null,
    'type' => 'text',
    'model' => null,
    'autocomplete' => null,
    'autofocus' => false,
    'required' => false,
    'placeholder' => '',
    'hint' => null,
    'hintHref' => null,
])

@php $model = $model ?: $name; @endphp

<div>
    @if ($label)
        <div class="{{ cls('labelRow') }}">
            <label for="{{ $name }}" class="{{ cls('label') }}">{{ $label }}</label>
            @if ($hint)
                <a href="{{ $hintHref }}" class="accent-fg-70 {{ cls('hint') }}">{{ $hint }}</a>
            @endif
        </div>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($model) wire:model="{{ $model }}" @endif
        autocomplete="{{ $autocomplete }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        @if ($autofocus) autofocus @endif
        class="{{ cls($errors->has($name) ? ['inputInvalid', 'inputInvalidPad'] : ['input', 'inputPad'], $class ?? '') }}"
        {{ $attributes }}
    >
</div>

@error($name)
    <p class="{{ cls('errorText') }}">{{ $message }}</p>
@enderror