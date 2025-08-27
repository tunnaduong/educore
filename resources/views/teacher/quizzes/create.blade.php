<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    @php
        $t = function ($vi, $en, $zh) {
            $l = app()->getLocale();
            return $l === 'vi' ? $vi : ($l === 'zh' ? $zh : $en);
        };
    @endphp
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.quizzes.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ $t('Quay lại danh sách bài kiểm tra', 'Back to quiz list', '返回测验列表') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-plus-circle mr-2"></i>{{ $t('Tạo bài kiểm tra mới', 'Create new quiz', '创建新测验') }}
            </h4>
            <p class="text-muted mb-0">{{ $t('Thêm bài kiểm tra mới vào hệ thống', 'Add a new quiz to the system', '向系统添加新测验') }}</p>
        </div>

        <!-- AI Tools -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-robot mr-2"></i>{{ $t('Công cụ AI Tiếng Trung', 'Chinese AI Tools', '中文AI工具') }}
                        </h6>
                    </div>
                    <div class="card-body">
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
                                background: rgba(255, 193, 7, 0.9);
                                border-radius: 0.375rem;
                                z-index: 10;
                            }

                            .loading-text {
                                color: white;
                                font-weight: 500;
                            }
                        </style>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-magic fa-3x text-primary mb-3"></i>
                                    <h6>{{ $t('Tạo Quiz Tiếng Trung bằng AI', 'Create Chinese Quiz with AI', '使用AI创建中文测验') }}</h6>
                                    <p class="text-muted small">{{ $t('Tự động tạo quiz tiếng Trung từ nội dung bài học', 'Automatically generate Chinese quizzes from lesson content', '根据课程内容自动生成中文测验') }}</p>
                                    <a href="{{ route('teacher.ai.quiz-generator') }}" class="btn btn-primary">
                                        <i class="fas fa-robot mr-1"></i>{{ $t('Tạo Quiz AI', 'Create AI Quiz', '创建AI测验') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-database fa-3x text-success mb-3"></i>
                                    <h6>{{ $t('Ngân hàng Câu hỏi Tiếng Trung', 'Chinese Question Bank', '中文题库') }}</h6>
                                    <p class="text-muted small">{{ $t('Tạo ngân hàng câu hỏi tiếng Trung với tối đa 100 câu', 'Create a Chinese question bank with up to 100 questions', '创建最多100题的中文题库') }}</p>
                                    <a href="{{ route('teacher.ai.question-bank-generator') }}" class="btn btn-success">
                                        <i class="fas fa-database mr-1"></i>{{ $t('Tạo Ngân hàng', 'Create Bank', '创建题库') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-check-circle fa-3x text-warning mb-3"></i>
                                    <h6>{{ $t('Kiểm tra Lỗi Quiz', 'Check Quiz Errors', '检查测验错误') }}</h6>
                                    <p class="text-muted small">{{ $t('Tự động kiểm tra và sửa lỗi quiz tiếng Trung', 'Automatically check and fix Chinese quiz errors', '自动检查并修复中文测验错误') }}</p>
                                    <button type="button" class="btn btn-warning position-relative"
                                        wire:click="validateQuizWithAI" wire:loading.attr="disabled"
                                        wire:loading.class="disabled">
                                        <div wire:loading.remove>
                                            <i class="fas fa-check-circle mr-1"></i>{{ $t('Kiểm tra AI', 'AI Check', 'AI检查') }}
                                        </div>
                                        <div wire:loading class="loading-overlay">
                                            <div class="spinner-border spinner-border-sm text-light me-2"
                                                role="status">
                                                <span class="visually-hidden">{{ $t('Loading...', 'Loading...', '加载中...') }}</span>
                                            </div>
                                            <span class="loading-text">{{ $t('Đang kiểm tra...', 'Checking...', '正在检查...') }}</span>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        @if (session()->has('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form wire:submit="save">
            <div class="row">
                <!-- Thông tin cơ bản -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle mr-2"></i>{{ $t('Thông tin cơ bản', 'Basic Information', '基本信息') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">{{ $t('Tiêu đề bài kiểm tra', 'Quiz title', '测验标题') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    wire:model="title" placeholder="{{ $t('Nhập tiêu đề...', 'Enter title...', '输入标题...') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ $t('Mô tả', 'Description', '描述') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3"
                                    placeholder="{{ $t('Mô tả bài kiểm tra...', 'Describe the quiz...', '填写测验描述...') }}"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ $t('Lớp học', 'Classroom', '班级') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('class_id') is-invalid @enderror"
                                    wire:model="class_id">
                                    <option value="">{{ $t('Chọn lớp học...', 'Select a class...', '请选择班级...') }}</option>
                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ $t('Hạn nộp', 'Deadline', '截止时间') }}</label>
                                <input type="datetime-local"
                                    class="form-control @error('deadline') is-invalid @enderror" wire:model="deadline"
                                    min="{{ date('Y-m-d\TH:i') }}">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ $t('Thời gian làm bài (phút)', 'Time limit (minutes)', '答题时长（分钟）') }}</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror"
                                    wire:model="time_limit" min="1" max="480"
                                    placeholder="{{ $t('Nhập thời gian làm bài (ví dụ: 30)', 'Enter time limit (e.g., 30)', '输入时长（例如：30）') }}">
                                <small class="form-text text-muted">{{ $t('Để trống nếu không giới hạn thời gian. Tối đa 8 giờ (480 phút)', 'Leave blank for no limit. Max 8 hours (480 minutes)', '留空表示不限时。最多8小时（480分钟）') }}</small>
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
                                <i class="bi bi-plus-circle mr-2"></i>{{ $t('Thêm câu hỏi mới', 'Add new question', '新增题目') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">{{ $t('Nội dung câu hỏi', 'Question content', '题目内容') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('currentQuestion.question') is-invalid @enderror"
                                            wire:model="currentQuestion.question" rows="3" placeholder="{{ $t('Nhập nội dung câu hỏi...', 'Enter question...', '输入题目内容...') }}"></textarea>
                                        @error('currentQuestion.question')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ $t('Loại câu hỏi', 'Question type', '题目类型') }} <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control @error('currentQuestion.type') is-invalid @enderror"
                                            wire:model="currentQuestion.type">
                                            <option value="multiple_choice">{{ $t('Trắc nghiệm', 'Multiple choice', '选择题') }}</option>
                                            <option value="fill_blank">{{ $t('Điền từ', 'Fill in the blanks', '填空') }}</option>
                                            <option value="drag_drop">{{ $t('Kéo thả', 'Drag and drop', '拖拽题') }}</option>
                                            <option value="essay">{{ $t('Tự luận', 'Essay', '问答题') }}</option>
                                        </select>
                                        @error('currentQuestion.type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ $t('Điểm', 'Score', '分数') }} <span class="text-danger">*</span></label>
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
                                    <label class="form-label">{{ $t('Các đáp án', 'Answer options', '答案选项') }} <span class="text-danger">*</span></label>
                                    @foreach ($currentQuestion['options'] as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                class="form-control @error('currentQuestion.options.' . $index) is-invalid @enderror"
                                                wire:model.live="currentQuestion.options.{{ $index }}"
                                                placeholder="{{ $t('Đáp án', 'Answer', '答案') }} {{ $index + 1 }}">
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
                                        <i class="bi bi-plus mr-1"></i>{{ $t('Thêm đáp án', 'Add answer', '添加答案') }}
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ $t('Đáp án đúng', 'Correct answer', '正确答案') }} <span class="text-danger">*</span></label>
                                    <select
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model.live="currentQuestion.correct_answer">
                                        <option value="">{{ $t('Chọn đáp án đúng...', 'Choose the correct answer...', '选择正确答案...') }}</option>
                                        @foreach ($currentQuestion['options'] as $index => $option)
                                            <option value="{{ $option }}" {{ $option ? '' : 'disabled' }}>
                                                {{ $option ?: ($t('Đáp án', 'Answer', '答案') . ' ' . ($index + 1)) }}</option>
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
                                    <label class="form-label">{{ $t('Đáp án đúng', 'Correct answer', '正确答案') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model="currentQuestion.correct_answer" placeholder="{{ $t('Nhập đáp án đúng...', 'Enter the correct answer...', '输入正确答案...') }}">
                                    @error('currentQuestion.correct_answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="text-end">
                                <button type="button" class="btn btn-outline-success mr-2"
                                    wire:click="$set('showQuestionBank', true)">
                                    <i class="bi bi-database mr-2"></i>{{ $t('Chèn từ ngân hàng câu hỏi', 'Insert from question bank', '从题库插入') }}
                                </button>
                                <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                    <i class="bi bi-plus-circle mr-2"></i>{{ $t('Thêm câu hỏi', 'Add question', '添加题目') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách câu hỏi đã thêm -->
                    @if (count($questions) > 0)
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bi bi-list-ul mr-2"></i>{{ $t('Danh sách câu hỏi', 'Question list', '题目列表') }} ({{ count($questions) }})
                                </h6>
                            </div>
                            <div class="card-body">
                                @foreach ($questions as $index => $question)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="badge bg-primary mr-2">{{ $t('Câu', 'Question', '第') }} {{ $index + 1 }}</span>
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                                <span class="badge bg-info">{{ $question['score'] }} {{ $t('điểm', 'pts', '分') }}</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                @if ($index > 0)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionUp({{ $index }})"
                                                        title="{{ $t('Di chuyển lên', 'Move up', '上移') }}">
                                                        <i class="bi bi-arrow-up"></i>
                                                    </button>
                                                @endif
                                                @if ($index < count($questions) - 1)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionDown({{ $index }})"
                                                        title="{{ $t('Di chuyển xuống', 'Move down', '下移') }}">
                                                        <i class="bi bi-arrow-down"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger"
                                                    wire:click="removeQuestion({{ $index }})" title="{{ $t('Xóa', 'Delete', '删除') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="fw-medium">{{ $question['question'] }}</div>
                                        @if ($question['type'] === 'multiple_choice' && isset($question['options']))
                                            <div class="mt-2">
                                                <small class="text-muted">{{ $t('Đáp án đúng', 'Correct answer', '正确答案') }}:
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

        <!-- Modal Ngân hàng Câu hỏi -->
        @if ($showQuestionBank)
            <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-dialog-scrollable modal-xl">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">
                                <i class="bi bi-database mr-2"></i>Ngân hàng Câu hỏi
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
                                        <option value="drag_drop">Kéo thả</option>
                                        <option value="essay">Tự luận</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Danh sách câu hỏi -->
                            @if (!empty($questionBankQuestions))
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6>{{ $t('Danh sách câu hỏi', 'Question list', '题目列表') }} ({{ count($filteredQuestions) }})</h6>
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="addSelectedQuestions">
                                                    <i class="bi bi-plus mr-1"></i>Thêm
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
                                                        <th>{{ $t('Nội dung câu hỏi', 'Question', '题目') }}</th>
                                                        <th width="120">{{ $t('Loại', 'Type', '类型') }}</th>
                                                        <th width="80">{{ $t('Điểm', 'Score', '分数') }}</th>
                                                        <th width="100">{{ $t('Độ khó', 'Difficulty', '难度') }}</th>
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
                                                                        {{ $t('Đáp án đúng', 'Correct answer', '正确答案') }}:
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
                                                                    class="badge bg-{{ ($question['difficulty'] ?? 'medium') == 'easy' ? 'success' : (($question['difficulty'] ?? 'medium') == 'medium' ? 'warning' : 'danger') }}">
                                                                    {{ ucfirst($question['difficulty'] ?? 'medium') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center text-muted">
                                                                <i class="bi bi-inbox fa-2x mb-2"></i>
                                                                <br>{{ $t('Không có câu hỏi nào trong ngân hàng này', 'No questions in this bank', '该题库暂无题目') }}
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
                                    <i class="bi bi-database fa-3x mb-3"></i>
                                    <h5>{{ $t('Chưa có ngân hàng câu hỏi nào', 'No question bank yet', '暂无题库') }}</h5>
                                    <p>{{ $t('Vui lòng tạo ngân hàng câu hỏi bằng AI trước khi sử dụng tính năng này.', 'Please create a question bank with AI before using this feature.', '请先使用AI创建题库后再使用该功能。') }}</p>
                                    <a href="{{ route('teacher.ai.question-bank-generator') }}"
                                        class="btn btn-primary">
                                        <i class="bi bi-plus mr-1"></i>{{ $t('Tạo Ngân hàng Câu hỏi', 'Create Question Bank', '创建题库') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeQuestionBank">
                                <i class="bi bi-x mr-1"></i>{{ $t('Đóng', 'Close', '关闭') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

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
</x-layouts.dash-teacher>
