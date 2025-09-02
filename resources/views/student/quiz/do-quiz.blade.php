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

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
        }

        #timer-container:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
                        <h3 class="mb-3">403 | {{ __('general.quiz_access_denied_title') }}</h3>
                        <p class="lead">{{ $accessDenied }}</p>
                        <a href="{{ route('student.quizzes.index') }}" class="btn btn-primary mt-3">
                            <i class="bi bi-arrow-left mr-1"></i>{{ __('general.back_to_quiz_list') }}
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
                                <i class="bi bi-check-circle mr-2"></i>{{ __('general.quiz_completed_title') }}
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
                                            <small class="text-muted">{{ __('general.score') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-info mb-0">
                                                {{ $result->duration ? gmdate('H:i:s', $result->duration) : '-' }}</h3>
                                            <small class="text-muted">{{ __('general.quiz_duration') }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h3 class="text-success mb-0">{{ count($questions) }}</h3>
                                            <small class="text-muted">{{ __('general.total_questions') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('student.quizzes.index') }}" class="btn btn-primary">
                                    <i class="bi bi-house mr-2"></i>{{ __('general.back_to_home') }}
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
                                            {{ __('general.time_limit') }}: <strong>{{ $quiz->time_limit }} {{ __('general.minutes') }}</strong>
                                        </small>
                                    @else
                                        <small class="text-success">
                                            <i class="bi bi-infinity mr-1"></i>
                                            {{ __('general.no_time_limit') }}
                                        </small>
                                    @endif
                                </div>
                                <div class="col-md-6 text-md-end">
                                    @if ($timeRemaining)
                                        <div class="d-inline-block px-3 py-2 rounded {{ $this->getTimerClass() }}"
                                            id="timer-container">
                                            <i class="bi bi-clock mr-2"></i>
                                            <span id="timer" class="fw-bold fs-5">
                                                {{ $this->getFormattedTimeRemaining() }}
                                            </span>
                                            @if ($this->shouldShowWarning())
                                                <span
                                                    class="badge bg-warning text-dark ml-2 animate__animated animate__pulse">
                                                    <i class="bi bi-exclamation-triangle"></i> {{ __('general.warning_label') }}
                                                </span>
                                            @endif
                                            @if ($this->shouldShowUrgentWarning())
                                                <span
                                                    class="badge bg-danger text-white ml-2 animate__animated animate__pulse">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> {{ __('general.urgent_label') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">{{ __('general.quiz_progress') }}</small>
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
                <!-- Sidebar - Question list -->
                <div class="col-lg-3">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-list-ul mr-2"></i>{{ __('general.question_list') }}
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
                                    <div class="btn btn-sm btn-outline-secondary mr-2"
                                        style="width: 20px; height: 20px; padding: 0;"></div>
                                    <small class="text-muted">{{ __('general.unanswered') }}</small>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <div class="btn btn-sm btn-success mr-2"
                                        style="width: 20px; height: 20px; padding: 0;"></div>
                                    <small class="text-muted">{{ __('general.answered') }}</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="btn btn-sm btn-primary mr-2"
                                        style="width: 20px; height: 20px; padding: 0;"></div>
                                    <small class="text-muted">{{ __('general.current_question') }}</small>
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
                                        {{ __('general.question') }} {{ $currentQuestionIndex + 1 }} / {{ count($questions) }}
                                    </h6>
                                    <span class="badge bg-primary">{{ __('general.' . $currentQuestion['type']) }}</span>
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
                                                {{ __('general.browser_not_support') }}
                                            </audio>
                                        </div>
                                    @endif
                                </div>

                                <!-- Form trả lời -->
                                <div class="mb-4">
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
                                </div>

                                <!-- Nút điều hướng -->
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-outline-secondary" wire:click="previousQuestion"
                                        {{ $currentQuestionIndex === 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-arrow-left mr-2"></i>{{ __('general.prev_question') }}
                                    </button>

                                    @if ($currentQuestionIndex === count($questions) - 1)
                                        <button class="btn btn-success" wire:click="submitQuiz"
                                            wire:confirm="{{ __('general.submit_quiz_confirm') }}">
                                            <i class="bi bi-check-circle mr-2"></i>{{ __('general.submit_quiz') }}
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-primary" wire:click="nextQuestion">
                                            {{ __('general.next_question') }}<i class="bi bi-arrow-right ml-2"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                                <h5>{{ __('general.no_questions') }}</h5>
                                <p class="text-muted">{{ __('general.quiz_has_no_questions') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- JavaScript cho timer -->
        <script>
            // Debug: Log khi trang được load
            console.log('Quiz page loaded');

            // Debug: Kiểm tra Livewire
            if (typeof Livewire !== 'undefined') {
                console.log('Livewire is available');
            } else {
                console.log('Livewire is not available');
            }

            // Biến global để theo dõi trạng thái timer
            window.quizTimer = window.quizTimer || {
                initialized: false,
                interval: null,
                timeRemaining: null
            };
        </script>

        @if ($timeRemaining)
            <script>
                // Chỉ khởi tạo timer một lần
                if (!window.quizTimer.initialized) {
                    // Lấy thời gian còn lại từ server
                    const serverTimeRemaining = {{ $timeRemaining }};
                    const quizId = {{ $quiz->id }};
                    const storageKey = `quiz_deadline_${quizId}`;

                    // Đọc deadline từ localStorage (millisecond timestamp)
                    const storedDeadline = localStorage.getItem(storageKey);
                    let localRemaining = null;
                    if (storedDeadline) {
                        const deadlineTs = parseInt(storedDeadline, 10);
                        if (!Number.isNaN(deadlineTs)) {
                            localRemaining = Math.floor((deadlineTs - Date.now()) / 1000);
                        }
                    }

                    // Nếu có localRemaining hợp lệ thì dùng min(local, server) để tránh reset/gian lận
                    let effectiveRemaining = serverTimeRemaining;
                    if (typeof localRemaining === 'number') {
                        effectiveRemaining = Math.max(0, Math.min(serverTimeRemaining, localRemaining));
                    }

                    // Nếu chưa có deadline trong localStorage hoặc local > server (có thể do lệch đồng hồ), set lại theo server
                    if (!storedDeadline || (typeof localRemaining === 'number' && localRemaining > serverTimeRemaining)) {
                        const newDeadline = Date.now() + serverTimeRemaining * 1000;
                        localStorage.setItem(storageKey, String(newDeadline));
                    }

                    window.quizTimer.timeRemaining = effectiveRemaining;
                    window.quizTimer.initialized = true;

                    const timerElement = document.getElementById('timer');
                    const timerContainer = document.getElementById('timer-container');

                    // Cảnh báo khi người dùng cố gắng reload hoặc rời khỏi trang
                    window.onbeforeunload = function(e) {
                        // Chỉ hiển thị cảnh báo khi chưa nộp bài và còn thời gian
                        if (window.quizTimer.timeRemaining > 0 && !window.quizSubmitted) {
                            e.preventDefault();
                            e.returnValue =
                                '{{ __('general.leave_quiz_warning') }}';
                            return e.returnValue;
                        }
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

                        if (window.quizTimer.timeRemaining <= 300) { // 5 phút cuối
                            timerContainer.className =
                                'd-inline-block bg-danger text-white px-3 py-2 rounded animate__animated animate__pulse timer-urgent';
                        } else if (window.quizTimer.timeRemaining <= 600) { // 10 phút cuối
                            timerContainer.className =
                                'd-inline-block bg-warning text-dark px-3 py-2 rounded animate__animated animate__pulse timer-warning';
                        } else {
                            timerContainer.className = 'd-inline-block bg-info text-white px-3 py-2 rounded timer-normal';
                        }
                    }

                    // Function để cập nhật cảnh báo
                    function updateWarnings() {
                        // Cảnh báo khi còn 5 phút
                        if (window.quizTimer.timeRemaining === 300) {
                            if (Notification.permission === 'granted') {
                                new Notification('{{ __('general.time_warning_title') }}', {
                                    body: '{{ __('general.five_minutes_left') }}',
                                    icon: '/favicon.ico'
                                });
                            }
                            // Hiển thị alert
                            alert('⚠️ ' + '{{ __('general.five_minutes_left') }}');
                        }

                        // Cảnh báo khi còn 1 phút
                        if (window.quizTimer.timeRemaining === 60) {
                            if (Notification.permission === 'granted') {
                                new Notification('{{ __('general.time_warning_title') }}', {
                                    body: '{{ __('general.one_minute_left') }}',
                                    icon: '/favicon.ico'
                                });
                            }
                            // Hiển thị alert
                            alert('🚨 ' + '{{ __('general.one_minute_left') }}');
                        }
                    }

                    // Khởi tạo timer
                    window.quizTimer.interval = setInterval(function() {
                        window.quizTimer.timeRemaining--;

                        if (timerElement) {
                            timerElement.textContent = formatTime(window.quizTimer.timeRemaining);
                            updateTimerClass();
                            updateWarnings();
                        }

                        if (window.quizTimer.timeRemaining <= 0) {
                            clearInterval(window.quizTimer.interval);
                            window.onbeforeunload = null; // Cho phép rời trang khi đã nộp
                            window.quizSubmitted = true;

                            // Hiển thị thông báo hết thời gian
                            if (timerContainer) {
                                timerContainer.className =
                                    'd-inline-block bg-danger text-white px-3 py-2 rounded animate__animated animate__shakeX';
                                timerElement.textContent = '{{ __('general.time_up') }}';
                            }

                            // Tự động nộp bài sau 2 giây
                            setTimeout(function() {
                                @this.call('submitQuiz').then(function(result) {
                                    if (result && result.submitted) {
                                        window.quizSubmitted = true;
                                        window.onbeforeunload = null;
                                    }
                                });
                            }, 2000);
                        }
                    }, 1000);

                    // Yêu cầu quyền thông báo
                    if (Notification.permission === 'default') {
                        Notification.requestPermission();
                    }

                    // Cập nhật timer mỗi 30 giây để đồng bộ với server
                    setInterval(function() {
                        if (window.quizTimer.timeRemaining > 0) {
                            @this.call('calculateTimeRemaining').then(function(result) {
                                // Cập nhật biến local từ server
                                if (result && result.timeRemaining !== undefined) {
                                    const serverRemain = Math.floor(result.timeRemaining);

                                    // Đọc deadline hiện tại
                                    const currentStoredDeadline = localStorage.getItem(storageKey);
                                    let currentLocalRemain = null;
                                    if (currentStoredDeadline) {
                                        const dlTs = parseInt(currentStoredDeadline, 10);
                                        if (!Number.isNaN(dlTs)) {
                                            currentLocalRemain = Math.floor((dlTs - Date.now()) / 1000);
                                        }
                                    }

                                    // Chọn giá trị an toàn: nhỏ hơn giữa server và local
                                    let nextRemain = serverRemain;
                                    if (typeof currentLocalRemain === 'number') {
                                        nextRemain = Math.max(0, Math.min(serverRemain, currentLocalRemain));
                                    }

                                    window.quizTimer.timeRemaining = nextRemain;

                                    // Ghi đè deadline theo server để ổn định giữa các lần reload
                                    const newDeadline = Date.now() + serverRemain * 1000;
                                    localStorage.setItem(storageKey, String(newDeadline));
                                }
                            });
                        }
                    }, 30000);
                } else {
                    // Nếu timer đã được khởi tạo, chỉ cập nhật hiển thị
                    const timerElement = document.getElementById('timer');
                    const timerContainer = document.getElementById('timer-container');

                    if (timerElement && window.quizTimer.timeRemaining !== null) {
                        function formatTime(seconds) {
                            const minutes = Math.floor(seconds / 60);
                            const secs = seconds % 60;
                            return (minutes < 10 ? '0' : '') + minutes + ':' +
                                (secs < 10 ? '0' : '') + secs;
                        }

                        timerElement.textContent = formatTime(window.quizTimer.timeRemaining);

                        // Cập nhật class CSS
                        if (timerContainer) {
                            timerContainer.classList.remove('timer-normal', 'timer-warning', 'timer-urgent');
                            if (window.quizTimer.timeRemaining <= 300) {
                                timerContainer.className =
                                    'd-inline-block bg-danger text-white px-3 py-2 rounded animate__animated animate__pulse timer-urgent';
                            } else if (window.quizTimer.timeRemaining <= 600) {
                                timerContainer.className =
                                    'd-inline-block bg-warning text-dark px-3 py-2 rounded animate__animated animate__pulse timer-warning';
                            } else {
                                timerContainer.className = 'd-inline-block bg-info text-white px-3 py-2 rounded timer-normal';
                            }
                        }
                    }
                }

                // Thêm event listener cho nút nộp bài để đánh dấu đã nộp
                document.addEventListener('DOMContentLoaded', function() {
                    const submitButton = document.querySelector('button[wire\\:click="submitQuiz"]');
                    if (submitButton) {
                        submitButton.addEventListener('click', function() {
                            window.quizSubmitted = true;
                            window.onbeforeunload = null;
                            // Xóa deadline khi đã nộp bài
                            try {
                                const quizId = {{ $quiz->id }};
                                const storageKey = `quiz_deadline_${quizId}`;
                                localStorage.removeItem(storageKey);
                            } catch (e) {}
                        });
                    }

                    // Thêm event listener cho các nút điều hướng để tránh confirm nộp bài
                    const navigationButtons = document.querySelectorAll(
                        'button[wire\\:click="nextQuestion"], button[wire\\:click="previousQuestion"], button[wire\\:click^="goToQuestion"]'
                    );
                    navigationButtons.forEach(function(button) {
                        button.addEventListener('click', function() {
                            // Tạm thời vô hiệu hóa onbeforeunload khi chuyển câu hỏi
                            const originalOnBeforeUnload = window.onbeforeunload;
                            window.onbeforeunload = null;

                            // Khôi phục lại sau 1 giây
                            setTimeout(function() {
                                window.onbeforeunload = originalOnBeforeUnload;
                            }, 1000);
                        });
                    });
                });
            </script>
        @endif
    @endif
</x-layouts.dash-student>
