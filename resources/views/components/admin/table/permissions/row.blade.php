@props([
    'permission' => null,
    'last' => false,
])

<tr class="@stylex('tbodyRow', $last ? 'tbodyRowLast' : null)" x-data="{ deleteOpen: false }">
    <td class="@stylex('tableCell')">
        <div class="@stylex('cellName')">{{ $permission->name }}</div>
    </td>

    <td class="@stylex('tableCell')">
        <code class="@stylex('cellUsername')">{{ $permission->slug }}</code>
    </td>

    <td class="@stylex('tableCell')">
        <div class="@stylex('roleCell')">
            @forelse ($permission->roles as $role)
                <x-admin.table.role-badge :slug="$role->slug" :name="$role->name" />
            @empty
                <span class="@stylex('textMutedDim')">—</span>
            @endforelse
        </div>
    </td>

    <td class="@stylex('tableCell')">
        <time class="@stylex('cellEmail')" datetime="{{ $permission->created_at?->toIso8601String() }}">
            {{ $permission->created_at?->diffForHumans() }}
        </time>
    </td>

    <td class="@stylex('tableCell', 'tableCellRight')">
        <div class="@stylex('rowActions')">
            <a href="{{ route('admin.permissions.edit', $permission) }}" class="@stylex('actionBtn')" aria-label="Edit {{ $permission->name }}">
                <x-lucide-edit class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            </a>

            <button
                type="button"
                class="@stylex('actionBtn', 'actionBtnDanger')"
                @click="deleteOpen = true"
                aria-label="Delete {{ $permission->name }}"
            >
                <x-lucide-trash-2 class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            </button>
        </div>
    </td>

    <x-admin.table.confirm-delete
        :dialogId="'delete-permission-dialog-'.$permission->id"
        title="Delete permission"
        :text="'Deleting \''.$permission->name.'\' removes it from every role that grants it.'"
        confirmLabel="Delete permission"
        :wireClick="'delete('.$permission->id.')'"
    />
</tr>