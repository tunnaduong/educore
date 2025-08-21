<x-layouts.dash-admin active="ai">
    @include('components.language')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-robot text-primary"></i>
                        @lang('general.ai_assistant') - @lang('general.smart_teaching')
                    </h3>
                </div>
                <div class="card-body">
                    <p class="text-muted">
                        {{ __('general.ai_intro_text') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chấm điểm tự động bằng AI -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bi bi-journal-check text-success"></i>
                        @lang('general.ai_grading')
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        {{ __('general.ai_grading_description') }}
                    </p>

                    @if ($recentSubmissions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>{{ __('general.student') }}</th>
                                        <th>{{ __('general.assignment') }}</th>
                                        <th>{{ __('general.submission_type') }}</th>
                                        <th>{{ __('general.submission_date') }}</th>
                                        <th>{{ __('general.status') }}</th>
                                        <th>{{ __('general.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentSubmissions as $submission)
                                        <tr>
                                            <td>{{ $submission->student->name }}</td>
                                            <td>{{ $submission->assignment->title }}</td>
                                            <td>
                                                @if ($submission->submission_type === 'text')
                                                    <span class="badge badge-info">{{ __('general.text_type') }}</span>
                                                @elseif ($submission->submission_type === 'file')
                                                    <span
                                                        class="badge badge-secondary">{{ __('general.file_type') }}</span>
                                                @elseif ($submission->submission_type === 'image')
                                                    <span
                                                        class="badge badge-warning">{{ __('general.image_type') }}</span>
                                                @elseif ($submission->submission_type === 'audio')
                                                    <span
                                                        class="badge badge-danger">{{ __('general.audio_type') }}</span>
                                                @elseif ($submission->submission_type === 'video')
                                                    <span
                                                        class="badge badge-dark">{{ __('general.video_type') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-light">{{ ucfirst($submission->submission_type) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $submission->submitted_at ? $submission->submitted_at->format('d/m/Y H:i') : $submission->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td>
                                                @if ($submission->score || $submission->ai_score)
                                                    <span class="badge badge-success">{{ __('general.graded') }}</span>
                                                @else
                                                    <span
                                                        class="badge badge-warning">{{ __('general.not_graded') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!$submission->score && !$submission->ai_score)
                                                    <a href="{{ route('ai.grading', $submission->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="bi bi-robot"></i>
                                                        {{ __('general.grade_with_ai') }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">{{ __('general.already_graded') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i>
                            {{ __('general.no_submissions_to_grade') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tạo Quiz bằng AI -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bi bi-patch-question text-info"></i>
                        @lang('general.ai_quiz_generator')
                    </h4>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        {{ __('general.high_quality_tests') }}
                    </p>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-file-text text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">{{ __('general.create_quiz_from_content') }}</h5>
                                    <p class="text-muted">{{ __('general.create_quiz_description') }}</p>
                                    <a href="{{ route('ai.quiz-generator') }}" class="btn btn-primary">
                                        <i class="bi bi-robot"></i>
                                        {{ __('general.create_quiz_button') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <i class="bi bi-collection text-success" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">{{ __('general.create_question_bank') }}</h5>
                                    <p class="text-muted">{{ __('general.create_question_bank_description') }}</p>
                                    <a href="{{ route('ai.question-bank-generator') }}" class="btn btn-success">
                                        <i class="bi bi-robot"></i>
                                        {{ __('general.create_question_bank_button') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê AI -->
    <div class="row">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $recentSubmissions->count() }}</h4>
                            <p class="mb-0">@lang('general.pending_grading')</p>
                        </div>
                        <div>
                            <i class="bi bi-journal-check" style="font-size: 2rem;"></i>
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
                            <h4>{{ $availableAssignments->count() }}</h4>
                            <p class="mb-0">@lang('general.can_create_quiz')</p>
                        </div>
                        <div>
                            <i class="bi bi-patch-question" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ $classrooms->count() }}</h4>
                            <p class="mb-0">@lang('general.active_classes')</p>
                        </div>
                        <div>
                            <i class="bi bi-people" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hướng dẫn sử dụng -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="bi bi-lightbulb text-warning"></i>
                        @lang('general.ai_guide')
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-1-circle text-primary" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">{{ __('general.auto_grading_title') }}</h5>
                                <p class="text-muted">{{ __('general.auto_grading_description') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-2-circle text-success" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">{{ __('general.create_quiz_title') }}</h5>
                                <p class="text-muted">{{ __('general.create_quiz_guide') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center mb-3">
                                <i class="bi bi-3-circle text-info" style="font-size: 2rem;"></i>
                                <h5 class="mt-2">{{ __('general.question_bank_title') }}</h5>
                                <p class="text-muted">{{ __('general.question_bank_description') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
