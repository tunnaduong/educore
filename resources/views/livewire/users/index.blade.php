<x-layouts.dash>
    <div>
        <input wire:model="search" placeholder="Tìm kiếm người dùng..." class="form-input mb-4 w-full">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}">Sửa</a>
                            <button wire:click="delete({{ $user->id }})">Xoá</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $users->links() }}
    </div>
</x-layouts.dash>
