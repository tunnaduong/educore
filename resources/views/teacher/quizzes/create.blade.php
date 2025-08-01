<x-layouts.dash-teacher active="quizzes">
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-plus-circle me-2"></i>Tạo bài kiểm tra mới
                    </h4>
                    <p class="text-muted mb-0">Tạo bài kiểm tra cho lớp học của bạn</p>
                </div>
                <a href="{{ route('teacher.quizzes.index') }}" wire:navigate class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>

        <form wire:submit.prevent="save">
            <div class="row">
                <!-- Thông tin cơ bản -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Thông tin bài kiểm tra
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Tiêu đề bài kiểm tra <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           wire:model="title" placeholder="Nhập tiêu đề bài kiểm tra...">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Mô tả</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              wire:model="description" rows="3" 
                                              placeholder="Nhập mô tả bài kiểm tra (tùy chọn)..."></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Lớp học <span class="text-danger">*</span></label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" wire:model="class_id">
                                        <option value="">Chọn lớp học...</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hạn nộp</label>
                                    <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror" 
                                           wire:model="deadline">
                                    @error('deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Thời gian làm bài (phút)</label>
                                    <input type="number" class="form-control @error('time_limit') is-invalid @enderror" 
                                           wire:model="time_limit" min="1" max="480" 
                                           placeholder="Để trống nếu không giới hạn thời gian">
                                    @error('time_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách câu hỏi -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-question-circle me-2"></i>Câu hỏi ({{ count($questions) }})
                            </h6>
                            <button type="button" class="btn btn-primary btn-sm" wire:click="addQuestion">
                                <i class="bi bi-plus-circle me-2"></i>Thêm câu hỏi
                            </button>
                        </div>
                        <div class="card-body">
                            @if (count($questions) > 0)
                                <div class="accordion" id="questionsAccordion">
                                    @foreach ($questions as $index => $question)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                                                        type="button" data-bs-toggle="collapse" 
                                                        data-bs-target="#question{{ $index }}">
                                                    <div class="d-flex justify-content-between align-items-center w-100 me-3">
                                                        <span>
                                                            <strong>Câu {{ $index + 1 }}:</strong> 
                                                            {{ Str::limit($question['question'] ?: 'Câu hỏi mới', 50) }}
                                                        </span>
                                                        <div class="btn-group btn-group-sm">
                                                            <button type="button" class="btn btn-outline-warning btn-sm"
                                                                    wire:click="editQuestion({{ $index }})">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-outline-danger btn-sm"
                                                                    wire:click="removeQuestion({{ $index }})"
                                                                    onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            <div id="question{{ $index }}" 
                                                 class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
                                                <div class="accordion-body">
                                                    <div class="row g-3">
                                                        <div class="col-12">
                                                            <label class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" 
                                                                      wire:model="questions.{{ $index }}.question" 
                                                                      rows="3" placeholder="Nhập nội dung câu hỏi..."></textarea>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Loại câu hỏi <span class="text-danger">*</span></label>
                                                            <select class="form-select" wire:model="questions.{{ $index }}.type">
                                                                <option value="multiple_choice">Trắc nghiệm</option>
                                                                <option value="true_false">Đúng/Sai</option>
                                                                <option value="essay">Tự luận</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label class="form-label">Điểm <span class="text-danger">*</span></label>
                                                            <input type="number" class="form-control" 
                                                                   wire:model="questions.{{ $index }}.score" 
                                                                   min="1" max="10" value="1">
                                                        </div>

                                                        @if ($question['type'] === 'multiple_choice')
                                                            <div class="col-12">
                                                                <label class="form-label">Các lựa chọn <span class="text-danger">*</span></label>
                                                                @foreach ($question['options'] as $optionIndex => $option)
                                                                    <div class="input-group mb-2">
                                                                        <span class="input-group-text">{{ chr(65 + $optionIndex) }}</span>
                                                                        <input type="text" class="form-control" 
                                                                               wire:model="questions.{{ $index }}.options.{{ $optionIndex }}" 
                                                                               placeholder="Nhập lựa chọn {{ chr(65 + $optionIndex) }}...">
                                                                        @if (count($question['options']) > 2)
                                                                            <button type="button" class="btn btn-outline-danger"
                                                                                    wire:click="removeOption({{ $index }}, {{ $optionIndex }})">
                                                                                <i class="bi bi-trash"></i>
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                                        wire:click="addOption({{ $index }})">
                                                                    <i class="bi bi-plus-circle me-2"></i>Thêm lựa chọn
                                                                </button>
                                                            </div>

                                                            <div class="col-12">
                                                                <label class="form-label">Đáp án đúng <span class="text-danger">*</span></label>
                                                                <select class="form-select" wire:model="questions.{{ $index }}.correct_answer">
                                                                    <option value="">Chọn đáp án đúng...</option>
                                                                    @foreach ($question['options'] as $optionIndex => $option)
                                                                        @if (!empty(trim($option)))
                                                                            <option value="{{ chr(65 + $optionIndex) }}">
                                                                                {{ chr(65 + $optionIndex) }}. {{ $option }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        @endif

                                                        @if ($question['type'] === 'true_false')
                                                            <div class="col-12">
                                                                <label class="form-label">Đáp án đúng <span class="text-danger">*</span></label>
                                                                <select class="form-select" wire:model="questions.{{ $index }}.correct_answer">
                                                                    <option value="">Chọn đáp án...</option>
                                                                    <option value="true">Đúng</option>
                                                                    <option value="false">Sai</option>
                                                                </select>
                                                            </div>
                                                        @endif

                                                        @if ($question['type'] === 'essay')
                                                            <div class="col-12">
                                                                <div class="alert alert-info">
                                                                    <i class="bi bi-info-circle me-2"></i>
                                                                    <strong>Lưu ý:</strong> Câu hỏi tự luận sẽ được chấm điểm thủ công bởi giáo viên.
                                                                </div>
                                                            </div>
                                                        @endif

                                                        <div class="col-12">
                                                            <label class="form-label">Giải thích (tùy chọn)</label>
                                                            <textarea class="form-control" 
                                                                      wire:model="questions.{{ $index }}.explanation" 
                                                                      rows="2" placeholder="Giải thích đáp án..."></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-question-circle fs-1 text-muted mb-3"></i>
                                    <h6 class="text-muted">Chưa có câu hỏi nào</h6>
                                    <p class="text-muted">Hãy thêm câu hỏi đầu tiên để bắt đầu.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle me-2"></i>Thông tin tổng quan
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Số câu hỏi:</strong> {{ count($questions) }}
                            </div>
                            <div class="mb-3">
                                <strong>Tổng điểm:</strong> 
                                {{ array_sum(array_column($questions, 'score')) }}
                            </div>
                            @if ($time_limit)
                                <div class="mb-3">
                                    <strong>Thời gian:</strong> {{ $time_limit }} phút
                                </div>
                            @endif
                            @if ($deadline)
                                <div class="mb-3">
                                    <strong>Hạn nộp:</strong> {{ \Carbon\Carbon::parse($deadline)->format('d/m/Y H:i') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>Tạo bài kiểm tra
                                </button>
                                <button type="button" wire:click="debugForm" class="btn btn-outline-info">
                                    <i class="bi bi-bug me-2"></i>Debug Form
                                </button>
                                <a href="{{ route('teacher.quizzes.index') }}" wire:navigate class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i>Hủy bỏ
                                </a>
                            </div>
                            
                            <!-- Debug Info -->
                            @if(app()->environment('local'))
                                <div class="mt-3 p-2 bg-light rounded">
                                    <small class="text-muted">
                                        <strong>Debug:</strong> 
                                        Title: "{{ $title }}", 
                                        Class: "{{ $class_id }}", 
                                        Questions: {{ count($questions) }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
