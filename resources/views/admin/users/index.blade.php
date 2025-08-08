<x-layouts.dash-admin>
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-people-fill mr-2"></i>Quản lý người dùng
            </h4>
            <a href="{{ route('users.create') ?? '#' }}" wire:navigate class="btn btn-primary">
                <i class="bi bi-plus-circle mr-2"></i>Thêm người dùng
            </a>
        </div>

        <!-- Search Bar & Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Tìm kiếm theo tên, số điện thoại hoặc email...">
                        </div>
                    </div>
                    <div class="col">
                        <select wire:model.live="filterRole" class="form-control">
                            <option value="">Tất cả vai trò</option>
                            <option value="admin">Quản trị viên</option>
                            <option value="teacher">Giảng viên</option>
                            <option value="student">Học viên</option>
                        </select>
                    </div>
                    <div class="col">
                        <select wire:model.live="filterStatus" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill mr-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

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
                                            <div class="avatar-sm mr-3">
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
                                            <button type="button" data-toggle="modal"
                                                data-target="#deleteModal{{ $user->id }}"
                                                class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1" role="dialog"
                                    aria-labelledby="deleteModalLabel{{ $user->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $user->id }}">
                                                    <i class="bi bi-exclamation-triangle text-danger mr-2"></i>
                                                    Xác nhận xóa người dùng
                                                </h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="text-center mb-3">
                                                    <i class="bi bi-person-x text-danger" style="font-size: 3rem;"></i>
                                                </div>
                                                <p class="text-center">
                                                    Bạn có chắc chắn muốn xóa người dùng <strong>"{{ $user->name }}"</strong>?
                                                </p>
                                                <div class="alert alert-warning">
                                                    <i class="bi bi-exclamation-triangle mr-2"></i>
                                                    <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác và sẽ xóa tất cả dữ liệu liên quan.
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                    <i class="bi bi-x-circle mr-1"></i>Hủy
                                                </button>
                                                <button type="button" class="btn btn-danger" 
                                                    wire:click="delete({{ $user->id }})"
                                                    data-dismiss="modal">
                                                    <i class="bi bi-trash mr-1"></i>Xóa người dùng
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $users->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
