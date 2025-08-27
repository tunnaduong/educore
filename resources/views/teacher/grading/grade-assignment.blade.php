<x-layouts.dash-teacher active="grading">
    @include('components.language')
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('teacher.grading.index') }}" class="text-decoration-none text-secondary">
                        <i class="bi bi-arrow-left mr-1"></i>{{ __('general.back_to_list') }}
                    </a>
                    <h4 class="mt-2 mb-0 text-primary fs-4">
                        <i class="bi bi-check-circle mr-2"></i>{{ __('general.grade_assignment') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $assignment->title }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Thông tin bài tập -->
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-journal-text mr-2"></i>{{ __('general.assignment_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('general.title') }}:</label>
                            <p class="mb-0">{{ $assignment->title }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('general.classroom') }}:</label>
                            <p class="mb-0">{{ $assignment->classroom->name ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('general.deadline') }}:</label>
                            @if ($assignment->deadline)
                                <p class="mb-0">{{ $assignment->deadline->format('d/m/Y H:i') }}</p>
                                <small class="text-muted">{{ $assignment->deadline->diffForHumans() }}</small>
                            @else
                                <p class="mb-0 text-muted">{{ __('general.no_deadline') }}</p>
                            @endif
                        </div>
                        @if ($assignment->description)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('general.description') }}:</label>
                                <p class="mb-0">{{ $assignment->description }}</p>
                            </div>
                        @endif
                        @if ($assignment->attachment)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('general.attachment') }}:</label>
                                <div>
                                    <a href="{{ Storage::url($assignment->attachment) }}" target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-download mr-1"></i>{{ __('general.download') }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        @if ($assignment->video)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">{{ __('general.video') }}:</label>
                                <div>
                                    <video controls class="w-100 rounded">
                                        <source src="{{ Storage::url($assignment->video) }}" type="video/mp4">
                                        {{ __('general.browser_not_support') }}
                                    </video>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('general.assignment_types') }}:</label>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach ($assignment->types as $type)
                                    <span class="badge bg-info">{{ $this->getSubmissionTypeLabel($type) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('general.max_score') }}:</label>
                            <p class="mb-0">{{ $assignment->score ?? __('general.not_available') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Thống kê -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-primary">
                            <i class="bi bi-bar-chart mr-2"></i>{{ __('general.statistics') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-primary mb-1">{{ $submissions->count() }}</h4>
                                    <small class="text-muted">{{ __('general.total_submissions') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-1">{{ $submissions->where('score', '!=', null)->count() }}
                                </h4>
                                <small class="text-muted">{{ __('general.graded') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách bài nộp -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
                        <div>
                            <i class="bi bi-people mr-2"></i>
                            <span class="mb-0">{{ __('general.student_submissions') }}</span>
                        </div>
                        <div class="text-white-50 small">
                            {{ $submissions->count() }} {{ __('general.submissions') }}
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if ($submissions->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('general.student') }}</th>
                                            <th>{{ __('general.submission_type') }}</th>
                                            <th>{{ __('general.submitted_at') }}</th>
                                            <th>{{ __('general.score') }}</th>
                                            <th>{{ __('general.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($submissions as $submission)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm mr-3">
                                                            <i class="bi bi-person-circle fs-4 text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">
                                                                {{ $submission->student->user->name ?? '-' }}</div>
                                                            <small
                                                                class="text-muted">{{ $submission->student->user->email ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($submission->submission_type)
                                                        <span
                                                            class="badge bg-info">{{ $this->getSubmissionTypeLabel($submission->submission_type) }}</span>
                                                        @if ($submission->content)
                                                            <br><small class="text-muted">{{ __('general.has_content') }}</small>
                                                        @else
                                                            <br><small class="text-muted">{{ __('general.not_submitted') }}</small>
                                                        @endif
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('general.undefined') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission->submitted_at)
                                                        <div class="fw-medium">
                                                            {{ $submission->submitted_at->format('d/m/Y H:i') }}</div>
                                                        <small
                                                            class="text-muted">{{ $submission->submitted_at->diffForHumans() }}</small>
                                                    @else
                                                        <span class="text-muted">{{ __('general.not_submitted') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($submission->score !== null)
                                                        <span
                                                            class="badge bg-success">{{ $submission->score }}/10</span>
                                                    @else
                                                        <span class="badge bg-warning">{{ __('general.not_graded') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button wire:click="viewSubmission({{ $submission->id }})"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye mr-1"></i>{{ __('general.view') }}
                                                        </button>
                                                        @if (
                                                            ($submission->submission_type === 'essay' || $submission->submission_type === 'text') &&
                                                                !in_array($submission->submission_type, ['image', 'audio', 'video']))
                                                            <a href="{{ route('teacher.ai.grading', $submission->id) }}"
                                                                class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-robot mr-1"></i>AI
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-people fs-1 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('general.no_submissions') }}</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem chi tiết bài nộp -->
    @if ($showModal && $selectedSubmission)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="bi bi-eye mr-2"></i>{{ __('general.submission_details') }}
                        </h5>
                        <button type="button" class="close" wire:click="closeModal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>{{ __('general.student_information') }}</h6>
                                <p><strong>{{ __('general.full_name') }}:</strong> {{ $selectedSubmission->student->user->name ?? '-' }}</p>
                                <p><strong>{{ __('general.email') }}:</strong> {{ $selectedSubmission->student->user->email ?? '-' }}</p>
                                <p><strong>{{ __('general.submitted_at') }}:</strong>
                                    {{ $selectedSubmission->submitted_at ? $selectedSubmission->submitted_at->format('d/m/Y H:i') : __('general.not_submitted') }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>{{ __('general.submission_content') }}</h6>
                                @if ($selectedSubmission->content)
                                    @if ($selectedSubmission->submission_type === 'text' || $selectedSubmission->submission_type === 'essay')
                                        <div class="mb-3">
                                            <div class="border rounded p-3 bg-light">
                                                {{ $selectedSubmission->content }}
                                            </div>
                                        </div>
                                    @elseif ($selectedSubmission->submission_type === 'image')
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.image_submission') }}:</label>
                                            <div>
                                                <img src="{{ Storage::url($selectedSubmission->content) }}"
                                                    alt="Bài nộp" class="img-fluid rounded border"
                                                    style="max-width: 100%;">
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download mr-1"></i>{{ __('general.download') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($selectedSubmission->submission_type === 'video')
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.video_submission') }}:</label>
                                            <div>
                                                <video controls class="w-100 rounded">
                                                    <source src="{{ Storage::url($selectedSubmission->content) }}"
                                                        type="video/mp4">
                                                    {{ __('general.browser_not_support') }}
                                                </video>
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download mr-1"></i>{{ __('general.download') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($selectedSubmission->submission_type === 'audio')
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.audio_submission') }}:</label>
                                            <div>
                                                <audio controls class="w-100">
                                                    <source src="{{ Storage::url($selectedSubmission->content) }}"
                                                        type="audio/mpeg">
                                                    {{ __('general.browser_not_support') }}
                                                </audio>
                                                <div class="mt-2">
                                                    <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                        target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-download mr-1"></i>{{ __('general.download') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">{{ __('general.attachment') }}:</label>
                                            <div>
                                                <a href="{{ Storage::url($selectedSubmission->content) }}"
                                                    target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download mr-1"></i>{{ __('general.download') }}
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="mb-3">
                                        <div class="alert alert-warning">
                                            <i class="bi bi-exclamation-triangle mr-2"></i>
                                            {{ __('general.no_submission_content') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <!-- Form chấm điểm -->
                        <h6>{{ __('general.grading') }}</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('general.score_0_10') }}:</label>
                                <input type="number" class="form-control"
                                    wire:model.defer="grading.{{ $selectedSubmission->id }}.score" min="0"
                                    max="10" step="0.1" placeholder="{{ __('general.enter_score_placeholder') }}"
                                    oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                    onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('general.feedback') }}:</label>
                                <textarea class="form-control" wire:model.defer="grading.{{ $selectedSubmission->id }}.feedback" rows="3"
                                    placeholder="{{ __('general.enter_feedback') }}"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">{{ __('general.close') }}</button>
                        <button type="button" class="btn btn-primary"
                            wire:click="saveGrade({{ $selectedSubmission->id }})">
                            <i class="bi bi-save mr-1"></i>{{ __('general.save_grade') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif
</x-layouts.dash-teacher>
