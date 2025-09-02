<x-layouts.dash-admin>
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('students.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
            </a>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-person-circle mr-2"></i>{{ __('general.student_details') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $student->name }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('students.edit', $student) }}" class="btn btn-primary">
                        <i class="bi bi-pencil-square mr-2"></i>{{ __('general.edit_student') }}
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
                            <i class="bi bi-person mr-2"></i>{{ __('general.personal_info') }}
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
                                    <label class="form-label text-muted small">{{ __('general.phone_number') }}</label>
                                    <div class="fw-medium">{{ $student->phone ?? __('general.not_available') }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label
                                        class="form-label text-muted small">{{ __('general.date_of_birth') }}</label>
                                    <div class="fw-medium">
                                        @if ($student->studentProfile && $student->studentProfile->date_of_birth)
                                            {{ \Carbon\Carbon::parse($student->studentProfile->date_of_birth)->format('d/m/Y') }}
                                        @else
                                            {{ __('general.not_available') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label
                                        class="form-label text-muted small">{{ __('general.enrollment_date') }}</label>
                                    <div class="fw-medium">
                                        @if ($student->studentProfile && $student->studentProfile->joined_at)
                                            {{ \Carbon\Carbon::parse($student->studentProfile->joined_at)->format('d/m/Y') }}
                                        @else
                                            {{ __('general.not_available') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small">{{ __('general.status') }}</label>
                                    <div>
                                        @if ($student->studentProfile)
                                            @php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'paused' => 'warning',
                                                    'dropped' => 'danger',
                                                ];
                                                $statusLabels = [
                                                    'active' => __('general.studying'),
                                                    'paused' => __('general.paused'),
                                                    'dropped' => __('general.reserved'),
                                                ];
                                                $color = $statusColors[$student->studentProfile->status] ?? 'secondary';
                                                $label =
                                                    $statusLabels[$student->studentProfile->status] ??
                                                    __('general.undefined');
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('general.not_available') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($student->studentProfile && $student->studentProfile->level)
                            <div class="mb-3">
                                <label class="form-label text-muted small">{{ __('general.level') }}</label>
                                <div class="fw-medium">{{ $student->studentProfile->level }}</div>
                            </div>
                        @endif

                        @if ($student->studentProfile && $student->studentProfile->notes)
                            <div class="mb-3">
                                <label class="form-label text-muted small">{{ __('general.notes') }}</label>
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
                            <i class="bi bi-mortarboard mr-2"></i>{{ __('general.enrolled_classes_info') }}
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
                                                    {{ $classroom->status == 'active' ? __('general.currently_active') : __('general.ended') }}
                                                </span>
                                            </div>
                                            <div class="small text-muted">
                                                <div><i
                                                        class="bi bi-person mr-1"></i>{{ $classroom->getFirstTeacher()?->name ?? __('general.no_teacher_assigned') }}
                                                </div>
                                                <div><i
                                                        class="bi bi-calendar mr-1"></i>{{ $classroom->level ?? __('general.no_level_assigned') }}
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
                                    {{ __('general.no_classes_registered') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Tiến độ học tập -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-graph-up mr-2"></i>{{ __('general.learning_progress') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-primary">{{ $studySessions }}</div>
                                    <div class="small text-muted">{{ __('general.study_sessions') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-success">
                                        {{ $averageScore > 0 ? $averageScore : '-' }}</div>
                                    <div class="small text-muted">{{ __('general.average_score') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-info">{{ $completedAssignments }}</div>
                                    <div class="small text-muted">{{ __('general.completed_assignments') }}</div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="border rounded p-3">
                                    <div class="fs-4 fw-bold text-warning">{{ $attendanceRate }}%</div>
                                    <div class="small text-muted">{{ __('general.attendance_rate') }}</div>
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
                            <i class="bi bi-journal-check mr-2"></i>{{ __('general.assignment_history') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-journal-check fs-1 d-block mb-2"></i>
                                {{ __('general.no_assignment_history') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
