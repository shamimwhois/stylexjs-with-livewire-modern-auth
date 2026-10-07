@props([
    'noticeModal' => false,
    'title' => 'Notice',
    'message' => '',
    'kicker' => null,
    'variant' => 'danger',
    'dialogId' => 'auth-notice',
])

@if ($noticeModal)
    @php
        $noticeIsWarning = $variant === 'warning';
    @endphp
    <div
        class="@stylex('noticeBackdrop')"
        x-data="{ open: true }"
        x-effect="open = $wire.noticeModal"
        x-show="open"
        x-cloak
        x-transition:enter="@stylex('fadeEnter')"
        x-transition:enter-start="@stylex('fadeStart')"
        x-transition:leave="@stylex('fadeEnter')"
        x-transition:leave-end="@stylex('fadeStart')"
        @click.self="open = false; $wire.dismissNotice()"
    >
        <div
            class="@stylex('noticeCard')"
            role="alertdialog"
            aria-modal="true"
            aria-labelledby="{{ $dialogId }}-title"
            aria-describedby="{{ $dialogId }}-text"
            x-transition:enter="@stylex('noticePop')"
            x-transition:enter-start="@stylex('noticeScale')"
            x-transition:leave="@stylex('noticePop')"
            x-transition:leave-end="@stylex('noticeScale')"
            @keydown.escape.window="open = false; $wire.dismissNotice()"
        >
            <div class="@stylex('noticeBar')" aria-hidden="true"></div>
            <div class="@stylex($noticeIsWarning ? 'noticeGlowWarning' : 'noticeGlow')" aria-hidden="true"></div>

            <button
                type="button"
                class="@stylex('noticeClose')"
                @click="open = false; $wire.dismissNotice()"
                aria-label="Close"
            >
                <x-lucide-x class="{{ cls('iconMd', 'iconStroke') }}" aria-hidden="true" />
            </button>

            <div class="@stylex('noticeHeader')">
                <span class="@stylex($noticeIsWarning ? 'noticeIconWrapWarning' : 'noticeIconWrap')">
                    @isset($icon)
                        {{ $icon }}
                    @else
                        <x-lucide-octagon-alert class="{{ cls('noticeIcon', 'iconStroke') }}" aria-hidden="true" />
                    @endisset
                </span>
                <div>
                    @if ($kicker)
                        <p class="@stylex('noticeKicker')">{{ $kicker }}</p>
                    @endif
                    <h2 id="{{ $dialogId }}-title" class="@stylex('noticeTitle')">
                        {{ $title }}
                    </h2>
                    <p id="{{ $dialogId }}-text" class="@stylex('noticeText')">
                        {{ $message }}
                    </p>
                </div>
            </div>

            <div class="@stylex('formFooter', 'mt4')">
                <button
                    type="button"
                    class="@stylex('btn', 'btnGradient', 'btnSm', 'wFull', 'wAuto')"
                    @click="open = false; $wire.dismissNotice()"
                >
                    Got it
                </button>
            </div>
        </div>
    </div>
@endif