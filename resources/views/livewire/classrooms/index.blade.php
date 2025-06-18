<x-layouts.dash>
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-mortarboard-fill me-2"></i>Quản lý lớp học
            </h4>
            <a href="{{ route('classrooms.create') ?? '#' }}" wire:navigate class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Thêm lớp học
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
                        placeholder="Tìm kiếm theo tên lớp hoặc giảng viên...">
                </div>
            </div>
        </div>

        <!-- Classrooms Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Tên lớp</th>
                                <th>Giảng viên</th>
                                <th class="text-center">Số học viên</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classrooms as $index => $classroom)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <i class="bi bi-mortarboard fs-4"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $classroom->name }}</div>
                                                <small class="text-muted">{{ $classroom->notes }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $classroom->getFirstTeacher()?->name ?? 'Chưa có giáo viên' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $classroom->students_count }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $classroom->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $classroom->status == 'active' ? 'Đang hoạt động' : 'Đã kết thúc' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-end gap-2">
                                            <a wire:navigate
                                                href="{{ route('classrooms.edit', $classroom->id) ?? '#' }}"
                                                class="btn btn-sm btn-outline-primary" title="Sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button wire:click="delete({{ $classroom->id }})"
                                                class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Bạn có chắc chắn muốn xóa lớp học này?')"
                                                title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Hiển thị {{ $classrooms->firstItem() ?? 0 }} - {{ $classrooms->lastItem() ?? 0 }}
                        trên tổng số {{ $classrooms->total() ?? 0 }} lớp học
                    </div>
                    <div>
                        {{ $classrooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash>
