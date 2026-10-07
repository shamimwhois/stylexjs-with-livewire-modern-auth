@props([
    'brand' => config('app.name', 'Laravel'),
    'rightLabel' => null,
    'rightText' => null,
    'rightHref' => null,
])

<header class="{{ cls('authHeader') }}">
    <div class="{{ cls('authHeaderInner') }}">
        <a href="{{ route('home') }}" class="{{ cls('bannerMark') }}">
            <span class="{{ cls('brandSlash') }}" aria-hidden="true">/</span>
            <span class="{{ cls('brandName') }}">{{ $brand }}</span>
            <span class="{{ cls('bannerVer') }}">v1.0</span>
        </a>

        <span class="{{ cls('bannerSpacer') }}" aria-hidden="true"></span>

        @include('partials.theme-switcher')

        @if ($rightHref)
            <a href="{{ $rightHref }}" class="{{ cls('headerLink') }}">
                {{ $rightLabel }} <span class="accent-fg {{ cls('headerLinkStrong') }}">{{ $rightText }}</span>
            </a>
        @endif
    </div>
</header>