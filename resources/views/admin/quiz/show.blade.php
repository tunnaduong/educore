<x-layouts.dash-admin active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('quizzes.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('views.back_to_quiz_list') }}
            </a>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-text mr-2"></i>{{ __('views.quiz_detail_title') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $quiz->title }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('quizzes.edit', $quiz) }}" class="btn btn-warning">
                        <i class="bi bi-pencil mr-2"></i>{{ __('general.edit') }}
                    </a>
                    <a href="{{ route('quizzes.results', $quiz) }}" class="btn btn-info">
                        <i class="bi bi-graph-up mr-2"></i>{{ __('views.view_results') }}
                    </a>
                    <button type="button" class="btn btn-danger"
                        wire:confirm="{{ __('views.confirm_delete_quiz_simple') }}" wire:click="deleteQuiz">
                        <i class="bi bi-trash mr-2"></i>{{ __('general.delete') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Quiz information -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-info-circle mr-2"></i>{{ __('views.quiz_information') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.title') }}</label>
                            <div class="fw-medium">{{ $quiz->title }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.classroom') }}</label>
                            <div class="fw-medium">{{ $classroom->name ?? __('views.not_available') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.time_limit') }}</label>
                            <div class="fw-medium">
                                @if ($quiz->time_limit)
                                    <span class="badge bg-warning text-dark">{{ $quiz->time_limit }} {{ __('views.minutes') }}</span>
                                @else
                                    <span class="text-muted">{{ __('views.unlimited') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.deadline') }}</label>
                            <div class="fw-medium">
                                @if ($quiz->deadline)
                                    {{ $quiz->deadline->format('d/m/Y H:i') }}
                                    @if ($quiz->isExpired())
                                        <span class="badge bg-danger ml-2">{{ __('views.overdue_status') }}</span>
                                    @else
                                        <span class="badge bg-success ml-2">{{ __('views.active_status') }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">{{ __('views.no_deadline') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.created_at') }}</label>
                            <div class="fw-medium">{{ $quiz->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('general.description') }}</label>
                            <div class="fw-medium">{!! nl2br(e($quiz->description)) !!}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.question_count') }}</label>
                            <div class="fw-medium">{{ $quiz->getQuestionCount() }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">{{ __('views.max_score') }}</label>
                            <div class="fw-medium">{{ $quiz->getMaxScore() }}</div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-graph-up mr-2"></i>{{ __('views.statistics') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-0">{{ $results->count() }}</h4>
                                    <small class="text-muted">{{ __('views.attempted') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-0">
                                    {{ $results->where('score', '>=', 80)->count() }}
                                </h4>
                                <small class="text-muted">{{ __('views.passed_with_threshold', ['threshold' => 80]) }}</small>
                            </div>
                        </div>
                        @if ($results->count() > 0)
                            <hr>
                            <div class="row text-center">
                                <div class="col-6">
                                    <h5 class="text-info mb-0">
                                        {{ round($results->avg('score'), 1) }}%
                                    </h5>
                                    <small class="text-muted">{{ __('views.average_score') }}</small>
                                </div>
                                <div class="col-6">
                                    <h5 class="text-warning mb-0">
                                        {{ $results->max('score') }}%
                                    </h5>
                                    <small class="text-muted">{{ __('views.highest_score') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Question list -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-list-ul mr-2"></i>{{ __('views.question_list_title') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        @if (count($quiz->questions) > 0)
                            @foreach ($quiz->questions as $index => $question)
                                <div class="border rounded p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <span class="badge bg-primary mr-2">{{ __('views.question_number', ['number' => $index + 1]) }}</span>
                                            <span class="badge bg-secondary">{{ ucfirst($question['type']) }}</span>
                                            <span class="badge bg-info">{{ $question['score'] ?? $question['points'] ?? 1 }} {{ __('views.points') }}</span>
                                        </div>
                                    </div>
                                    <div class="fw-medium mb-2">{{ $question['question'] }}</div>

                                    @if (isset($question['options']))
                                        <div class="ml-3">
                                            @foreach ($question['options'] as $optionIndex => $option)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" disabled
                                                        {{ $option === $question['correct_answer'] ? 'checked' : '' }}>
                                                    <label
                                                        class="form-check-label {{ $option === $question['correct_answer'] ? 'fw-bold text-success' : '' }}">
                                                        {{ $option }}
                                                        @if ($option === $question['correct_answer'])
                                                            <i class="bi bi-check-circle-fill text-success ml-1"></i>
                                                        @endif
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-exclamation-triangle fs-1 text-warning mb-3"></i>
                                <h5 class="text-muted">{{ __('views.no_questions') }}</h5>
                                <p class="text-muted">{{ __('views.quiz_has_no_questions') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Students and results -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">
                            <i class="bi bi-people mr-2"></i>{{ __('views.student_list_and_results') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        @if ($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('general.student') }}</th>
                                            <th>{{ __('general.email') }}</th>
                                            <th>{{ __('general.status') }}</th>
                                            <th>{{ __('general.score') }}</th>
                                            <th>{{ __('views.duration') }}</th>
                                            <th>{{ __('views.submission_time') }}</th>
                                            <th>{{ __('general.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            @php
                                                $result = $student->student
                                                    ? $results->firstWhere('student_id', $student->student->id)
                                                    : null;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $student->email ?? __('views.not_available') }}</td>
                                                <td>
                                                    @if ($result)
                                                        @if ($result->isOnTime())
                                                            <span class="badge bg-success">{{ __('views.on_time') }}</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ __('views.late') }}</span>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('general.not_started') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result)
                                                        <span
                                                            class="fw-medium {{ $result->score >= 80 ? 'text-success' : ($result->score >= 60 ? 'text-warning' : 'text-danger') }}">
                                                            {{ $result->score }}%
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result && $result->duration)
                                                        <span
                                                            class="text-muted">{{ $result->getDurationString() }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result && $result->submitted_at)
                                                        <div class="fw-medium">
                                                            {{ $result->submitted_at->format('d/m/Y') }}</div>
                                                        <small
                                                            class="text-muted">{{ $result->submitted_at->format('H:i') }}</small>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($result)
                                                        <a href="{{ route('quizzes.results', $quiz) }}?student={{ $student->student->id }}"
                                                            class="btn btn-sm btn-outline-primary"
                                                            title="{{ __('general.view_details') }}">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-people fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('general.no_students_yet') }}</h5>
                                <p class="text-muted">{{ __('views.please_assign_students_to_class') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

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
