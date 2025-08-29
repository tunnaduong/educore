<div class="card shadow-sm mb-4">
    @include('components.language')
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('student.assignments.overview') }}"
                    class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-journal-text fs-2 mb-2"></i>
                    <span class="fw-medium">{{ __('general.assignment_list') }}</span>
                    <small class="text-muted">{{ $totalAssignments }} {{ __('general.total_assignments') }}</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.assignments.overview', ['filterStatus' => 'upcoming']) }}"
                    class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-clock fs-2 mb-2"></i>
                    <span class="fw-medium">{{ __('general.need_to_do') }}</span>
                    <small class="text-muted">{{ $upcomingAssignments }} {{ __('general.total_assignments') }}</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.assignments.overview', ['filterStatus' => 'overdue']) }}"
                    class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-exclamation-triangle fs-2 mb-2"></i>
                    <span class="fw-medium">{{ __('general.overdue') }}</span>
                    <small class="text-muted">{{ $overdueAssignments }} {{ __('general.total_assignments') }}</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.assignments.submissions') }}"
                    class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-folder-check fs-2 mb-2"></i>
                    <span class="fw-medium">{{ __('general.submitted_assignments') }}</span>
                    <small class="text-muted">{{ $completedAssignments }} {{ __('general.total_assignments') }}</small>
                </a>
            </div>
        </div>
    </div>
</div>
