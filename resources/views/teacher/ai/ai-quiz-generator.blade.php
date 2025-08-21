<x-layouts.dash-teacher active="ai">
    @include('components.language')
    <div class="container-fluid">
        <style>
            /* AI Loading Modal */
            .ai-loading-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                backdrop-filter: blur(5px);
            }

            .ai-loading-content {
                margin: 0 auto;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                border-radius: 20px;
                padding: 3rem;
                text-align: center;
                color: white;
                max-width: 500px;
                width: 90%;
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
                animation: modalSlideIn 0.3s ease-out;
            }

            @keyframes modalSlideIn {
                from {
                    opacity: 0;
                    transform: translateY(-50px) scale(0.9);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            /* AI Loading Animation */
            .ai-loading-animation {
                position: relative;
                min-height: 200px;
                margin-bottom: 2rem;
            }

            .ai-brain {
                animation: pulse 2s infinite;
                margin-bottom: 1rem;
            }

            .ai-particles {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 150px;
                height: 150px;
            }

            .particle {
                position: absolute;
                width: 6px;
                height: 6px;
                background: linear-gradient(45deg, #ffffff, #e3f2fd);
                border-radius: 50%;
                animation: particle-float 3s infinite ease-in-out;
            }

            .particle:nth-child(1) {
                top: 20%;
                left: 20%;
                animation-delay: 0s;
            }

            .particle:nth-child(2) {
                top: 20%;
                right: 20%;
                animation-delay: 0.5s;
            }

            .particle:nth-child(3) {
                bottom: 20%;
                left: 20%;
                animation-delay: 1s;
            }

            .particle:nth-child(4) {
                bottom: 20%;
                right: 20%;
                animation-delay: 1.5s;
            }

            .particle:nth-child(5) {
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                animation-delay: 2s;
            }

            @keyframes pulse {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.1);
                    opacity: 0.8;
                }
            }

            @keyframes particle-float {

                0%,
                100% {
                    transform: translateY(0) scale(1);
                    opacity: 0.7;
                }

                50% {
                    transform: translateY(-15px) scale(1.2);
                    opacity: 1;
                }
            }

            /* Progress bar animation */
            .progress-bar-animated {
                background-image: linear-gradient(45deg,
                        rgba(255, 255, 255, .15) 25%,
                        transparent 25%,
                        transparent 50%,
                        rgba(255, 255, 255, .15) 50%,
                        rgba(255, 255, 255, .15) 75%,
                        transparent 75%,
                        transparent);
                background-size: 1rem 1rem;
                animation: progress-bar-stripes 1s linear infinite;
            }

            @keyframes progress-bar-stripes {
                0% {
                    background-position: 1rem 0;
                }

                100% {
                    background-position: 0 0;
                }
            }

            /* Typing animation for loading text */
            .typing-animation {
                overflow: hidden;
                border-right: 2px solid white;
                white-space: nowrap;
                animation: typing 3s steps(40, end), blink-caret 0.75s step-end infinite;
            }

            @keyframes typing {
                from {
                    width: 0;
                }

                to {
                    width: 100%;
                }
            }

            @keyframes blink-caret {

                from,
                to {
                    border-color: transparent;
                }

                50% {
                    border-color: white;
                }
            }

            /* Loading steps */
            .loading-steps {
                margin-top: 1rem;
            }

            .loading-step {
                opacity: 0.5;
                transition: opacity 0.3s ease;
            }

            .loading-step.active {
                opacity: 1;
            }

            .loading-step i {
                margin-right: 0.5rem;
            }
        </style>

        <!-- AI Loading Modal -->
        <div wire:loading wire:target="generateQuiz" class="ai-loading-modal">
            <div class="ai-loading-content">
                <div class="ai-loading-animation">
                    <div class="ai-brain">
                        <i class="fas fa-brain fa-4x"></i>
                    </div>
                    <div class="ai-particles">
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                    </div>
                </div>

                <h3 class="mb-3 typing-animation">{{ __('general.ai_processing') }}</h3>
                <p class="mb-4">{{ __('general.ai_processing_description') }}</p>

                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                </div>

                <div class="loading-steps">
                    <div class="loading-step active">
                        <i class="fas fa-search"></i> {{ __('general.analyzing_content') }}
                    </div>
                    <div class="loading-step">
                        <i class="fas fa-cogs"></i> {{ __('general.ai_processing_step') }}
                    </div>
                    <div class="loading-step">
                        <i class="fas fa-check"></i> {{ __('general.completing') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-robot text-primary"></i>
                            {{ __('general.create_chinese_quiz_with_ai') }}
                        </h4><br>
                        <p class="text-muted">{{ __('general.auto_create_chinese_quiz') }}</p>
                    </div>
                    <div class="card-body">
                        <!-- Thông báo -->
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session()->has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <!-- Form tạo quiz -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">{{ __('general.chinese_quiz_configuration') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <form wire:submit.prevent="generateQuiz">
                                            <div class="mb-3">
                                                <label for="selectedClass"
                                                    class="form-label">{{ __('general.class') }}</label>
                                                <select wire:model.live="selectedClass" id="selectedClass"
                                                    class="form-control">
                                                    <option value="">{{ __('general.select_class') }}</option>
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('selectedClass')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="selectedLesson"
                                                    class="form-label">{{ __('general.chinese_lesson') }}</label>
                                                <select wire:model.live="selectedLesson" id="selectedLesson"
                                                    class="form-control" {{ !$selectedClass ? 'disabled' : '' }}>
                                                    <option value="">{{ __('general.select_lesson') }}</option>
                                                    @foreach ($lessons as $lesson)
                                                        <option value="{{ $lesson->id }}">{{ $lesson->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('selectedLesson')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="quizTitle"
                                                    class="form-label">{{ __('general.quiz_title') }}</label>
                                                <input type="text" wire:model="quizTitle" id="quizTitle"
                                                    class="form-control"
                                                    placeholder="{{ __('general.enter_chinese_quiz_title') }}">
                                                @error('quizTitle')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="questionCount"
                                                    class="form-label">{{ __('general.question_count') }}</label>
                                                <input type="number" wire:model="questionCount" id="questionCount"
                                                    class="form-control" min="5" max="50">
                                                @error('questionCount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="difficulty"
                                                    class="form-label">{{ __('general.difficulty') }}</label>
                                                <select wire:model="difficulty" id="difficulty" class="form-control">
                                                    <option value="easy">{{ __('general.easy_hsk_1_2') }}</option>
                                                    <option value="medium">{{ __('general.medium_hsk_3_4') }}</option>
                                                    <option value="hard">{{ __('general.hard_hsk_5_6') }}</option>
                                                </select>
                                                @error('difficulty')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 position-relative"
                                                wire:loading.attr="disabled" wire:loading.class="disabled">
                                                <i class="fas fa-magic me-2"></i>
                                                {{ __('general.create_chinese_quiz_with_ai_button') }}
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview quiz -->
                            <div class="col-md-8">
                                @if ($showPreview && $generatedQuiz)
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">{{ __('general.preview_chinese_quiz') }}</h5>
                                            <div class="btn-group">
                                                <button wire:click="validateQuiz"
                                                    class="btn btn-outline-warning btn-sm position-relative"
                                                    wire:loading.attr="disabled" wire:loading.class="disabled">
                                                    <div wire:loading.remove>
                                                        <i class="fas fa-check-circle"></i>
                                                        {{ __('general.check_errors') }}
                                                    </div>
                                                    <div wire:loading class="loading-overlay">
                                                        <div class="spinner-border spinner-border-sm text-light me-2"
                                                            role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <span class="loading-text">{{ __('general.checking') }}</span>
                                                    </div>
                                                </button>
                                                <button wire:click="saveQuiz" class="btn btn-success btn-sm">
                                                    <i class="fas fa-save"></i> Lưu Quiz
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Tổng điểm:</strong>
                                                    {{ $generatedQuiz['total_score'] ?? 0 }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Thời gian ước tính:</strong>
                                                    {{ $generatedQuiz['estimated_time'] ?? 30 }} phút
                                                </div>
                                            </div>

                                            <div class="questions-preview">
                                                @foreach ($generatedQuiz['questions'] as $index => $question)
                                                    <div class="card mb-3">
                                                        <div class="card-header">
                                                            <strong>Câu {{ $index + 1 }}:</strong>
                                                            {{ $question['question'] }}
                                                            <span
                                                                class="badge bg-secondary float-end">{{ ucfirst($question['type']) }}</span>
                                                        </div>
                                                        <div class="card-body">
                                                            @if ($question['type'] === 'multiple_choice' && !empty($question['options']))
                                                                <div class="options">
                                                                    @foreach ($question['options'] as $optionIndex => $option)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="radio" disabled>
                                                                            <label class="form-check-label">
                                                                                {{ chr(65 + $optionIndex) }}.
                                                                                {{ $option }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @elseif($question['type'] === 'fill_blank')
                                                                <div class="form-control"
                                                                    style="background-color: #f8f9fa;">
                                                                    <em>Chỗ trống để điền</em>
                                                                </div>
                                                            @elseif($question['type'] === 'essay')
                                                                <div class="form-control"
                                                                    style="background-color: #f8f9fa; min-height: 100px;">
                                                                    <em>Vùng viết câu trả lời</em>
                                                                </div>
                                                            @endif

                                                            <div class="mt-3">
                                                                <strong>Đáp án:</strong>
                                                                <span
                                                                    class="text-success">{{ $question['correct_answer'] }}</span>
                                                            </div>

                                                            @if (!empty($question['explanation']))
                                                                <div class="mt-2">
                                                                    <strong>Giải thích:</strong>
                                                                    <p class="text-muted mb-0">
                                                                        {{ $question['explanation'] }}</p>
                                                                </div>
                                                            @endif

                                                            <div class="mt-2">
                                                                <strong>Điểm:</strong> {{ $question['score'] ?? 1 }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="card">
                                        <div class="card-body text-center text-muted">
                                            <i class="fas fa-robot fa-5x mb-4"></i>
                                            <h5>Chưa có quiz tiếng Trung được tạo</h5>
                                            <p>Hãy chọn lớp học, bài học và cấu hình để tạo quiz tiếng Trung bằng AI</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
