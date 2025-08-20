<x-layouts.dash-teacher active="ai">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-robot text-primary"></i>
                        @lang('general.ai_assistant') - @lang('general.smart_teaching')
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        Khám phá các công cụ AI mạnh mẽ để hỗ trợ công việc giảng dạy của bạn.
                        Từ chấm điểm tự động đến tạo bài kiểm tra, AI sẽ giúp bạn tiết kiệm thời gian và nâng cao chất
                        lượng giảng dạy.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chấm điểm tự động bằng AI -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bi bi-journal-check text-success"></i>
                        @lang('general.ai_grading')
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Sử dụng AI để chấm điểm các bài tập một cách nhanh chóng và chính xác.
                        AI sẽ phân tích nội dung và đưa ra đánh giá chi tiết.
                    </p>

                    @if ($recentSubmissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Học sinh</th>
                                        <th>Bài tập</th>
                                        <th>Loại nộp</th>
                                        <th>Ngày nộp</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSubmissions as $submission)
                                        <tr>
                                            <td>{{ $submission->student?->user?->name }}</td>
                                            <td>{{ $submission->assignment->title }}</td>
                                            <td>
                                                @if ($submission->submission_type === 'text')
                                                    <span class="badge badge-info">Văn bản</span>
                                                @elseif ($submission->submission_type === 'file')
                                                    <span class="badge badge-secondary">File</span>
                                                @elseif ($submission->submission_type === 'image')
                                                    <span class="badge badge-warning">Hình ảnh</span>
                                                @elseif ($submission->submission_type === 'audio')
                                                    <span class="badge badge-danger">Âm thanh</span>
                                                @elseif ($submission->submission_type === 'video')
                                                    <span class="badge badge-dark">Video</span>
                                                @else
                                                    <span class="badge badge-light">{{ ucfirst($submission->submission_type) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : $submission->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if ($submission->score || $submission->ai_score)
                                                    <span class="badge badge-success">Đã chấm</span>
                                                @else
                                                    <span class="badge badge-warning">Chưa chấm</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$submission->score && !$submission->ai_score)
                                                    <a href="{{ route('teacher.ai.grading', $submission->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-robot"></i>
                                                        Chấm điểm bằng AI
                                                    </a>
                                                @else
                                                    <span class="text-muted">Đã chấm</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            Hiện tại không có bài nộp nào cần chấm điểm.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tạo Quiz bằng AI -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bi bi-patch-question text-info"></i>
                        @lang('general.ai_quiz_generator')
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        Tạo các bài kiểm tra chất lượng cao dựa trên nội dung bài giảng hoặc bài tập.
                        AI sẽ tạo ra các câu hỏi đa dạng và phù hợp với mức độ học sinh.
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-text text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Tạo Quiz từ bài tập</h5>
                                    <p class="text-muted">Tạo quiz dựa trên nội dung bài tập hiện có</p>
                                    <a href="{{ route('teacher.ai.quiz-generator') }}" class="btn btn-primary">
                                        <i class="bi bi-robot"></i>
                                        Tạo Quiz từ bài tập
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-collection text-success" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Tạo ngân hàng câu hỏi</h5>
                                    <p class="text-muted">Tạo bộ câu hỏi đa dạng cho nhiều bài kiểm tra</p>
                                    <a href="{{ route('teacher.ai.question-bank-generator') }}" class="btn btn-success">
                                        <i class="bi bi-robot"></i>
                                        Tạo ngân hàng câu hỏi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê AI -->
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $recentSubmissions->count() }}</h4>
                            <p class="mb-0">@lang('general.pending_grading')</p>
                        </div>
                        <div>
                            <i class="bi bi-journal-check" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $availableAssignments->count() }}</h4>
                            <p class="mb-0">@lang('general.can_create_quiz')</p>
                        </div>
                        <div>
                            <i class="bi bi-patch-question" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $classrooms->count() }}</h4>
                            <p class="mb-0">@lang('general.active_classes')</p>
                        </div>
                        <div>
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hướng dẫn sử dụng -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bi bi-lightbulb text-warning"></i>
                        @lang('general.ai_guide')
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-1-circle text-primary" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">Chấm điểm tự động</h5>
                                <p class="text-muted">Chọn bài nộp cần chấm và để AI phân tích nội dung, đưa ra điểm số
                                    và nhận xét</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-2-circle text-success" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">Tạo Quiz</h5>
                                <p class="text-muted">Nhập nội dung bài giảng hoặc chọn bài tập để AI tạo ra các câu hỏi
                                    kiểm tra phù hợp</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-3-circle text-info" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">Ngân hàng câu hỏi</h5>
                                <p class="text-muted">Tạo bộ câu hỏi đa dạng để sử dụng cho nhiều bài kiểm tra khác
                                    nhau</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
