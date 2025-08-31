<x-layouts.dash-admin active="classrooms">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('classrooms.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>@lang('general.back_to_classrooms_list')
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-mortarboard mr-2"></i>@lang('general.classroom_details')
            </h4>
            <p class="text-muted mb-0">{{ $classroom->name }}</p>
        </div>



        <div class="row">
            <!-- Thông tin lớp học -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle mr-2"></i>@lang('general.classroom_information')
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
                                    <label class="form-label text-muted small">@lang('general.teacher')</label>
                                    <div class="fw-medium">
                                        @if ($classroom->teachers->count())
                                            {{ $classroom->teachers->pluck('name')->join(', ') }}
                                        @else
                                            @lang('general.not_available')
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">@lang('general.status')</label>
                                    <div>
                                        <span
                                            class="badge bg-{{ $classroom->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $classroom->status == 'active' ? __('general.active') : __('general.completed') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">@lang('general.student_count')</label>
                                    <div class="fw-medium">{{ $classroom->students->count() }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">@lang('general.created_date')</label>
                                    <div class="fw-medium">{{ $classroom->created_at->format('d/m/Y') }}</div>
                                </div>
                            </div>
                        </div>

                        @if ($classroom->schedule)
                            <div class="mb-3">
                                <label class="form-label text-muted small">@lang('general.schedule')</label>
                                <div class="fw-medium">
                                    {{ $this->formatSchedule($classroom->schedule) }}
                                </div>
                            </div>
                        @endif

                        @if ($classroom->notes)
                            <div class="mb-3">
                                <label class="form-label text-muted small">@lang('general.notes')</label>
                                <div class="fw-medium">{{ $classroom->notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Thống kê nhanh -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-graph-up mr-2"></i>@lang('general.quick_statistics')
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-primary">{{ $classroom->students->count() }}</div>
                                    <div class="small text-muted">@lang('general.students')</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-success">{{ $classroom->attendances->count() }}</div>
                                    <div class="small text-muted">@lang('general.total_attendances')</div>
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
                                <i class="bi bi-people mr-2"></i>@lang('general.student_list')
                            </h5>
                            <a href="{{ route('classrooms.assign-students', $classroom) }}"
                                class="btn btn-sm btn-primary">
                                <i class="bi bi-person-plus mr-2"></i>@lang('general.assign_students')
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($classroom->students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>@lang('general.student')</th>
                                            <th>@lang('general.email')</th>
                                            <th>@lang('general.phone_number')</th>
                                            <th class="text-center">@lang('general.status')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classroom->students as $student)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->name }}</div>
                                                            @if ($student->studentProfile)
                                                                <small class="text-muted">{{ $student->studentProfile->level ?? __('general.not_available') }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->email ?? __('general.not_available') }}</td>
                                                <td>{{ $student->phone ?? __('general.not_available') }}</td>
                                                <td class="text-center">
                                                    @if ($student->studentProfile)
                                                        @php
                                                            $statusColors = [
                                                                'active' => 'success',
                                                                'paused' => 'warning',
                                                                'dropped' => 'danger',
                                                                'new' => 'info',
                                                            ];
                                                            $statusLabels = [
                                                                'active' => __('general.student_status_active'),
                                                                'paused' => __('general.student_status_paused'),
                                                                'dropped' => __('general.student_status_reserved'),
                                                                'new' => __('general.student_status_new'),
                                                            ];
                                                            $color =
                                                                $statusColors[$student->studentProfile->status] ??
                                                                'secondary';
                                                            $label =
                                                                $statusLabels[$student->studentProfile->status] ??
                                                                __('general.undefined');
                                                        @endphp
                                                        <span
                                                            class="badge bg-{{ $color }}">{{ $label }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">@lang('general.not_available')</span>
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
                                <h5 class="text-muted">@lang('general.no_students_yet')</h5>
                                <p class="text-muted">@lang('general.please_assign_students')</p>
                                <a href="{{ route('classrooms.assign-students', $classroom) }}"
                                    class="btn btn-primary">
                                    <i class="bi bi-person-plus mr-2"></i>@lang('general.assign_students')
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Các hành động -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-gear mr-2"></i>@lang('general.actions')
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row gy-3">
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.attendance', $classroom) }}"
                                    class="btn btn-outline-primary w-100">
                                    <i class="bi bi-calendar-check mr-2"></i>@lang('general.take_attendance')
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.attendance-history', $classroom) }}"
                                    class="btn btn-outline-info w-100">
                                    <i class="bi bi-calendar-week mr-2"></i>@lang('general.attendance_history')
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.assign-students', $classroom) }}"
                                    class="btn btn-outline-success w-100">
                                    <i class="bi bi-person-plus mr-2"></i>@lang('general.assign_students')
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('classrooms.edit', $classroom) }}"
                                    class="btn btn-outline-warning w-100">
                                    <i class="bi bi-pencil-square mr-2"></i>@lang('general.edit')
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
