<x-layouts.dash-admin active="classrooms">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('classrooms.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách lớp học
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-mortarboard me-2"></i>Chi tiết lớp học
            </h4>
            <p class="text-muted mb-0">{{ $classroom->name }}</p>
        </div>

        <div class="row">
            <!-- Thông tin lớp học -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle me-2"></i>Thông tin lớp học
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <i class="bi bi-mortarboard fs-1 text-primary"></i>
                            </div>
                            <h5 class="mb-1">{{ $classroom->name }}</h5>
                            <p class="text-muted mb-0">{{ $classroom->level }}</p>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Giảng viên</label>
                                    <div class="fw-medium">{{ $classroom->teacher?->name ?? 'Chưa có' }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Trạng thái</label>
                                    <div>
                                        <span
                                            class="badge bg-{{ $classroom->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $classroom->status == 'active' ? 'Đang hoạt động' : 'Đã kết thúc' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Số học viên</label>
                                    <div class="fw-medium">{{ $classroom->students->count() }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Ngày tạo</label>
                                    <div class="fw-medium">{{ $classroom->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        @if ($classroom->schedule)
                            <div class="mb-3">
                                <label class="form-label text-muted small">Lịch học</label>
                                <div class="fw-medium">
                                    {{ $this->formatSchedule($classroom->schedule) }}
                                </div>
                            </div>
                        @endif

                        @if ($classroom->notes)
                            <div class="mb-3">
                                <label class="form-label text-muted small">Ghi chú</label>
                                <div class="fw-medium">{{ $classroom->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Thống kê nhanh -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-graph-up me-2"></i>Thống kê nhanh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-primary">{{ $classroom->students->count() }}</div>
                                    <div class="small text-muted">Học viên</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-success">{{ $classroom->attendances->count() }}</div>
                                    <div class="small text-muted">Tổng lượt điểm danh</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách học viên -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="bi bi-people me-2"></i>Danh sách học viên
                            </h5>
                            <a href="{{ route('classrooms.assign-students', $classroom) }}" wire:navigate
                                class="btn btn-sm btn-primary">
                                <i class="bi bi-person-plus me-2"></i>Gán học viên
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($classroom->students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Học viên</th>
                                            <th>Email</th>
                                            <th>Số điện thoại</th>
                                            <th class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classroom->students as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm me-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->name }}</div>
                                                            @if ($student->studentProfile)
                                                                <small
                                                                    class="text-muted">{{ $student->studentProfile->level ?? 'Chưa có trình độ' }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->email ?? 'Chưa có' }}</td>
                                                <td>{{ $student->phone ?? 'Chưa có' }}</td>
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
                                                            $color =
                                                                $statusColors[$student->studentProfile->status] ??
                                                                'secondary';
                                                            $label =
                                                                $statusLabels[$student->studentProfile->status] ??
                                                                'Không xác định';
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $color }}">{{ $label }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">Chưa có</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-people fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có học viên nào</h5>
                                <p class="text-muted">Vui lòng gán học viên vào lớp học.</p>
                                <a href="{{ route('classrooms.assign-students', $classroom) }}" wire:navigate
                                    class="btn btn-primary">
                                    <i class="bi bi-person-plus me-2"></i>Gán học viên
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Các hành động -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-gear me-2"></i>Hành động
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.attendance', $classroom) }}" wire:navigate
                                    class="btn btn-outline-primary w-100">
                                    <i class="bi bi-calendar-check me-2"></i>Điểm danh
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.attendance-history', $classroom) }}" wire:navigate
                                    class="btn btn-outline-info w-100">
                                    <i class="bi bi-calendar-week me-2"></i>Lịch sử điểm danh
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.assign-students', $classroom) }}" wire:navigate
                                    class="btn btn-outline-success w-100">
                                    <i class="bi bi-person-plus me-2"></i>Gán học viên
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.edit', $classroom) }}" wire:navigate
                                    class="btn btn-outline-warning w-100">
                                    <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
