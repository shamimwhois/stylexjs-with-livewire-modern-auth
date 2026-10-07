@props([
    'name' => 'code',
    'model' => 'code',
    'digitCount' => config('otp.length', 6),
])

<div {{ $attributes }}>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        wire:model="{{ $model }}"
        type="text"
        inputmode="numeric"
        autocomplete="one-time-code"
        maxlength="{{ $digitCount }}"
        required
        placeholder="{{ str_repeat('•', $digitCount) }}"
        class="{{ cls('input', 'inputPad', 'otpInput') }}"
    >
</div>

@error($name)
    <p class="{{ cls('errorText') }}">{{ $message }}</p>
@enderror
