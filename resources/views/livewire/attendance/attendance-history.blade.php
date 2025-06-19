<x-layouts.dash>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('classrooms.show', $classroom) }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại lớp học
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-calendar-week me-2"></i>Lịch sử điểm danh - {{ $classroom->name }}
            </h4>
        </div>

        <!-- Bộ lọc tháng/năm -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-funnel me-2"></i>Lọc theo tháng
                        </h5>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2 justify-content-end">
                            <select wire:model.live="selectedMonth" class="form-select" style="max-width: 150px;">
                                @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}">{{ $this->getMonthName($month) }}</option>
                                @endfor
                            </select>
                            <select wire:model.live="selectedYear" class="form-select" style="max-width: 120px;">
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
                                <h3 class="mb-0">{{ $monthlyStats['total_students'] }}</h3>
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
                                <h6 class="card-title mb-0">Tổng buổi có mặt</h6>
                                <h3 class="mb-0">{{ $monthlyStats['total_present'] }}</h3>
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
                                <h6 class="card-title mb-0">Tổng buổi vắng</h6>
                                <h3 class="mb-0">{{ $monthlyStats['total_absent'] }}</h3>
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
                                <h6 class="card-title mb-0">Tỷ lệ trung bình</h6>
                                <h3 class="mb-0">{{ $monthlyStats['average_rate'] }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng thống kê chi tiết -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-table me-2"></i>Thống kê điểm danh chi tiết -
                    {{ $this->getMonthName($selectedMonth) }} {{ $selectedYear }}
                </h5>
            </div>
            <div class="card-body">
                @if (empty($attendanceHistory))
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có dữ liệu điểm danh</h5>
                        <p class="text-muted">Chưa có dữ liệu điểm danh cho tháng
                            {{ $this->getMonthName($selectedMonth) }} {{ $selectedYear }}.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Học viên</th>
                                    <th class="text-center">Tổng buổi</th>
                                    <th class="text-center">Có mặt</th>
                                    <th class="text-center">Vắng</th>
                                    <th class="text-center">Tỷ lệ</th>
                                    <th class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceHistory as $data)
                                    <tr>
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
                                        <td class="text-center">
                                            <span class="badge bg-secondary">{{ $data['stats']['total_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $data['stats']['present_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-danger">{{ $data['stats']['absent_days'] }}</span>
                                        </td>
                                        <td class="text-center">
                                            @if ($data['stats']['attendance_rate'] >= 90)
                                                <span
                                                    class="badge bg-success">{{ $data['stats']['attendance_rate'] }}%</span>
                                            @elseif ($data['stats']['attendance_rate'] >= 70)
                                                <span
                                                    class="badge bg-warning">{{ $data['stats']['attendance_rate'] }}%</span>
                                            @else
                                                <span
                                                    class="badge bg-danger">{{ $data['stats']['attendance_rate'] }}%</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($data['stats']['attendance_rate'] >= 90)
                                                <span class="badge bg-success">Tốt</span>
                                            @elseif ($data['stats']['attendance_rate'] >= 70)
                                                <span class="badge bg-warning">Khá</span>
                                            @else
                                                <span class="badge bg-danger">Cần cải thiện</span>
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
</x-layouts.dash>
