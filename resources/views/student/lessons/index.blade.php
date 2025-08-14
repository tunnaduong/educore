<x-layouts.dash-student active="lessons">
    @include('components.language')

    @php
        $student = Auth::user()->student;
        $currentEvaluationRounds = \App\Models\EvaluationRound::current()->get();
        $currentEvaluationRound = $currentEvaluationRounds->first(); // Lấy đợt đầu tiên để hiển thị

        // Kiểm tra xem student đã đánh giá tất cả đợt hiện tại chưa
        $hasEvaluation = false;
        if ($student && $currentEvaluationRounds->count() > 0) {
            // Kiểm tra xem student đã đánh giá tất cả đợt hiện tại chưa
            $evaluatedRounds = $student
                ->evaluations()
                ->whereIn('evaluation_round_id', $currentEvaluationRounds->pluck('id'))
                ->whereNotNull('submitted_at')
                ->count();

            $hasEvaluation = $evaluatedRounds >= $currentEvaluationRounds->count();
        }

        // Debug info - đã bỏ để tránh hiển thị debug trên trang

    @endphp

    @if (!$hasEvaluation && $student && $currentEvaluationRounds->count() > 0)
        <!-- Modal đánh giá bắt buộc - không thể đóng -->
        <div class="modal fade show d-block" id="requiredEvaluationModal" tabindex="-1"
            style="background-color: rgba(0,0,0,0.8); z-index: 1050; overflow-y: auto;">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            Yêu cầu đánh giá chất lượng học tập
                        </h5>
                    </div>
                    <div class="modal-body p-0" style="max-height: 80vh; overflow-y: auto;">
                        <livewire:student.evaluation.index />
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung bị khóa -->
        <div class="container-fluid py-5 text-center" style="opacity: 0.3; pointer-events: none;">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <i class="bi bi-lock-fill fs-1 text-muted mb-3"></i>
                    <h4 class="text-muted">Chức năng đã bị khóa</h4>
                    <p class="text-muted">Bạn cần hoàn thành đánh giá chất lượng học tập trước khi có thể sử dụng hệ
                        thống.</p>
                </div>
            </div>
        </div>
    @elseif (!$hasEvaluation && $student && $currentEvaluationRounds->count() == 0)
        <!-- Thông báo không có đợt đánh giá -->
        <div class="container-fluid py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <i class="bi bi-calendar-x fs-1 text-warning mb-3"></i>
                            <h4 class="text-warning">Chưa có đợt đánh giá</h4>
                            <p class="text-muted">Hiện tại chưa có đợt đánh giá chất lượng học tập nào đang diễn ra.</p>
                            <p class="text-muted">Vui lòng liên hệ admin để được hỗ trợ hoặc chờ đợt đánh giá tiếp theo.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Nội dung bình thường khi đã đánh giá -->
        <div class="container-fluid">
            <!-- Header -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-primary fs-4">
                            <i class="bi bi-book mr-2"></i>Danh sách bài học
                        </h4>
                        <p class="text-muted mb-0">Các bài học bạn có thể xem và tham khảo</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" wire:model.live="search"
                                placeholder="Tìm theo tên hoặc mô tả...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Lớp học</label>
                            <select class="form-control" wire:model.live="filterClass">
                                <option value="">Tất cả lớp</option>
                                @foreach ($classrooms ?? [] as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                                <i class="bi bi-arrow-clockwise mr-2"></i>Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lesson List -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-list-ul mr-2"></i>Danh sách bài học
                    </h6>
                </div>
                <div class="card-body">
                    @if ($lessons->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tiêu đề</th>
                                        <th>Lớp học</th>
                                        <th>Mô tả</th>
                                        <th>Tệp/Video</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lessons as $lesson)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $lesson->number }}</span>
                                                <span class="fw-medium">{{ $lesson->title }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-info">{{ $lesson->classroom->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <small
                                                    class="text-muted">{{ Str::limit($lesson->description, 50) }}</small>
                                            </td>
                                            <td>
                                                @if ($lesson->attachment)
                                                    <a href="{{ asset('storage/' . $lesson->attachment) }}"
                                                        target="_blank" class="badge bg-success">Tệp</a>
                                                @endif
                                                @if ($lesson->video)
                                                    <a href="{{ $lesson->video }}" target="_blank"
                                                        class="badge bg-warning text-dark">Video</a>
                                                @endif
                                                @if (!$lesson->attachment && !$lesson->video)
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($completedLessons[$lesson->id]) && $completedLessons[$lesson->id])
                                                    <span class="badge bg-success">Đã hoàn thành</span>
                                                @else
                                                    <span class="badge bg-danger">Chưa xem</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('student.lessons.show', $lesson->id) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i> Xem chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $lessons->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                            <h5 class="text-muted">Không có bài học nào</h5>
                            <p class="text-muted">Bạn chưa có bài học nào để xem.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</x-layouts.dash-student>
