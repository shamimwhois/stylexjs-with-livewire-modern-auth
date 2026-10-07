@props([
    'user' => null,
    'last' => false,
])

<tr class="@stylex('tbodyRow', $last ? 'tbodyRowLast' : null)" x-data="{ deleteOpen: false, suspendOpen: false, blockOpen: false }">
    <td class="@stylex('tableCell')">
        <div class="@stylex('cellUser')">
            <span class="@stylex('avatarSm')" aria-hidden="true">{{ strtoupper(mb_substr($user->name, 0, 1)) }}</span>
            <div class="@stylex('minW0')">
                <div class="@stylex('cellName')">{{ $user->name }}</div>
                <div class="@stylex('cellUsername')">{{ $user->username }}</div>
            </div>
        </div>
    </td>

    <td class="@stylex('tableCell')">
        <div class="@stylex('cellEmail')">{{ $user->email }}</div>
        <div class="@stylex('mt1')">
            <x-admin.table.userlist.status-badge :user="$user" />
        </div>
    </td>

    <td class="@stylex('tableCell')">
        <div class="@stylex('roleCell')">
            @forelse ($user->roles as $role)
                <x-admin.table.role-badge :slug="$role->slug" :name="$role->name" />
            @empty
                <span class="@stylex('textMutedDim')">—</span>
            @endforelse
        </div>
    </td>

    <td class="@stylex('tableCell')">
        <div class="@stylex('roleCell')">
            @php
                $permissions = $user->roles
                    ->flatMap(fn ($role) => $role->permissions)
                    ->unique(fn ($permission) => $permission->slug)
                    ->sortBy('name');
            @endphp
            @forelse ($permissions as $permission)
                <x-admin.table.role-badge :slug="$permission->slug" :name="$permission->name" />
            @empty
                <span class="@stylex('textMutedDim')">—</span>
            @endforelse
        </div>
    </td>

    <td class="@stylex('tableCell')">
        <time class="@stylex('cellEmail')" datetime="{{ $user->created_at?->toIso8601String() }}">
            {{ $user->created_at?->diffForHumans() }}
        </time>
    </td>

    <td class="@stylex('tableCell', 'tableCellRight')">
        <div class="@stylex('rowActions')">
            <a href="{{ route('admin.users.edit', $user) }}" class="@stylex('actionBtn')" aria-label="Edit {{ $user->name }}">
                <x-lucide-edit class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            </a>

            @if ($user->getKey() !== auth()->id())
                @if ($user->isRestricted())
                    <button
                        type="button"
                        class="@stylex('actionBtn')"
                        wire:click="restore({{ $user->id }})"
                        wire:loading.attr="disabled"
                        aria-label="Restore {{ $user->name }}"
                    >
                        <x-lucide-rotate-ccw class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    </button>
                @else
                    <button
                        type="button"
                        class="@stylex('actionBtn')"
                        @click="suspendOpen = true"
                        aria-label="Suspend {{ $user->name }}"
                    >
                        <x-lucide-pause class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    </button>

                    <button
                        type="button"
                        class="@stylex('actionBtn', 'actionBtnDanger')"
                        @click="blockOpen = true"
                        aria-label="Block {{ $user->name }}"
                    >
                        <x-lucide-ban class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                    </button>
                @endif

                <button
                    type="button"
                    class="@stylex('actionBtn', 'actionBtnDanger')"
                    @click="deleteOpen = true"
                    aria-label="Delete {{ $user->name }}"
                >
                    <x-lucide-trash-2 class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                </button>
            @endif
        </div>
    </td>

    <x-admin.table.confirm-delete
        :dialogId="'suspend-user-dialog-'.$user->id"
        title="Suspend user"
        intro="The user will not be able to sign in."
        :text="'Are you sure you want to suspend '.$user->name.'? Their active sessions will be closed immediately.'"
        confirmLabel="Suspend user"
        :wireClick="'suspend('.$user->id.')'"
        openState="suspendOpen"
    />

    <x-admin.table.confirm-delete
        :dialogId="'block-user-dialog-'.$user->id"
        title="Block user"
        intro="This permanently removes access."
        :text="'Are you sure you want to permanently block '.$user->name.'? Their active sessions will be closed immediately.'"
        confirmLabel="Block user"
        :wireClick="'block('.$user->id.')'"
        openState="blockOpen"
    />

    <x-admin.table.confirm-delete
        :dialogId="'delete-user-dialog-'.$user->id"
        title="Delete user"
        :text="'Are you sure you want to permanently delete '.$user->name.' ('.$user->email.')?'"
        confirmLabel="Delete user"
        :wireClick="'delete('.$user->id.')'"
    />
</tr>