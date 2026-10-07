@props(['users'])

<div class="@stylex('tableCard')">
    <div class="@stylex('tableWrap')">
        <table class="@stylex('table')">
            <thead>
                <tr>
                    <th scope="col" class="@stylex('tableHead')">User</th>
                    <th scope="col" class="@stylex('tableHead')">Email</th>
                    <th scope="col" class="@stylex('tableHead')">Roles</th>
                    <th scope="col" class="@stylex('tableHead')">Permissions</th>
                    <th scope="col" class="@stylex('tableHead')">Joined</th>
                    <th scope="col" class="@stylex('tableHead', 'tableHeadRight')">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <x-admin.table.userlist.row :user="$user" :last="$loop->last" />
                @empty
                    <x-admin.table.userlist.empty />
                @endforelse
            </tbody>
        </table>
    </div>
</div>