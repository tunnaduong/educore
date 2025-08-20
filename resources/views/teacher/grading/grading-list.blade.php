<x-layouts.dash-teacher active="grading">
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
                        <label class="form-label">Lớp học</label>
                        <select class="form-control" wire:model.live="filterClassroom">
                            <option value="">Tất cả lớp</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-control" wire:model.live="filterStatus">
                            <option value="all">Tất cả</option>
                            <option value="has_submissions">Có bài nộp</option>
                            <option value="no_submissions">Chưa có bài nộp</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">{{ __('general.sort') }}</label>
                        <select class="form-control" wire:model.live="sortBy">
                            <option value="submissions_count">Số bài nộp</option>
                            <option value="created_at">Ngày tạo</option>
                            <option value="deadline">Hạn nộp</option>
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
                            <span class="mb-0">Danh sách bài tập cần chấm</span>
                        </div>
                        <div class="text-white-50 small">
                            {{ $assignments->total() }} bài tập
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($assignments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>
                                                Lớp học
                                                @if ($sortBy === 'created_at')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th>Tiêu đề</th>
                                            <th>
                                                Hạn nộp
                                                @if ($sortBy === 'deadline')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                Số bài nộp
                                                @if ($sortBy === 'submissions_count')
                                                    <i
                                                        class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th class="text-center">Trạng thái</th>
                                            <th class="text-center">Thao tác</th>
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
                                                        <span class="text-muted">Không có hạn</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span
                                                        class="badge bg-info">{{ $assignment->submissions_count }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($assignment->submissions_count > 0)
                                                        <span class="badge bg-success">Có bài nộp</span>
                                                    @else
                                                        <span class="badge bg-secondary">Chưa có bài nộp</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <button wire:click="selectAssignment({{ $assignment->id }})"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-check-circle mr-1"></i>Chấm bài
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
                                <h5 class="text-muted">Chưa có bài tập nào</h5>
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
                            <i class="bi bi-info-circle mr-2"></i>Thông tin
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Tổng bài tập:</span>
                                <strong>{{ $assignments->total() }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Có bài nộp:</span>
                                <strong>{{ $assignments->where('submissions_count', '>', 0)->count() }}</strong>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Chưa có bài nộp:</span>
                                <strong>{{ $assignments->where('submissions_count', 0)->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
