<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('student.assignments.overview') }}"
                    class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-journal-text fs-2 mb-2"></i>
                    <span class="fw-medium">Tất cả bài tập</span>
                    <small class="text-muted">{{ $totalAssignments }} bài tập</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.assignments.overview', ['filterStatus' => 'upcoming']) }}"
                    class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-clock fs-2 mb-2"></i>
                    <span class="fw-medium">Cần làm</span>
                    <small class="text-muted">{{ $upcomingAssignments }} bài tập</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.assignments.overview', ['filterStatus' => 'overdue']) }}"
                    class="btn btn-outline-danger w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-exclamation-triangle fs-2 mb-2"></i>
                    <span class="fw-medium">Quá hạn</span>
                    <small class="text-muted">{{ $overdueAssignments }} bài tập</small>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('student.assignments.submissions') }}"
                    class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-3">
                    <i class="bi bi-folder-check fs-2 mb-2"></i>
                    <span class="fw-medium">Đã nộp</span>
                    <small class="text-muted">{{ $completedAssignments }} bài tập</small>
                </a>
            </div>
        </div>
    </div>
</div>
