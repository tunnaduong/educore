<div>
    <div class="row g-3 mb-4">
        <!-- Tổng số bài học -->
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ __('views.total_lessons') }}</h6>
                            <h3 class="mb-0">{{ $totalLessons }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-book fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số bài tập -->
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ __('views.total_assignments') }}</h6>
                            <h3 class="mb-0">{{ $totalAssignments }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-journal-text fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tổng số kiểm tra -->
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ __('views.total_quizzes') }}</h6>
                            <h3 class="mb-0">{{ $totalQuizzes }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-patch-question fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sự kiện hôm nay -->
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">{{ __('views.today') }}</h6>
                            <h3 class="mb-0">{{ $todayEvents }}</h3>
                        </div>
                        <div>
                            <i class="bi bi-calendar-day fs-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <!-- Bài học sắp tới -->
        <div class="col-md-4">
            <div class="card border-primary h-100">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-clock mr-2"></i>{{ __('views.upcoming_lessons') }}</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-primary mb-0">{{ $upcomingLessons }}</h2>
                    <p class="text-muted mb-0">{{ __('views.lessons') }}</p>
                </div>
            </div>
        </div>

        <!-- Bài tập chưa đến hạn -->
        <div class="col-md-4">
            <div class="card border-warning h-100">
                <div class="card-header bg-warning text-white">
                    <h6 class="mb-0"><i class="bi bi-hourglass-split mr-2"></i>{{ __('views.pending_assignments') }}</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-warning mb-0">{{ $pendingAssignments }}</h2>
                    <p class="text-muted mb-0">{{ __('views.assignments_pending') }}</p>
                </div>
            </div>
        </div>

        <!-- Sự kiện tuần này -->
        <div class="col-md-4">
            <div class="card border-success h-100">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-week mr-2"></i>Tuần này</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-success mb-0">{{ $thisWeekEvents }}</h2>
                    <p class="text-muted mb-0">sự kiện</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <!-- Sự kiện tháng này -->
        <div class="col-md-6">
            <div class="card border-info h-100">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-month mr-2"></i>Tháng này</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-info mb-0">{{ $thisMonthEvents }}</h2>
                    <p class="text-muted mb-0">sự kiện</p>
                </div>
            </div>
        </div>

        <!-- Tổng sự kiện -->
        <div class="col-md-6">
            <div class="card border-secondary h-100">
                <div class="card-header bg-secondary text-white">
                    <h6 class="mb-0"><i class="bi bi-calendar-check mr-2"></i>Tổng sự kiện</h6>
                </div>
                <div class="card-body text-center">
                    <h2 class="text-secondary mb-0">{{ $totalLessons + $totalAssignments + $totalQuizzes }}</h2>
                    <p class="text-muted mb-0">sự kiện</p>
                </div>
            </div>
        </div>
    </div>
</div>
