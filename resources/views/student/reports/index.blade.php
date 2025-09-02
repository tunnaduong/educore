<x-layouts.dash-student active="reports">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bar-chart-fill mr-2"></i>{{ __('views.student_pages.reports.index.title') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('views.student_pages.reports.index.subtitle') }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="{{ route('student.lessons.index') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="bi bi-book me-3" style="font-size: 1.5rem; color: #007bff;"></i>
                                        <div>
                                            <h6 class="mb-0">{{ __('views.student_pages.reports.index.lessons') }}</h6>
                                            <small class="text-muted">{{ __('views.student_pages.reports.index.lessons_desc') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('student.assignments.overview') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="bi bi-list-task me-3" style="font-size: 1.5rem; color: #28a745;"></i>
                                        <div>
                                            <h6 class="mb-0">{{ __('views.student_pages.reports.index.assignments') }}</h6>
                                            <small class="text-muted">{{ __('views.student_pages.reports.index.assignments_desc') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('student.schedules') }}" class="text-decoration-none">
                                    <div class="d-flex align-items-center p-3 border rounded">
                                        <i class="bi bi-calendar-check me-3" style="font-size: 1.5rem; color: #ffc107;"></i>
                                        <div>
                                            <h6 class="mb-0">{{ __('views.student_pages.reports.index.attendance') }}</h6>
                                            <small class="text-muted">{{ __('views.student_pages.reports.index.attendance_desc') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Class Details Section -->
        @if($studentClasses->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 text-primary">
                            <i class="bi bi-info-circle me-2"></i>{{ __('views.student_pages.reports.index.class_details') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($studentClasses as $class)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="fw-bold">{{ __('views.student_pages.reports.index.class_name') }}:</div>
                                <div>{{ $class->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="fw-bold">{{ __('views.student_pages.reports.index.status') }}:</div>
                                <span class="badge bg-success">{{ __('views.student_pages.reports.index.active') }}</span>
                            </div>
                        </div>
                        @if($class->description)
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="fw-bold">{{ __('views.student_pages.reports.index.description') }}:</div>
                                <div>{{ $class->description }}</div>
                            </div>
                        </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fw-bold">{{ __('views.student_pages.reports.index.created_date') }}:</div>
                                <div>{{ $class->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Statistics -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0 text-success">
                            <i class="bi bi-graph-up me-2"></i>{{ __('views.student_pages.reports.index.quick_statistics') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="fs-4 text-primary fw-bold">{{ $totalLessons }}</div>
                                    <div class="text-muted">{{ __('views.student_pages.reports.index.lessons') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="fs-4 text-success fw-bold">{{ $totalAssignments }}</div>
                                    <div class="text-muted">{{ __('views.student_pages.reports.index.assignments') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="fs-4 text-info fw-bold">{{ $totalStudents }}</div>
                                    <div class="text-muted">{{ __('views.student_pages.reports.index.students') }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-center">
                                    <div class="fs-4 text-warning fw-bold">{{ $totalSessions }}</div>
                                    <div class="text-muted">{{ __('views.student_pages.reports.index.sessions') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning Results Overview Cards -->
        <div class="row mb-4">
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-journal-text" style="font-size:2.5rem; color:#fd7e14;"></i>
                        </div>
                        <div class="fw-bold">{{ __('views.student_pages.reports.index.avg_assignment_score') }}</div>
                        <div class="fs-4 text-primary">{{ $avgAssignmentScore }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-clipboard-check-fill" style="font-size:2.5rem; color:#6f42c1;"></i>
                        </div>
                        <div class="fw-bold">{{ __('views.student_pages.reports.index.avg_quiz_score') }}</div>
                        <div class="fs-4 text-success">{{ $avgQuizScore }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-person-check-fill" style="font-size:2.5rem; color:#20c997;"></i>
                        </div>
                        <div class="fw-bold">{{ __('views.student_pages.reports.index.attendance_present') }}</div>
                        <div class="fs-4 text-info">{{ $attendancePresent }}</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3 mb-3">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <div class="mb-2">
                            <i class="bi bi-person-x-fill" style="font-size:2.5rem; color:#dc3545;"></i>
                        </div>
                        <div class="fw-bold">{{ __('views.student_pages.reports.index.attendance_absent') }}</div>
                        <div class="fs-4 text-danger">{{ $attendanceAbsent }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs: 3 tabs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'assignments' ? 'active' : '' }}" type="button" wire:click="setActiveTab('assignments')" role="tab">
                                    <i class="bi bi-journal-check me-2"></i>{{ __('views.student_pages.reports.index.assignments_scores') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'quizzes' ? 'active' : '' }}" type="button" wire:click="setActiveTab('quizzes')" role="tab">
                                    <i class="bi bi-clipboard-check me-2"></i>{{ __('views.student_pages.reports.index.quiz_scores') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $activeTab === 'attendance' ? 'active' : '' }}" type="button" wire:click="setActiveTab('attendance')" role="tab">
                                    <i class="bi bi-calendar-check me-2"></i>{{ __('views.student_pages.reports.index.attendance_stats') }}
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Contents -->
        @if($activeTab === 'assignments')
        <div class="card mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('views.student_pages.reports.index.assignment') }}</th>
                                <th>{{ __('views.student_pages.reports.index.class') }}</th>
                                <th>{{ __('views.student_pages.reports.index.score') }}</th>
                                <th>{{ __('views.student_pages.reports.index.feedback') }}</th>
                                <th>{{ __('views.student_pages.reports.index.submission_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->getPaginatedAssignments() as $submission)
                                <tr>
                                    <td>{{ $submission->assignment->title ?? '-' }}</td>
                                    <td>{{ $submission->assignment->classroom->name ?? '-' }}</td>
                                    <td>{{ $submission->score ?? '-' }}</td>
                                    <td>{{ $submission->feedback ?? '-' }}</td>
                                    <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('views.student_pages.reports.index.no_assignments') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($this->getTotalPages('assignment') > 1)
                <div class="card-footer">
                    <nav aria-label="Assignment pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item {{ $assignmentPage == 1 ? 'disabled' : '' }}">
                                <button class="page-link" wire:click="previousPage('assignment')" {{ $assignmentPage == 1 ? 'disabled' : '' }}>{{ __('pagination.previous') }}</button>
                            </li>
                            @for($i = 1; $i <= $this->getTotalPages('assignment'); $i++)
                                <li class="page-item {{ $assignmentPage == $i ? 'active' : '' }}">
                                    <button class="page-link" wire:click="goToPage('assignment', {{ $i }})">{{ $i }}</button>
                                </li>
                            @endfor
                            <li class="page-item {{ $assignmentPage == $this->getTotalPages('assignment') ? 'disabled' : '' }}">
                                <button class="page-link" wire:click="nextPage('assignment')" {{ $assignmentPage == $this->getTotalPages('assignment') ? 'disabled' : '' }}>{{ __('pagination.next') }}</button>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($activeTab === 'quizzes')
        <div class="card mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('views.student_pages.reports.index.quiz_title') }}</th>
                                <th>{{ __('views.student_pages.reports.index.class') }}</th>
                                <th>{{ __('views.student_pages.reports.index.score') }}</th>
                                <th>{{ __('views.student_pages.reports.index.duration') }}</th>
                                <th>{{ __('views.student_pages.reports.index.submission_date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->getPaginatedQuizzes() as $result)
                                <tr>
                                    <td>{{ $result->quiz->title ?? '-' }}</td>
                                    <td>{{ $result->quiz->classroom->name ?? '-' }}</td>
                                    <td>{{ $result->score ?? '-' }}</td>
                                    <td>{{ $result->getDurationString() }}</td>
                                    <td>{{ $result->submitted_at ? $result->submitted_at->format('d/m/Y H:i') : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('views.student_pages.reports.index.no_quizzes') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($this->getTotalPages('quiz') > 1)
                <div class="card-footer">
                    <nav aria-label="Quiz pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item {{ $quizPage == 1 ? 'disabled' : '' }}">
                                <button class="page-link" wire:click="previousPage('quiz')" {{ $quizPage == 1 ? 'disabled' : '' }}>{{ __('pagination.previous') }}</button>
                            </li>
                            @for($i = 1; $i <= $this->getTotalPages('quiz'); $i++)
                                <li class="page-item {{ $quizPage == $i ? 'active' : '' }}">
                                    <button class="page-link" wire:click="goToPage('quiz', {{ $i }})">{{ $i }}</button>
                                </li>
                            @endfor
                            <li class="page-item {{ $quizPage == $this->getTotalPages('quiz') ? 'disabled' : '' }}">
                                <button class="page-link" wire:click="nextPage('quiz')" {{ $quizPage == $this->getTotalPages('quiz') ? 'disabled' : '' }}>{{ __('pagination.next') }}</button>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
        @endif

        @if($activeTab === 'attendance')
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('views.student_pages.reports.index.date') }}</th>
                                <th>{{ __('views.student_pages.reports.index.class') }}</th>
                                <th>{{ __('views.student_pages.reports.index.status') }}</th>
                                <th>{{ __('views.student_pages.reports.index.absence_reason') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->getPaginatedAttendances() as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                    <td>{{ $attendance->classroom->name ?? '-' }}</td>
                                    <td>
                                        @if ($attendance->present)
                                            <span class="badge bg-success">{{ __('views.student_pages.reports.index.present') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('views.student_pages.reports.index.absent') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->reason ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">{{ __('views.student_pages.reports.index.no_attendance_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($this->getTotalPages('attendance') > 1)
                <div class="card-footer">
                    <nav aria-label="Attendance pagination">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item {{ $attendancePage == 1 ? 'disabled' : '' }}">
                                <button class="page-link" wire:click="previousPage('attendance')" {{ $attendancePage == 1 ? 'disabled' : '' }}>{{ __('pagination.previous') }}</button>
                            </li>
                            @for($i = 1; $i <= $this->getTotalPages('attendance'); $i++)
                                <li class="page-item {{ $attendancePage == $i ? 'active' : '' }}">
                                    <button class="page-link" wire:click="goToPage('attendance', {{ $i }})">{{ $i }}</button>
                                </li>
                            @endfor
                            <li class="page-item {{ $attendancePage == $this->getTotalPages('attendance') ? 'disabled' : '' }}">
                                <button class="page-link" wire:click="nextPage('attendance')" {{ $attendancePage == $this->getTotalPages('attendance') ? 'disabled' : '' }}>{{ __('pagination.next') }}</button>
                            </li>
                        </ul>
                    </nav>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Students section removed per 3-tab requirement -->
    </div>
</x-layouts.dash-student>
