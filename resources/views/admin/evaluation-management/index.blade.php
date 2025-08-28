<x-layouts.dash-admin active="evaluation-management">
    @include('components.language')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bar-chart-line mr-2"></i>{{ __('views.evaluation_management') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('views.view_evaluations_and_manage_questions') }}</p>
                </div>
            </div>
        </div>

        <!-- Alerts on top -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tabs -->
        <ul class="nav nav-tabs mb-4" id="evaluationTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'evaluations' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'evaluations')" type="button" role="tab"
                    style="cursor: pointer;">
                    <i class="bi bi-list-check mr-2"></i> {{ __('views.evaluation_list') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'questions' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'questions')" type="button" role="tab" style="cursor: pointer;">
                    <i class="bi bi-question-circle mr-2"></i> {{ __('views.question_management') }}
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $activeTab === 'rounds' ? 'active' : '' }}"
                    wire:click="$set('activeTab', 'rounds')" type="button" role="tab" style="cursor: pointer;">
                    <i class="bi bi-calendar-event mr-2"></i> {{ __('views.round_management') }}
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="evaluationTabsContent">
            <!-- Tab 1: Danh sách đánh giá -->
            <div class="{{ $activeTab === 'evaluations' ? 'd-block' : 'd-none' }}" id="evaluations" role="tabpanel">
                <!-- Hướng dẫn tính điểm -->
                <div class="alert mb-4" style="background-color: #17a2b8; color: white; border: none;">
                    <h6 class="alert-heading fw-bold" style="color: white;">
                        <i class="bi bi-info-circle mr-2"></i>{{ __('views.scoring_guide') }}
                    </h6>
                    <div style="color: white;">
                        <div class="mb-2">
                            <strong style="color: white;">{{ __('views.teacher_rating_avg') }}</strong> {{ __('views.teacher_rating_desc') }}
                        </div>
                        <div class="mb-2">
                            <strong style="color: white;">{{ __('views.course_rating_avg') }}</strong> {{ __('views.course_rating_desc') }}
                        </div>
                        <div class="mb-2">
                            <strong style="color: white;">{{ __('views.personal_satisfaction') }}</strong> {{ __('views.personal_satisfaction_desc') }}
                        </div>
                        <div style="color: #f0f0f0; font-size: 0.9em;">
                            {{ __('views.higher_score_better') }}
                        </div>
                    </div>
                </div>

                <!-- Bộ lọc -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="classroomFilter" class="form-label">{{ __('views.filter_by_class') }}</label>
                                <div class="d-flex gap-2">
                                    <select wire:model.live="classroomId" class="form-control" id="classroomFilter">
                                        <option value="">{{ __('views.all_classes') }}</option>
                                        @foreach ($classrooms as $classroom)
                                            <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                        @endforeach
                                    </select>
                                    @if ($classroomId)
                                        <button class="btn btn-outline-secondary btn-sm" wire:click="resetFilter"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('views.clear_filter') }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="roundFilter" class="form-label">{{ __('views.filter_by_round') }}</label>
                                <div class="d-flex gap-2">
                                    <select wire:model.live="roundId" class="form-control" id="roundFilter">
                                        <option value="">{{ __('views.all_rounds') }}</option>
                                        @foreach ($evaluationRounds as $round)
                                            <option value="{{ $round->id }}">{{ $round->name }}
                                                ({{ $round->start_date->format('d/m') }} -
                                                {{ $round->end_date->format('d/m') }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($roundId)
                                        <button class="btn btn-outline-secondary btn-sm" wire:click="resetFilter"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('views.clear_filter') }}">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thống kê điểm trung bình -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">{{ __('views.teacher_avg_score') }}</h6>
                                        <h3 class="mb-0">{{ number_format($avgTeacher, 1) }}/5.0</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-person-check fs-1"></i>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar"
                                        style="width: {{ ($avgTeacher / 5) * 100 }}%; background-color: #000000; opacity: 0.3;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">{{ __('views.course_avg_score') }}</h6>
                                        <h3 class="mb-0">{{ number_format($avgCourse, 1) }}/5.0</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-book-check fs-1"></i>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar"
                                        style="width: {{ ($avgCourse / 5) * 100 }}%; background-color: #000000; opacity: 0.3;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">{{ __('views.personal_avg_score') }}</h6>
                                        <h3 class="mb-0">{{ number_format($avgPersonal, 1) }}/5.0</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-heart-check fs-1"></i>
                                    </div>
                                </div>
                                <div class="progress mt-2" style="height: 6px;">
                                    <div class="progress-bar"
                                        style="width: {{ ($avgPersonal / 5) * 100 }}%; background-color: #000000; opacity: 0.3;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Danh sách đánh giá -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('views.evaluation_list_title') }} ({{ $total }} {{ __('views.evaluation_list') }})</h6>
                    </div>
                    <div class="card-body">
                        @if ($evaluations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('views.student') }}</th>
                                            <th>{{ __('views.class') }}</th>
                                            <th>{{ __('views.round') }}</th>
                                            <th>{{ __('views.avg_score') }}</th>
                                            <th>{{ __('views.status') }}</th>
                                            <th>{{ __('views.submission_date') }}</th>
                                            <th>{{ __('views.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evaluations as $evaluation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-2"
                                                            style="width: 40px; height: 40px;">
                                                            <span
                                                                class="text-white fw-bold">{{ substr($evaluation->student->user->name ?? 'N/A', 0, 1) }}</span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">
                                                                {{ $evaluation->student->user->name ?? 'N/A' }}</div>
                                                            <small
                                                                class="text-muted">{{ $evaluation->student->user->email ?? 'N/A' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($evaluation->student->user->enrolledClassrooms->count() > 0)
                                                        @foreach ($evaluation->student->user->enrolledClassrooms as $classroom)
                                                            <span
                                                                class="badge bg-info mr-1">{{ $classroom->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">{{ __('views.not_assigned') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-secondary">{{ $evaluation->evaluationRound->name ?? 'N/A' }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <span
                                                            class="fw-bold mr-2">{{ number_format($evaluation->getOverallRating(), 1) }}/5.0</span>
                                                        @php
                                                            $rating = $evaluation->getOverallRating();
                                                            $color =
                                                                $rating >= 4
                                                                    ? 'success'
                                                                    : ($rating >= 3
                                                                        ? 'warning'
                                                                        : 'danger');
                                                        @endphp
                                                        <span class="badge bg-{{ $color }}">
                                                            @if ($rating >= 4)
                                                                {{ __('views.satisfied') }}
                                                            @elseif($rating >= 3)
                                                                {{ __('views.normal') }}
                                                            @else
                                                                {{ __('views.not_satisfied') }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($evaluation->submitted_at)
                                                        <span class="badge bg-success">{{ __('views.submitted') }}</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ __('views.not_submitted') }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $evaluation->submitted_at ? $evaluation->submitted_at->format('d/m/Y H:i') : 'N/A' }}
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary"
                                                            wire:click="showEvaluationDetail({{ $evaluation->id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('views.view_details') }}">
                                                            <i class="bi bi-eye"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger"
                                                            wire:click="deleteEvaluation({{ $evaluation->id }})"
                                                            wire:confirm="{{ __('views.confirm_delete_evaluation') }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('views.delete_evaluation') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $evaluations->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('views.no_evaluations') }}</h5>
                                <p class="text-muted">{{ __('views.no_evaluations_desc') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab 2: Quản lý câu hỏi -->
            <div class="{{ $activeTab === 'questions' ? 'd-block' : 'd-none' }}" id="questions" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('views.question_management_title') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" wire:click="showAddQuestionModal"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('views.add_new_question') }}">
                                <i class="bi bi-plus-circle mr-2"></i>{{ __('views.add_question') }}
                            </button>
                        </div>
                        @if ($questions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('views.order') }}</th>
                                            <th>{{ __('views.category') }}</th>
                                            <th>{{ __('views.question') }}</th>
                                            <th>{{ __('views.status') }}</th>
                                            <th>{{ __('views.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($questions as $question)
                                            <tr>
                                                <td>
                                                    @if ($question->is_active && isset($displayOrderMap[$question->id]))
                                                        {{ $displayOrderMap[$question->id] }}
                                                    @else
                                                        —
                                                    @endif
                                                </td>
                                                <td>
                                                    @switch($question->category)
                                                        @case('teacher')
                                                            <span class="badge bg-primary">{{ __('views.teacher_category') }}</span>
                                                        @break

                                                        @case('course')
                                                            <span class="badge bg-success">{{ __('views.course_category') }}</span>
                                                        @break

                                                        @case('personal')
                                                            <span class="badge bg-warning">{{ __('views.personal_category') }}</span>
                                                        @break
                                                    @endswitch
                                                </td>
                                                <td>{{ $question->question }}</td>
                                                <td>
                                                    @if ($question->is_active)
                                                        <span class="badge bg-success">{{ __('views.active') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('views.inactive') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary"
                                                            wire:click="showEditQuestionModal({{ $question->id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('views.edit_question') }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button
                                                            class="btn btn-outline-{{ $question->is_active ? 'warning' : 'success' }}"
                                                            wire:click="toggleQuestionStatus({{ $question->id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $question->is_active ? __('views.pause') : __('views.activate') }}">
                                                            <i
                                                                class="bi bi-{{ $question->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger"
                                                            wire:click="deleteQuestion({{ $question->id }})"
                                                            wire:confirm="{{ __('views.confirm_delete_question') }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('views.delete_question') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $questions->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-question-circle fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('views.no_questions') }}</h5>
                                <p class="text-muted">{{ __('views.no_questions_desc') }}</p>
                                <button class="btn btn-primary" wire:click="showAddQuestionModal">
                                    <i class="bi bi-plus-circle mr-2"></i>{{ __('views.add_first_question') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tab 3: Quản lý đợt đánh giá -->
            <div class="{{ $activeTab === 'rounds' ? 'd-block' : 'd-none' }}" id="rounds" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('views.round_management_title') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" wire:click="showAddRoundModal" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="{{ __('views.add_new_round') }}">
                                <i class="bi bi-plus-circle mr-2"></i>{{ __('views.add_round') }}
                            </button>
                        </div>
                        @if ($evaluationRounds->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('views.stt') }}</th>
                                            <th>{{ __('views.round_name') }}</th>
                                            <th>{{ __('views.description') }}</th>
                                            <th>{{ __('views.time_period') }}</th>
                                            <th>{{ __('views.status') }}</th>
                                            <th>{{ __('views.evaluation_count') }}</th>
                                            <th>{{ __('views.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($evaluationRounds as $round)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $round->name }}</div>
                                                </td>
                                                <td>
                                                    @if ($round->description)
                                                        <span
                                                            class="text-muted">{{ Str::limit($round->description, 50) }}</span>
                                                    @else
                                                        <span class="text-muted">{{ __('views.no_description') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="small">
                                                        <div><strong>{{ __('views.from') }}</strong>
                                                            {{ $round->start_date->format('d/m/Y') }}</div>
                                                        <div><strong>{{ __('views.to') }}</strong>
                                                            {{ $round->end_date->format('d/m/Y') }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusColor = match ($round->status) {
                                                            'active' => 'success',
                                                            'upcoming' => 'info',
                                                            'ended' => 'secondary',
                                                            'inactive' => 'danger',
                                                            default => 'secondary',
                                                        };
                                                    @endphp
                                                    <span
                                                        class="badge bg-{{ $statusColor }}">{{ $round->status_text }}</span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary">{{ $round->evaluations->count() }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-primary"
                                                            wire:click="showEditRoundModal({{ $round->id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('views.edit_round') }}">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <button
                                                            class="btn btn-outline-{{ $round->is_active ? 'warning' : 'success' }}"
                                                            wire:click="toggleRoundStatus({{ $round->id }})"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ $round->is_active ? __('views.pause') : __('views.activate') }}">
                                                            <i
                                                                class="bi bi-{{ $round->is_active ? 'pause' : 'play' }}"></i>
                                                        </button>
                                                        <button class="btn btn-outline-danger"
                                                            wire:click="deleteRound({{ $round->id }})"
                                                            wire:confirm="{{ __('views.confirm_delete_round') }}"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="{{ __('views.delete_round') }}">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $evaluationRounds->links('vendor.pagination.bootstrap-4') }}
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-calendar-x fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('views.no_rounds') }}</h5>
                                <p class="text-muted">{{ __('views.no_rounds_desc') }}</p>
                                <button class="btn btn-primary" wire:click="showAddRoundModal">
                                    <i class="bi bi-plus-circle mr-2"></i>{{ __('views.create_first_round') }}
                                </button>
                            </div>
                        @endif
                    </div>
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
                            <h5 class="modal-title text-primary fw-bold fs-4">
                                <i class="bi bi-star-fill text-warning mr-2"></i>
                                {{ __('views.evaluation_detail') }} {{ $selectedEvaluation->student->user->name ?? __('views.student') }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeEvaluationDetail">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>{{ __('views.group1_teacher') }}</strong>
                                <ul class="mb-2">
                                    @foreach ($selectedEvaluation->teacher_ratings ?? [] as $k => $v)
                                        @php
                                            $question = $questions
                                                ->where('category', 'teacher')
                                                ->where('order', $k)
                                                ->first();
                                        @endphp
                                        <li>{{ $question ? $question->question : __('views.question') . ' ' . $k }}:
                                            {{ $v }}/5</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('views.group2_course') }}</strong>
                                <ul class="mb-2">
                                    @foreach ($selectedEvaluation->course_ratings ?? [] as $k => $v)
                                        @php
                                            $question = $questions
                                                ->where('category', 'course')
                                                ->where('order', $k)
                                                ->first();
                                        @endphp
                                        <li>{{ $question ? $question->question : __('views.question') . ' ' . $k }}:
                                            {{ $v }}/5</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-3">
                                <strong>{{ __('views.group3_personal') }}</strong>
                                @php
                                    $personalQuestion = $questions->where('category', 'personal')->first();
                                @endphp
                                <p class="mb-2">
                                    {{ $personalQuestion ? $personalQuestion->question : __('views.personal_satisfaction_question') }}:
                                    {{ $selectedEvaluation->personal_satisfaction }}/5</p>
                            </div>
                            @if ($selectedEvaluation->suggestions)
                                <div class="mb-3">
                                    <strong>{{ __('views.improvement_suggestions') }}</strong>
                                    <p class="mb-0">{{ $selectedEvaluation->suggestions }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeEvaluationDetail">
                                {{ __('views.close') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal thêm/sửa câu hỏi -->
        @if ($showQuestionModal)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); z-index: 1050;"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-question-circle mr-2"></i>
                                {{ $editingQuestion ? __('views.edit_question_title') : __('views.add_question_title') }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeQuestionModal">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <form wire:submit.prevent="saveQuestion">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="category" class="form-label">{{ __('views.category') }} <span
                                            class="text-danger">*</span></label>
                                    <select wire:model="questionForm.category" class="form-control" id="category">
                                        <option value="">{{ __('views.select_category') }}</option>
                                        <option value="teacher">{{ __('views.teacher_category') }}</option>
                                        <option value="course">{{ __('views.course_category') }}</option>
                                        <option value="personal">{{ __('views.personal_category') }}</option>
                                    </select>
                                    @error('questionForm.category')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="question" class="form-label">{{ __('views.question') }} <span
                                            class="text-danger">*</span></label>
                                    <textarea wire:model="questionForm.question" class="form-control" id="question" rows="3"
                                        placeholder="{{ __('views.question_text') }}"></textarea>
                                    @error('questionForm.question')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="order" class="form-label">{{ __('views.display_order') }}</label>
                                    <input type="number" wire:model="questionForm.order" class="form-control"
                                        id="order" min="0" placeholder="0">
                                    @error('questionForm.order')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input wire:model="questionForm.is_active" class="form-check-input"
                                            type="checkbox" id="is_active">
                                        <label class="form-check-label" for="is_active">
                                            {{ __('views.question_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="closeQuestionModal">
                                    {{ __('views.cancel') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    {{ $editingQuestion ? __('views.update') : __('views.add_new') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal thêm/sửa đợt đánh giá -->
        @if ($showRoundModal)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); z-index: 1050;"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-calendar-event mr-2"></i>
                                {{ $editingRound ? __('views.edit_round_title') : __('views.add_round_title') }}
                            </h5>
                            <button type="button" class="btn-close" wire:click="closeRoundModal">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <form wire:submit.prevent="saveRound" novalidate>
                            <div class="modal-body">
                                @if (session()->has('error'))
                                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                                        <div>{{ session('error') }}</div>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label for="round_name" class="form-label">{{ __('views.round_name') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" wire:model="roundForm.name" class="form-control"
                                        id="round_name" placeholder="{{ __('views.round_name_input') }}">
                                    @error('roundForm.name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="round_description" class="form-label">{{ __('views.description') }}</label>
                                    <textarea wire:model="roundForm.description" class="form-control" id="round_description" rows="3"
                                        placeholder="{{ __('views.round_description_input') }}"></textarea>
                                    @error('roundForm.description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_date" class="form-label">{{ __('views.start_date') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" wire:model="roundForm.start_date"
                                                class="form-control" id="start_date">
                                            @error('roundForm.start_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_date" class="form-label">{{ __('views.end_date') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" wire:model="roundForm.end_date"
                                                class="form-control" id="end_date">
                                            @error('roundForm.end_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input wire:model="roundForm.is_active" class="form-check-input"
                                            type="checkbox" id="round_is_active">
                                        <label class="form-check-label" for="round_is_active">
                                            {{ __('views.round_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" wire:click="closeRoundModal">
                                    {{ __('views.cancel') }}
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle mr-2"></i>
                                    {{ $editingRound ? __('views.update') : __('views.add_new') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle mr-2"></i>
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
                <i class="bi bi-exclamation-triangle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
</x-layouts.dash-admin>
