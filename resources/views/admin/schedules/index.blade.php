<x-layouts.dash-admin active="schedules">
    @include('components.language')

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="d-flex align-items-center fs-4 text-primary mb-0">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                Lịch học
            </h2>
            <a wire:navigate href="{{ route('schedules.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Thêm lịch học
            </a>
        </div>

        <!-- Bộ lọc -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Tìm kiếm lớp học</label>
                        <input type="text" wire:model.live="search" class="form-control" id="search"
                            placeholder="Nhập tên lớp học...">
                    </div>
                    <div class="col-md-3">
                        <label for="filterLevel" class="form-label">Cấp độ</label>
                        <select wire:model.live="filterLevel" class="form-select" id="filterLevel">
                            <option value="">Tất cả cấp độ</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterTeacher" class="form-label">Giáo viên</label>
                        <select wire:model.live="filterTeacher" class="form-select" id="filterTeacher">
                            <option value="">Tất cả giáo viên</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i>Làm mới
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng lịch học -->
        <div class="card">
            <div class="card-body">
                @if ($classrooms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Lớp học</th>
                                    <th>Cấp độ</th>
                                    <th>Giáo viên</th>
                                    <th>Lịch học</th>
                                    <th>Số học viên</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classrooms as $classroom)
                                    <tr>
                                        <td>
                                            <strong>{{ $classroom->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $classroom->level }}</span>
                                        </td>
                                        <td>
                                            @if ($classroom->teachers->count())
                                                <div class="mb-2">
                                                    <i class="bi bi-person-circle me-2"></i>
                                                    {{ $classroom->teachers->pluck('name')->join(', ') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar3 me-2 text-primary"></i>
                                                <span>{{ $this->formatSchedule($classroom->schedule) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $classroom->students->count() }} học
                                                viên</span>
                                        </td>
                                        <td>
                                            @if ($classroom->status === 'active')
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            @elseif($classroom->status === 'inactive')
                                                <span class="badge bg-warning">Tạm dừng</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $classroom->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-x-2">
                                                <a wire:navigate href="{{ route('schedules.show', $classroom) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a wire:navigate href="{{ route('schedules.edit', $classroom) }}"
                                                    class="btn btn-sm btn-outline-warning" title="Chỉnh sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $classrooms->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                        <h5 class="mt-3 text-muted">Không tìm thấy lịch học nào</h5>
                        <p class="text-muted">Hãy thử thay đổi bộ lọc hoặc tạo lịch học mới</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
