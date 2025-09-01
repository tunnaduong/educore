<x-layouts.dash-admin active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('views.back_to_quiz_list') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-plus-circle mr-2"></i>{{ __('views.create_new_quiz') }}
            </h4>
            <p class="text-muted mb-0">{{ __('views.create_quiz_subtitle') }}</p>
        </div>

        <!-- AI Tools -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-robot mr-2"></i>{{ __('views.ai_tools_chinese_title') }}
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
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-magic fa-3x text-primary mb-3"></i>
                                    <h6>{{ __('views.ai_quiz_title') }}</h6>
                                    <p class="text-muted small">{{ __('views.ai_quiz_desc') }}</p>
                                    <a href="{{ route('ai.quiz-generator') }}" class="btn btn-primary">
                                        <i class="fas fa-robot mr-1"></i>{{ __('views.ai_quiz_button') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-database fa-3x text-success mb-3"></i>
                                    <h6>{{ __('views.question_bank_cn_title') }}</h6>
                                    <p class="text-muted small">{{ __('views.question_bank_cn_desc') }}</p>
                                    <a href="{{ route('ai.question-bank-generator') }}" class="btn btn-success">
                                        <i
                                            class="fas fa-database mr-1"></i>{{ __('general.create_question_bank_button') }}
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-folder-open fa-3x text-info mb-3"></i>
                                    <h6>{{ __('views.pick_from_question_bank_title') }}</h6>
                                    <p class="text-muted small">{{ __('views.pick_from_question_bank_desc') }}</p>
                                    <button type="button" class="btn btn-info"
                                        wire:click="$set('showQuestionBank', true)">
                                        <i class="fas fa-folder-open mr-1"></i>{{ __('views.pick_questions_button') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <i class="fas fa-check-circle fa-3x text-warning mb-3"></i>
                                    <h6>{{ __('views.quiz_check_title') }}</h6>
                                    <p class="text-muted small">{{ __('views.quiz_check_desc') }}</p>
                                    <button type="button" class="btn btn-warning" wire:click="validateQuizWithAI">
                                        <i class="fas fa-check-circle mr-1"></i>{{ __('views.quiz_check_button') }}
                                        <div wire:loading.remove>
                                            <i class="fas fa-check-circle mr-1"></i>{{ __('views.quiz_check_button') }}
                                        </div>
                                        <div wire:loading class="loading-overlay">
                                            <div class="spinner-border spinner-border-sm text-light me-2"
                                                role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                            <span class="loading-text">Đang kiểm tra...</span>
                                        </div>
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
                <!-- Basic Information -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle mr-2"></i>{{ __('views.basic_information') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('views.quiz_title_label') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    wire:model="title" placeholder="{{ __('views.enter_title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3"
                                    placeholder="{{ __('views.quiz_description_placeholder') }}"></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.classroom') }} <span
                                        class="text-danger">*</span></label>
                                <select class="form-control @error('class_id') is-invalid @enderror"
                                    wire:model="class_id">
                                    <option value="">{{ __('general.choose_class') }}</option>
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
                                    class="form-control @error('deadline') is-invalid @enderror" wire:model="deadline">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('views.time_limit_minutes_label') }}</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror"
                                    wire:model="time_limit" min="1" max="480"
                                    placeholder="{{ __('views.enter_time_limit_placeholder') }}">
                                <small class="form-text text-muted">{{ __('views.time_limit_help') }}</small>
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add questions -->
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
                                            wire:model="currentQuestion.question" rows="3" placeholder="{{ __('general.question_content') }}..."></textarea>
                                        @error('currentQuestion.question')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('general.score') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('currentQuestion.score') is-invalid @enderror"
                                            wire:model="currentQuestion.score" min="1" max="10">
                                        @error('currentQuestion.score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Options for multiple choice -->
                            @if ($currentQuestion['type'] === 'multiple_choice')
                                <div class="mb-3">
                                    <label class="form-label">{{ __('general.answer_options') }} <span
                                            class="text-danger">*</span></label>
                                    @foreach ($currentQuestion['options'] as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                class="form-control @error('currentQuestion.options.' . $index) is-invalid @enderror"
                                                wire:model.live="currentQuestion.options.{{ $index }}"
                                                placeholder="{{ __('general.answer_number', ['number' => $index + 1]) }}">
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
                                    <label class="form-label">{{ __('general.correct_answer') }} <span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model.live="currentQuestion.correct_answer">
                                        <option value="">{{ __('general.choose_correct_answer') }}</option>
                                        @foreach ($currentQuestion['options'] as $index => $option)
                                            <option value="{{ $option }}" {{ $option ? '' : 'disabled' }}>
                                                {{ $option ?: __('general.answer_number', ['number' => $index + 1]) }}
                                            </option>
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
                                        <i class="bi bi-x-circle mr-2"></i>{{ __('general.cancel_edit') }}
                                    </button>
                                    <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                        <i class="bi bi-check-circle mr-2"></i>{{ __('general.update_question') }}
                                    </button>
                                @else
                                    <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                        <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_question_btn') }}
                                    </button>
                                @endif
                                =======
                                <button type="button" class="btn btn-primary" wire:click="addQuestion">
                                    <i class="bi bi-plus-circle mr-2"></i>Thêm câu hỏi
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Added questions list -->
                    @if (count($questions) > 0)
                        <div class="card shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i
                                        class="bi bi-list-ul mr-2"></i>{{ __('general.question_list', ['count' => count($questions)]) }}
                                </h6>
                            </div>
                            <div class="card-body">
                                @foreach ($questions as $index => $question)
                                    <div class="border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <span
                                                    class="badge bg-primary mr-2">{{ __('views.question_number', ['number' => $index + 1]) }}</span>
                                                <span
                                                    class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                                <span class="badge bg-info">
                                                    {{ $question['score'] ?? ($question['points'] ?? 1) }}
                                                    {{ __('views.points') }}</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-warning"
                                                    wire:click="editQuestion({{ $index }})"
                                                    title="{{ __('general.edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                @if ($index > 0)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionUp({{ $index }})"
                                                        title="{{ __('views.move_up') }}">
                                                        <i class="bi bi-arrow-up"></i>
                                                    </button>
                                                @endif
                                                @if ($index < count($questions) - 1)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionDown({{ $index }})"
                                                        title="{{ __('views.move_down') }}">
                                                        <i class="bi bi-arrow-down"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger"
                                                    wire:click="removeQuestion({{ $index }})"
                                                    title="{{ __('general.delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="fw-medium">{{ $question['question'] }}</div>
                                        @if ($question['type'] === 'multiple_choice' && isset($question['options']))
                                            <div class="mt-2">
                                                <small class="text-muted">{{ __('general.correct_answer') }}:
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

            <!-- Save buttons -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-end">
                            <a href="{{ route('quizzes.index') }}" class="btn btn-secondary mr-2">
                                <i class="bi bi-x-circle mr-2"></i>{{ __('general.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary"
                                @if (count($questions) === 0) disabled @endif>
                                <i class="bi bi-check-circle mr-2"></i>{{ __('views.save_quiz') }}
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

        <!-- Question Bank Modal -->
        @if ($showQuestionBank)
            <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-database mr-2"></i>{{ __('views.select_from_question_bank_title') }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white"
                                wire:click="closeQuestionBank"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Select question bank -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('views.select_question_bank_label') }}</label>
                                    <select class="form-control" wire:model.live="selectedQuestionBank">
                                        <option value="">{{ __('views.select_question_bank_placeholder') }}
                                        </option>
                                        @foreach ($questionBanks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}
                                                ({{ $bank->getQuestionCount() }} {{ __('views.questions') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('general.search') }}:</label>
                                    <input type="text" class="form-control" wire:model.live="questionBankFilter"
                                        placeholder="{{ __('views.search_questions_placeholder') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('general.question_type') }}:</label>
                                    <select class="form-control" wire:model.live="questionTypeFilter">
                                        <option value="">{{ __('views.all') }}</option>
                                        <option value="multiple_choice">{{ __('general.multiple_choice') }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Question list -->
                            @if (!empty($questionBankQuestions))
                                <div class="row">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6>{{ __('general.question_list', ['count' => count($filteredQuestions)]) }}
                                            </h6>
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="addSelectedQuestions">
                                                    <i
                                                        class="fas fa-plus mr-1"></i>{{ __('views.add_selected_questions', ['count' => count($selectedQuestions)]) }}
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
                                                        <th>{{ __('general.question_content') }}</th>
                                                        <th width="120">{{ __('general.question_type') }}</th>
                                                        <th width="80">{{ __('general.score') }}</th>
                                                        <th width="100">{{ __('views.difficulty') }}</th>
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
                                                                        {{ __('general.correct_answer') }}:
                                                                        <strong>{{ $question['correct_answer'] ?? '' }}</strong>
                                                                    </small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="badge bg-secondary">
                                                                    {{ __('general.' . ($question['type'] ?? 'multiple_choice')) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-info">{{ $question['score'] ?? 1 }}
                                                                    {{ __('views.points') }}</span>
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
                                                                <br>{{ __('views.no_questions_in_bank') }}
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
                                    <h5>{{ __('views.no_question_banks') }}</h5>
                                    <p>{{ __('views.please_create_question_bank_ai') }}</p>
                                    <a href="{{ route('ai.question-bank-generator') }}" class="btn btn-primary">
                                        <i
                                            class="fas fa-plus mr-1"></i>{{ __('general.create_question_bank_button') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeQuestionBank">
                                <i class="fas fa-times mr-1"></i>{{ __('general.close') }}
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

            // Leave confirm before unload (full reload)
            window.addEventListener('beforeunload', function(e) {
                if (!isDirty) return;
                e.preventDefault();
                e.returnValue = '';
                return '';
            });

            // Block navigating via links when dirty
            document.addEventListener('click', function(e) {
                const anchor = e.target.closest('a[href]');
                if (!anchor) return;
                if (anchor.hasAttribute('data-bypass-leave-confirm')) return; // cho phép bỏ qua
                if (!isDirty) return;
                const proceed = confirm("{{ __('views.leave_confirm') }}");
                if (!proceed) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                }
            }, true);
        })();
    </script>
    =======
    >>>>>>> 282150b466aa7ba9b73489633ade3712c2eaa82a
</x-layouts.dash-admin>
