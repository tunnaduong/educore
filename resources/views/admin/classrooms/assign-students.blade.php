<x-layouts.dash-admin active="classrooms">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('classrooms.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-people-fill mr-2"></i>Gán học viên cho lớp học
            </h4>
            <p class="text-muted mb-0">{{ $classroom->name }}</p>
        </div>

        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <!-- Danh sách học viên có sẵn -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="bi bi-person-plus mr-2"></i>Danh sách học viên có sẵn
                            </h5>
                            <div class="d-flex gap-2">
                                <button wire:click="selectAll" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-check-all mr-1"></i>Chọn tất cả
                                </button>
                                <button wire:click="deselectAll" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle mr-1"></i>Bỏ chọn tất cả
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search Bar -->
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input wire:model.live="search" type="text" class="form-control"
                                    placeholder="Tìm kiếm học viên theo tên, email hoặc số điện thoại...">
                            </div>
                        </div>

                        <!-- Students List -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">
                                            @php
                                                $availableStudentIds = collect($availableStudents->items())
                                                    ->pluck('id')
                                                    ->toArray();
                                                $allSelected =
                                                    count($availableStudentIds) > 0 &&
                                                    count(array_intersect($availableStudentIds, $selectedStudents)) ==
                                                        count($availableStudentIds);
                                            @endphp
                                            <input type="checkbox" class="form-check-input" wire:click="toggleSelectAll"
                                                wire:key="select-all-{{ count($selectedStudents) }}"
                                                {{ $allSelected ? 'checked' : '' }}>
                                        </th>
                                        <th>Học viên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th class="text-center">Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($availableStudents as $student)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" class="form-check-input"
                                                    wire:click="toggleStudent({{ $student->id }})"
                                                    wire:key="student-{{ $student->id }}-{{ in_array($student->id, $selectedStudents) ? 'selected' : 'unselected' }}"
                                                    {{ in_array($student->id, $selectedStudents) ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm mr-3">
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
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone ?? 'Chưa có' }}</td>
                                            <td class="text-center">
                                                @php
                                                    $statusColors = [
                                                        'active' => 'success',
                                                        'paused' => 'warning',
                                                        'dropped' => 'danger',
                                                    ];
                                                    $statusLabels = [
                                                        'active' => 'Hoạt động',
                                                        'paused' => 'Nghỉ',
                                                        'dropped' => 'Bảo lưu',
                                                    ];
                                                    $color = $statusColors[$student->status] ?? 'secondary';
                                                    $label = $statusLabels[$student->status] ?? 'Không xác định';
                                                @endphp
                                                <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
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
                        @if ($availableStudents->hasPages())
                            <div class="d-flex justify-content-center mt-3">
                                {{ $availableStudents->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Danh sách học viên đã gán -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-people mr-2"></i>Tổng quan học viên
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Học viên đã gán (vẫn được giữ lại) -->
                        @php
                            $enrolledStudentsToKeep = $enrolledStudents->filter(function ($student) use (
                                $selectedStudents,
                            ) {
                                return in_array($student->id, $selectedStudents);
                            });
                        @endphp

                        @if ($enrolledStudentsToKeep->count() > 0)
                            <h6 class="text-success mb-3">
                                <i class="bi bi-check-circle mr-2"></i>Học viên đã gán
                                ({{ $enrolledStudentsToKeep->count() }})
                            </h6>
                            <div class="list-group list-group-flush mb-4">
                                @foreach ($enrolledStudentsToKeep as $student)
                                    <div class="list-group-item d-flex align-items-center px-0">
                                        <div class="avatar-sm mr-3">
                                            <i class="bi bi-person-circle fs-4 text-success"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                        <span class="badge bg-success">Đã gán</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Học viên chưa gán (đang được chọn để thêm mới) -->
                        @php
                            $selectedStudentsNotEnrolled = collect($availableStudents->items())->filter(function (
                                $student,
                            ) use ($selectedStudents, $enrolledStudents) {
                                return in_array($student->id, $selectedStudents) &&
                                    !$enrolledStudents->contains('id', $student->id);
                            });
                        @endphp

                        @if ($selectedStudentsNotEnrolled->count() > 0)
                            <h6 class="text-primary mb-3">
                                <i class="bi bi-plus-circle mr-2"></i>Học viên sẽ thêm mới
                                ({{ $selectedStudentsNotEnrolled->count() }})
                            </h6>
                            <div class="list-group list-group-flush mb-4">
                                @foreach ($selectedStudentsNotEnrolled as $student)
                                    <div class="list-group-item d-flex align-items-center px-0">
                                        <div class="avatar-sm mr-3">
                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                        <span class="badge bg-primary">Sẽ thêm</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Học viên chuẩn bị xóa (đã gán nhưng bị bỏ chọn) -->
                        @php
                            $enrolledStudentsToRemove = $enrolledStudents->filter(function ($student) use (
                                $selectedStudents,
                            ) {
                                return !in_array($student->id, $selectedStudents);
                            });
                        @endphp

                        @if ($enrolledStudentsToRemove->count() > 0)
                            <h6 class="text-danger mb-3">
                                <i class="bi bi-trash mr-2"></i>Học viên sẽ bị xóa
                                ({{ $enrolledStudentsToRemove->count() }})
                            </h6>
                            <div class="list-group list-group-flush mb-4">
                                @foreach ($enrolledStudentsToRemove as $student)
                                    <div class="list-group-item d-flex align-items-center px-0">
                                        <div class="avatar-sm mr-3">
                                            <i class="bi bi-person-circle fs-4 text-danger"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $student->name }}</div>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                        <span class="badge bg-danger">Sẽ xóa</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Thông báo khi không có thay đổi -->
                        @if (
                            $enrolledStudentsToKeep->count() == 0 &&
                                $selectedStudentsNotEnrolled->count() == 0 &&
                                $enrolledStudentsToRemove->count() == 0)
                            <div class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    Chưa có học viên nào được gán
                                </div>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <button wire:click="assignStudents" class="btn btn-primary w-100"
                                {{ empty($selectedStudents) && $enrolledStudents->count() == 0 ? 'disabled' : '' }}>
                                <i class="bi bi-check-circle mr-2"></i>
                                Cập nhật danh sách học viên
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Trùng Lịch -->
    @if ($showConflictModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                            Phát hiện trùng lịch học!
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeConflictModal"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                            <strong>Cảnh báo:</strong> Một số học sinh đã được chọn có lịch học trùng với lớp này.
                            Vui lòng xem xét lại trước khi tiếp tục.
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-danger mb-3">
                                    <i class="bi bi-people-fill mr-2"></i>
                                    Danh sách học sinh trùng lịch ({{ count($scheduleConflicts) }} học sinh)
                                </h6>
                            </div>
                        </div>

                        @foreach ($scheduleConflicts as $studentId => $conflictData)
                            <div class="card mb-3 border-warning">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-danger">
                                        <i class="bi bi-person-circle mr-2"></i>
                                        {{ $conflictData['student']->name }}
                                        <small class="text-muted">({{ $conflictData['student']->email }})</small>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong class="text-primary">Lớp hiện tại:</strong>
                                            <div class="mt-2" style="max-height: 150px; overflow-y: auto;">
                                                @foreach ($conflictData['conflicts'] as $conflict)
                                                    <div class="border-start border-primary ps-3 mb-2">
                                                        <div class="d-flex align-items-start">
                                                            <i class="bi bi-calendar-event text-primary mr-2 mt-1"></i>
                                                            <div class="flex-grow-1">
                                                                <strong>{{ $conflict['classroom']->name }}</strong><br>
                                                                <small class="text-muted">
                                                                    {{ $conflict['message'] }}
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <strong class="text-success">Lớp mới:</strong>
                                            <div class="mt-2">
                                                <div class="d-flex align-items-start">
                                                    <i class="bi bi-calendar-event text-success mr-2 mt-1"></i>
                                                    <div class="flex-grow-1">
                                                        <strong>{{ $classroom->name }}</strong><br>
                                                        <small class="text-muted">
                                                            @if ($classroom->schedule)
                                                                {{ implode(', ', $classroom->schedule['days'] ?? []) }}
                                                                -
                                                                {{ $classroom->schedule['time'] ?? '' }}
                                                            @else
                                                                Chưa có lịch học
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if (count($scheduleConflicts) > 5)
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle mr-2"></i>
                                <strong>Lưu ý:</strong> Có {{ count($scheduleConflicts) }} học sinh trùng lịch.
                                Bạn có thể scroll để xem tất cả chi tiết.
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeConflictModal">
                            <i class="bi bi-x-circle mr-2"></i>
                            Hủy bỏ
                        </button>
                        <button type="button" class="btn btn-warning" wire:click="forceAssignStudents">
                            <i class="bi bi-exclamation-triangle mr-2"></i>
                            Gán bất chấp trùng lịch
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-admin>
