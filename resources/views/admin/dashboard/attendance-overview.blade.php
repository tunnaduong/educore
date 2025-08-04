<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-graph-up mr-2"></i>Thống kê điểm danh tổng quan
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
    <div class="card-body">
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
                                <h6 class="card-title mb-0">Lớp đang hoạt động</h6>
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
                                <h6 class="card-title mb-0">Tổng buổi có mặt</h6>
                                <h3 class="mb-0">{{ $overviewStats['total_present'] }}</h3>
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

        <!-- Top học viên và lớp -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-trophy mr-2"></i>Top 5 học viên điểm danh tốt nhất
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($overviewStats['top_students']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Học viên</th>
                                            <th class="text-center">Tỷ lệ</th>
                                            <th class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overviewStats['top_students'] as $student)
                                            <tr>
                                                <td>
                                                    <div class="fw-medium">{{ $student['student']->name }}</div>
                                                    <small
                                                        class="text-muted">{{ $student['present_days'] }}/{{ $student['total_days'] }}
                                                        buổi</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $student['rate'] }}%</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($student['rate'] >= 90)
                                                        <span class="badge bg-success">Tốt</span>
                                                    @elseif ($student['rate'] >= 70)
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
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Chưa có dữ liệu điểm danh</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-award mr-2"></i>Top 5 lớp điểm danh tốt nhất
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($overviewStats['top_classes']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Lớp học</th>
                                            <th class="text-center">Tỷ lệ</th>
                                            <th class="text-center">Trạng thái</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($overviewStats['top_classes'] as $class)
                                            <tr>
                                                <td>
                                                    <div class="fw-medium">{{ $class['classroom']->name }}</div>
                                                    <small
                                                        class="text-muted">{{ $class['present_days'] }}/{{ $class['total_days'] }}
                                                        buổi</small>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $class['rate'] }}%</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($class['rate'] >= 90)
                                                        <span class="badge bg-success">Tốt</span>
                                                    @elseif ($class['rate'] >= 70)
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
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-2"></i>
                                <p class="text-muted mb-0">Chưa có dữ liệu điểm danh</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
