<x-layouts.dash-student active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('student.assignments.overview') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-folder-check me-2"></i>Bài tập đã nộp
                    </h4>
                    <p class="text-muted mb-0">Danh sách các bài tập bạn đã hoàn thành</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="Tìm theo tên bài tập...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái chấm điểm</label>
                        <select class="form-select" wire:model.live="filterGraded">
                            <option value="">Tất cả</option>
                            <option value="graded">Đã chấm điểm</option>
                            <option value="ungraded">Chưa chấm điểm</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lớp học</label>
                        <select class="form-select" wire:model.live="filterClassroom">
                            <option value="">Tất cả lớp</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        @if ($submissions->count() > 0)
            <div class="row my-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-file-earmark-check fs-1 mb-2"></i>
                            <h5 class="card-title">{{ $totalSubmissions }}</h5>
                            <p class="card-text">Tổng số bài nộp</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle fs-1 mb-2"></i>
                            <h5 class="card-title">{{ $gradedSubmissions }}</h5>
                            <p class="card-text">Đã chấm điểm</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <i class="bi bi-hourglass-split fs-1 mb-2"></i>
                            <h5 class="card-title">{{ $ungradedSubmissions }}</h5>
                            <p class="card-text">Chờ chấm điểm</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-star fs-1 mb-2"></i>
                            <h5 class="card-title">{{ number_format($averageScore, 1) }}</h5>
                            <p class="card-text">Điểm trung bình</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Submissions List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Danh sách bài nộp
                </h6>
            </div>
            <div class="card-body">
                @if ($submissions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Bài tập</th>
                                    <th>Lớp học</th>
                                    <th>Loại bài nộp</th>
                                    <th>Ngày nộp</th>
                                    <th>Điểm số</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $submission)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $submission->assignment->title }}</div>
                                            @if ($submission->assignment->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($submission->assignment->description, 60) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-info">{{ $submission->assignment->classroom->name }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                @switch($submission->submission_type)
                                                    @case('text')
                                                        Điền từ
                                                    @break

                                                    @case('essay')
                                                        Tự luận
                                                    @break

                                                    @case('image')
                                                        Upload ảnh
                                                    @break

                                                    @case('audio')
                                                        Ghi âm
                                                    @break

                                                    @case('video')
                                                        Video
                                                    @break

                                                    @default
                                                        {{ $submission->submission_type }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $submission->submitted_at->format('d/m/Y H:i') }}
                                            </div>
                                            <small
                                                class="text-muted">{{ $submission->submitted_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if ($submission->score !== null)
                                                <div class="fw-bold text-primary">{{ $submission->score }}/10</div>
                                                @if ($submission->feedback)
                                                    <small
                                                        class="text-muted">{{ Str::limit($submission->feedback, 30) }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">Chưa chấm</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($submission->score !== null)
                                                <span class="badge bg-success">Đã chấm điểm</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Chờ chấm điểm</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('student.assignments.show', $submission->assignment->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="Xem chi tiết"
                                                wire:navigate>
                                                <i class="bi bi-eye"></i> Xem
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $submissions->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Chưa có bài tập nào được nộp</h5>
                        <p class="text-muted">Bạn chưa nộp bài tập nào hoặc không có bài tập nào phù hợp với bộ lọc.</p>
                        <a href="{{ route('student.assignments.overview') }}" class="btn btn-primary" wire:navigate>
                            <i class="bi bi-journal-text me-2"></i>Xem danh sách bài tập
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-student>
