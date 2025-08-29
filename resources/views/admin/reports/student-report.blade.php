<x-layouts.dash-admin active="reports">
    @include('components.language')
    <div class="mb-4">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> {{ __('general.back_to_summary_report') }}
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-3 text-primary"><i class="bi bi-person-circle mr-2"></i>{{ __('views.detailed_student_report') }}</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-bold">{{ __('general.full_name') }}:</div>
                    <div>{{ $student->user->name }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">{{ __('views.class') }}:</div>
                    @if (isset($classNames) && count($classNames))
                        @foreach ($classNames as $cname)
                            <span class="badge bg-secondary mr-1">{{ $cname }}</span>
                        @endforeach
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">{{ __('general.status') }}:</div>
                    <span class="badge {{ $needSupport ? 'bg-danger' : 'bg-success' }}">
                        {{ $needSupport ? __('views.needs_support') : __('views.stable') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">{{ __('views.learning_progress') }}</div>
                    <div class="display-6">{{ $progress }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">{{ __('views.average_score') }}</div>
                    <div class="display-6">{{ $avgScore }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">{{ __('views.submission_rate') }}</div>
                    <div class="display-6">{{ $submitRate }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">{{ __('views.attendance_count') }}</div>
                    <div class="display-6">{{ $attendanceCount }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header fw-bold">{{ __('views.unsubmitted_assignments') }}</div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($notSubmittedAssignments as $assignment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $assignment->title }}
                        <span class="badge bg-warning">{{ __('views.not_submitted') }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">{{ __('views.all_assignments_submitted') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="alert alert-info">
        <i class="bi bi-lightbulb mr-2"></i>
        <b>{{ __('views.support_suggestions') }}</b>
        @if ($needSupport)
            {{ __('views.student_needs_support_message') }}
        @else
            {{ __('views.student_is_stable_message') }}
        @endif
    </div>
</x-layouts.dash-admin>
