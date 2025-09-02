<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.quizzes.show', $quiz) }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-pencil mr-2"></i>{{ __('general.edit_quiz') }}
            </h4>
            <p class="text-muted mb-0">{{ $quiz->title }}</p>
            
            <!-- Hiển thị trạng thái khóa -->
            @if(isset($lockStatus))
                <div class="mt-3">
                    @if($lockStatus['status'] === 'locked')
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-lock-fill mr-2"></i>
                            <strong>{{ $lockStatus['message'] }}</strong>
                            <br>
                            <small class="text-muted">Quiz đã bị khóa chỉnh sửa để đảm bảo tính công bằng cho học viên đang làm bài.</small>
                        </div>
                    @elseif($lockStatus['status'] === 'completed')
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-check-circle-fill mr-2"></i>
                            <strong>{{ $lockStatus['message'] }}</strong>
                            <br>
                            <small class="text-muted">Quiz đã hoàn thành và không thể chỉnh sửa.</small>
                        </div>
                    @else
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-unlock-fill mr-2"></i>
                            <strong>{{ $lockStatus['message'] }}</strong>
                        </div>
                    @endif
                </div>
            @endif
        </div>

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
                                    wire:model="title" placeholder="{{ __('general.enter_quiz_title') }}"
                                    @if(!$lockStatus['can_edit']) disabled @endif>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description" rows="3"
                                    placeholder="{{ __('general.describe_quiz_placeholder') }}"
                                    @if(!$lockStatus['can_edit']) disabled @endif></textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.classroom') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('class_id') is-invalid @enderror"
                                    wire:model="class_id"
                                    @if(!$lockStatus['can_edit']) disabled @endif>
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
                                    class="form-control @error('deadline') is-invalid @enderror" wire:model="deadline"
                                    @if(!$lockStatus['can_edit']) disabled @endif>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('general.time_limit_minutes') }}</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror"
                                    wire:model="time_limit" min="1" max="480"
                                    placeholder="{{ __('general.enter_time_limit_example') }}"
                                    @if(!$lockStatus['can_edit']) disabled @endif>
                                <small class="form-text text-muted">{{ __('general.no_time_limit_hint') }}</small>
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- {{ __('general.add_question') }} -->
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                @if ($editingIndex !== null)
                                    <i
                                        class="bi bi-pencil mr-2"></i>{{ __('general.edit_question', ['number' => $editingIndex + 1]) }}
                                @else
                                    <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_new_question') }}
                                @endif
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('general.question_content') }} <span
                                                class="text-danger">*</span></label>
                                        <textarea class="form-control @error('currentQuestion.question') is-invalid @enderror"
                                            wire:model="currentQuestion.question" rows="3" placeholder="{{ __('general.question_content') }}..."
                                            @if(!$lockStatus['can_edit']) disabled @endif></textarea>
                                        @error('currentQuestion.question')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('general.question_type') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control @error('currentQuestion.type') is-invalid @enderror"
                                            wire:model="currentQuestion.type"
                                            @if(!$lockStatus['can_edit']) disabled @endif>
                                            <option value="multiple_choice">{{ __('general.multiple_choice') }}
                                            </option>
                                        </select>
                                        @error('currentQuestion.type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">{{ __('general.score') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('currentQuestion.score') is-invalid @enderror"
                                            wire:model="currentQuestion.score" min="1" max="10"
                                            @if(!$lockStatus['can_edit']) disabled @endif>
                                        @error('currentQuestion.score')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Tùy chọn cho câu hỏi trắc nghiệm -->
                            @if ($currentQuestion['type'] === 'multiple_choice')
                                <div class="mb-3">
                                    <label class="form-label">{{ __('general.answer_options') }} <span
                                            class="text-danger">*</span></label>
                                    @foreach ($currentQuestion['options'] as $index => $option)
                                        <div class="input-group mb-2">
                                            <input type="text"
                                                class="form-control @error('currentQuestion.options.' . $index) is-invalid @enderror"
                                                wire:model.live="currentQuestion.options.{{ $index }}"
                                                placeholder="{{ __('general.answer_number', ['number' => $index + 1]) }}"
                                                @if(!$lockStatus['can_edit']) disabled @endif>
                                            @if (count($currentQuestion['options']) > 2)
                                                                                            <button type="button" class="btn btn-outline-danger"
                                                wire:click="removeOption({{ $index }})"
                                                @if(!$lockStatus['can_edit']) disabled @endif>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    @endforeach
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        wire:click="addOption"
                                        @if(!$lockStatus['can_edit']) disabled @endif>
                                        <i class="bi bi-plus mr-1"></i>{{ __('general.add_answer') }}
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">{{ __('general.correct_answer') }} <span
                                            class="text-danger">*</span></label>
                                    <select
                                        class="form-control @error('currentQuestion.correct_answer') is-invalid @enderror"
                                        wire:model.live="currentQuestion.correct_answer"
                                        @if(!$lockStatus['can_edit']) disabled @endif>
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



                            <div class="text-end">
                                @if ($editingIndex !== null)
                                    <button type="button" class="btn btn-warning mr-2"
                                        wire:click="resetCurrentQuestion">
                                        <i class="bi bi-x-circle mr-2"></i>{{ __('general.cancel_edit') }}
                                    </button>
                                @endif
                                <button type="button" class="btn btn-primary" wire:click="addQuestion" 
                                    @if(!$lockStatus['can_edit']) disabled @endif>
                                    @if ($editingIndex !== null)
                                        <i class="bi bi-check-circle mr-2"></i>{{ __('general.update_question') }}
                                    @else
                                        <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_question_btn') }}
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Danh sách câu hỏi đã thêm -->
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
                                                <span class="badge bg-primary mr-2">{{ __('general.question') }} {{ $index + 1 }}</span>
                                                <span
                                                    class="badge bg-secondary">{{ __('general.' . $question['type']) }}</span>
                                                <span class="badge bg-info">{{ $question['score'] ?? $question['points'] ?? 1 }} {{ __('general.pts') }}</span>
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-warning"
                                                    wire:click="editQuestion({{ $index }})"
                                                    title="{{ __('general.edit') }}"
                                                    @if(!$lockStatus['can_edit']) disabled @endif>
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                @if ($index > 0)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionUp({{ $index }})"
                                                        @if(!$lockStatus['can_edit']) disabled @endif>
                                                        <i class="bi bi-arrow-up"></i>
                                                    </button>
                                                @endif
                                                @if ($index < count($questions) - 1)
                                                    <button type="button" class="btn btn-outline-secondary"
                                                        wire:click="moveQuestionDown({{ $index }})"
                                                        @if(!$lockStatus['can_edit']) disabled @endif>
                                                        <i class="bi bi-arrow-down"></i>
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-outline-danger"
                                                    wire:click="removeQuestion({{ $index }})" title="{{ __('general.delete') }}"
                                                    @if(!$lockStatus['can_edit']) disabled @endif>
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

            <!-- Nút lưu -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body text-end">
                            <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-secondary mr-2">
                                <i class="bi bi-x-circle mr-2"></i>{{ __('general.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary"
                                @if (count($questions) === 0 || !$lockStatus['can_edit']) disabled @endif>
                                <i class="bi bi-check-circle mr-2"></i>{{ __('general.update') }}
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
</x-layouts.dash-teacher>
