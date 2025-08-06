<x-layouts.dash-admin>
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('students.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-person-circle me-2"></i>Chi tiết học viên
                    </h4>
                    <p class="text-muted mb-0">{{ $student->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('students.edit', $student) }}" wire:navigate class="btn btn-primary">
                        <i class="bi bi-pencil-square me-2"></i>Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin cơ bản -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-person me-2"></i>Thông tin cá nhân
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="avatar-lg mx-auto mb-3">
                                <i class="bi bi-person-circle fs-1 text-primary"></i>
                            </div>
                            <h5 class="mb-1">{{ $student->name }}</h5>
                            <p class="text-muted mb-0">{{ $student->email }}</p>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Số điện thoại</label>
                                    <div class="fw-medium">{{ $student->phone ?? 'Chưa có' }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Ngày sinh</label>
                                    <div class="fw-medium">
                                        @if ($student->studentProfile && $student->studentProfile->date_of_birth)
                                            {{ \Carbon\Carbon::parse($student->studentProfile->date_of_birth)->format('d/m/Y') }}
                                        @else
                                            Chưa có
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Ngày vào học</label>
                                    <div class="fw-medium">
                                        @if ($student->studentProfile && $student->studentProfile->joined_at)
                                            {{ \Carbon\Carbon::parse($student->studentProfile->joined_at)->format('d/m/Y') }}
                                        @else
                                            Chưa có
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Trạng thái</label>
                                    <div>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($student->studentProfile && $student->studentProfile->level)
                            <div class="mb-3">
                                <label class="form-label text-muted small">Trình độ</label>
                                <div class="fw-medium">{{ $student->studentProfile->level }}</div>
                            </div>
                        @endif

                        @if ($student->studentProfile && $student->studentProfile->notes)
                            <div class="mb-3">
                                <label class="form-label text-muted small">Ghi chú</label>
                                <div class="fw-medium">{{ $student->studentProfile->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Thông tin học tập -->
            <div class="col-lg-8">
                <!-- Lớp đang học -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-mortarboard me-2"></i>Lớp đang học
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($student->enrolledClassrooms->count() > 0)
                            <div class="row">
                                @foreach ($student->enrolledClassrooms as $classroom)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0">{{ $classroom->name }}</h6>
                                                <span
                                                    class="badge bg-{{ $classroom->status == 'active' ? 'success' : 'secondary' }}">
                                                    {{ $classroom->status == 'active' ? 'Đang hoạt động' : 'Đã kết thúc' }}
                                                </span>
                                            </div>
                                            <div class="small text-muted">
                                                <div><i
                                                        class="bi bi-person me-1"></i>{{ $classroom->getFirstTeacher()?->name ?? 'Chưa có giáo viên' }}
                                                </div>
                                                <div><i
                                                        class="bi bi-calendar me-1"></i>{{ $classroom->level ?? 'Chưa có trình độ' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-mortarboard fs-1 d-block mb-2"></i>
                                    Chưa đăng ký lớp học nào
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tiến độ học tập -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-graph-up me-2"></i>Tiến độ học tập
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-primary">0</div>
                                    <div class="small text-muted">Buổi học</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-success">-</div>
                                    <div class="small text-muted">Điểm TB</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-info">0</div>
                                    <div class="small text-muted">Bài tập hoàn thành</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-warning">0%</div>
                                    <div class="small text-muted">Tỷ lệ tham gia</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê điểm danh -->
                @livewire('components.attendance-stats', ['student' => $student])

                <!-- Lịch sử bài tập -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-journal-check me-2"></i>Lịch sử bài tập
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-journal-check fs-1 d-block mb-2"></i>
                                Chưa có lịch sử bài tập
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
