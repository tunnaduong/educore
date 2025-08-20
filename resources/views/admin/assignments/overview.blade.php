<x-layouts.dash-admin active="assignments" title="Tổng quan bài tập">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('assignments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle mr-2"></i>Giao bài tập mới
        </a>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-chart-bar mr-2"></i>Tổng quan bài tập
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <select wire:model.live="selectedMonth" class="form-control mr-2"
                                    style="max-width: 150px;">
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
                                    <h6 class="card-title mb-0">Tổng bài tập</h6>
                                    <h3 class="mb-0">{{ $overviewStats['total_assignments'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-alt fa-2x"></i>
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
                                    <h6 class="card-title mb-0">Lớp có bài tập</h6>
                                    <h3 class="mb-0">{{ $overviewStats['total_classes'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-graduation-cap fa-2x"></i>
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
                                    <h6 class="card-title mb-0">Tổng lượt nộp</h6>
                                    <h3 class="mb-0">{{ $overviewStats['total_submissions'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-upload fa-2x"></i>
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
                                    <h6 class="card-title mb-0">Tỷ lệ nộp bài</h6>
                                    <h3 class="mb-0">{{ $overviewStats['submission_rate'] ?? 0 }}%</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-percentage fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách lớp nhiều bài tập nhất -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-outline card-primary mb-4">
                        <div class="card-header">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-trophy mr-2"></i>Top 5 lớp có nhiều bài tập nhất
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($topClasses->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Lớp học</th>
                                                <th class="text-center">Số bài tập</th>
                                                <th class="text-center">Tỷ lệ nộp</th>
                                                <th class="text-center">Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topClasses as $classData)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-bold">
                                                                    {{ $classData['classroom']->name ?? '-' }}</div>
                                                                <small
                                                                    class="text-muted">{{ $classData['classroom']->level ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-primary">{{ $classData['total_assignments'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-secondary">-</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('assignments.list', ['classroomFilter' => $classData['classroom']->id ?? '']) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye mr-1"></i>Xem chi tiết
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có dữ liệu bài tập</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-chart-pie mr-2"></i>Thống kê theo tháng
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($monthlyStats->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Tháng</th>
                                                <th class="text-center">Bài tập</th>
                                                <th class="text-center">Nộp bài</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($monthlyStats as $stat)
                                                <tr>
                                                    <td>{{ $stat['month_name'] }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-primary">{{ $stat['assignments_count'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-info">{{ $stat['submissions_count'] }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small">Chưa có dữ liệu thống kê</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
