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
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-journal-check mr-2"></i>{{ __('views.student_pages.reports.index.assignments_scores') }}
                    </div>
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
                                    @forelse($assignmentSubmissions as $submission)
                                        <tr>
                                            <td>{{ $submission->assignment->title ?? '-' }}</td>
                                            <td>{{ $submission->assignment->classroom->name ?? '-' }}</td>
                                            <td>{{ $submission->score ?? '-' }}</td>
                                            <td>{{ $submission->feedback ?? '-' }}</td>
                                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">{{ __('views.student_pages.reports.index.no_assignments') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-clipboard-check mr-2"></i>{{ __('views.student_pages.reports.index.quiz_scores') }}
                    </div>
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
                                    @forelse($quizResults as $result)
                                        <tr>
                                            <td>{{ $result->quiz->title ?? '-' }}</td>
                                            <td>{{ $result->quiz->classroom->name ?? '-' }}</td>
                                            <td>{{ $result->score ?? '-' }}</td>
                                            <td>{{ $result->getDurationString() }}</td>
                                            <td>{{ $result->submitted_at ? $result->submitted_at->format('d/m/Y H:i') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">{{ __('views.student_pages.reports.index.no_quizzes') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-calendar-check mr-2"></i>{{ __('views.student_pages.reports.index.attendance_stats') }}
                    </div>
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
                                    @php $attendances = Auth::user()->studentProfile ? Auth::user()->studentProfile->attendances : (Auth::user()->student ? Auth::user()->student->attendances : collect()); @endphp
                                    @forelse($attendances as $attendance)
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-student>
