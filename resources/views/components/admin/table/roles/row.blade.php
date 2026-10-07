@props([
    'role' => null,
    'last' => false,
])

<tr class="@stylex('tbodyRow', $last ? 'tbodyRowLast' : null)" x-data="{ deleteOpen: false }">
    <td class="@stylex('tableCell')">
        <x-admin.table.role-badge :slug="$role->slug" :name="$role->name" />
    </td>

    <td class="@stylex('tableCell')">
        <code class="@stylex('cellUsername')">{{ $role->slug }}</code>
    </td>

    <td class="@stylex('tableCell')">
        <span class="@stylex('cellEmail')">{{ $role->users_count }}</span>
    </td>

    <td class="@stylex('tableCell')">
        <span class="@stylex('cellEmail')">{{ $role->permissions_count }}</span>
    </td>

    <td class="@stylex('tableCell', 'tableCellRight')">
        <div class="@stylex('rowActions')">
            <a href="{{ route('admin.roles.edit', $role) }}" class="@stylex('actionBtn')" aria-label="Edit {{ $role->name }}">
                <x-lucide-edit class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
            </a>

            @if ($role->slug !== 'admin')
                <button
                    type="button"
                    class="@stylex('actionBtn', 'actionBtnDanger')"
                    @click="deleteOpen = true"
                    aria-label="Delete {{ $role->name }}"
                >
                    <x-lucide-trash-2 class="@stylex('iconSm', 'iconStroke')" aria-hidden="true" />
                </button>
            @endif
        </div>
    </td>

    @if ($role->slug !== 'admin')
        <x-admin.table.confirm-delete
            :dialogId="'delete-role-dialog-'.$role->id"
            title="Delete role"
            :text="'Deleting \''.$role->name.'\' permanently removes it. Users currently assigned this role cannot exist while it is in use.'"
            confirmLabel="Delete role"
            :wireClick="'delete('.$role->id.')'"
        />
    @endif
</tr>