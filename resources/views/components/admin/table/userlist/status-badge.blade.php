@props(['user' => null])

@if ($user->isBlocked())
    <span class="@stylex('badge', 'badgeDestructive')">
        <x-lucide-ban class="@stylex('iconXs', 'iconStroke')" aria-hidden="true" />
        Blocked
    </span>
@elseif ($user->isSuspended())
    <span class="@stylex('badge', 'badgeWarning')">
        <x-lucide-pause class="@stylex('iconXs', 'iconStroke')" aria-hidden="true" />
        Suspended
    </span>
@endif

@if ($user->email_verified_at)
    <span class="@stylex('badge', 'badgeSuccess')">
        <x-lucide-shield-check class="@stylex('iconXs', 'iconStroke')" aria-hidden="true" />
        Verified
    </span>
@else
    <span class="@stylex('badge', 'badgeWarning')">
        <x-lucide-mail class="@stylex('iconXs', 'iconStroke')" aria-hidden="true" />
        Unverified
    </span>
@endif