<x-layouts.dash-student active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('student.quizzes.index') }}"
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back_to_quiz_list') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-clipboard-check mr-2"></i>{{ __('general.review_quiz') }}
            </h4>
            <p class="text-muted mb-0">{{ $quiz->title }}</p>
        </div>

        @if (!$result)
            <div class="alert alert-warning mt-4">
                <h5 class="alert-heading"><i class="bi bi-exclamation-triangle mr-2"></i>{{ __('general.quiz_result_not_found') }}</h5>
                <p>{{ __('general.quiz_result_not_found_description') }}</p>
                <a href="{{ route('student.quizzes.index') }}" class="btn btn-primary mt-2"><i
                        class="bi bi-arrow-left mr-1"></i>{{ __('general.back_to_quiz_list') }}</a>
            </div>
        @else
            <!-- Thống kê tổng quan -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $result->score }}%</h3>
                            <small>{{ __('general.score') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $result->getDurationString() }}</h3>
                            <small>{{ __('general.time_taken') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ $result->getCorrectAnswersCount() }}</h3>
                            <small>{{ __('general.correct_answers') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3 class="mb-0">{{ count($quiz->questions) }}</h3>
                            <small>{{ __('general.total_questions') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin chi tiết -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-info-circle mr-2"></i>{{ __('general.quiz_information') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">{{ __('general.classroom') }}:</small>
                                    <div class="fw-medium">{{ $quiz->classroom ? $quiz->classroom->name : __('general.not_available') }}</div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">{{ __('general.deadline') }}:</small>
                                    <div class="fw-medium">
                                        @if ($quiz->deadline)
                                            {{ $quiz->deadline->format('d/m/Y H:i') }}
                                        @else
                                            {{ __('general.no_deadline') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <small class="text-muted">{{ __('general.started_at') }}:</small>
                                    <div class="fw-medium">
                                        @if ($result->started_at)
                                            {{ $result->started_at->format('d/m/Y H:i') }}
                                        @else
                                            {{ __('general.not_available') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">{{ __('general.submitted_at') }}:</small>
                                    <div class="fw-medium">
                                        @if ($result->submitted_at)
                                            {{ $result->submitted_at->format('d/m/Y H:i') }}
                                        @else
                                            {{ __('general.not_submitted') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="bi bi-graph-up mr-2"></i>{{ __('general.answer_statistics') }}
                            </h6>
                        </div>
                        <div class="card-body">
                            @php
                                $correctCount = 0;
                                $incorrectCount = 0;
                                $unansweredCount = 0;
                                foreach ($quiz->questions as $index => $question) {
                                    $status = $this->getQuestionStatus($index);
                                    if ($status === 'correct') {
                                        $correctCount++;
                                    } elseif ($status === 'incorrect') {
                                        $incorrectCount++;
                                    } else {
                                        $unansweredCount++;
                                    }
                                }
                            @endphp
                            <div class="row text-center">
                                <div class="col-4">
                                    <div class="text-success">
                                        <h4 class="mb-0">{{ $correctCount }}</h4>
                                        <small>{{ __('general.correct') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-danger">
                                        <h4 class="mb-0">{{ $incorrectCount }}</h4>
                                        <small>{{ __('general.incorrect') }}</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="text-secondary">
                                        <h4 class="mb-0">{{ $unansweredCount }}</h4>
                                        <small>{{ __('general.unanswered') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách câu hỏi -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="bi bi-list-ul mr-2"></i>{{ __('general.question_details') }}
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Navigation câu hỏi -->
                    <div class="mb-4">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($quiz->questions as $index => $question)
                                <button type="button"
                                    class="btn btn-sm {{ $selectedQuestion == $index ? 'btn-primary' : 'btn-outline-' . $this->getQuestionStatusClass($index) }}"
                                    wire:click="selectQuestion({{ $index }})">
                                    {{ __('general.question') }} {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Chi tiết câu hỏi được chọn -->
                    @if (isset($quiz->questions[$selectedQuestion]))
                        @php
                            $question = $quiz->questions[$selectedQuestion];

                            $answers = $result->getAnswersArray();
                            $answer = $answers[$selectedQuestion] ?? null;
                            $status = $this->getQuestionStatus($selectedQuestion);
                        @endphp
                        <div class="border rounded p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge bg-primary mr-2">{{ __('general.question') }} {{ $selectedQuestion + 1 }}</span>
                                    <span class="badge bg-secondary mr-2">{{ ucfirst($question['type']) }}</span>
                                    <span class="badge bg-{{ $this->getQuestionStatusClass($selectedQuestion) }}">
                                        {{ $this->getQuestionStatusText($selectedQuestion) }}
                                    </span>
                                </div>
                            </div>
                            <div class="fw-medium mb-3 fs-5">{{ $question['question'] }}</div>
                            @if ($question['type'] === 'multiple_choice')
                                <div class="mb-3">
                                    <h6>{{ __('general.options') }}:</h6>
                                    @foreach ($question['options'] as $optionIndex => $option)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" disabled
                                                {{ $answer == $option ? 'checked' : '' }}
                                                id="option_{{ $selectedQuestion }}_{{ $optionIndex }}">
                                            <label
                                                class="form-check-label {{ isset($question['correct_answer']) && $option === $question['correct_answer'] ? 'text-success fw-bold' : (isset($question['correct_answer']) && $answer == $option && $answer !== $question['correct_answer'] ? 'text-danger' : '') }}"
                                                for="option_{{ $selectedQuestion }}_{{ $optionIndex }}">
                                                {{ $option }}
                                                @if (isset($question['correct_answer']) && $option === $question['correct_answer'])
                                                    <i class="bi bi-check-circle-fill text-success ml-2"></i>
                                                @elseif(isset($question['correct_answer']) && $answer == $option && $answer !== $question['correct_answer'])
                                                    <i class="bi bi-x-circle-fill text-danger ml-2"></i>
                                                @endif
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info">
                                            <strong>{{ __('general.your_answer') }}:</strong><br>
                                            {{ $answer ?: __('general.not_answered') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success">
                                            <strong>{{ __('general.correct_answer') }}:</strong><br>
                                            @if (isset($question['correct_answer']))
                                                @if (is_array($question['correct_answer']))
                                                    {{ implode(', ', $question['correct_answer']) }}
                                                @else
                                                    {{ $question['correct_answer'] }}
                                                @endif
                                            @else
                                                <span class="text-muted">{{ __('general.needs_manual_grading') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if (isset($question['explanation']) && $question['explanation'])
                                    <div class="alert alert-warning">
                                        <strong>{{ __('general.explanation') }}:</strong><br>
                                        {{ $question['explanation'] }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-layouts.dash-student>
