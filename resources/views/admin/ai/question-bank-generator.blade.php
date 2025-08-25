<x-layouts.dash-admin active="ai">
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
        <div wire:loading class="ai-loading-modal">
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

                <h3 class="mb-3 typing-animation">AI đang xử lý...</h3>
                <p class="mb-4">Đang phân tích và xử lý dữ liệu với trí tuệ nhân tạo</p>

                <div class="progress mb-3" style="height: 8px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
                </div>

                <div class="loading-steps">
                    <div class="loading-step active">
                        <i class="fas fa-search"></i> Phân tích nội dung
                    </div>
                    <div class="loading-step">
                        <i class="fas fa-cogs"></i> Xử lý AI
                    </div>
                    <div class="loading-step">
                        <i class="fas fa-check"></i> Hoàn thành
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
                            Tạo Ngân hàng Câu hỏi Tiếng Trung bằng AI
                        </h4><br>
                        <p class="text-muted">Tự động tạo ngân hàng câu hỏi tiếng Trung với tối đa 100 câu hỏi</p>
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
                            <!-- Form tạo ngân hàng câu hỏi -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Cấu hình Ngân hàng Câu hỏi</h5>
                                    </div>
                                    <div class="card-body">
                                        <form wire:submit.prevent="generateQuestionBank">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Tên ngân hàng câu hỏi</label>
                                                <input type="text" wire:model="name" id="name"
                                                    class="form-control" placeholder="Nhập tên ngân hàng câu hỏi">
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="description" class="form-label">Mô tả</label>
                                                <textarea wire:model="description" id="description" class="form-control" rows="3"
                                                    placeholder="Mô tả ngân hàng câu hỏi"></textarea>
                                                @error('description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="topic" class="form-label">Chủ đề tiếng Trung</label>
                                                <input type="text" wire:model="topic" id="topic"
                                                    class="form-control"
                                                    placeholder="Ví dụ: Giao tiếp cơ bản, Ngữ pháp HSK 1, Từ vựng chủ đề gia đình...">
                                                @error('topic')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="maxQuestions" class="form-label">Số câu hỏi tối đa</label>
                                                <input type="number" wire:model="maxQuestions" id="maxQuestions"
                                                    class="form-control" min="10" max="100">
                                                @error('maxQuestions')
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
                                                        <span class="loading-text">Đang tạo ngân hàng câu hỏi...</span>
                                                    </div>
                                                @else
                                                    <i class="fas fa-magic me-2"></i>
                                                    Tạo Ngân hàng Câu hỏi Tiếng Trung
                                                @endif
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview ngân hàng câu hỏi -->
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
                                                <h5 class="mt-4 text-primary">AI đang tạo ngân hàng câu hỏi...</h5>
                                                <p class="text-muted">Vui lòng chờ trong giây lát</p>
                                                <div class="progress mt-3" style="height: 6px;">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                                        style="width: 100%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($showPreview && $generatedBank)
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="card-title">Preview Ngân hàng Câu hỏi Tiếng Trung</h5>
                                            <div class="btn-group">
                                                <button wire:click="createQuizFromBank"
                                                    class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-plus"></i> Tạo Quiz
                                                </button>
                                                <button wire:click="saveQuestionBank" class="btn btn-success btn-sm">
                                                    <i class="fas fa-save"></i> Lưu Ngân hàng
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <!-- Thống kê -->
                                            <div class="row mb-4">
                                                <div class="col-md-3">
                                                    <div class="card bg-primary text-white">
                                                        <div class="card-body text-center">
                                                            <h4>{{ $generatedBank['statistics']['total_questions'] ?? 0 }}
                                                            </h4>
                                                            <small>Tổng câu hỏi</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-success text-white">
                                                        <div class="card-body text-center">
                                                            <h4>{{ $generatedBank['statistics']['easy_count'] ?? 0 }}
                                                            </h4>
                                                            <small>Câu dễ</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-warning text-white">
                                                        <div class="card-body text-center">
                                                            <h4>{{ $generatedBank['statistics']['medium_count'] ?? 0 }}
                                                            </h4>
                                                            <small>Câu trung bình</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="card bg-danger text-white">
                                                        <div class="card-body text-center">
                                                            <h4>{{ $generatedBank['statistics']['hard_count'] ?? 0 }}
                                                            </h4>
                                                            <small>Câu khó</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Thống kê theo loại -->
                                            <div class="row mb-4">
                                                <div class="col-md-6">
                                                    <h6>Phân bố theo loại câu hỏi:</h6>
                                                    <ul class="list-group">
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span>Trắc nghiệm</span>
                                                            <span
                                                                class="badge bg-primary">{{ $generatedBank['statistics']['multiple_choice_count'] ?? 0 }}</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span>Điền khuyết</span>
                                                            <span
                                                                class="badge bg-info">{{ $generatedBank['statistics']['fill_blank_count'] ?? 0 }}</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span>Tự luận</span>
                                                            <span
                                                                class="badge bg-warning">{{ $generatedBank['statistics']['essay_count'] ?? 0 }}</span>
                                                        </li>
                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span>Đúng/Sai</span>
                                                            <span
                                                                class="badge bg-secondary">{{ $generatedBank['statistics']['true_false_count'] ?? 0 }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Phân bố theo độ khó:</h6>
                                                    <div class="progress mb-2" style="height: 25px;">
                                                        <div class="progress-bar bg-success"
                                                            style="width: {{ (($generatedBank['statistics']['easy_count'] ?? 0) / ($generatedBank['statistics']['total_questions'] ?? 1)) * 100 }}%">
                                                            Dễ ({{ $generatedBank['statistics']['easy_count'] ?? 0 }})
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-2" style="height: 25px;">
                                                        <div class="progress-bar bg-warning"
                                                            style="width: {{ (($generatedBank['statistics']['medium_count'] ?? 0) / ($generatedBank['statistics']['total_questions'] ?? 1)) * 100 }}%">
                                                            TB
                                                            ({{ $generatedBank['statistics']['medium_count'] ?? 0 }})
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-2" style="height: 25px;">
                                                        <div class="progress-bar bg-danger"
                                                            style="width: {{ (($generatedBank['statistics']['hard_count'] ?? 0) / ($generatedBank['statistics']['total_questions'] ?? 1)) * 100 }}%">
                                                            Khó ({{ $generatedBank['statistics']['hard_count'] ?? 0 }})
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Danh sách câu hỏi mẫu -->
                                            <h6>Một số câu hỏi mẫu:</h6>
                                            <div class="questions-preview"
                                                style="max-height: 400px; overflow-y: auto;">
                                                @foreach ($generatedBank['questions'] as $index => $question)
                                                    <div class="card mb-3">
                                                        <div
                                                            class="card-header d-flex justify-content-between align-items-center">
                                                            <strong>Câu {{ $index + 1 }}:</strong>
                                                            <div>
                                                                <span
                                                                    class="badge bg-{{ $question['difficulty'] === 'easy' ? 'success' : ($question['difficulty'] === 'medium' ? 'warning' : 'danger') }}">
                                                                    {{ ucfirst($question['difficulty']) }}
                                                                </span>
                                                                <span
                                                                    class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="mb-2">{{ $question['question'] }}</p>

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
                                                            @endif

                                                            <div class="mt-2">
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

                                                            @if (!empty($question['tags']))
                                                                <div class="mt-2">
                                                                    <strong>Tags:</strong>
                                                                    @foreach ($question['tags'] as $tag)
                                                                        <span
                                                                            class="badge bg-light text-dark">{{ $tag }}</span>
                                                                    @endforeach
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
                                            <i class="fas fa-database fa-5x mb-4"></i>
                                            <h5>Chưa có ngân hàng câu hỏi được tạo</h5>
                                            <p>Hãy điền thông tin và tạo ngân hàng câu hỏi tiếng Trung bằng AI</p>
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
</x-layouts.dash-admin>
