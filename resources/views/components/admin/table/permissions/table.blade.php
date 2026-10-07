@props(['permissions'])

<div class="@stylex('tableCard')">
    <div class="@stylex('tableWrap')">
        <table class="@stylex('table')">
            <thead>
                <tr>
                    <th scope="col" class="@stylex('tableHead')">Permission</th>
                    <th scope="col" class="@stylex('tableHead')">Key</th>
                    <th scope="col" class="@stylex('tableHead')">Granted to</th>
                    <th scope="col" class="@stylex('tableHead')">Created</th>
                    <th scope="col" class="@stylex('tableHead', 'tableHeadRight')">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($permissions as $permission)
                    <x-admin.table.permissions.row :permission="$permission" :last="$loop->last" />
                @empty
                    <x-admin.table.permissions.empty />
                @endforelse
            </tbody>
        </table>
    </div>
</div>