<x-layouts.dash-admin active="quizzes">
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
        <div wire:loading wire:target="validateQuizWithAI" class="ai-loading-modal">
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

                <h3 class="mb-3 typing-animation">{{ __('views.ai_processing') }}</h3>
                <p class="mb-4">{{ __('views.ai_processing_description') }}</p>

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

        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại danh sách bài kiểm tra
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-plus-circle mr-2"></i>Tạo bài kiểm tra mới
            </h4>
            <p class="text-muted mb-0">Thêm bài kiểm tra mới vào hệ thống</p>
        </div>

        <!-- AI Tools -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-robot mr-2"></i>Công cụ AI Tiếng Trung
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-magic fa-3x text-primary mb-3"></i>
                                    <h6>Tạo Quiz Tiếng Trung bằng AI</h6>
                                    <p class="text-muted small">Tự động tạo quiz tiếng Trung từ nội dung bài học</p>
                                    <a href="{{ route('ai.quiz-generator') }}" class="btn btn-primary">
                                        <i class="fas fa-robot mr-1"></i>Tạo Quiz AI
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-database fa-3x text-success mb-3"></i>
                                    <h6>Ngân hàng Câu hỏi Tiếng Trung</h6>
                                    <p class="text-muted small">Tạo ngân hàng câu hỏi tiếng Trung với tối đa 100 câu</p>
                                    <a href="{{ route('ai.question-bank-generator') }}" class="btn btn-success">
                                        <i class="fas fa-database mr-1"></i>Tạo Ngân hàng
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-folder-open fa-3x text-info mb-3"></i>
                                    <h6>Chọn từ Ngân hàng Câu hỏi</h6>
                                    <p class="text-muted small">Chọn câu hỏi từ các ngân hàng câu hỏi đã tạo</p>
                                    <button type="button" class="btn btn-info"
                                        wire:click="$set('showQuestionBank', true)">
                                        <i class="fas fa-folder-open mr-1"></i>Chọn Câu hỏi
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-check-circle fa-3x text-warning mb-3"></i>
                                    <h6>Kiểm tra Lỗi Quiz</h6>
                                    <p class="text-muted small">Tự động kiểm tra và sửa lỗi quiz tiếng Trung</p>
                                    <button type="button" class="btn btn-warning" wire:click="validateQuizWithAI">
                                        <i class="fas fa-check-circle mr-1"></i>Kiểm tra AI
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form wire:submit="save">
            <div class="row">
                <!-- Thông tin cơ bản -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle mr-2"></i>Thông tin cơ bản
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Tiêu đề bài kiểm tra <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    wire:model="title" placeholder="Nhập tiêu đề...">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3"
                                    placeholder="Mô tả bài kiểm tra..."></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Lớp học <span class="text-danger">*</span></label>
                                <select class="form-control @error('class_id') is-invalid @enderror"
                                    wire:model="class_id">
                                    <option value="">Chọn lớp học...</option>
                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hạn nộp</label>
                                <input type="datetime-local"
                                    class="form-control @error('deadline') is-invalid @enderror"
                                    wire:model="deadline">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Thời gian làm bài (phút)</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror"
                                    wire:model="time_limit" min="1" max="480"
                                    placeholder="Nhập thời gian làm bài (ví dụ: 30)">
                                <small class="form-text text-muted">Để trống nếu không giới hạn thời gian. Tối đa 8 giờ
                                    (480 phút)</small>
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thêm câu hỏi -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-plus-circle mr-2"></i>Thêm câu hỏi mới
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Nội dung câu hỏi <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('currentQuestion.question') is-invalid @enderror"
                                            wire:model="currentQuestion.question" rows="3" placeholder="Nhập nội dung câu hỏi..."></textarea>
                                        @error('currentQuestion.question')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Loại câu hỏi <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control @error('currentQuestion.type') is-invalid @enderror"
                                            wire:model="currentQuestion.type">
                                            <option value="multiple_choice">Trắc nghiệm</option>
                                            <option value="fill_blank">Điền từ</option>
                                            <option value="drag_drop">Kéo thả</option>
                                            <option value="essay">Tự luận</option>
                                        </select>
                                        @error('currentQuestion.type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Điểm <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('currentQuestion.score') is-invalid @enderror"
                                            wire:model="currentQuestion.score" min="1" max="10">
                                        @error('currentQuestion.score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tùy chọn cho câu hỏi trắc nghiệm -->
                            @if ($currentQuestion['type'] === 'multiple_choice')
                                <div class="mb-3">
                                    <label class="form-label">Các đáp án <span class="text-danger">*</span></label>
                                    @foreach ($currentQuestion['options'] as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                class="form-control @error('currentQuestion.options.' . $index) is-invalid @enderror"
                                                wire:model.live="currentQuestion.options.{{ $index }}"
                                                placeholder="Đáp án {{ $index + 1 }}">
                                            @if (count($currentQuestion['options']) > 2)
                                                <button type="button" class="btn btn-outline-danger"
                                                    wire:click="removeOption({{ $index }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        wire:click="addOption">
                                        <i class="bi bi-plus mr-1"></i>Thêm đáp án
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Đáp án đúng <span class="text-danger">*</span></label>
                                    <select
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model.live="currentQuestion.correct_answer">
                                        <option value="">Chọn đáp án đúng...</option>
                                        @foreach ($currentQuestion['options'] as $index => $option)
                                            <option value="{{ $option }}" {{ $option ? '' : 'disabled' }}>
                                                {{ $option ?: 'Đáp án ' . ($index + 1) }}</option>
                                        @endforeach
                                    </select>
                                    @error('currentQuestion.correct_answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <!-- Tùy chọn cho câu hỏi điền từ -->
                            @if ($currentQuestion['type'] === 'fill_blank')
                                <div class="mb-3">
                                    <label class="form-label">Đáp án đúng <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model="currentQuestion.correct_answer" placeholder="Nhập đáp án đúng...">
                                    @error('currentQuestion.correct_answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="text-end">
                                @if ($editingIndex !== null)
                                    <button type="button" class="btn btn-warning mr-2"
                                        wire:click="resetCurrentQuestion">
                                        <i class="bi bi-x-circle mr-2"></i>Hủy chỉnh sửa
                                    </button>
                                    <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                        <i class="bi bi-check-circle mr-2"></i>Cập nhật câu hỏi
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                        <i class="bi bi-plus-circle mr-2"></i>Thêm câu hỏi
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách câu hỏi đã thêm -->
                    @if (count($questions) > 0)
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bi bi-list-ul mr-2"></i>Danh sách câu hỏi ({{ count($questions) }})
                                </h6>
                            </div>
                            <div class="card-body">
                                @foreach ($questions as $index => $question)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="badge bg-primary mr-2">Câu {{ $index + 1 }}</span>
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                                <span class="badge bg-info">{{ $question['score'] }} điểm</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-warning"
                                                    wire:click="editQuestion({{ $index }})" title="Sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                @if ($index > 0)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionUp({{ $index }})"
                                                        title="Di chuyển lên">
                                                        <i class="bi bi-arrow-up"></i>
                                                    </button>
                                                @endif
                                                @if ($index < count($questions) - 1)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionDown({{ $index }})"
                                                        title="Di chuyển xuống">
                                                        <i class="bi bi-arrow-down"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger"
                                                    wire:click="removeQuestion({{ $index }})" title="Xóa">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="fw-medium">{{ $question['question'] }}</div>
                                        @if ($question['type'] === 'multiple_choice' && isset($question['options']))
                                            <div class="mt-2">
                                                <small class="text-muted">Đáp án đúng:
                                                    <strong>{{ $question['correct_answer'] }}</strong></small>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Nút lưu -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-end">
                            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary mr-2">
                                <i class="bi bi-x-circle mr-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn btn-primary"
                                @if (count($questions) === 0) disabled @endif>
                                <i class="bi bi-check-circle mr-2"></i>Lưu bài kiểm tra
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Modal Ngân hàng Câu hỏi -->
        @if ($showQuestionBank)
            <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-database mr-2"></i>Chọn Câu hỏi từ Ngân hàng
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeQuestionBank"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Chọn ngân hàng câu hỏi -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Chọn Ngân hàng Câu hỏi:</label>
                                    <select class="form-control" wire:model.live="selectedQuestionBank">
                                        <option value="">Chọn ngân hàng câu hỏi...</option>
                                        @foreach ($questionBanks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}
                                                ({{ $bank->getQuestionCount() }} câu)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Tìm kiếm:</label>
                                    <input type="text" class="form-control" wire:model.live="questionBankFilter"
                                        placeholder="Tìm câu hỏi...">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Loại câu hỏi:</label>
                                    <select class="form-control" wire:model.live="questionTypeFilter">
                                        <option value="">Tất cả</option>
                                        <option value="multiple_choice">Trắc nghiệm</option>
                                        <option value="fill_blank">Điền từ</option>
                                        <option value="essay">Tự luận</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Danh sách câu hỏi -->
                            @if (!empty($questionBankQuestions))
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6>Danh sách câu hỏi ({{ count($filteredQuestions) }})</h6>
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="addSelectedQuestions">
                                                    <i class="fas fa-plus mr-1"></i>Thêm
                                                    {{ count($selectedQuestions) }} câu hỏi đã chọn
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th width="50">
                                                            <input type="checkbox" wire:click="toggleAllQuestions"
                                                                @if (count($selectedQuestions) == count($filteredQuestions)) checked @endif>
                                                        </th>
                                                        <th>Nội dung câu hỏi</th>
                                                        <th width="120">Loại</th>
                                                        <th width="80">Điểm</th>
                                                        <th width="100">Độ khó</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($filteredQuestions as $index => $question)
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox"
                                                                    wire:click="toggleQuestionSelection({{ $index }})"
                                                                    @if (in_array($index, $selectedQuestions)) checked @endif>
                                                            </td>
                                                            <td>
                                                                <div class="fw-medium">
                                                                    {{ Str::limit($question['question'] ?? '', 100) }}
                                                                </div>
                                                                @if (isset($question['options']) && is_array($question['options']))
                                                                    <small class="text-muted">
                                                                        Đáp án đúng:
                                                                        <strong>{{ $question['correct_answer'] ?? '' }}</strong>
                                                                    </small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-secondary">
                                                                    {{ ucfirst(str_replace('_', ' ', $question['type'] ?? 'multiple_choice')) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-info">{{ $question['score'] ?? 1 }}
                                                                    điểm</span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $question['difficulty'] == 'easy' ? 'success' : ($question['difficulty'] == 'medium' ? 'warning' : 'danger') }}">
                                                                    {{ ucfirst($question['difficulty'] ?? 'medium') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">
                                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                                <br>Không có câu hỏi nào trong ngân hàng này
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-database fa-3x mb-3"></i>
                                    <h5>Chưa có ngân hàng câu hỏi nào</h5>
                                    <p>Vui lòng tạo ngân hàng câu hỏi bằng AI trước khi sử dụng tính năng này.</p>
                                    <a href="{{ route('ai.question-bank-generator') }}" class="btn btn-primary">
                                        <i class="fas fa-plus mr-1"></i>Tạo Ngân hàng Câu hỏi
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeQuestionBank">
                                <i class="fas fa-times mr-1"></i>Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
        @endif
    </div>
    <script>
        (function() {
            let isDirty = false;

            // Đánh dấu có thay đổi trên toàn trang
            document.addEventListener('input', () => {
                isDirty = true;
            }, {
                passive: true
            });
            document.addEventListener('change', () => {
                isDirty = true;
            }, {
                passive: true
            });

            // Xác định form lưu để reset cờ khi submit
            const form = document.querySelector('form[wire\\:submit="save"], form[wire\\:submit]') || document
                .querySelector('form');
            if (form) {
                form.addEventListener('submit', () => {
                    isDirty = false;
                });
            }

            // Cảnh báo trước khi rời/trang reload (full reload)
            window.addEventListener('beforeunload', function(e) {
                if (!isDirty) return;
                e.preventDefault();
                e.returnValue = '';
                return '';
            });

            // Chặn click vào link nếu có thay đổi, hiển thị confirm
            document.addEventListener('click', function(e) {
                const anchor = e.target.closest('a[href]');
                if (!anchor) return;
                if (anchor.hasAttribute('data-bypass-leave-confirm')) return; // cho phép bỏ qua
                if (!isDirty) return;
                const proceed = confirm('Bạn có thay đổi chưa lưu. Bạn có chắc muốn rời trang?');
                if (!proceed) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }
            }, true);
        })();
    </script>
</x-layouts.dash-admin>
