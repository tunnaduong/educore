<x-layouts.dash-teacher active="quizzes">
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%);
        }
        .bg-success-light { background-color: rgba(40, 167, 69, 0.08); }
        .bg-info-light { background-color: rgba(23, 162, 184, 0.08); }
        .border-success { border-color: #28a745 !important; }
        .border-primary { border-color: #007bff !important; }
        .question-card { transition: all 0.3s; }
        .question-card:hover { box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.12)!important; transform: translateY(-2px); }
        .form-label { font-weight: 600; }
        .input-group-text { font-weight: 600; }
        .card-header { font-size: 1.1rem; }
        .badge { font-size: 0.95em; }
    </style>
    <div class="container-fluid">
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4 fw-bold">
                        <i class="bi bi-plus-circle me-2"></i>Tạo bài kiểm tra mới
                    </h4>
                    <p class="text-muted mb-0 fs-5">Tạo bài kiểm tra cho lớp học của bạn</p>
                </div>
                <a href="{{ route('teacher.quizzes.index') }}" wire:navigate class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                </a>
            </div>
        </div>
        <form wire:submit.prevent="save">
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-primary text-white rounded-top">
                            <i class="bi bi-info-circle me-2"></i>Thông tin bài kiểm tra
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-file-text me-2 text-primary"></i>Tiêu đề bài kiểm tra <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control form-control-lg @error('title') is-invalid @enderror" wire:model="title" placeholder="Nhập tiêu đề bài kiểm tra...">
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-chat-quote me-2 text-primary"></i>Mô tả
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3" placeholder="Nhập mô tả bài kiểm tra (tùy chọn)..."></textarea>
                                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-people me-2 text-primary"></i>Lớp học <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('class_id') is-invalid @enderror" wire:model="class_id">
                                        <option value="">Chọn lớp học...</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('class_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-clock me-2 text-primary"></i>Thời gian làm bài (phút)
                                    </label>
                                    <input type="number" class="form-control @error('time_limit') is-invalid @enderror" wire:model="time_limit" min="1" max="480" placeholder="Để trống nếu không giới hạn thời gian">
                                    @error('time_limit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-plus me-2 text-primary"></i>Ngày giao bài
                                    </label>
                                    @php
                                        $now = \Carbon\Carbon::now()->format('Y-m-d\\TH:i');
                                    @endphp
                                    <input type="datetime-local" class="form-control @error('assigned_date') is-invalid @enderror" 
                                           wire:model="assigned_date" min="{{ $minAssignedDate }}">
                                    @error('assigned_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-check me-2 text-primary"></i>Hạn nộp
                                    </label>
                                    <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror" wire:model="deadline" min="{{ $assigned_date ? \Carbon\Carbon::parse($assigned_date)->format('Y-m-d\TH:i') : date('Y-m-d\TH:i') }}">
                                    @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center rounded-top">
                            <span><i class="bi bi-question-circle me-2"></i>Câu hỏi <span class="badge bg-light text-success ms-1">{{ count($questions) }}</span></span>
                            <button type="button" class="btn btn-light btn-sm fw-bold" wire:click="addQuestion">
                                <i class="bi bi-plus-circle me-2"></i>Thêm câu hỏi
                            </button>
                        </div>
                        <div class="card-body">
                            @if (count($questions) > 0)
                                <div class="accordion" id="questionsAccordion">
                                    @foreach ($questions as $index => $question)
                                        <div class="accordion-item border-0 mb-3">
                                            <div class="card shadow-sm question-card">
                                                <div class="card-header bg-light border-0 rounded-top">
                                                    <button class="btn btn-link text-decoration-none w-100 text-start p-0" type="button" data-bs-toggle="collapse" data-bs-target="#question{{ $index }}">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div class="d-flex align-items-center">
                                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                                                    {{ $index + 1 }}
                                                                </div>
                                                                <div>
                                                                    <h6 class="mb-1 fw-bold text-dark">{{ Str::limit($question['question'] ?: 'Câu hỏi mới', 60) }}</h6>
                                                                    <div class="d-flex gap-2">
                                                                        <span class="badge bg-primary">{{ $question['score'] ?? 1 }} điểm</span>
                                                                        <span class="badge bg-info">Trắc nghiệm</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="d-flex align-items-center">
                                                                <div class="btn-group btn-group-sm me-3">
                                                                    <button type="button" class="btn btn-outline-warning btn-sm" wire:click="editQuestion({{ $index }})"><i class="bi bi-pencil"></i></button>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeQuestion({{ $index }})" onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này?')"><i class="bi bi-trash"></i></button>
                                                                </div>
                                                                <i class="bi bi-chevron-down text-muted"></i>
                                                            </div>
                                                        </div>
                                                    </button>
                                                </div>
                                                <div id="question{{ $index }}" class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}">
                                                    <div class="card-body bg-white">
                                                        <div class="row g-4">
                                                            <div class="col-12">
                                                                <label class="form-label"><i class="bi bi-chat-quote me-2 text-primary"></i>Nội dung câu hỏi <span class="text-danger">*</span></label>
                                                                <textarea class="form-control" wire:model="questions.{{ $index }}.question" rows="3" placeholder="Nhập nội dung câu hỏi..."></textarea>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label"><i class="bi bi-star me-2 text-primary"></i>Điểm <span class="text-danger">*</span></label>
                                                                <input type="number" class="form-control" wire:model="questions.{{ $index }}.score" min="1" max="10" value="1">
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label"><i class="bi bi-list-check me-2 text-primary"></i>Các lựa chọn <span class="text-danger">*</span></label>
                                                                <div class="row g-3">
                                                                    @foreach ($question['options'] as $optionIndex => $option)
                                                                        <div class="col-12">
                                                                            <div class="input-group">
                                                                                <span class="input-group-text bg-primary text-white fw-bold" style="min-width: 50px; justify-content: center;">{{ chr(65 + $optionIndex) }}</span>
                                                                                <input type="text" class="form-control" wire:model="questions.{{ $index }}.options.{{ $optionIndex }}" placeholder="Nhập lựa chọn {{ chr(65 + $optionIndex) }}...">
                                                                                @if (count($question['options']) > 2)
                                                                                    <button type="button" class="btn btn-outline-danger" wire:click="removeOption({{ $index }}, {{ $optionIndex }})"><i class="bi bi-trash"></i></button>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                <button type="button" class="btn btn-outline-primary btn-sm mt-3" wire:click="addOption({{ $index }})"><i class="bi bi-plus-circle me-2"></i>Thêm lựa chọn</button>
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label"><i class="bi bi-check-circle me-2 text-primary"></i>Đáp án đúng <span class="text-danger">*</span></label>
                                                                <div class="row g-3">
                                                                    @foreach ($question['options'] as $optionIndex => $option)
                                                                        <div class="col-md-6">
                                                                            <div class="card border-2 {{ $question['correct_answer'] === chr(65 + $optionIndex) ? 'border-success bg-success-light' : 'border-light' }} h-100">
                                                                                <div class="card-body p-3">
                                                                                    <div class="form-check">
                                                                                        <input class="form-check-input" type="radio" name="correct_answer_{{ $index }}" id="correct_{{ $index }}_{{ $optionIndex }}" value="{{ chr(65 + $optionIndex) }}" wire:model.live="questions.{{ $index }}.correct_answer">
                                                                                        <label class="form-check-label fw-medium" for="correct_{{ $index }}_{{ $optionIndex }}">
                                                                                            <span class="badge bg-primary me-2">{{ chr(65 + $optionIndex) }}</span>
                                                                                            {{ $option ?: 'Lựa chọn ' . chr(65 + $optionIndex) }}
                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                                @if (empty($question['options']) || count(array_filter($question['options'], function($opt) { return !empty(trim($opt)); })) < 2)
                                                                    <div class="alert alert-warning mt-3"><i class="bi bi-exclamation-triangle me-2"></i>Cần ít nhất 2 lựa chọn có nội dung để chọn đáp án</div>
                                                                @endif
                                                            </div>
                                                            <div class="col-12">
                                                                <label class="form-label"><i class="bi bi-lightbulb me-2 text-primary"></i>Giải thích (tùy chọn)</label>
                                                                <textarea class="form-control" wire:model="questions.{{ $index }}.explanation" rows="2" placeholder="Giải thích đáp án..."></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                        <i class="bi bi-question-circle fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="text-muted mb-2">Chưa có câu hỏi nào</h6>
                                    <p class="text-muted mb-0">Hãy thêm câu hỏi đầu tiên để bắt đầu.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-gradient-info text-white rounded-top">
                            <i class="bi bi-info-circle me-2"></i>Thông tin tổng quan
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"><i class="bi bi-question-circle fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block">Số câu hỏi</small>
                                            <strong class="text-dark fs-5">{{ count($questions) }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"><i class="bi bi-star fs-5"></i></div>
                                        <div>
                                            <small class="text-muted d-block">Tổng điểm</small>
                                            <strong class="text-dark fs-5">{{ array_sum(array_column($questions, 'score')) }}</strong>
                                        </div>
                                    </div>
                                </div>
                                @if ($time_limit)
                                    <div class="col-12">
                                        <div class="d-flex align-items-center p-3 bg-light rounded">
                                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"><i class="bi bi-clock fs-5"></i></div>
                                            <div>
                                                <small class="text-muted d-block">Thời gian</small>
                                                <strong class="text-dark fs-5">{{ $time_limit }} phút</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($deadline)
                                    <div class="col-12">
                                        <div class="d-flex align-items-center p-3 bg-light rounded">
                                            <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;"><i class="bi bi-calendar-check fs-5"></i></div>
                                            <div>
                                                <small class="text-muted d-block">Hạn nộp</small>
                                                <strong class="text-dark fs-5">{{ \Carbon\Carbon::parse($deadline)->format('d/m/Y H:i') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-grid gap-3">
                                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-check-circle me-2"></i>Tạo bài kiểm tra</button>
                                <a href="{{ route('teacher.quizzes.index') }}" wire:navigate class="btn btn-outline-secondary"><i class="bi bi-x-circle me-2"></i>Hủy bỏ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
