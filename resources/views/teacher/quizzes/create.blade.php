<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.quizzes.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back_to_quiz_list') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-plus-circle mr-2"></i>{{ __('general.create_new_quiz') }}
            </h4>
            <p class="text-muted mb-0">{{ __('general.add_new_quiz_desc') }}</p>
        </div>

        <!-- AI Tools -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-robot mr-2"></i>{{ __('general.chinese_ai_tools') }}
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
                                    <h6>{{ __('general.create_chinese_quiz_with_ai') }}</h6>
                                    <p class="text-muted small">{{ __('general.auto_create_chinese_quiz') }}</p>
                                    <a href="{{ route('teacher.ai.quiz-generator') }}" class="btn btn-primary">
                                        <i class="fas fa-robot mr-1"></i>{{ __('general.create_ai_quiz') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-database fa-3x text-success mb-3"></i>
                                    <h6>{{ __('general.chinese_question_bank') }}</h6>
                                    <p class="text-muted small">{{ __('general.create_chinese_question_bank_desc') }}</p>
                                    <a href="{{ route('teacher.ai.question-bank-generator') }}" class="btn btn-success">
                                        <i class="fas fa-database mr-1"></i>{{ __('general.create_bank') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-check-circle fa-3x text-warning mb-3"></i>
                                    <h6>{{ __('general.check_quiz_errors') }}</h6>
                                    <p class="text-muted small">{{ __('general.auto_check_fix_quiz_errors') }}</p>
                                    <button type="button" class="btn btn-warning position-relative"
                                        wire:click="validateQuizWithAI" wire:loading.attr="disabled"
                                        wire:loading.class="disabled">
                                        <div wire:loading.remove>
                                            <i class="fas fa-check-circle mr-1"></i>{{ __('general.ai_check') }}
                                        </div>
                                        <div wire:loading class="loading-overlay">
                                            <div class="spinner-border spinner-border-sm text-light me-2"
                                                role="status">
                                                <span class="visually-hidden">{{ __('general.loading') }}</span>
                                            </div>
                                            <span class="loading-text">{{ __('general.checking') }}</span>
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
                                <i class="bi bi-info-circle mr-2"></i>{{ __('general.quiz_information') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('general.quiz_title') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    wire:model="title" placeholder="{{ __('general.enter_quiz_title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3"
                                    placeholder="{{ __('general.describe_quiz_placeholder') }}"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.classroom') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('class_id') is-invalid @enderror"
                                    wire:model="class_id">
                                    <option value="">{{ __('general.select_class') }}</option>
                                    @foreach ($classrooms as $classroom)
                                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.deadline') }}</label>
                                <input type="datetime-local"
                                    class="form-control @error('deadline') is-invalid @enderror" wire:model="deadline"
                                    min="{{ date('Y-m-d\TH:i') }}">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.time_limit_minutes') }}</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror"
                                    wire:model="time_limit" min="1" max="480"
                                    placeholder="{{ __('general.enter_time_limit_example') }}">
                                <small class="form-text text-muted">{{ __('general.no_time_limit_hint') }}</small>
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
                                <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_new_question') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('general.question_content') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('currentQuestion.question') is-invalid @enderror"
                                            wire:model="currentQuestion.question" rows="3" placeholder="{{ __('general.enter_question_content') }}"></textarea>
                                        @error('currentQuestion.question')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('general.question_type') }} <span
                                                class="text-danger">*</span></label>
                                        <select
                                            class="form-control @error('currentQuestion.type') is-invalid @enderror"
                                            wire:model="currentQuestion.type">
                                            <option value="multiple_choice">{{ __('general.multiple_choice') }}</option>
                                            <option value="fill_blank">{{ __('general.fill_blank') }}</option>
                                            <option value="drag_drop">{{ __('general.drag_drop') }}</option>
                                            <option value="essay">{{ __('general.essay') }}</option>
                                        </select>
                                        @error('currentQuestion.type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('general.score') }} <span class="text-danger">*</span></label>
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
                                    <label class="form-label">{{ __('general.answer_options') }} <span class="text-danger">*</span></label>
                                    @foreach ($currentQuestion['options'] as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                class="form-control @error('currentQuestion.options.' . $index) is-invalid @enderror"
                                                wire:model.live="currentQuestion.options.{{ $index }}"
                                                placeholder="{{ __('general.answer') }} {{ $index + 1 }}">
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
                                        <i class="bi bi-plus mr-1"></i>{{ __('general.add_answer') }}
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('general.correct_answer') }} <span class="text-danger">*</span></label>
                                    <select
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model.live="currentQuestion.correct_answer">
                                        <option value="">{{ __('general.choose_correct_answer') }}</option>
                                        @foreach ($currentQuestion['options'] as $index => $option)
                                            <option value="{{ $option }}" {{ $option ? '' : 'disabled' }}>
                                                {{ $option ?: (__('general.answer') . ' ' . ($index + 1)) }}</option>
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
                                    <label class="form-label">{{ __('general.correct_answer') }} <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model="currentQuestion.correct_answer" placeholder="{{ __('general.enter_correct_answer') }}">
                                    @error('currentQuestion.correct_answer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="text-end">
                                <button type="button" class="btn btn-outline-success mr-2"
                                    wire:click="$set('showQuestionBank', true)">
                                    <i class="bi bi-database mr-2"></i>{{ __('general.insert_from_question_bank') }}
                                </button>
                                <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                    <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_question_btn') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách câu hỏi đã thêm -->
                    @if (count($questions) > 0)
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="bi bi-list-ul mr-2"></i>{{ __('general.question_list') }} ({{ count($questions) }})
                                </h6>
                            </div>
                            <div class="card-body">
                                @foreach ($questions as $index => $question)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span class="badge bg-primary mr-2">{{ __('general.question') }} {{ $index + 1 }}</span>
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                                <span class="badge bg-info">{{ $question['score'] }} {{ __('general.pts') }}</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                @if ($index > 0)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionUp({{ $index }})"
                                                        title="{{ __('general.move_up') }}">
                                                        <i class="bi bi-arrow-up"></i>
                                                    </button>
                                                @endif
                                                @if ($index < count($questions) - 1)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionDown({{ $index }})"
                                                        title="{{ __('general.move_down') }}">
                                                        <i class="bi bi-arrow-down"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger"
                                                    wire:click="removeQuestion({{ $index }})" title="{{ __('general.delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="fw-medium">{{ $question['question'] }}</div>
                                        @if ($question['type'] === 'multiple_choice' && isset($question['options']))
                                            <div class="mt-2">
                                                <small class="text-muted">{{ __('general.correct_answer', ['answer' => $question['correct_answer']]) }}:
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
                                            <h6>{{ __('general.question_list', ['count' => count($filteredQuestions)]) }} ({{ count($filteredQuestions) }})</h6>
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
                                                        <th>{{ __('general.question') }}</th>
                                                        <th width="120">{{ __('general.type') }}</th>
                                                        <th width="80">{{ __('general.score') }}</th>
                                                        <th width="100">{{ __('general.difficulty') }}</th>
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
                                                                        {{ __('general.correct_answer', ['answer' => $question['correct_answer'] ?? '']) }}:
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
                                                                <br>{{ __('general.no_questions_in_this_bank') }}
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
                                    <h5>{{ __('general.no_question_bank_yet') }}</h5>
                                    <p>{{ __('general.please_create_question_bank_with_ai_before_using_this_feature') }}</p>
                                    <a href="{{ route('teacher.ai.question-bank-generator') }}"
                                        class="btn btn-primary">
                                        <i class="bi bi-plus mr-1"></i>{{ __('general.create_question_bank') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeQuestionBank">
                                <i class="bi bi-x mr-1"></i>{{ __('general.close') }}
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
