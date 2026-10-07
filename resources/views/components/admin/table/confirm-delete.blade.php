@props([
    'dialogId' => null,
    'title' => 'Delete record',
    'intro' => 'This action cannot be undone.',
    'text' => 'Are you sure you want to permanently delete this record?',
    'confirmLabel' => 'Delete',
    'dismissLabel' => 'Cancel',
    'wireClick' => null,
    'openState' => 'deleteOpen',
])

<template x-teleport="body">
    <div
        class="@stylex('modalBackdrop')"
        x-show="{{ $openState }}"
        x-cloak
        x-transition:enter="@stylex('fadeEnter')"
        x-transition:enter-start="@stylex('fadeStart')"
        x-transition:leave="@stylex('fadeEnter')"
        x-transition:leave-end="@stylex('fadeStart')"
        @click.self="{{ $openState }} = false"
    >
        <div
            class="@stylex('modalCard')"
            role="dialog"
            aria-modal="true"
            :aria-hidden="!{{ $openState }}"
            aria-labelledby="{{ $dialogId }}"
            x-transition:enter="@stylex('modalPop')"
            x-transition:enter-start="@stylex('modalScale')"
            x-transition:leave="@stylex('modalPop')"
            x-transition:leave-end="@stylex('modalScale')"
            @keydown.escape.window="{{ $openState }} = false"
        >
            <div class="@stylex('cellUser')">
                <span class="@stylex('modalIconWrap')">
                    <x-lucide-trash-2 class="@stylex('iconMd', 'iconStroke')" aria-hidden="true" />
                </span>
                <div>
                    <h2 class="@stylex('modalTitle')" id="{{ $dialogId }}">{{ $title }}</h2>
                    <p class="@stylex('modalText')">{{ $intro }}</p>
                </div>
            </div>

            <p class="@stylex('modalText', 'mt4')">{{ $text }}</p>

            <div class="@stylex('formFooter', 'mt4')">
                <button type="button" class="@stylex('btn', 'btnOutline', 'btnSm')" @click="{{ $openState }} = false">
                    {{ $dismissLabel }}
                </button>
                <button type="button" class="@stylex('btn', 'btnDestructive', 'btnSm')" wire:click="{{ $wireClick }}">
                    <x-lucide-trash-2 class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    {{ $confirmLabel }}
                </button>
            </div>
        </div>
    </div>
</template>