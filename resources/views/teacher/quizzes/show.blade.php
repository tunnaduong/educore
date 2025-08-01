<x-layouts.dash-teacher active="quizzes">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-eye me-2"></i>Chi tiết bài kiểm tra
                    </h4>
                    <p class="text-muted mb-0">{{ $quiz->title }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('teacher.quizzes.results', $quiz) }}" wire:navigate class="btn btn-info">
                        <i class="bi bi-graph-up me-2"></i>Xem kết quả
                    </a>
                    <a href="{{ route('teacher.quizzes.edit', $quiz) }}" wire:navigate class="btn btn-warning">
                        <i class="bi bi-pencil me-2"></i>Chỉnh sửa
                    </a>
                    <a href="{{ route('teacher.quizzes.index') }}" wire:navigate class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin bài kiểm tra -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Thông tin bài kiểm tra
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tiêu đề:</label>
                                <p class="mb-0">{{ $quiz->title }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Lớp học:</label>
                                <p class="mb-0">
                                    <span class="badge bg-info">{{ $quiz->classroom->name ?? 'N/A' }}</span>
                                </p>
                            </div>
                            @if ($quiz->description)
                                <div class="col-12">
                                    <label class="form-label fw-bold">Mô tả:</label>
                                    <p class="mb-0">{{ $quiz->description }}</p>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Số câu hỏi:</label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary">{{ count($questions) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tổng điểm:</label>
                                <p class="mb-0">
                                    <span class="badge bg-success">{{ $quiz->getMaxScore() }}</span>
                                </p>
                            </div>
                            @if ($quiz->time_limit)
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Thời gian làm bài:</label>
                                    <p class="mb-0">{{ $quiz->time_limit }} phút</p>
                                </div>
                            @endif
                            @if ($quiz->deadline)
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Hạn nộp:</label>
                                    <p class="mb-0">
                                        {{ $quiz->deadline->format('d/m/Y H:i') }}
                                        @if ($quiz->isExpired())
                                            <span class="badge bg-danger ms-2">Hết hạn</span>
                                        @else
                                            <span class="badge bg-success ms-2">Còn hạn</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày tạo:</label>
                                <p class="mb-0">{{ $quiz->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Cập nhật lần cuối:</label>
                                <p class="mb-0">{{ $quiz->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách câu hỏi -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-question-circle me-2"></i>Danh sách câu hỏi ({{ count($questions) }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @if (count($questions) > 0)
                            <div class="accordion" id="questionsAccordion">
                                @foreach ($questions as $index => $question)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                                                    type="button" data-bs-toggle="collapse" 
                                                    data-bs-target="#question{{ $index }}">
                                                <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                    <span>
                                                        <strong>Câu {{ $index + 1 }}:</strong> 
                                                        {{ Str::limit($question['question'], 50) }}
                                                        <span class="badge bg-primary ms-2">{{ $question['score'] }} điểm</span>
                                                    </span>
                                                    <span class="badge bg-secondary">
                                                        @switch($question['type'])
                                                            @case('multiple_choice')
                                                                Trắc nghiệm
                                                                @break
                                                            @case('true_false')
                                                                Đúng/Sai
                                                                @break
                                                            @case('essay')
                                                                Tự luận
                                                                @break
                                                        @endswitch
                                                    </span>
                                                </div>
                                            </button>
                                        </h2>
                                        <div id="question{{ $index }}" 
                                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
                                            <div class="accordion-body">
                                                <div class="mb-3">
                                                    <strong>Nội dung câu hỏi:</strong>
                                                    <p class="mb-2">{{ $question['question'] }}</p>
                                                </div>

                                                @if ($question['type'] === 'multiple_choice')
                                                    <div class="mb-3">
                                                        <strong>Các lựa chọn:</strong>
                                                        <div class="mt-2">
                                                            @foreach ($question['options'] as $optionIndex => $option)
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" disabled
                                                                           {{ $question['correct_answer'] === chr(65 + $optionIndex) ? 'checked' : '' }}>
                                                                    <label class="form-check-label">
                                                                        {{ chr(65 + $optionIndex) }}. {{ $option }}
                                                                        @if ($question['correct_answer'] === chr(65 + $optionIndex))
                                                                            <span class="badge bg-success ms-2">Đáp án đúng</span>
                                                                        @endif
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                @if ($question['type'] === 'true_false')
                                                    <div class="mb-3">
                                                        <strong>Đáp án đúng:</strong>
                                                        <p class="mb-0">
                                                            <span class="badge bg-success">
                                                                {{ $question['correct_answer'] === 'true' ? 'Đúng' : 'Sai' }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                @endif

                                                @if ($question['type'] === 'essay')
                                                    <div class="mb-3">
                                                        <strong>Loại câu hỏi:</strong>
                                                        <p class="mb-0">Tự luận - Cần chấm điểm thủ công</p>
                                                    </div>
                                                @endif

                                                @if (!empty($question['explanation']))
                                                    <div class="mb-3">
                                                        <strong>Giải thích:</strong>
                                                        <p class="mb-0">{{ $question['explanation'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-question-circle fs-1 text-muted mb-3"></i>
                                <h6 class="text-muted">Không có câu hỏi nào</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Thống kê kết quả -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Thống kê kết quả
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Số học sinh đã làm:</strong> 
                            <span class="badge bg-primary">{{ $results->count() }}</span>
                        </div>
                        @if ($results->count() > 0)
                            @php
                                $avgScore = $results->avg('score');
                                $maxScore = $results->max('score');
                                $minScore = $results->min('score');
                            @endphp
                            <div class="mb-3">
                                <strong>Điểm trung bình:</strong> 
                                <span class="badge bg-info">{{ number_format($avgScore, 1) }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Điểm cao nhất:</strong> 
                                <span class="badge bg-success">{{ $maxScore }}</span>
                            </div>
                            <div class="mb-3">
                                <strong>Điểm thấp nhất:</strong> 
                                <span class="badge bg-warning">{{ $minScore }}</span>
                            </div>
                        @else
                            <div class="text-muted">Chưa có học sinh nào làm bài</div>
                        @endif
                    </div>
                </div>

                <!-- Danh sách kết quả -->
                @if ($results->count() > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-list-ul me-2"></i>Kết quả học sinh
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                @foreach ($results->take(5) as $result)
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $result->student->user->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $result->completed_at ? $result->completed_at->format('d/m/Y H:i') : 'N/A' }}</small>
                                        </div>
                                        <span class="badge bg-primary">{{ $result->score }}/{{ $quiz->getMaxScore() }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if ($results->count() > 5)
                                <div class="text-center mt-3">
                                    <small class="text-muted">Và {{ $results->count() - 5 }} kết quả khác...</small>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
