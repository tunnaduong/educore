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
                        Tạo Ngân hàng Câu hỏi Tiếng Trung bằng AI
                    </h4>
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
                                            <input type="text" wire:model="name" id="name" class="form-control"
                                                placeholder="Nhập tên ngân hàng câu hỏi">
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
                                            <input type="text" wire:model="topic" id="topic" class="form-control"
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
                                            <button wire:click="createQuizFromBank" class="btn btn-outline-info btn-sm">
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
                                                        TB ({{ $generatedBank['statistics']['medium_count'] ?? 0 }})
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
                                        <div class="questions-preview" style="max-height: 400px; overflow-y: auto;">
                                            @foreach (array_slice($generatedBank['questions'], 0, 5) as $index => $question)
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
                                                                        <input class="form-check-input" type="radio"
                                                                            disabled>
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

                                        @if (count($generatedBank['questions']) > 5)
                                            <div class="text-center mt-3">
                                                <p class="text-muted">Và {{ count($generatedBank['questions']) - 5 }}
                                                    câu hỏi khác...</p>
                                            </div>
                                        @endif
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
