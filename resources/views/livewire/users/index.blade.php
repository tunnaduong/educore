<x-layouts.dash>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-people-fill me-2"></i>Quản lý người dùng
            </h4>
            <a href="{{ route('users.create') ?? '#' }}" wire:navigate class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Thêm người dùng
            </a>
        </div>

        <!-- Search Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="input-group">
                    <span class="input-group-text bg-light">
                        <i class="bi bi-search"></i>
                    </span>
                    <input wire:model.live="search" type="text" class="form-control"
                        placeholder="Tìm kiếm theo tên, số điện thoại hoặc email...">
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th class="text-center">Vai trò</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <i class="bi bi-person-circle fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $user->name }}</div>
                                                <small class="text-muted">{{ $user->phone }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'teacher' ? 'success' : 'info') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }}">
                                            {{ $user->is_active ? 'Hoạt động' : 'Không hoạt động' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a wire:navigate href="{{ route('users.edit', $user->id) ?? '#' }}"
                                                class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $user->id }}"
                                                class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">
                                                    Xác nhận xóa người dùng</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn xóa người dùng "{{ $user->name }}"? Hành động
                                                này không thể hoàn tác.
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <button type="button" class="btn btn-danger" id="confirmDelete"
                                                    wire:click="delete({{ $user->id }})"
                                                    data-bs-dismiss="modal">Xóa</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }}
                        trên tổng số {{ $users->total() ?? 0 }} người dùng
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading overlay -->
    <div wire:loading.delay.long wire:target="previousPage, nextPage, gotoPage"
        class="position-fixed top-0 start-0 w-100 h-100" style="z-index: 2000; background: rgba(0,0,0,0.3);">
        <div class="d-flex justify-content-center align-items-center h-100">
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem;" role="status">
                <span class="visually-hidden">Đang tải...</span>
            </div>
        </div>
    </div>
</x-layouts.dash>
