<x-layouts.dash-admin active="quizzes">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
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
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-warning">
                        <i class="bi bi-pencil mr-2"></i>Sửa
                    </a>
                    <a href="{{ route('quizzes.results', $quiz) }}" class="btn btn-info">
                        <i class="bi bi-graph-up mr-2"></i>Kết quả
                    </a>
                    <button type="button" class="btn btn-danger"
                        onclick="confirm('Bạn có chắc chắn muốn xóa bài kiểm tra này?') || event.stopImmediatePropagation()"
                        wire:click="deleteQuiz">
                        <i class="bi bi-trash mr-2"></i>Xóa
                    </button>
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
                            <div class="fw-medium">{{ $classroom->name ?? 'N/A' }}</div>
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
                            <div class="fw-medium">{{ $quiz->getQuestionCount() }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">Tổng điểm tối đa</label>
                            <div class="fw-medium">{{ $quiz->getMaxScore() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up mr-2"></i>Thống kê
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $results->count() }}</h4>
                                    <small class="text-muted">Đã làm bài</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0">
                                    {{ $results->where('score', '>=', 80)->count() }}
                                </h4>
                                <small class="text-muted">Đạt (≥80%)</small>
                            </div>
                        </div>
                        @if ($results->count() > 0)
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-info mb-0">
                                        {{ round($results->avg('score'), 1) }}%
                                    </h5>
                                    <small class="text-muted">Điểm trung bình</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-warning mb-0">
                                        {{ $results->max('score') }}%
                                    </h5>
                                    <small class="text-muted">Điểm cao nhất</small>
                                </div>
                            </div>
                        @endif
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
                                            <span class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                            <span class="badge bg-info">{{ $question['score'] }} điểm</span>
                                        </div>
                                    </div>
                                    <div class="fw-medium mb-2">{{ $question['question'] }}</div>

                                    @if ($question['type'] === 'multiple_choice' && isset($question['options']))
                                        <div class="ms-3">
                                            @foreach ($question['options'] as $optionIndex => $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" disabled
                                                        {{ $option === $question['correct_answer'] ? 'checked' : '' }}>
                                                    <label
                                                        class="form-check-label {{ $option === $question['correct_answer'] ? 'fw-bold text-success' : '' }}">
                                                        {{ $option }}
                                                        @if ($option === $question['correct_answer'])
                                                            <i class="bi bi-check-circle-fill text-success ml-1"></i>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($question['type'] === 'fill_blank')
                                        <div class="ms-3">
                                            <div class="alert alert-success mb-0">
                                                <strong>Đáp án:</strong> {{ $question['correct_answer'] }}
                                            </div>
                                        </div>
                                    @elseif($question['type'] === 'essay')
                                        <div class="ms-3">
                                            <div class="alert alert-info mb-0">
                                                <strong>Loại câu hỏi:</strong> Tự luận (cần chấm thủ công)
                                            </div>
                                        </div>
                                    @endif
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

                <!-- Danh sách học viên và kết quả -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-people mr-2"></i>Danh sách học viên & kết quả
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Học viên</th>
                                            <th>Email</th>
                                            <th>Trạng thái</th>
                                            <th>Điểm số</th>
                                            <th>Thời gian làm</th>
                                            <th>Thời gian nộp</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            @php
                                                $result = $student->student
                                                    ? $results->firstWhere('student_id', $student->student->id)
                                                    : null;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->email ?? 'Chưa có' }}</td>
                                                <td>
                                                    @if ($result)
                                                        @if ($result->isOnTime())
                                                            <span class="badge bg-success">Đúng hạn</span>
                                                        @else
                                                            <span class="badge bg-warning">Trễ hạn</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">Chưa làm</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result)
                                                        <span
                                                            class="fw-medium {{ $result->score >= 80 ? 'text-success' : ($result->score >= 60 ? 'text-warning' : 'text-danger') }}">
                                                            {{ $result->score }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result && $result->duration)
                                                        <span
                                                            class="text-muted">{{ $result->getDurationString() }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result && $result->submitted_at)
                                                        <div class="fw-medium">
                                                            {{ $result->submitted_at->format('d/m/Y') }}</div>
                                                        <small
                                                            class="text-muted">{{ $result->submitted_at->format('H:i') }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result)
                                                        <a href="{{ route('quizzes.results', $quiz) }}?student={{ $student->id }}"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="Xem chi tiết">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-people fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">Chưa có học viên nào</h5>
                                <p class="text-muted">Vui lòng gán học viên vào lớp học.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

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
