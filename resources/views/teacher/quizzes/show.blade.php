<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.quizzes.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại danh sách bài kiểm tra
            </a>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-text mr-2"></i>Chi tiết bài kiểm tra
                    </h4>
                    <p class="text-muted mb-0">{{ $quiz->title }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('teacher.quizzes.edit', $quiz) }}" class="btn btn-warning">
                        <i class="bi bi-pencil mr-2"></i>Sửa
                    </a>
                    <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="btn btn-info">
                        <i class="bi bi-graph-up mr-2"></i>Kết quả
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin bài kiểm tra -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle mr-2"></i>Thông tin bài kiểm tra
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tiêu đề</label>
                            <div class="fw-medium">{{ $quiz->title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Lớp học</label>
                            <div class="fw-medium">{{ $quiz->classroom->name ?? 'N/A' }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Thời gian làm bài</label>
                            <div class="fw-medium">
                                @if ($quiz->time_limit)
                                    <span class="badge bg-warning text-dark">{{ $quiz->time_limit }} phút</span>
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Hạn nộp</label>
                            <div class="fw-medium">
                                @if ($quiz->deadline)
                                    {{ $quiz->deadline->format('d/m/Y H:i') }}
                                    @if ($quiz->isExpired())
                                        <span class="badge bg-danger ml-2">Hết hạn</span>
                                    @else
                                        <span class="badge bg-success ml-2">Còn hạn</span>
                                    @endif
                                @else
                                    <span class="text-muted">Không có hạn</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Ngày tạo</label>
                            <div class="fw-medium">{{ $quiz->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Mô tả</label>
                            <div class="fw-medium">{!! nl2br(e($quiz->description)) !!}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Số câu hỏi</label>
                            <div class="fw-medium">{{ count($quiz->questions) }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tổng điểm tối đa</label>
                            <div class="fw-medium">{{ $quiz->getMaxScore() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách câu hỏi -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-list-ul mr-2"></i>Danh sách câu hỏi
                        </h6>
                    </div>
                    <div class="card-body">
                        @if (count($quiz->questions) > 0)
                            @foreach ($quiz->questions as $index => $question)
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="badge bg-primary mr-2">Câu {{ $index + 1 }}</span>
                                            <span class="badge bg-info">{{ $question['score'] }} điểm</span>
                                        </div>
                                    </div>
                                    <div class="fw-medium mb-2">{{ $question['question'] }}</div>
                                    <div class="ml-3">
                                        @foreach ($question['options'] as $optionIndex => $option)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" disabled
                                                    {{ $question['correct_answer'] === chr(65 + $optionIndex) ? 'checked' : '' }}>
                                                <label
                                                    class="form-check-label {{ $question['correct_answer'] === chr(65 + $optionIndex) ? 'fw-bold text-success' : '' }}">
                                                    {{ $option }}
                                                    @if ($question['correct_answer'] === chr(65 + $optionIndex))
                                                        <i class="bi bi-check-circle-fill text-success ml-1"></i>
                                                    @endif
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                                <h5 class="text-muted">Không có câu hỏi nào</h5>
                                <p class="text-muted">Bài kiểm tra này chưa có câu hỏi.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
