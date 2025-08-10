<x-layouts.dash-teacher active="quizzes">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.quizzes.index') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại danh sách bài kiểm tra
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-plus-circle mr-2"></i>Tạo bài kiểm tra mới
            </h4>
            <p class="text-muted mb-0">Thêm bài kiểm tra mới vào hệ thống</p>
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
                                    class="form-control @error('deadline') is-invalid @enderror" wire:model="deadline"
                                    min="{{ date('Y-m-d\TH:i') }}">
                                @error('deadline')
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
                                        <select class="form-control @error('currentQuestion.type') is-invalid @enderror"
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
                                <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                    <i class="bi bi-plus-circle mr-2"></i>Thêm câu hỏi
                                </button>
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
                            <a href="{{ route('quizzes.index') }}" wire:navigate class="btn btn-secondary mr-2">
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
