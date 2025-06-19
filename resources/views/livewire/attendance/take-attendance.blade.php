<x-layouts.dash active="attendances">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('classrooms.show', $classroom) }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại lớp học
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-calendar-check me-2"></i>Điểm danh - {{ $classroom->name }}
            </h4>
        </div>

        <!-- Thống kê nhanh -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tổng học viên</h6>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Có mặt</h6>
                                <h3 class="mb-0">{{ $stats['present'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Vắng</h6>
                                <h3 class="mb-0">{{ $stats['absent'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tỷ lệ có mặt</h6>
                                <h3 class="mb-0">{{ $stats['presentPercentage'] }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form điểm danh -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-calendar-event me-2"></i>Điểm danh ngày
                            {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <input wire:model.live="selectedDate" type="date" class="form-control"
                                style="max-width: 200px;">
                            <button wire:click="saveAttendance" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Lưu điểm danh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (session()->has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (empty($attendanceData))
                    <div class="text-center py-5">
                        <i class="bi bi-people fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có học viên nào trong lớp</h5>
                        <p class="text-muted">Vui lòng thêm học viên vào lớp trước khi điểm danh.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Học viên</th>
                                    <th width="120">Trạng thái</th>
                                    <th>Lý do nghỉ</th>
                                    <th width="100">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceData as $index => $data)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-3">
                                                    <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ $data['student']->name }}</div>
                                                    <small class="text-muted">{{ $data['student']->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:click="toggleAttendance({{ $data['student_record']->id }})"
                                                    {{ $data['present'] ? 'checked' : '' }}
                                                    id="attendance_{{ $data['student_record']->id }}">
                                                <label class="form-check-label"
                                                    for="attendance_{{ $data['student_record']->id }}">
                                                    @if ($data['present'])
                                                        <span class="badge bg-success">Có mặt</span>
                                                    @else
                                                        <span class="badge bg-danger">Vắng</span>
                                                    @endif
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            @if (!$data['present'])
                                                @if ($data['reason'])
                                                    <span
                                                        class="text-muted">{{ Str::limit($data['reason'], 30) }}</span>
                                                @else
                                                    <span class="text-muted">Chưa có lý do</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$data['present'])
                                                <button wire:click="openReasonModal({{ $data['student_record']->id }})"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-pencil me-1"></i>Lý do
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal nhập lý do nghỉ -->
    @if ($showReasonModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle me-2"></i>Lý do nghỉ học
                        </h5>
                        <button type="button" class="btn-close"
                            wire:click="$set('showReasonModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="absenceReason" class="form-label">Lý do nghỉ học</label>
                            <textarea wire:model="absenceReason" class="form-control" id="absenceReason" rows="3"
                                placeholder="Nhập lý do nghỉ học..."></textarea>
                            @error('absenceReason')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showReasonModal', false)">
                            Hủy
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="saveReason">
                            <i class="bi bi-check-circle me-2"></i>Lưu lý do
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash>
