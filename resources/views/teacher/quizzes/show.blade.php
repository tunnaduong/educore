<x-layouts.dash-teacher active="quizzes">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
        }
        .bg-success-light {
            background-color: rgba(40, 167, 69, 0.1);
        }
        .bg-warning-light {
            background-color: rgba(255, 193, 7, 0.1);
        }
        .border-success {
            border-color: #28a745 !important;
        }
        .border-warning {
            border-color: #ffc107 !important;
        }
        .border-primary {
            border-color: #007bff !important;
        }
        .accordion-item .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            transition: box-shadow 0.3s ease;
        }
        .btn-link:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
    </style>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4 fw-bold">
                        <i class="bi bi-eye me-2"></i>Chi tiết bài kiểm tra
                    </h4>
                    <p class="text-muted mb-0 fs-5">{{ $quiz->title }}</p>
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
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Thông tin bài kiểm tra
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-file-text fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tiêu đề</small>
                                        <strong class="text-dark">{{ $quiz->title }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-people fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Lớp học</small>
                                        <strong class="text-dark">{{ $quiz->classroom->name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            @if ($quiz->description)
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded">
                                        <small class="text-muted d-block mb-2">Mô tả</small>
                                        <p class="mb-0 text-dark">{{ $quiz->description }}</p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-question-circle fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Số câu hỏi</small>
                                        <strong class="text-dark">{{ count($questions) }} câu</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 50px; height: 50px;">
                                        <i class="bi bi-star fs-4"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Tổng điểm</small>
                                        <strong class="text-dark">{{ $quiz->getMaxScore() }} điểm</strong>
                                    </div>
                                </div>
                            </div>
                            @if ($quiz->time_limit)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-clock fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Thời gian làm bài</small>
                                            <strong class="text-dark">{{ $quiz->time_limit }} phút</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($quiz->deadline)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-calendar-check fs-4"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Hạn nộp</small>
                                            <strong class="text-dark">{{ $quiz->deadline->format('d/m/Y H:i') }}</strong>
                                            @if ($quiz->isExpired())
                                                <span class="badge bg-danger ms-2">Hết hạn</span>
                                            @else
                                                <span class="badge bg-success ms-2">Còn hạn</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Danh sách câu hỏi -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-gradient-primary text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-question-circle me-2"></i>Danh sách câu hỏi ({{ count($questions) }})
                        </h6>
                    </div>
                    <div class="card-body">
                        @if (count($questions) > 0)
                            <div class="accordion" id="questionsAccordion">
                                @foreach ($questions as $index => $question)
                                    <div class="accordion-item border-0 mb-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-light border-0">
                                                <button class="btn btn-link text-decoration-none w-100 text-start p-0" 
                                                        type="button" data-bs-toggle="collapse" 
                                                        data-bs-target="#question{{ $index }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                                 style="width: 40px; height: 40px; font-weight: bold;">
                                                                {{ $index + 1 }}
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($question['question'], 60) }}</h6>
                                                                <div class="d-flex gap-2">
                                                                    <span class="badge bg-success">{{ $question['score'] }} điểm</span>
                                                                    <span class="badge bg-info">Trắc nghiệm</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <i class="bi bi-chevron-down text-muted"></i>
                                                    </div>
                                                </button>
                                            </div>
                                            <div id="question{{ $index }}" 
                                                 class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
                                                <div class="card-body bg-white">
                                                    <!-- Nội dung câu hỏi -->
                                                    <div class="mb-4">
                                                        <h6 class="text-primary fw-bold mb-2">
                                                            <i class="bi bi-chat-quote me-2"></i>Nội dung câu hỏi
                                                        </h6>
                                                        <div class="bg-light p-3 rounded border-start border-primary border-4">
                                                            <p class="mb-0 fw-medium">{{ $question['question'] }}</p>
                                                        </div>
                                                    </div>

                                                    <!-- Các lựa chọn -->
                                                    <div class="mb-4">
                                                        <h6 class="text-primary fw-bold mb-3">
                                                            <i class="bi bi-list-check me-2"></i>Các lựa chọn
                                                        </h6>
                                                        <div class="row g-3">
                                                            @foreach ($question['options'] as $optionIndex => $option)
                                                                <div class="col-12">
                                                                    <div class="card border-2 {{ $question['correct_answer'] === chr(65 + $optionIndex) ? 'border-success bg-success-light' : 'border-light' }} h-100">
                                                                        <div class="card-body p-3">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="me-3">
                                                                                    @if ($question['correct_answer'] === chr(65 + $optionIndex))
                                                                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                                                             style="width: 30px; height: 30px; font-weight: bold;">
                                                                                            <i class="bi bi-check-lg"></i>
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                                                             style="width: 30px; height: 30px; font-weight: bold;">
                                                                                            {{ chr(65 + $optionIndex) }}
                                                                                        </div>
                                                                                    @endif
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <p class="mb-0 fw-medium">{{ $option }}</p>
                                                                                </div>
                                                                                @if ($question['correct_answer'] === chr(65 + $optionIndex))
                                                                                    <div class="ms-3">
                                                                                        <span class="badge bg-success fs-6">
                                                                                            <i class="bi bi-star-fill me-1"></i>Đáp án đúng
                                                                                        </span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>

                                                    <!-- Giải thích -->
                                                    @if (!empty($question['explanation']))
                                                        <div class="mb-3">
                                                            <h6 class="text-primary fw-bold mb-2">
                                                                <i class="bi bi-lightbulb me-2"></i>Giải thích
                                                            </h6>
                                                            <div class="bg-warning-light p-3 rounded border-start border-warning border-4">
                                                                <p class="mb-0">{{ $question['explanation'] }}</p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 80px; height: 80px;">
                                    <i class="bi bi-question-circle fs-1 text-muted"></i>
                                </div>
                                <h6 class="text-muted mb-2">Không có câu hỏi nào</h6>
                                <p class="text-muted mb-0">Bài kiểm tra này chưa có câu hỏi nào.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Thống kê kết quả -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-gradient-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Thống kê kết quả
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-people fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Số học sinh đã làm</small>
                                        <strong class="text-dark fs-5">{{ $results->count() }}</strong>
                                    </div>
                                </div>
                            </div>
                            @if ($results->count() > 0)
                                @php
                                    $avgScore = $results->avg('score');
                                    $maxScore = $results->max('score');
                                    $minScore = $results->min('score');
                                    $passRate = ($results->where('score', '>=', $quiz->getMaxScore() * 0.5)->count() / $results->count()) * 100;
                                @endphp
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-bar-chart fs-5"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Điểm trung bình</small>
                                            <strong class="text-dark fs-5">{{ number_format($avgScore, 1) }}/{{ $quiz->getMaxScore() }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-trophy fs-5"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Điểm cao nhất</small>
                                            <strong class="text-dark fs-5">{{ $maxScore }}/{{ $quiz->getMaxScore() }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-percent fs-5"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Tỷ lệ đạt</small>
                                            <strong class="text-dark fs-5">{{ number_format($passRate, 1) }}%</strong>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="col-12">
                                    <div class="text-center py-4">
                                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                             style="width: 60px; height: 60px;">
                                            <i class="bi bi-graph-down fs-2 text-muted"></i>
                                        </div>
                                        <h6 class="text-muted mb-2">Chưa có kết quả</h6>
                                        <p class="text-muted mb-0 small">Học sinh chưa làm bài kiểm tra này.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Thông tin bổ sung -->
                <div class="card shadow-sm">
                    <div class="card-header bg-gradient-info text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Thông tin bổ sung
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-calendar-plus fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Ngày tạo</small>
                                        <strong class="text-dark">{{ $quiz->created_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3 bg-light rounded">
                                    <div class="bg-dark text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Cập nhật lần cuối</small>
                                        <strong class="text-dark">{{ $quiz->updated_at->format('d/m/Y H:i') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
