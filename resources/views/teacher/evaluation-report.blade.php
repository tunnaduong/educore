<x-layouts.dash-teacher active="evaluations-report">
    @include('components.language')
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bar-chart-line mr-2"></i>{{ __('general.student_quality_evaluation_report') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.evaluation_summary') }}</p>
                </div>
            </div>
        </div>
        <div class="alert alert-info mb-4">
            <strong>{{ __('general.scoring_guide') }}</strong><br>
            <ul class="mb-1">
                <li><b>{{ __('general.teacher_rating_avg') }}</b></li>
                <li><b>{{ __('general.course_rating_avg') }}</b></li>
                <li><b>{{ __('general.personal_satisfaction_score') }}</b></li>
            </ul>
            <span class="text-muted">{{ __('general.higher_score_higher_satisfaction') }}</span>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form wire:submit.prevent="loadEvaluations" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="classroomId" class="form-label">{{ __('general.filter_by_class') }}</label>
                        <select wire:model="classroomId" id="classroomId" class="form-control">
                            <option value="">{{ __('general.all_classes') }}</option>
                            @foreach ($classrooms as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="roundId" class="form-label">{{ __('general.filter_by_evaluation_round') }}</label>
                        <select wire:model="roundId" id="roundId" class="form-control">
                            <option value="">{{ __('general.all_rounds') }}</option>
                            @foreach ($rounds as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}
                                    ({{ $r->start_date->format('d/m') }} - {{ $r->end_date->format('d/m') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i>
                            {{ __('general.filter') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Thống kê mức độ hài lòng -->
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-emoji-smile"></i> {{ __('general.student_satisfaction_statistics') }}
            </div>
            <div class="card-body">
                @php
                    $satisfactionStats = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                    foreach ($evaluations as $eva) {
                        if ($eva->personal_satisfaction) {
                            $satisfactionStats[$eva->personal_satisfaction]++;
                        }
                    }
                    $totalEva = $total > 0 ? $total : 1;
                @endphp
                <div class="row text-center">
                    @foreach ($satisfactionStats as $level => $count)
                        <div class="col">
                            <div class="fw-bold">{{ $level }}</div>
                            <div class="progress mb-1" style="height: 18px;">
                                <div class="progress-bar bg-{{ $level == 5 ? 'success' : ($level == 1 ? 'danger' : ($level >= 4 ? 'info' : 'warning')) }}"
                                    role="progressbar" style="width: {{ round(($count / $totalEva) * 100) }}%">
                                    {{ round(($count / $totalEva) * 100) }}%
                                </div>
                            </div>
                            <small class="text-muted">{{ $count }} {{ __('general.times') }}</small>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-2 text-muted small">1: {{ __('general.very_dissatisfied') }} &nbsp; 5:
                    {{ __('general.very_satisfied') }}</div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold text-success fs-3">{{ number_format($avgTeacher, 1) }}</div>
                        <div class="text-muted">{{ __('general.teacher_rating_average') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold text-info fs-3">{{ number_format($avgCourse, 1) }}</div>
                        <div class="text-muted">{{ __('general.course_rating_average') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold text-warning fs-3">{{ number_format($avgPersonal, 1) }}</div>
                        <div class="text-muted">{{ __('general.personal_satisfaction_average') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-list-check"></i> {{ __('general.evaluation_list') }} ({{ $total }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>{{ __('general.student') }}</th>
                                <th>{{ __('general.teacher_score') }}</th>
                                <th>{{ __('general.course_score') }}</th>
                                <th>{{ __('general.satisfaction') }}</th>
                                <th>{{ __('general.suggestions') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($evaluations as $eva)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $eva->student->user->name ?? 'N/A' }}</span><br>
                                        <small class="text-muted">ID: {{ $eva->student_id }}</small>
                                    </td>
                                    <td>{{ number_format($eva->getTeacherAverageRating(), 1) }}</td>
                                    <td>{{ number_format($eva->getCourseAverageRating(), 1) }}</td>
                                    <td>{{ $eva->personal_satisfaction }}</td>
                                    <td>
                                        @if ($eva->suggestions)
                                            <span class="text-dark">{{ $eva->suggestions }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            wire:click="showEvaluationDetail({{ $eva->id }})">
                                            <i class="bi bi-eye"></i> {{ __('general.view_details') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        {{ __('general.no_evaluations_yet') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đánh giá -->
        @if ($selectedEvaluation)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); z-index: 1050;"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold text-primary fs-4">{{ __('general.evaluation_details') }}
                                của
                                {{ $selectedEvaluation->student->user->name ?? __('general.student') }}</h5>
                            <button type="button" class="btn-close" wire:click="closeEvaluationDetail">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>{{ __('general.group_1_teacher_evaluation') }}</strong>
                                <ul class="mb-2">
                                    @foreach ($selectedEvaluation->teacher_ratings ?? [] as $k => $v)
                                        @php
                                            $question = $questions
                                                ->where('category', 'teacher')
                                                ->where('order', $k)
                                                ->first();
                                        @endphp
                                        <li>
                                            {{ $question ? $question->question : 'Câu hỏi ' . $k }}:
                                            <b>{{ $v }}/5</b>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('general.group_2_course_evaluation') }}</strong>
                                <ul class="mb-2">
                                    @foreach ($selectedEvaluation->course_ratings ?? [] as $k => $v)
                                        @php
                                            $question = $questions
                                                ->where('category', 'course')
                                                ->where('order', $k)
                                                ->first();
                                        @endphp
                                        <li>
                                            {{ $question ? $question->question : 'Câu hỏi ' . $k }}:
                                            <b>{{ $v }}/5</b>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('general.group_3_personal_satisfaction') }}</strong>
                                @php
                                    $personalQuestion = $questions->where('category', 'personal')->first();
                                @endphp
                                <div>
                                    {{ $personalQuestion ? $personalQuestion->question : __('general.personal_satisfaction_level') }}:
                                    <b>{{ $selectedEvaluation->personal_satisfaction }}/5</b>
                                </div>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('general.suggestions_comments') }}</strong>
                                <div>{{ $selectedEvaluation->suggestions ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeEvaluationDetail">
                                {{ __('general.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
</x-layouts.dash-teacher>
