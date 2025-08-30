<div class="p-4">
    @include('components.language')
    <!-- Header -->
    <div class="text-center mb-4">
        <h4 class="text-primary fw-bold">
            <i class="bi bi-star-fill text-warning mr-2"></i>
            {{ __('views.student_pages.evaluation.index.title') }}
        </h4>
        @php
            $student = Auth::user()->student;
            $currentRounds = \App\Models\EvaluationRound::current()->get();
            $currentRound = null;

            // Debug: Log thông tin đợt đánh giá
            \Log::info('Student evaluation view - Current rounds found: ' . $currentRounds->count());
            foreach ($currentRounds as $round) {
                \Log::info(
                    'Round in view: ID=' .
                        $round->id .
                        ', Name=' .
                        $round->name .
                        ', Start=' .
                        $round->start_date .
                        ', End=' .
                        $round->end_date,
                );
            }

            if ($student && $currentRounds->count() > 0) {
                // Tìm đợt đầu tiên mà student chưa đánh giá
                foreach ($currentRounds as $round) {
                    $evaluated = \App\Models\Evaluation::where('student_id', $student->id)
                        ->where('evaluation_round_id', $round->id)
                        ->whereNotNull('submitted_at')
                        ->exists();

                    \Log::info(
                        'Student ' .
                            $student->id .
                            ' evaluated round ' .
                            $round->id .
                            ' in view: ' .
                            ($evaluated ? 'YES' : 'NO'),
                    );

                    if (!$evaluated) {
                        $currentRound = $round;
                        \Log::info('Selected round for student: ID=' . $round->id . ', Name=' . $round->name);
                        break;
                    }
                }
            }
        @endphp

        @if ($currentRound)
            <div class="alert alert-primary">
                <i class="bi bi-calendar-event mr-2"></i>
                <strong>{{ __('views.student_pages.evaluation.index.evaluation_round') }}:</strong> {{ $currentRound->name }}
                @if ($currentRound->description)
                    <br><small class="text-white">{{ $currentRound->description }}</small>
                @endif
            </div>
        @endif

        <div class="alert alert-info">
            <i class="bi bi-info-circle mr-2"></i>
            <strong>{{ __('views.student_pages.evaluation.index.note') }}:</strong> {{ __('views.student_pages.evaluation.index.note_content') }}
        </div>

        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            // Tính xem còn đợt chưa đánh giá không
            $student = Auth::user()->student;
            $currentRounds = \App\Models\EvaluationRound::current()->get();
            $remainingCountView = 0;
            if ($student) {
                foreach ($currentRounds as $r) {
                    $evaluated = \App\Models\Evaluation::where('student_id', $student->id)
                        ->where('evaluation_round_id', $r->id)
                        ->whereNotNull('submitted_at')
                        ->exists();
                    if (!$evaluated) {
                        $remainingCountView++;
                    }
                }
            }
        @endphp

        @if ($isSubmitted && $remainingCountView === 0)
            <div class="alert alert-success">
                <i class="bi bi-check2-circle mr-2"></i>
                <strong>{{ __('views.student_pages.evaluation.index.thank_you') }}</strong> {{ __('views.student_pages.evaluation.index.completed_message') }}
                <div class="mt-3">
                    <button type="button" class="btn btn-success" onclick="location.reload()">
                        <i class="bi bi-arrow-right mr-2"></i>{{ __('views.student_pages.evaluation.index.continue_system') }}
                    </button>
                </div>
            </div>
        @else
            <form wire:submit.prevent="saveEvaluation">
                <!-- Nhóm 1: Đánh giá về giáo viên -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-workspace mr-2"></i>
                            {{ __('views.student_pages.evaluation.index.group1_title') }}
                            <span class="badge bg-light text-primary ml-2">{{ count($teacherQuestions) }} {{ __('views.student_pages.evaluation.index.questions') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($teacherQuestions as $key => $question)
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    {{ $loop->iteration }}. {{ $question }}
                                </label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">{{ __('views.student_pages.evaluation.index.strongly_disagree') }}</span>
                                    <div class="star-rating" data-question="teacher_{{ $key }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="star-input"
                                                wire:model="teacher_ratings.{{ $key }}"
                                                value="{{ $i }}"
                                                id="teacher_{{ $key }}_{{ $i }}">
                                            <label class="star-label"
                                                for="teacher_{{ $key }}_{{ $i }}"
                                                data-rating="{{ $i }}">
                                                <i class="bi bi-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">{{ __('views.student_pages.evaluation.index.strongly_agree') }}</span>
                                </div>
                                @error("teacher_ratings.{$key}")
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nhóm 2: Đánh giá về chất lượng khóa học -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-book mr-2"></i>
                            {{ __('views.student_pages.evaluation.index.group2_title') }}
                            <span class="badge bg-light text-success ml-2">{{ count($courseQuestions) }} {{ __('views.student_pages.evaluation.index.questions') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($courseQuestions as $key => $question)
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    {{ $loop->iteration + count($teacherQuestions) }}. {{ $question }}
                                </label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">{{ __('views.student_pages.evaluation.index.strongly_disagree') }}</span>
                                    <div class="star-rating" data-question="course_{{ $key }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="star-input"
                                                wire:model="course_ratings.{{ $key }}"
                                                value="{{ $i }}"
                                                id="course_{{ $key }}_{{ $i }}">
                                            <label class="star-label"
                                                for="course_{{ $key }}_{{ $i }}"
                                                data-rating="{{ $i }}">
                                                <i class="bi bi-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">{{ __('views.student_pages.evaluation.index.strongly_agree') }}</span>
                                </div>
                                @error("course_ratings.{$key}")
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Nhóm 3: Cảm nhận cá nhân -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="bi bi-emoji-smile mr-2"></i>
                            {{ __('views.student_pages.evaluation.index.group3_title') }}
                            <span class="badge bg-light text-warning ml-2">{{ count($personalQuestions) }} {{ __('views.student_pages.evaluation.index.questions') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($personalQuestions as $key => $question)
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    {{ $loop->iteration + count($teacherQuestions) + count($courseQuestions) }}.
                                    {{ $question }}
                                </label>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">{{ __('views.student_pages.evaluation.index.very_dissatisfied') }}</span>
                                    <div class="star-rating" data-question="personal_{{ $key }}">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" class="star-input" wire:model="personal_satisfaction"
                                                value="{{ $i }}"
                                                id="personal_{{ $key }}_{{ $i }}">
                                            <label class="star-label"
                                                for="personal_{{ $key }}_{{ $i }}"
                                                data-rating="{{ $i }}">
                                                <i class="bi bi-star"></i>
                                            </label>
                                        @endfor
                                    </div>
                                    <span class="text-muted small">{{ __('views.student_pages.evaluation.index.very_satisfied') }}</span>
                                </div>
                                @error('personal_satisfaction')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach

                        <div class="mb-3">
                            <label for="suggestions" class="form-label fw-bold">
                                {{ count($teacherQuestions) + count($courseQuestions) + count($personalQuestions) + 1 }}.
                                {{ __('views.student_pages.evaluation.index.suggestions_label') }}
                            </label>
                            <textarea wire:model="suggestions" class="form-control" id="suggestions" rows="4"
                                placeholder="{{ __('views.student_pages.evaluation.index.suggestions_placeholder') }}"></textarea>
                            @error('suggestions')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save mr-2"></i>{{ __('views.student_pages.evaluation.index.save_evaluation') }}
                    </button>
                    @php
                        $teacherAnswered = count($teacher_ratings) >= count($teacherQuestions);
                        $courseAnswered = count($course_ratings) >= count($courseQuestions);
                        $personalAnswered = !empty($personal_satisfaction);
                        $allAnswered = $teacherAnswered && $courseAnswered && $personalAnswered;
                    @endphp
                    <button type="button" wire:click="submitEvaluation"
                        class="btn btn-success {{ !$allAnswered ? 'disabled' : '' }}"
                        {{ !$allAnswered ? 'disabled' : '' }}>
                        <i class="bi bi-send mr-2"></i>{{ __('views.student_pages.evaluation.index.submit_evaluation') }}
                    </button>
                </div>

                @if (!$allAnswered)
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        <strong>{{ __('views.student_pages.evaluation.index.warning_note') }}:</strong> {{ __('views.student_pages.evaluation.index.warning_content') }}
                        @if (!$teacherAnswered)
                            <br><small class="text-muted">{{ __('views.student_pages.evaluation.index.incomplete_teacher') }}</small>
                        @endif
                        @if (!$courseAnswered)
                            <br><small class="text-muted">{{ __('views.student_pages.evaluation.index.incomplete_course') }}</small>
                        @endif
                        @if (!$personalAnswered)
                            <br><small class="text-muted">{{ __('views.student_pages.evaluation.index.incomplete_personal') }}</small>
                        @endif
                    </div>
                @endif
            </form>
        @endif

        <style>
            .star-rating {
                display: flex;
                align-items: center;
                gap: 2px;
            }

            .star-input {
                display: none;
            }

            .star-label {
                cursor: pointer;
                font-size: 1.5rem;
                color: #dee2e6;
                transition: color 0.2s ease;
                padding: 2px;
            }

            /* Chỉ highlight icon đang hover, không ảnh hưởng các icon bên phải (tránh hiệu ứng ngược) */
            .star-label:hover {
                color: #ffc107;
            }

            /* Bỏ hiệu ứng checked theo sibling ~ để không bị tô ngược; trạng thái chọn do JS + class .selected xử lý */
            /* .star-input:checked ~ .star-label { color: #ffc107; } */

            .star-label i {
                transition: transform 0.1s ease;
            }

            .star-label:hover i {
                transform: scale(1.1);
            }

            /* Màu sắc cho từng nhóm */
            .card-header.bg-primary+.card-body .star-rating .star-label i.bi-star-fill {
                color: #0d6efd;
            }

            .card-header.bg-success+.card-body .star-rating .star-label i.bi-star-fill {
                color: #198754;
            }

            .card-header.bg-warning+.card-body .star-rating .star-label i.bi-star-fill {
                color: #ffc107;
            }

            /* Hiệu ứng hover cho từng nhóm */
            .card-header.bg-primary+.card-body .star-rating .star-label:hover {
                color: #0d6efd;
            }

            .card-header.bg-success+.card-body .star-rating .star-label:hover {
                color: #198754;
            }

            .card-header.bg-warning+.card-body .star-rating .star-label:hover {
                color: #ffc107;
            }

            /* Star fill khi được chọn (do JS thêm class .selected) */
            .star-label.selected i {
                color: #ffc107 !important;
            }

            .star-label.selected i.bi-star {
                display: none;
            }

            .star-label.selected i.bi-star-fill {
                display: inline-block;
            }
        </style>

        <script>
            function applySelectedFromChecked(container) {
                const labels = container.querySelectorAll('.star-label');
                const checked = container.querySelector('input:checked');
                if (!labels.length) return;
                const selectedRating = checked ? parseInt(checked.value) : 0;
                labels.forEach((label, idx) => {
                    if (idx < selectedRating) {
                        label.classList.add('selected');
                        label.querySelector('i').className = 'bi bi-star-fill';
                    } else {
                        label.classList.remove('selected');
                        label.querySelector('i').className = 'bi bi-star';
                    }
                });
            }

            function initStarRatings() {
                document.querySelectorAll('.star-rating').forEach(function(container) {
                    if (container.dataset.initialized === '1') return;
                    container.dataset.initialized = '1';

                    const labels = container.querySelectorAll('.star-label');
                    const inputs = container.querySelectorAll('.star-input');

                    // Lắng nghe thay đổi trực tiếp trên radio (hỗ trợ Livewire re-render)
                    inputs.forEach(function(input) {
                        input.addEventListener('change', function() {
                            applySelectedFromChecked(container);
                        });
                    });

                    labels.forEach(function(label) {
                        const rating = parseInt(label.getAttribute('data-rating'));

                        // Click: chọn sao và cập nhật Livewire (wire:model)
                        label.addEventListener('click', function(e) {
                            e.preventDefault();
                            if (inputs[rating - 1]) {
                                inputs[rating - 1].checked = true;
                                inputs[rating - 1].dispatchEvent(new Event('change', {
                                    bubbles: true
                                }));
                                inputs[rating - 1].dispatchEvent(new Event('input', {
                                    bubbles: true
                                }));
                            }
                            // Hiển thị fill sao
                            applySelectedFromChecked(container);
                        });

                        // Hover preview
                        label.addEventListener('mouseenter', function() {
                            labels.forEach(l => {
                                l.querySelector('i').className = 'bi bi-star';
                            });
                            for (let i = 0; i < rating; i++) {
                                labels[i].querySelector('i').className = 'bi bi-star-fill';
                            }
                        });
                    });

                    // Mouse leave: giữ trạng thái đã chọn
                    container.addEventListener('mouseleave', function() {
                        applySelectedFromChecked(container);
                    });

                    // Áp dụng ban đầu theo radio đang checked
                    applySelectedFromChecked(container);
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                initStarRatings();
            });

            document.addEventListener('livewire:load', function() {
                Livewire.hook('message.processed', () => {
                    initStarRatings();
                    document.querySelectorAll('.star-rating').forEach(function(container) {
                        applySelectedFromChecked(container);
                    });
                });
            });
        </script>
    </div>
</div>
