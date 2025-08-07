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

        <form wire:submit.prevent="save">
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
                                <label class="form-label">Tiêu đề bài kiểm tra <span class="text-danger">*</span></label>
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
                                <label class="form-label">Ngày giao bài</label>
                                <input type="datetime-local" class="form-control @error('assigned_date') is-invalid @enderror"
                                    wire:model="assigned_date" min="{{ $minAssignedDate }}">
                                @error('assigned_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Hạn nộp</label>
                                <input type="datetime-local" class="form-control @error('deadline') is-invalid @enderror"
                                    wire:model="deadline" min="{{ $assigned_date ? \Carbon\Carbon::parse($assigned_date)->format('Y-m-d\TH:i') : date('Y-m-d\TH:i') }}">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Thời gian làm bài (phút)</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror"
                                    wire:model="time_limit" min="1" max="480" placeholder="Để trống nếu không giới hạn thời gian">
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
                            @if($showQuestionForm && $editingQuestionIndex !== null && isset($questions[$editingQuestionIndex]))
                                @php $question = $questions[$editingQuestionIndex]; @endphp
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label class="form-label">Nội dung câu hỏi <span class="text-danger">*</span></label>
                                            <textarea class="form-control @error('questions.' . $editingQuestionIndex . '.question') is-invalid @enderror"
                                                wire:model="questions.{{ $editingQuestionIndex }}.question" rows="3" placeholder="Nhập nội dung câu hỏi..."></textarea>
                                            @error('questions.' . $editingQuestionIndex . '.question')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Điểm <span class="text-danger">*</span></label>
                                            <input type="number"
                                                class="form-control @error('questions.' . $editingQuestionIndex . '.score') is-invalid @enderror"
                                                wire:model="questions.{{ $editingQuestionIndex }}.score" min="1" max="10">
                                            @error('questions.' . $editingQuestionIndex . '.score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Các đáp án <span class="text-danger">*</span></label>
                                    @foreach ($question['options'] as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                class="form-control @error('questions.' . $editingQuestionIndex . '.options.' . $index) is-invalid @enderror"
                                                wire:model="questions.{{ $editingQuestionIndex }}.options.{{ $index }}"
                                                placeholder="Đáp án {{ $index + 1 }}">
                                            @if (count($question['options']) > 2)
                                                <button type="button" class="btn btn-outline-danger"
                                                    wire:click="removeOption({{ $editingQuestionIndex }}, {{ $index }})">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        wire:click="addOption({{ $editingQuestionIndex }})">
                                        <i class="bi bi-plus mr-1"></i>Thêm đáp án
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Đáp án đúng <span class="text-danger">*</span></label>
                                    <select
                                        class="form-control @error('questions.' . $editingQuestionIndex . '.correct_answer') is-invalid @enderror"
                                        wire:model="questions.{{ $editingQuestionIndex }}.correct_answer">
                                        <option value="">Chọn đáp án đúng...</option>
                                        @foreach ($question['options'] as $index => $option)
                                            <option value="{{ chr(65 + $index) }}" {{ $option ? '' : 'disabled' }}>
                                                {{ $option ?: 'Đáp án ' . ($index + 1) }}</option>
                                        @endforeach
                                    </select>
                                    @error('questions.' . $editingQuestionIndex . '.correct_answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Giải thích (tùy chọn)</label>
                                    <textarea class="form-control" wire:model="questions.{{ $editingQuestionIndex }}.explanation" rows="2" placeholder="Giải thích đáp án..."></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="button" class="btn btn-success" wire:click="saveQuestion">
                                        <i class="bi bi-check-circle mr-2"></i>Lưu câu hỏi
                                    </button>
                                </div>
                            @else
                                <div class="text-center text-muted">Chọn hoặc thêm câu hỏi để chỉnh sửa</div>
                            @endif
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
                                                <span class="badge bg-info">{{ $question['score'] }} điểm</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-warning btn-sm" wire:click="editQuestion({{ $index }})"><i class="bi bi-pencil"></i></button>
                                                <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeQuestion({{ $index }})" onclick="return confirm('Bạn có chắc muốn xóa câu hỏi này?')"><i class="bi bi-trash"></i></button>
                                            </div>
                                        </div>
                                        <div class="fw-medium">{{ $question['question'] }}</div>
                                        <div class="mt-2">
                                            <small class="text-muted">Đáp án đúng:
                                                <strong>{{ $question['correct_answer'] }}</strong></small>
                                        </div>
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
                            <a href="{{ route('teacher.quizzes.index') }}" wire:navigate class="btn btn-secondary mr-2">
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
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3"
                role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
