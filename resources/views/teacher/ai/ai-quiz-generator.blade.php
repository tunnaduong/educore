<x-layouts.dash-teacher active="ai">
    <div class="container-fluid">
        <style>
            /* Loading Button Styles */
            .loading-overlay {
                display: flex;
                align-items: center;
                justify-content: center;
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(13, 110, 253, 0.9);
                border-radius: 0.375rem;
                z-index: 10;
            }

            .loading-text {
                color: white;
                font-weight: 500;
            }

            /* AI Loading Animation */
            .ai-loading-animation {
                position: relative;
                min-height: 300px;
            }

            .ai-brain {
                animation: pulse 2s infinite;
            }

            .ai-particles {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 200px;
                height: 200px;
            }

            .particle {
                position: absolute;
                width: 8px;
                height: 8px;
                background: linear-gradient(45deg, #007bff, #6610f2);
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
                    transform: translateY(-20px) scale(1.2);
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
        </style>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">
                            <i class="fas fa-robot text-primary"></i>
                            Tạo Quiz Tiếng Trung bằng AI
                        </h4>
                        <p class="text-muted">Tự động tạo quiz tiếng Trung từ nội dung bài học</p>
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
                                        <h5 class="card-title">Cấu hình Quiz Tiếng Trung</h5>
                                    </div>
                                    <div class="card-body">
                                        <form wire:submit.prevent="generateQuiz">
                                            <div class="mb-3">
                                                <label for="selectedClass" class="form-label">Lớp học</label>
                                                <select wire:model="selectedClass" id="selectedClass"
                                                    class="form-select">
                                                    <option value="">Chọn lớp học</option>
                                                    @foreach ($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('selectedClass')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="selectedLesson" class="form-label">Bài học tiếng
                                                    Trung</label>
                                                <select wire:model="selectedLesson" id="selectedLesson"
                                                    class="form-select" {{ !$selectedClass ? 'disabled' : '' }}>
                                                    <option value="">Chọn bài học</option>
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
                                                <label for="quizTitle" class="form-label">Tiêu đề Quiz</label>
                                                <input type="text" wire:model="quizTitle" id="quizTitle"
                                                    class="form-control" placeholder="Nhập tiêu đề quiz tiếng Trung">
                                                @error('quizTitle')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="questionCount" class="form-label">Số câu hỏi</label>
                                                <input type="number" wire:model="questionCount" id="questionCount"
                                                    class="form-control" min="5" max="50">
                                                @error('questionCount')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="difficulty" class="form-label">Độ khó</label>
                                                <select wire:model="difficulty" id="difficulty" class="form-select">
                                                    <option value="easy">Dễ (HSK 1-2)</option>
                                                    <option value="medium">Trung bình (HSK 3-4)</option>
                                                    <option value="hard">Khó (HSK 5-6)</option>
                                                </select>
                                                @error('difficulty')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <button type="submit" class="btn btn-primary w-100 position-relative"
                                                {{ $isProcessing ? 'disabled' : '' }}>
                                                @if ($isProcessing)
                                                    <div class="loading-overlay">
                                                        <div class="spinner-border spinner-border-sm text-light me-2"
                                                            role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        <span class="loading-text">Đang tạo quiz...</span>
                                                    </div>
                                                @else
                                                    <i class="fas fa-magic me-2"></i>
                                                    Tạo Quiz Tiếng Trung bằng AI
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview quiz -->
                            <div class="col-md-8">
                                @if ($isProcessing)
                                    <div class="card">
                                        <div class="card-body text-center py-5">
                                            <div class="ai-loading-animation">
                                                <div class="ai-brain">
                                                    <i class="fas fa-brain fa-3x text-primary mb-3"></i>
                                                </div>
                                                <div class="ai-particles">
                                                    <div class="particle"></div>
                                                    <div class="particle"></div>
                                                    <div class="particle"></div>
                                                    <div class="particle"></div>
                                                    <div class="particle"></div>
                                                </div>
                                                <h5 class="mt-4 text-primary">AI đang tạo quiz tiếng Trung...</h5>
                                                <p class="text-muted">Vui lòng chờ trong giây lát</p>
                                                <div class="progress mt-3" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                                        style="width: 100%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($showPreview && $generatedQuiz)
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">Preview Quiz Tiếng Trung</h5>
                                            <div class="btn-group">
                                                <button wire:click="validateQuiz"
                                                    class="btn btn-outline-warning btn-sm"
                                                    {{ $isProcessing ? 'disabled' : '' }}>
                                                    <i class="fas fa-check-circle"></i>
                                                    {{ $isProcessing ? 'Đang kiểm tra...' : 'Kiểm tra lỗi' }}
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
