<x-layouts.dash-admin active="schedules">
    @include('components.language')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0 fs-4">{{ __('general.schedule_details') }}</h2>
            <div class="btn-group" role="group">
                <a href="{{ route('schedules.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back_to_schedules') }}
                </a>
                <a href="{{ route('schedules.edit', $classroom) }}" class="btn btn-warning">
                    <i class="bi bi-pencil mr-2"></i>{{ __('general.edit_schedule') }}
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin chính -->
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar3 mr-2 text-primary"></i>
                            {{ __('general.classroom_information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label
                                        class="form-label fw-bold text-muted">{{ __('general.classroom_name') }}</label>
                                    <p class="mb-0 fs-5">{{ $classroom->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">{{ __('general.level_grade') }}</label>
                                    <p class="mb-0">
                                        <span class="badge bg-info fs-6">{{ $classroom->level }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label
                                        class="form-label fw-bold text-muted">{{ __('general.assigned_teacher') }}</label>
                                    <p class="mb-0">
                                        @if ($classroom->teachers->count())
                                            <div class="mb-2">
                                                <i class="bi bi-person-circle mr-2"></i>
                                                {{ $classroom->teachers->pluck('name')->join(', ') }}
                                            </div>
                                        @else
                                            <span class="text-muted">{{ __('general.not_assigned') }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-muted">{{ __('general.status') }}</label>
                                    <p class="mb-0">
                                        <span class="badge {{ $this->getStatusBadgeClass($classroom->status) }} fs-6">
                                            {{ $this->getStatusText($classroom->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if ($classroom->notes)
                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted">{{ __('general.notes') }}</label>
                                <p class="mb-0">{{ $classroom->notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Lịch học chi tiết -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="bi bi-clock mr-2 text-success"></i>
                            {{ __('general.detailed_schedule') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($classroom->schedule && is_array($classroom->schedule))
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label
                                            class="form-label fw-bold text-muted">{{ __('general.study_days') }}</label>
                                        <div class="mt-2">
                                            @if (isset($classroom->schedule['days']) && is_array($classroom->schedule['days']))
                                                @foreach ($classroom->schedule['days'] as $day)
                                                    @php
                                                        $dayNames = [
                                                            'Monday' => 'Thứ 2',
                                                            'Tuesday' => 'Thứ 3',
                                                            'Wednesday' => 'Thứ 4',
                                                            'Thursday' => 'Thứ 5',
                                                            'Friday' => 'Thứ 6',
                                                            'Saturday' => 'Thứ 7',
                                                            'Sunday' => 'Chủ nhật',
                                                        ];
                                                    @endphp
                                                    <span
                                                        class="badge bg-primary mr-2 mb-2">{{ $dayNames[$day] ?? $day }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">Chưa có thông tin</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-muted">{{ __('general.study_time') }}</label>
                                        <p class="mb-0 fs-5">
                                            @if (isset($classroom->schedule['time']))
                                                <i class="bi bi-clock mr-2"></i>
                                                {{ $classroom->schedule['time'] }}
                                            @else
                                                <span class="text-muted">{{ __('general.no_schedule_info') }}</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                                <h6 class="mt-3 text-muted">{{ __('general.no_schedule') }}</h6>
                                <a href="{{ route('schedules.edit', $classroom) }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle mr-2"></i>Thêm lịch học
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Thống kê nhanh -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up mr-2 text-info"></i>
                            Thống kê nhanh
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Tổng số học viên</span>
                            <span class="badge bg-primary fs-6">{{ $classroom->students->count() }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Số buổi học/tuần</span>
                            <span class="badge bg-success fs-6">
                                {{ $classroom->schedule && isset($classroom->schedule['days']) ? count($classroom->schedule['days']) : 0 }}
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Thời lượng/buổi</span>
                            <span class="badge bg-warning fs-6">
                                @if ($classroom->schedule && isset($classroom->schedule['time']))
                                    @php
                                        $timeParts = explode(' - ', $classroom->schedule['time']);
                                        if (count($timeParts) === 2) {
                                            $start = \Carbon\Carbon::parse($timeParts[0]);
                                            $end = \Carbon\Carbon::parse($timeParts[1]);
                                            $duration = $start->diffInMinutes($end);
                                            echo $duration . ' phút';
                                        } else {
                                            echo 'N/A';
                                        }
                                    @endphp
                                @else
                                    N/A
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Danh sách học viên -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-people mr-2 text-success"></i>
                            Danh sách học viên
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($classroom->students->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach ($classroom->students->take(5) as $student)
                                    <div class="list-group-item d-flex align-items-center px-0">
                                        <i class="bi bi-person-circle mr-3 text-primary"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $student->name }}</h6>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if ($classroom->students->count() > 5)
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        Và {{ $classroom->students->count() - 5 }} học viên khác...
                                    </small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <i class="bi bi-people" style="font-size: 2rem; color: #6c757d;"></i>
                                <p class="text-muted mb-0 mt-2">Chưa có học viên nào</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
