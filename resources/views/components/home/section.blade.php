@props([
    'eyebrow' => null,
    'title' => null,
    'subtitle' => null,
    'alt' => false,
    'center' => false,
])

<section class="@stylex('homeSection', $alt ? 'homeSectionAlt' : '')">
    <div class="@stylex('homeSectionInner')">
        @if ($title)
            <div class="@stylex('homeSectionHead', $center ? 'homeSectionHeadCenter' : '')">
                @if ($eyebrow)
                    <span class="@stylex('homeEyebrow')">{{ $eyebrow }}</span>
                @endif
                <h2 class="@stylex('homeSectionTitle')">{{ $title }}</h2>
                @if ($subtitle)
                    <p class="@stylex('homeSectionSub')">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        {{ $slot }}
    </div>
</section>
