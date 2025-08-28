<x-layouts.dash-admin active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('quizzes.show', $quiz) }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('views.back_to_quiz_detail') }}
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-graph-up mr-2"></i>{{ __('views.quiz_results_title') }}
            </h4>
            <p class="text-muted mb-0">{{ $quiz->title }}</p>
        </div>

        <!-- Overview statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $totalResults }}</h3>
                        <small>{{ __('views.total_attempts') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $passCount }}</h3>
                        <small>{{ __('views.passed_with_threshold', ['threshold' => 80]) }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $averageScore }}%</h3>
                        <small>{{ __('views.average_score') }}</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h3 class="mb-0">{{ $maxScore }}%</h3>
                        <small>{{ __('views.highest_score') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('views.search_student') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('views.search_student_placeholder') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('views.filter_by_student') }}</label>
                        <select class="form-control" wire:model.live="selectedStudent">
                            <option value="">{{ __('views.all_students') }}</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('views.filter_by_score') }}</label>
                        <select class="form-control" wire:model.live="filterScore">
                            <option value="">{{ __('views.all_scores') }}</option>
                            <option value="excellent">{{ __('views.score_excellent') }}</option>
                            <option value="good">{{ __('views.score_good') }}</option>
                            <option value="average">{{ __('views.score_average') }}</option>
                            <option value="poor">{{ __('views.score_poor') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise mr-2"></i>{{ __('views.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results table -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-table mr-2"></i>{{ __('views.results_list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.student') }}</th>
                                    <th>{{ __('general.score') }}</th>
                                    <th>{{ __('views.duration') }}</th>
                                    <th>{{ __('views.submission_time') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm mr-3">
                                                    <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">
                                                        {{ $result->user ? $result->user->name : __('views.not_available') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="fw-medium {{ $result->score >= 80 ? 'text-success' : ($result->score >= 60 ? 'text-warning' : 'text-danger') }}">
                                                {{ $result->score }}%
                                            </span>
                                        </td>
                                        <td>
                                            @if ($result->duration)
                                                <span class="text-muted">{{ $result->getDurationString() }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->submitted_at)
                                                <div class="fw-medium">{{ $result->submitted_at->format('d/m/Y') }}
                                                </div>
                                                <small
                                                    class="text-muted">{{ $result->submitted_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->isOnTime())
                                                <span class="badge bg-success">{{ __('views.on_time') }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ __('views.late') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                wire:click="selectStudent({{ $result->student_id }})"
                                                title="{{ __('general.view_details') }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $results->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-graph-down fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('views.no_results') }}</h5>
                        <p class="text-muted">{{ __('views.no_results_description') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Selected student result details -->
        @if ($selectedStudent && $results->count() > 0)
            @php
                $selectedResult = $results->firstWhere('student_id', $selectedStudent);
            @endphp
            @if ($selectedResult)
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <i class="bi bi-person-check mr-2"></i>{{ __('views.selected_result_title') }}
                                {{ $selectedResult->student->name }}
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                wire:click="clearStudentFilter">
                                <i class="bi bi-x mr-1"></i>{{ __('general.close') }}
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-primary mb-0">{{ $selectedResult->score }}%</h4>
                                    <small class="text-muted">{{ __('general.score') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-info mb-0">{{ $selectedResult->getDurationString() }}</h4>
                                    <small class="text-muted">{{ __('views.duration') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-success mb-0">{{ $selectedResult->getCorrectAnswersCount() }}</h4>
                                    <small class="text-muted">{{ __('views.answers_label') }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center">
                                    <h4 class="text-warning mb-0">{{ count($quiz->questions) }}</h4>
                                    <small class="text-muted">{{ __('views.total_questions') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Each question details -->
                        <h6 class="mb-3">{{ __('views.each_question_details') }}</h6>
                        @foreach ($quiz->questions as $index => $question)
                            @php
                                $answer = $selectedResult->answers[$index] ?? null;
                                $isCorrect = false;

                                if ($question['type'] === 'multiple_choice') {
                                    $isCorrect = $answer === $question['correct_answer'];
                                } elseif ($question['type'] === 'fill_blank') {
                                    $correctAnswers = is_array($question['correct_answer'])
                                        ? $question['correct_answer']
                                        : [$question['correct_answer']];
                                    $isCorrect = in_array(
                                        strtolower(trim($answer)),
                                        array_map('strtolower', $correctAnswers),
                                    );
                                }
                            @endphp

                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="badge bg-primary mr-2">{{ __('views.question_number', ['number' => $index + 1]) }}</span>
                                        <span class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                        <span class="badge {{ $isCorrect ? 'bg-success' : 'bg-danger' }}">
                                            {{ $isCorrect ? __('views.correct') : __('views.incorrect') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="fw-medium mb-2">{{ $question['question'] }}</div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="alert alert-info mb-0">
                                            <strong>{{ __('views.student_answer_label') }}</strong><br>
                                            {{ $answer ?: __('views.not_answered') }}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="alert alert-success mb-0">
                                            <strong>{{ __('views.correct_answer_label') }}</strong><br>
                                            @if (isset($question['correct_answer']))
                                                {{ $question['correct_answer'] }}
                                            @else
                                                <span class="text-white">{{ __('views.manual_grading_required') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
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
</x-layouts.dash-admin>
