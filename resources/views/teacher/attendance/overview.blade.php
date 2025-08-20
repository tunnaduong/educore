<x-layouts.dash-teacher active="attendances">
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 text-primary fs-4">
                    <i class="bi bi-calendar-check mr-2"></i>Tổng quan điểm danh
                </h4>
                <p class="text-muted mb-0">Quản lý và theo dõi điểm danh học viên</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('teacher.my-class.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-mortarboard mr-2"></i>Lớp học của tôi
                </a>
            </div>
        </div>

        <!-- Bộ lọc tháng/năm -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-funnel mr-2"></i>Lọc theo tháng
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <select wire:model.live="selectedMonth" class="form-control" style="max-width: 150px;">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ $this->getMonthName($month) }}</option>
                                @endfor
                            </select>
                            <select wire:model.live="selectedYear" class="form-control" style="max-width: 120px;">
                                @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Tổng học viên</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_students'] }}</h3>
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
                                <h6 class="card-title mb-0">Lớp đang dạy</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_classes'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-mortarboard fs-1"></i>
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
                                <h6 class="card-title mb-0">Số lần điểm danh</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_attendance_days'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-event fs-1"></i>
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
                                <h6 class="card-title mb-0">Tỷ lệ trung bình</h6>
                                <h3 class="mb-0">{{ $overviewStats['attendance_rate'] }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê chi tiết -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Số lần có mặt</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_present'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title mb-0">Số lần vắng</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_absent'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách lớp học và nút điểm danh -->
        <div class="row">
            <div class="col-lg-8">
                <!-- Top lớp học -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-trophy mr-2"></i>Top 5 lớp học có điểm danh nhiều nhất
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topClasses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Lớp học</th>
                                            <th class="text-center">Tổng số</th>
                                            <th class="text-center">Có mặt</th>
                                            <th class="text-center">Tỷ lệ</th>
                                            <th class="text-center">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($topClasses as $classData)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-mortarboard fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $classData['classroom']->name }}
                                                            </div>
                                                            <small
                                                                class="text-muted">{{ $classData['classroom']->level }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-secondary">{{ $classData['total_days'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-success">{{ $classData['present_days'] }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($classData['attendance_rate'] >= 90)
                                                        <span
                                                            class="badge bg-success">{{ $classData['attendance_rate'] }}%</span>
                                                    @elseif($classData['attendance_rate'] >= 70)
                                                        <span
                                                            class="badge bg-warning">{{ $classData['attendance_rate'] }}%</span>
                                                    @else
                                                        <span
                                                            class="badge bg-danger">{{ $classData['attendance_rate'] }}%</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('teacher.attendance.take', $classData['classroom']) }}"
                                                            class="btn btn-sm btn-outline-primary" title="Điểm danh">
                                                            <i class="bi bi-calendar-check"></i>
                                                        </a>
                                                        <a href="{{ route('teacher.attendance.classroom-history', $classData['classroom']) }}"
                                                            class="btn btn-sm btn-outline-info" title="Lịch sử">
                                                            <i class="bi bi-calendar-week"></i>
                                                        </a>
                                                        <a href="{{ route('teacher.my-class.show', $classData['classroom']) }}"
                                                            class="btn btn-sm btn-outline-secondary" title="Chi tiết">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có dữ liệu điểm danh</h5>
                                <p class="text-muted">Chưa có dữ liệu điểm danh cho tháng
                                    {{ $this->getMonthName($selectedMonth) }} {{ $selectedYear }}.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Điểm danh gần đây -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-clock-history mr-2"></i>Điểm danh gần đây
                        </h5>
                        <a href="{{ route('teacher.attendance.history') }}"
                            class="btn btn-sm btn-outline-secondary float-end">
                            <i class="bi bi-calendar-week"></i> Lịch sử điểm danh
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($recentAttendances->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Ngày</th>
                                            <th>Lớp học</th>
                                            <th>Học viên</th>
                                            <th class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentAttendances as $attendance)
                                            <tr>
                                                <td>
                                                    <div class="fw-medium">{{ $attendance->date->format('d/m/Y') }}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $attendance->date->format('D') }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">
                                                        {{ $attendance->classroom?->name ?? 'N/A' }}</div>
                                                    <small
                                                        class="text-muted">{{ $attendance->classroom?->level ?? 'N/A' }}</small>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">
                                                        {{ $attendance->student?->user?->name ?? 'N/A' }}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $attendance->student?->user?->email ?? 'N/A' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    @if ($attendance->present)
                                                        <span class="badge bg-success">Có mặt</span>
                                                    @else
                                                        <span class="badge bg-danger">Vắng</span>
                                                        @if ($attendance->reason)
                                                            <br><small
                                                                class="text-muted">{{ $attendance->reason }}</small>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có điểm danh</h5>
                                <p class="text-muted">Chưa có dữ liệu điểm danh nào.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Top học viên -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-star mr-2"></i>Top 5 học viên xuất sắc
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($topStudents->count() > 0)
                            @foreach ($topStudents as $index => $studentData)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="mr-3">
                                        @if ($loop->index + 1 < 4)
                                            <span class="badge bg-warning">{{ $loop->index + 1 }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $loop->index + 1 }}</span>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-medium">{{ $studentData['student']->name }}</div>
                                        <small class="text-muted">{{ $studentData['student']->email }}</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success">{{ $studentData['attendance_rate'] }}%</div>
                                        <small
                                            class="text-muted">{{ $studentData['present_days'] }}/{{ $studentData['total_days'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-people fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Chưa có dữ liệu học viên</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Nút hành động nhanh -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-lightning mr-2"></i>Hành động nhanh
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('teacher.my-class.index') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-calendar-check mr-2"></i>Điểm danh theo lớp
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('teacher.attendance.history') }}"
                                    class="btn btn-outline-primary w-100">
                                    <i class="bi bi-calendar-week mr-2"></i>Lịch sử điểm danh
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
