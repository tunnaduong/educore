<x-layouts.dash-student active="tests">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('student.quizzes.index') }}" wire:navigate class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách bài kiểm tra
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-clipboard-check me-2"></i>Xem lại bài kiểm tra
            </h4>
            <p class="text-muted mb-0">{{ $quiz->title }}</p>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $result->score }}%</h3>
                        <small>Điểm số</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $result->getDurationString() }}</h3>
                        <small>Thời gian làm</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $result->getCorrectAnswersCount() }}</h3>
                        <small>Câu trả lời đúng</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ count($quiz->questions) }}</h3>
                        <small>Tổng câu hỏi</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle me-2"></i>Thông tin bài kiểm tra
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Lớp học:</small>
                                <div class="fw-medium">{{ $quiz->classroom ? $quiz->classroom->name : 'N/A' }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Hạn nộp:</small>
                                <div class="fw-medium">
                                    @if($quiz->deadline)
                                        {{ $quiz->deadline->format('d/m/Y H:i') }}
                                    @else
                                        Không có hạn
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">Bắt đầu làm:</small>
                                <div class="fw-medium">
                                    @if($result->started_at)
                                        {{ $result->started_at->format('d/m/Y H:i') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Nộp bài:</small>
                                <div class="fw-medium">
                                    @if($result->submitted_at)
                                        {{ $result->submitted_at->format('d/m/Y H:i') }}
                                    @else
                                        Chưa nộp
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>Thống kê câu trả lời
                        </h6>
                    </div>
                    <div class="card-body">
                        @php
                            $correctCount = 0;
                            $incorrectCount = 0;
                            $unansweredCount = 0;
                            
                            foreach($quiz->questions as $index => $question) {
                                $status = $this->getQuestionStatus($index);
                                if ($status === 'correct') $correctCount++;
                                elseif ($status === 'incorrect') $incorrectCount++;
                                else $unansweredCount++;
                            }
                        @endphp
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="text-success">
                                    <h4 class="mb-0">{{ $correctCount }}</h4>
                                    <small>Đúng</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-danger">
                                    <h4 class="mb-0">{{ $incorrectCount }}</h4>
                                    <small>Sai</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-secondary">
                                    <h4 class="mb-0">{{ $unansweredCount }}</h4>
                                    <small>Chưa trả lời</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách câu hỏi -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Chi tiết từng câu hỏi
                </h6>
            </div>
            <div class="card-body">
                <!-- Navigation câu hỏi -->
                <div class="mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($quiz->questions as $index => $question)
                            <button type="button" 
                                    class="btn btn-sm {{ $selectedQuestion == $index ? 'btn-primary' : 'btn-outline-' . $this->getQuestionStatusClass($index) }}"
                                    wire:click="selectQuestion({{ $index }})">
                                Câu {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Chi tiết câu hỏi được chọn -->
                @if(isset($quiz->questions[$selectedQuestion]))
                    @php
                        $question = $quiz->questions[$selectedQuestion];
                        $answer = $result->answers[$selectedQuestion] ?? null;
                        $status = $this->getQuestionStatus($selectedQuestion);
                    @endphp
                    
                    <div class="border rounded p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="badge bg-primary me-2">Câu {{ $selectedQuestion + 1 }}</span>
                                <span class="badge bg-secondary me-2">{{ ucfirst($question['type']) }}</span>
                                <span class="badge bg-{{ $this->getQuestionStatusClass($selectedQuestion) }}">
                                    {{ $this->getQuestionStatusText($selectedQuestion) }}
                                </span>
                            </div>
                        </div>

                        <div class="fw-medium mb-3 fs-5">{{ $question['question'] }}</div>

                        @if($question['type'] === 'multiple_choice')
                            <div class="mb-3">
                                <h6>Các lựa chọn:</h6>
                                @foreach($question['options'] as $optionIndex => $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" disabled
                                               {{ $answer == $option ? 'checked' : '' }}
                                               id="option_{{ $selectedQuestion }}_{{ $optionIndex }}">
                                        <label class="form-check-label {{ $option === $question['correct_answer'] ? 'text-success fw-bold' : ($answer == $option && $answer !== $question['correct_answer'] ? 'text-danger' : '') }}" 
                                               for="option_{{ $selectedQuestion }}_{{ $optionIndex }}">
                                            {{ $option }}
                                            @if($option === $question['correct_answer'])
                                                <i class="bi bi-check-circle-fill text-success ms-2"></i>
                                            @elseif($answer == $option && $answer !== $question['correct_answer'])
                                                <i class="bi bi-x-circle-fill text-danger ms-2"></i>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($question['type'] === 'fill_blank')
                            <div class="mb-3">
                                <h6>Điền vào chỗ trống:</h6>
                                <div class="alert alert-light">
                                    <strong>Đáp án của bạn:</strong> {{ $answer ?: 'Chưa trả lời' }}
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <strong>Đáp án của bạn:</strong><br>
                                    {{ $answer ?: 'Chưa trả lời' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <strong>Đáp án đúng:</strong><br>
                                    @if(is_array($question['correct_answer']))
                                        {{ implode(', ', $question['correct_answer']) }}
                                    @else
                                        {{ $question['correct_answer'] }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(isset($question['explanation']) && $question['explanation'])
                            <div class="alert alert-warning">
                                <strong>Giải thích:</strong><br>
                                {{ $question['explanation'] }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-student>
