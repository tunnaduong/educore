<x-layouts.dash-student active="quizzes">
    <style>
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes shakeX {

            0%,
            100% {
                transform: translateX(0);
            }

            10%,
            30%,
            50%,
            70%,
            90% {
                transform: translateX(-5px);
            }

            20%,
            40%,
            60%,
            80% {
                transform: translateX(5px);
            }
        }

        @keyframes fadeInOut {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .animate__pulse {
            animation: pulse 1s infinite;
        }

        .animate__shakeX {
            animation: shakeX 0.5s infinite;
        }

        .animate__fadeInOut {
            animation: fadeInOut 2s infinite;
        }

        #timer-container {
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border: 2px solid transparent;
        }

        #timer-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        #timer {
            font-size: 1.2rem;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        .timer-warning {
            border-color: #ffc107 !important;
            box-shadow: 0 0 10px rgba(255, 193, 7, 0.5) !important;
        }

        .timer-urgent {
            border-color: #dc3545 !important;
            box-shadow: 0 0 15px rgba(220, 53, 69, 0.6) !important;
        }

        .timer-normal {
            border-color: #17a2b8 !important;
            box-shadow: 0 0 8px rgba(23, 162, 184, 0.4) !important;
        }
    </style>
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
                                    @if ($quiz->time_limit)
                                        <small class="text-info">
                                            <i class="bi bi-clock-history mr-1"></i>
                                            Thời gian làm bài: <strong>{{ $quiz->time_limit }} phút</strong>
                                        </small>
                                    @else
                                        <small class="text-success">
                                            <i class="bi bi-infinity mr-1"></i>
                                            Không giới hạn thời gian
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-end">
                                    @if ($timeRemaining)
                                        <div class="d-inline-block px-3 py-2 rounded {{ $this->getTimerClass() }}" id="timer-container">
                                            <i class="bi bi-clock mr-2"></i>
                                            <span id="timer" class="fw-bold fs-5">
                                                {{ $this->getFormattedTimeRemaining() }}
                                            </span>
                                            @if($this->shouldShowWarning())
                                                <span class="badge bg-warning text-dark ms-2 animate__animated animate__pulse">
                                                    <i class="bi bi-exclamation-triangle"></i> Cảnh báo
                                                </span>
                                            @endif
                                            @if($this->shouldShowUrgentWarning())
                                                <span class="badge bg-danger text-white ms-2 animate__animated animate__pulse">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Khẩn cấp
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">Tiến độ làm bài</small>
                                    <small class="text-muted">{{ $currentQuestionIndex + 1 }} /
                                        {{ count($questions) }}</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ count($questions) > 0 ? (($currentQuestionIndex + 1) / count($questions)) * 100 : 0 }}%"
                                        aria-valuenow="{{ $currentQuestionIndex + 1 }}" aria-valuemin="0"
                                        aria-valuemax="{{ count($questions) }}">
                                    </div>
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
                                            class="btn btn-sm w-100 position-relative {{ $index === $currentQuestionIndex ? 'btn-primary' : (isset($answers[$index]) && $answers[$index] ? 'btn-success' : 'btn-outline-secondary') }}">
                                            {{ $index + 1 }}
                                            @if (isset($answers[$index]) && $answers[$index])
                                                <span
                                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-light text-dark"
                                                    style="font-size: 0.6rem;">
                                                    ✓
                                                </span>
                                            @endif
                                        </button>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Legend -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="d-flex align-items-center mb-1">
                                    <div class="btn btn-sm btn-outline-secondary me-2"
                                        style="width: 20px; height: 20px; padding: 0;"></div>
                                    <small class="text-muted">Chưa trả lời</small>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="btn btn-sm btn-success me-2"
                                        style="width: 20px; height: 20px; padding: 0;"></div>
                                    <small class="text-muted">Đã trả lời</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="btn btn-sm btn-primary me-2"
                                        style="width: 20px; height: 20px; padding: 0;"></div>
                                    <small class="text-muted">Câu hiện tại</small>
                                </div>
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
                                                        wire:model.live="answers.{{ $currentQuestionIndex }}"
                                                        wire:change="saveAnswer" value="{{ $option }}"
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
                                                    wire:model.live.debounce.500ms="answers.{{ $currentQuestionIndex }}"
                                                    wire:change="saveAnswer" placeholder="Nhập câu trả lời...">
                                            </div>
                                        @break

                                        @case('drag_drop')
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle mr-2"></i>
                                                Kéo thả các đáp án vào vị trí đúng
                                            </div>
                                            <div class="form-group">
                                                <select class="form-control"
                                                    wire:model.live="answers.{{ $currentQuestionIndex }}"
                                                    wire:change="saveAnswer">
                                                    <option value="">Chọn đáp án...</option>
                                                    @foreach ($currentQuestion['options'] as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @break

                                        @case('essay')
                                            <div class="form-group">
                                                <textarea class="form-control" rows="6" wire:model.live.debounce.1000ms="answers.{{ $currentQuestionIndex }}"
                                                    wire:change="saveAnswer" placeholder="Viết câu trả lời của bạn..."></textarea>
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
                                            onclick="return confirmSubmit()">
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
                const timerContainer = document.getElementById('timer-container');

                // Cảnh báo khi người dùng cố gắng reload hoặc rời khỏi trang
                window.onbeforeunload = function() {
                    return 'Nếu bạn tải lại hoặc rời khỏi trang, bài kiểm tra sẽ bị nộp tự động và bạn không thể tiếp tục làm tiếp!';
                };

                // Function để format thời gian - chỉ hiển thị phút:giây
                function formatTime(seconds) {
                    const minutes = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    
                    // Luôn hiển thị định dạng MM:SS
                    return (minutes < 10 ? '0' : '') + minutes + ':' +
                           (secs < 10 ? '0' : '') + secs;
                }

                // Function để cập nhật class CSS cho timer
                function updateTimerClass() {
                    if (!timerContainer) return;

                    // Xóa tất cả class cũ
                    timerContainer.classList.remove('timer-normal', 'timer-warning', 'timer-urgent');

                    if (timeRemaining <= 300) { // 5 phút cuối
                        timerContainer.className = 'd-inline-block bg-danger text-white px-3 py-2 rounded animate__animated animate__pulse timer-urgent';
                    } else if (timeRemaining <= 600) { // 10 phút cuối
                        timerContainer.className = 'd-inline-block bg-warning text-dark px-3 py-2 rounded animate__animated animate__pulse timer-warning';
                    } else {
                        timerContainer.className = 'd-inline-block bg-info text-white px-3 py-2 rounded timer-normal';
                    }
                }

                // Function để cập nhật cảnh báo
                function updateWarnings() {
                    // Cảnh báo khi còn 5 phút
                    if (timeRemaining === 300) {
                        if (Notification.permission === 'granted') {
                            new Notification('Cảnh báo thời gian', {
                                body: 'Chỉ còn 5 phút để hoàn thành bài kiểm tra!',
                                icon: '/favicon.ico'
                            });
                        }
                        // Hiển thị alert
                        alert('⚠️ CẢNH BÁO: Chỉ còn 5 phút để hoàn thành bài kiểm tra!');
                    }

                    // Cảnh báo khi còn 1 phút
                    if (timeRemaining === 60) {
                        if (Notification.permission === 'granted') {
                            new Notification('Cảnh báo thời gian', {
                                body: 'Chỉ còn 1 phút để hoàn thành bài kiểm tra!',
                                icon: '/favicon.ico'
                            });
                        }
                        // Hiển thị alert
                        alert('🚨 KHẨN CẤP: Chỉ còn 1 phút để hoàn thành bài kiểm tra!');
                    }
                }

                const timer = setInterval(function() {
                    timeRemaining--;

                    if (timerElement) {
                        timerElement.textContent = formatTime(timeRemaining);
                        updateTimerClass();
                        updateWarnings();
                    }

                    if (timeRemaining <= 0) {
                        clearInterval(timer);
                        window.onbeforeunload = null; // Cho phép rời trang khi đã nộp

                        // Hiển thị thông báo hết thời gian
                        if (timerContainer) {
                            timerContainer.className = 'd-inline-block bg-danger text-white px-3 py-2 rounded animate__animated animate__shakeX';
                            timerElement.textContent = 'HẾT THỜI GIAN!';
                        }

                        // Tự động nộp bài sau 2 giây
                        setTimeout(function() {
                            @this.call('submitQuiz');
                        }, 2000);
                    }
                }, 1000);

                // Yêu cầu quyền thông báo
                if (Notification.permission === 'default') {
                    Notification.requestPermission();
                }

                // Cập nhật timer mỗi 30 giây để đồng bộ với server
                setInterval(function() {
                    if (timeRemaining > 0) {
                        @this.call('calculateTimeRemaining');
                        // Cập nhật biến local - đảm bảo chỉ lấy số nguyên
                        timeRemaining = Math.floor({{ $timeRemaining }});
                    }
                }, 30000);

                // Function xác nhận nộp bài
                function confirmSubmit() {
                    const answeredCount = {{ count(array_filter($answers)) }};
                    const totalQuestions = {{ count($questions) }};
                    const unansweredCount = totalQuestions - answeredCount;

                    let message = 'Bạn có chắc chắn muốn nộp bài?\n\n';
                    message += `- Tổng số câu hỏi: ${totalQuestions}\n`;
                    message += `- Đã trả lời: ${answeredCount}\n`;
                    message += `- Chưa trả lời: ${unansweredCount}\n\n`;

                    if (unansweredCount > 0) {
                        message += '⚠️ Có ' + unansweredCount + ' câu chưa trả lời. Bạn có muốn tiếp tục?';
                    } else {
                        message += '✅ Tất cả câu hỏi đã được trả lời!';
                    }

                    return confirm(message);
                }
            </script>
        @endif
    @endif
</x-layouts.dash-student>
