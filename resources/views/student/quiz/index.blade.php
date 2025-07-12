<x-layouts.dash-student active="tests">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-clipboard-check-fill me-2"></i>Danh sách bài kiểm tra
                    </h4>
                    <p class="text-muted mb-0">Các bài kiểm tra bạn cần hoàn thành</p>
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
                            placeholder="Tìm theo tên hoặc mô tả...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lớp học</label>
                        <select class="form-select" wire:model.live="filterClass">
                            <option value="">Tất cả lớp</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" wire:model.live="filterStatus">
                            <option value="">Tất cả</option>
                            <option value="active">Còn hạn</option>
                            <option value="expired">Hết hạn</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Danh sách bài kiểm tra
                </h6>
            </div>
            <div class="card-body">
                @if ($quizzes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Lớp học</th>
                                    <th>Số câu hỏi</th>
                                    <th>Hạn nộp</th>
                                    <th>Trạng thái</th>
                                    <th>Trạng thái làm bài</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quizzes as $quiz)
                                    @php
                                        $result = $quizResults[$quiz->id] ?? null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="fw-medium">{{ $quiz->title }}</div>
                                            @if ($quiz->description)
                                                <small
                                                    class="text-muted">{{ Str::limit($quiz->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $quiz->classroom->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $quiz->getQuestionCount() }}</span>
                                        </td>
                                        <td>
                                            @if ($quiz->deadline)
                                                <div class="fw-medium">{{ $quiz->deadline->format('d/m/Y H:i') }}</div>
                                                <small class="text-muted">{{ $quiz->deadline->diffForHumans() }}</small>
                                            @else
                                                <span class="text-muted">Không có hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($quiz->isExpired())
                                                <span class="badge bg-danger">Hết hạn</span>
                                            @else
                                                <span class="badge bg-success">Còn hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result)
                                                <span class="badge bg-primary">Đã làm</span>
                                                @if ($result->submitted_at)
                                                    <span class="badge bg-success">Đã nộp</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Chưa nộp</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Chưa làm</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result && $result->submitted_at)
                                                <a href="{{ route('student.quizzes.review', ['quizId' => $quiz->id]) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-info" title="Xem lại bài">
                                                    <i class="bi bi-eye"></i> Xem lại
                                                </a>
                                            @elseif ($quiz->isExpired())
                                                <span class="text-muted">Đã hết hạn</span>
                                            @else
                                                <a href="{{ route('student.quizzes.do', $quiz) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-primary" title="Làm bài">
                                                    <i class="bi bi-pencil"></i> Làm bài
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $quizzes->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Không có bài kiểm tra nào</h5>
                        <p class="text-muted">Bạn chưa có bài kiểm tra nào cần làm.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-student>
