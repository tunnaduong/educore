<x-layouts.dash-admin active="students">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-people-fill me-2"></i>Quản lý học viên
            </h4>
            <a href="{{ route('students.create') ?? '#' }}" wire:navigate class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Thêm học viên
            </a>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Filter Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="Tìm kiếm theo tên, email, SĐT...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="statusFilter" class="form-select">
                            <option value="">Trạng thái</option>
                            <option value="active">Đang học</option>
                            <option value="paused">Nghỉ</option>
                            <option value="dropped">Bảo lưu</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="classroomFilter" class="form-select">
                            <option value="">Lớp học</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise me-1"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Học viên</th>
                                <th>Thông tin liên hệ</th>
                                <th>Lớp đang học</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Tiến độ</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $index => $student)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-3">
                                                <i class="bi bi-person-circle fs-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $student->name }}</div>
                                                <small class="text-muted">
                                                    @if ($student->studentProfile && $student->studentProfile->date_of_birth)
                                                        {{ \Carbon\Carbon::parse($student->studentProfile->date_of_birth)->format('d/m/Y') }}
                                                    @else
                                                        Chưa có ngày sinh
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="small">
                                                <i class="bi bi-envelope me-1"></i>{{ $student->email }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="bi bi-telephone me-1"></i>{{ $student->phone ?? 'Chưa có' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($student->enrolledClassrooms->count() > 0)
                                            @foreach ($student->enrolledClassrooms->take(2) as $classroom)
                                                <span class="badge bg-info me-1">{{ $classroom->name }}</span>
                                            @endforeach
                                            @if ($student->enrolledClassrooms->count() > 2)
                                                <span
                                                    class="badge bg-secondary">+{{ $student->enrolledClassrooms->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span class="text-muted small">Chưa đăng ký lớp</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($student->studentProfile)
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'paused' => 'warning',
                                                    'dropped' => 'danger',
                                                ];
                                                $statusLabels = [
                                                    'active' => 'Đang học',
                                                    'paused' => 'Nghỉ',
                                                    'dropped' => 'Bảo lưu',
                                                ];
                                                $color = $statusColors[$student->studentProfile->status] ?? 'secondary';
                                                $label =
                                                    $statusLabels[$student->studentProfile->status] ?? 'Không xác định';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                        @else
                                            <span class="badge bg-secondary">Chưa có</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="small">
                                            <div class="text-muted">Buổi học: <span class="fw-medium">0</span></div>
                                            <div class="text-muted">Điểm TB: <span class="fw-medium">-</span></div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('students.show', $student) }}" wire:navigate
                                                class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}" wire:navigate
                                                class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $student->id }}"
                                                class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">
                                                    Xác nhận xóa học viên</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Bạn có chắc chắn muốn xóa học viên "{{ $student->name }}"? Hành động
                                                này không thể hoàn tác.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <button type="button" class="btn btn-danger"
                                                    wire:click="delete({{ $student->id }})"
                                                    data-bs-dismiss="modal">Xóa</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                                            Không tìm thấy học viên nào
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Hiển thị {{ $students->firstItem() ?? 0 }} - {{ $students->lastItem() ?? 0 }}
                        trên tổng số {{ $students->total() ?? 0 }} học viên
                    </div>
                    <div>
                        {{ $students->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
