<x-layouts.dash-admin active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('quizzes.show', $quiz) }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại chi tiết bài kiểm tra
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-graph-up mr-2"></i>Kết quả bài kiểm tra
            </h4>
            <p class="text-muted mb-0">{{ $quiz->title }}</p>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $totalResults }}</h3>
                        <small>Tổng số bài làm</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $passCount }}</h3>
                        <small>Đạt (≥80%)</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $averageScore }}%</h3>
                        <small>Điểm trung bình</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $maxScore }}%</h3>
                        <small>Điểm cao nhất</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm học viên</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="Tìm theo tên hoặc email...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lọc theo học viên</label>
                        <select class="form-control" wire:model.live="selectedStudent">
                            <option value="">Tất cả học viên</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Lọc theo điểm</label>
                        <select class="form-control" wire:model.live="filterScore">
                            <option value="">Tất cả điểm</option>
                            <option value="excellent">Xuất sắc (≥90%)</option>
                            <option value="good">Tốt (80-89%)</option>
                            <option value="average">Trung bình (60-79%)</option>
                            <option value="poor">Yếu (<60%)< /option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise mr-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng kết quả -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-table mr-2"></i>Danh sách kết quả
                </h6>
            </div>
            <div class="card-body">
                @if ($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Học viên</th>
                                    <th>Điểm số</th>
                                    <th>Thời gian làm</th>
                                    <th>Thời gian nộp</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm mr-3">
                                                    <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">
                                                        {{ $result->user ? $result->user->name : 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-medium {{ $result->score >= 80 ? 'text-success' : ($result->score >= 60 ? 'text-warning' : 'text-danger') }}">
                                                {{ $result->score }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if ($result->duration)
                                                <span class="text-muted">{{ $result->getDurationString() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->submitted_at)
                                                <div class="fw-medium">{{ $result->submitted_at->format('d/m/Y') }}
                                                </div>
                                                <small
                                                    class="text-muted">{{ $result->submitted_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->isOnTime())
                                                <span class="badge bg-success">Đúng hạn</span>
                                            @else
                                                <span class="badge bg-warning">Trễ hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                wire:click="selectStudent({{ $result->student_id }})"
                                                title="Xem chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div>
                        {{ $results->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-graph-down fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Không có kết quả nào</h5>
                        <p class="text-muted">Chưa có học viên nào làm bài kiểm tra này.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Chi tiết kết quả của học viên được chọn -->
        @if ($selectedStudent && $results->count() > 0)
            @php
                $selectedResult = $results->firstWhere('student_id', $selectedStudent);
            @endphp
            @if ($selectedResult)
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-person-check mr-2"></i>Chi tiết kết quả:
                                {{ $selectedResult->student->name }}
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                wire:click="clearStudentFilter">
                                <i class="bi bi-x mr-1"></i>Đóng
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-primary mb-0">{{ $selectedResult->score }}%</h4>
                                    <small class="text-muted">Điểm số</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-info mb-0">{{ $selectedResult->getDurationString() }}</h4>
                                    <small class="text-muted">Thời gian làm</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-success mb-0">{{ $selectedResult->getCorrectAnswersCount() }}</h4>
                                    <small class="text-muted">Câu trả lời</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-warning mb-0">{{ count($quiz->questions) }}</h4>
                                    <small class="text-muted">Tổng câu hỏi</small>
                                </div>
                            </div>
                        </div>

                        <!-- Chi tiết từng câu hỏi -->
                        <h6 class="mb-3">Chi tiết từng câu hỏi:</h6>
                        @foreach ($quiz->questions as $index => $question)
                            @php
                                $answer = $selectedResult->answers[$index] ?? null;
                                $isCorrect = false;

                                if ($question['type'] === 'multiple_choice') {
                                    $isCorrect = $answer === $question['correct_answer'];
                                } elseif ($question['type'] === 'fill_blank') {
                                    $correctAnswers = is_array($question['correct_answer'])
                                        ? $question['correct_answer']
                                        : [$question['correct_answer']];
                                    $isCorrect = in_array(
                                        strtolower(trim($answer)),
                                        array_map('strtolower', $correctAnswers),
                                    );
                                }
                            @endphp

                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-primary mr-2">Câu {{ $index + 1 }}</span>
                                        <span class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                        <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }}">
                                            {{ $isCorrect ? 'Đúng' : 'Sai' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="fw-medium mb-2">{{ $question['question'] }}</div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info mb-0">
                                            <strong>Đáp án của học viên:</strong><br>
                                            {{ $answer ?: 'Chưa trả lời' }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success mb-0">
                                            <strong>Đáp án đúng:</strong><br>
                                            @if (isset($question['correct_answer']))
                                                {{ $question['correct_answer'] }}
                                            @else
                                                <span class="text-white">Cần chấm thủ công</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</x-layouts.dash-admin>
