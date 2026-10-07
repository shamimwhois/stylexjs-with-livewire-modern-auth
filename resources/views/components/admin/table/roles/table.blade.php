@props(['roles'])

<div class="@stylex('tableCard')">
    <div class="@stylex('tableWrap')">
        <table class="@stylex('table')">
            <thead>
                <tr>
                    <th scope="col" class="@stylex('tableHead')">Role</th>
                    <th scope="col" class="@stylex('tableHead')">Key</th>
                    <th scope="col" class="@stylex('tableHead')">Users</th>
                    <th scope="col" class="@stylex('tableHead')">Permissions</th>
                    <th scope="col" class="@stylex('tableHead', 'tableHeadRight')">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($roles as $role)
                    <x-admin.table.roles.row :role="$role" :last="$loop->last" />
                @empty
                    <x-admin.table.roles.empty />
                @endforelse
            </tbody>
        </table>
    </div>
</div>