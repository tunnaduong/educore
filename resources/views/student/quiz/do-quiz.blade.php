<x-layouts.dash-student active="quizzes">
    @include('components.language')
    @if ($accessDenied)
        <div class="container-fluid">
            <div class="row justify-content-center align-items-center" style="min-height: 60vh;">
                <div class="col-md-8 text-center">
                    <div class="alert alert-warning py-5">
                        <i class="bi bi-exclamation-triangle display-4 mb-3"></i>
                        <h3 class="mb-3">403 | Không thể truy cập bài kiểm tra</h3>
                        <p class="lead">{{ $accessDenied }}</p>
                        <a href="{{ route('student.quizzes.index') }}" wire:navigate class="btn btn-primary mt-3">
                            <i class="bi bi-arrow-left mr-1"></i>Quay lại danh sách bài kiểm tra
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif ($isFinished && $result)
        <!-- Hiển thị kết quả -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">
                                <i class="bi bi-check-circle mr-2"></i>Hoàn thành bài kiểm tra
                            </h4>
                        </div>
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <h2 class="text-primary">{{ $quiz->title }}</h2>
                                <p class="text-muted">{{ $quiz->description }}</p>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-primary mb-0">{{ $result->score }}</h3>
                                            <small class="text-muted">Điểm số</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-info mb-0">
                                                {{ $result->duration ? gmdate('H:i:s', $result->duration) : '-' }}</h3>
                                            <small class="text-muted">Thời gian làm bài</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-success mb-0">{{ count($questions) }}</h3>
                                            <small class="text-muted">Tổng số câu hỏi</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('student.quizzes.index') }}" wire:navigate class="btn btn-primary">
                                    <i class="bi bi-house mr-2"></i>Về trang chủ
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Giao diện làm bài kiểm tra -->
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h4 class="mb-1 text-primary">{{ $quiz->title }}</h4>
                                    <p class="text-muted mb-0">{{ $quiz->description }}</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    @if ($timeRemaining)
                                        <div class="d-inline-block bg-warning text-dark px-3 py-2 rounded">
                                            <i class="bi bi-clock mr-2"></i>
                                            <span id="timer">{{ gmdate('H:i:s', $timeRemaining) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar - Danh sách câu hỏi -->
                <div class="col-lg-3">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-list-ul mr-2"></i>Danh sách câu hỏi
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-2">
                                @foreach ($questions as $index => $question)
                                    <div class="col-4">
                                        <button wire:click="goToQuestion({{ $index }})"
                                            class="btn btn-sm w-100 {{ $index === $currentQuestionIndex ? 'btn-primary' : (isset($answers[$index]) && $answers[$index] ? 'btn-success' : 'btn-outline-secondary') }}">
                                            {{ $index + 1 }}
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nội dung câu hỏi -->
                <div class="col-lg-9">
                    @if (count($questions) > 0)
                        @php $currentQuestion = $questions[$currentQuestionIndex]; @endphp

                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="bi bi-question-circle mr-2"></i>
                                        Câu hỏi {{ $currentQuestionIndex + 1 }} / {{ count($questions) }}
                                    </h6>
                                    <span class="badge bg-primary">{{ ucfirst($currentQuestion['type']) }}</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Nội dung câu hỏi -->
                                <div class="mb-4">
                                    <h5>{{ $currentQuestion['question'] }}</h5>

                                    @if (isset($currentQuestion['audio']))
                                        <div class="mb-3">
                                            <audio controls class="w-100">
                                                <source src="{{ asset('storage/' . $currentQuestion['audio']) }}"
                                                    type="audio/mpeg">
                                                Trình duyệt không hỗ trợ audio.
                                            </audio>
                                        </div>
                                    @endif
                                </div>

                                <!-- Form trả lời -->
                                <div class="mb-4">
                                    @switch($currentQuestion['type'])
                                        @case('multiple_choice')
                                            @foreach ($currentQuestion['options'] as $option)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="radio"
                                                        wire:model="answers.{{ $currentQuestionIndex }}"
                                                        value="{{ $option }}"
                                                        id="option_{{ $currentQuestionIndex }}_{{ $loop->index }}">
                                                    <label class="form-check-label"
                                                        for="option_{{ $currentQuestionIndex }}_{{ $loop->index }}">
                                                        {{ $option }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @break

                                        @case('fill_blank')
                                            <div class="form-group">
                                                <input type="text" class="form-control"
                                                    wire:model="answers.{{ $currentQuestionIndex }}"
                                                    placeholder="Nhập câu trả lời...">
                                            </div>
                                        @break

                                        @case('drag_drop')
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle mr-2"></i>
                                                Kéo thả các đáp án vào vị trí đúng
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control" wire:model="answers.{{ $currentQuestionIndex }}">
                                                    <option value="">Chọn đáp án...</option>
                                                    @foreach ($currentQuestion['options'] as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @break

                                        @case('essay')
                                            <div class="form-group">
                                                <textarea class="form-control" rows="6" wire:model="answers.{{ $currentQuestionIndex }}"
                                                    placeholder="Viết câu trả lời của bạn..."></textarea>
                                            </div>
                                        @break
                                    @endswitch
                                </div>

                                <!-- Nút điều hướng -->
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary" wire:click="previousQuestion"
                                        {{ $currentQuestionIndex === 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-arrow-left mr-2"></i>Câu trước
                                    </button>

                                    @if ($currentQuestionIndex === count($questions) - 1)
                                        <button class="btn btn-success" wire:click="submitQuiz"
                                            onclick="return confirm('Bạn có chắc chắn muốn nộp bài?')">
                                            <i class="bi bi-check-circle mr-2"></i>Nộp bài
                                        </button>
                                    @else
                                        <button class="btn btn-primary" wire:click="nextQuestion">
                                            Câu tiếp<i class="bi bi-arrow-right ml-2"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                                <h5>Không có câu hỏi nào</h5>
                                <p class="text-muted">Bài kiểm tra này chưa có câu hỏi.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- JavaScript cho timer -->
        @if ($timeRemaining)
            <script>
                let timeRemaining = {{ $timeRemaining }};
                const timerElement = document.getElementById('timer');

                // Cảnh báo khi người dùng cố gắng reload hoặc rời khỏi trang
                window.onbeforeunload = function() {
                    return 'Nếu bạn tải lại hoặc rời khỏi trang, bài kiểm tra sẽ bị nộp tự động và bạn không thể tiếp tục làm tiếp!';
                };

                const timer = setInterval(function() {
                    timeRemaining--;

                    if (timerElement) {
                        const hours = Math.floor(timeRemaining / 3600);
                        const minutes = Math.floor((timeRemaining % 3600) / 60);
                        const seconds = timeRemaining % 60;

                        timerElement.textContent =
                            (hours < 10 ? '0' : '') + hours + ':' +
                            (minutes < 10 ? '0' : '') + minutes + ':' +
                            (seconds < 10 ? '0' : '') + seconds;
                    }

                    if (timeRemaining <= 0) {
                        clearInterval(timer);
                        window.onbeforeunload = null; // Cho phép rời trang khi đã nộp
                        @this.call('submitQuiz');
                    }
                }, 1000);
            </script>
        @endif
    @endif
</x-layouts.dash-student>
