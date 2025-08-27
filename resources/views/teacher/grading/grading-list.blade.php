<x-layouts.dash-teacher active="grading">
    @include('components.language')
    @php
        $t = function ($vi, $en, $zh) {
            $l = app()->getLocale();
            return $l === 'vi' ? $vi : ($l === 'zh' ? $zh : $en);
        };
    @endphp
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-check mr-2"></i>{{ __('general.assignments_to_grade') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.manage_grade_assignments') }}</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.search') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="Tìm theo tên hoặc mô tả bài tập...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ $t('Lớp học', 'Classroom', '班级') }}</label>
                        <select class="form-control" wire:model.live="filterClassroom">
                            <option value="">{{ $t('Tất cả lớp', 'All classes', '所有班级') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ $t('Trạng thái', 'Status', '状态') }}</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="all">{{ $t('Tất cả', 'All', '全部') }}</option>
                            <option value="has_submissions">{{ $t('Có bài nộp', 'Has submissions', '有提交') }}</option>
                            <option value="no_submissions">{{ $t('Chưa có bài nộp', 'No submissions', '无提交') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.sort') }}</label>
                        <select class="form-control" wire:model.live="sortBy">
                            <option value="submissions_count">{{ $t('Số bài nộp', 'Submission count', '提交数') }}</option>
                            <option value="created_at">{{ $t('Ngày tạo', 'Created date', '创建日期') }}</option>
                            <option value="deadline">{{ $t('Hạn nộp', 'Deadline', '截止时间') }}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-journal-check mr-2"></i>
                            <span class="mb-0">{{ $t('Danh sách bài tập cần chấm', 'Assignments to grade', '待批改作业列表') }}</span>
                        </div>
                        <div class="text-white-50 small">
                            {{ $assignments->total() }} {{ $t('bài tập', 'assignments', '个作业') }}
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($assignments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                {{ $t('Lớp học', 'Classroom', '班级') }}
                                                @if ($sortBy === 'created_at')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th>{{ $t('Tiêu đề', 'Title', '标题') }}</th>
                                            <th>
                                                {{ $t('Hạn nộp', 'Deadline', '截止时间') }}
                                                @if ($sortBy === 'deadline')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                {{ $t('Số bài nộp', 'Submission count', '提交数') }}
                                                @if ($sortBy === 'submissions_count')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">{{ $t('Trạng thái', 'Status', '状态') }}</th>
                                            <th class="text-center">{{ $t('Thao tác', 'Actions', '操作') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($assignments as $assignment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-mortarboard fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">
                                                                {{ $assignment->classroom->name ?? '-' }}</div>
                                                            <small
                                                                class="text-muted">{{ $assignment->classroom->level ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="fw-medium">{{ $assignment->title }}</div>
                                                    @if ($assignment->description)
                                                        <small
                                                            class="text-muted">{{ Str::limit($assignment->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($assignment->deadline)
                                                        <div class="fw-medium">
                                                            {{ $assignment->deadline->format('d/m/Y H:i') }}</div>
                                                        <small
                                                            class="text-muted">{{ $assignment->deadline->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">{{ $t('Không có hạn', 'No deadline', '无截止日期') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-info">{{ $assignment->submissions_count }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($assignment->submissions_count > 0)
                                                        <span class="badge bg-success">{{ $t('Có bài nộp', 'Has submissions', '有提交') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $t('Chưa có bài nộp', 'No submissions', '无提交') }}</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button wire:click="selectAssignment({{ $assignment->id }})"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-check-circle mr-1"></i>{{ $t('Chấm bài', 'Grade', '批改') }}
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ $t('Chưa có bài tập nào', 'No assignments yet', '暂无作业') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Pagination -->
                @if ($assignments->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $assignments->links() }}
                    </div>
                @endif
            </div>

            <div class="col-lg-3">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-info-circle mr-2"></i>{{ $t('Thông tin', 'Info', '信息') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ $t('Tổng bài tập', 'Total assignments', '作业总数') }}:</span>
                                <strong>{{ $assignments->total() }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ $t('Có bài nộp', 'Has submissions', '有提交') }}:</span>
                                <strong>{{ $assignments->where('submissions_count', '>', 0)->count() }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>{{ $t('Chưa có bài nộp', 'No submissions', '无提交') }}:</span>
                                <strong>{{ $assignments->where('submissions_count', 0)->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
