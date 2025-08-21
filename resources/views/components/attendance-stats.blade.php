<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0 text-primary">
                    <i class="bi bi-calendar-check mr-2"></i>{{ __('general.attendance_statistics') }}
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
                                <h6 class="card-title mb-0">{{ __('general.total_sessions') }}</h6>
                                <h3 class="mb-0">{{ $totalStats['total_days'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-calendar-event fs-1"></i>
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
                                <h6 class="card-title mb-0">{{ __('general.present') }}</h6>
                                <h3 class="mb-0">{{ $totalStats['present_days'] }}</h3>
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
                                <h6 class="card-title mb-0">{{ __('general.absent') }}</h6>
                                <h3 class="mb-0">{{ $totalStats['absent_days'] }}</h3>
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
                                <h6 class="card-title mb-0">{{ __('general.attendance_rate') }}</h6>
                                <h3 class="mb-0">{{ $totalStats['attendance_rate'] }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê theo từng lớp -->
        @if (empty($attendanceStats))
            <div class="text-center py-4">
                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                <h6 class="text-muted">{{ __('general.no_attendance_data') }}</h6>
                <p class="text-muted small">{{ __('general.no_attendance_data_month', ['month' => $this->getMonthName($selectedMonth), 'year' => $selectedYear]) }}</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('general.classroom') }}</th>
                            <th class="text-center">{{ __('general.total_count') }}</th>
                            <th class="text-center">{{ __('general.present') }}</th>
                            <th class="text-center">{{ __('general.absent') }}</th>
                            <th class="text-center">{{ __('general.rate') }}</th>
                            <th class="text-center">{{ __('general.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendanceStats as $stat)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $stat['classroom']->name }}</div>
                                    <small class="text-muted">{{ $stat['classroom']->level }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $stat['total_days'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $stat['present_days'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $stat['absent_days'] }}</span>
                                </td>
                                <td class="text-center">
                                    @if ($stat['attendance_rate'] >= 90)
                                        <span class="badge bg-success">{{ $stat['attendance_rate'] }}%</span>
                                    @elseif ($stat['attendance_rate'] >= 70)
                                        <span class="badge bg-warning">{{ $stat['attendance_rate'] }}%</span>
                                    @else
                                        <span class="badge bg-danger">{{ $stat['attendance_rate'] }}%</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($stat['attendance_rate'] >= 90)
                                        <span class="badge bg-success">{{ __('general.good') }}</span>
                                    @elseif ($stat['attendance_rate'] >= 70)
                                        <span class="badge bg-warning">{{ __('general.fair') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('general.needs_improvement') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Chi tiết điểm danh -->
            <div class="mt-4">
                <h6 class="text-primary mb-3">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('general.attendance_details') }}
                </h6>
                @foreach ($attendanceStats as $stat)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">{{ $stat['classroom']->name }}</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>{{ __('general.date') }}</th>
                                            <th>{{ __('general.status') }}</th>
                                            <th>{{ __('general.absence_reason') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($stat['attendances'] as $attendance)
                                            <tr>
                                                <td>
                                                    <div class="fw-medium">{{ $attendance->date->format('d/m/Y') }}
                                                    </div>
                                                    <small
                                                        class="text-muted">{{ $attendance->date->format('l') }}</small>
                                                </td>
                                                <td>
                                                    @if ($attendance->present)
                                                        <span class="badge bg-success">{{ __('general.present') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ __('general.absent') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if (!$attendance->present && $attendance->reason)
                                                        <span class="text-muted">{{ $attendance->reason }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">
                                                    {{ __('general.no_attendance_records') }}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
