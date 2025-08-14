<x-layouts.dash-admin active="submissions" title="@lang('general.grade_assignment')">
    @include('components.language')
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-white shadow-sm rounded">
                    <li class="breadcrumb-item">
                        <a href="{{ route('grading.list') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left mr-1"></i>@lang('general.back_to_list')
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">@lang('general.grade_assignment')</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Assignment Header Card -->
                    <div class="card border-0 shadow-sm mb-4"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body text-white p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="mb-2 font-weight-bold">
                                        <i class="fas fa-check-circle mr-2"></i>{{ $assignment->title }}
                                    </h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge badge-light text-dark">
                                            <i class="fas fa-graduation-cap mr-1"></i>
                                            {{ $assignment->classroom?->name ?? '-' }}
                                        </span>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-calendar-alt mr-1"></i>
                                            {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}
                                        </span>
                                        <span class="badge badge-info">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $submissions->count() }} @lang('general.submissions')
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="text-center">
                                        <div class="h2 mb-0 font-weight-bold">
                                            {{ $submissions->where('score', '!=', null)->count() }}/{{ $submissions->count() }}
                                        </div>
                                        <small>@lang('general.graded')</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submissions List -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0 py-3">
                            <h5 class="mb-0 text-primary font-weight-bold">
                                <i class="fas fa-list-alt mr-2"></i>@lang('general.submission_list')
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            @if ($submissions->count() > 0)
                                <div class="p-3">
                                    @foreach ($submissions as $submission)
                                        <div class="card mb-3 border-0 shadow-sm hover-shadow"
                                            style="transition: all 0.3s ease; {{ $submission->score !== null ? 'border-left: 4px solid #28a745 !important;' : 'border-left: 4px solid #6c757d !important;' }}">
                                            <div class="card-body p-4">
                                                <div class="row align-items-center">
                                                    <!-- Student Info -->
                                                    <div class="col-md-4 mb-3 mb-md-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                                    style="width: 50px; height: 50px; font-size: 20px;">
                                                                    {{ substr($submission->student?->user?->name ?? 'A', 0, 1) }}
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <h6 class="mb-1 font-weight-bold">
                                                                    {{ $submission->student?->user?->name ?? '-' }}</h6>
                                                                <div class="d-flex flex-wrap gap-1">
                                                                    <span class="badge badge-secondary badge-pill">
                                                                        @switch($submission->submission_type)
                                                                            @case('text')
                                                                                @lang('general.text')
                                                                            @break

                                                                            @case('essay')
                                                                                @lang('general.essay')
                                                                            @break

                                                                            @case('image')
                                                                                @lang('general.image')
                                                                            @break

                                                                            @case('audio')
                                                                                @lang('general.audio')
                                                                            @break

                                                                            @case('video')
                                                                                @lang('general.video')
                                                                            @break

                                                                            @default
                                                                                {{ $submission->submission_type }}
                                                                        @endswitch
                                                                    </span>
                                                                    @if ($submission->submitted_at)
                                                                        <span class="badge badge-info badge-pill">
                                                                            <i class="fas fa-clock mr-1"></i>
                                                                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Grading Status -->
                                                    <div class="col-md-2 mb-3 mb-md-0 text-center">
                                                        @if ($submission->score !== null)
                                                            <div class="text-success">
                                                                <i class="fas fa-check-circle fa-2x"></i>
                                                                <div class="small font-weight-bold">@lang('general.graded')
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-muted">
                                                                <i class="fas fa-hourglass-half fa-2x"></i>
                                                                <div class="small">@lang('general.not_graded')</div>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Score Input -->
                                                    <div class="col-md-2 mb-3 mb-md-0">
                                                        <div class="form-group mb-0">
                                                            <label
                                                                class="small text-muted mb-1">@lang('general.score')</label>
                                                            <input type="number" min="0" max="10"
                                                                step="0.1"
                                                                class="form-control form-control-sm text-center"
                                                                wire:model.defer="grading.{{ $submission->id }}.score"
                                                                placeholder="0-10" style="border-radius: 20px;"
                                                                oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                                                        </div>
                                                    </div>

                                                    <!-- Actions -->
                                                    <div class="col-md-4">
                                                        <div class="d-flex gap-2">
                                                            <button class="btn btn-outline-primary btn-sm flex-fill"
                                                                wire:click="viewSubmission({{ $submission->id }})"
                                                                style="border-radius: 20px;">
                                                                <i class="fas fa-eye mr-1"></i>@lang('general.view')
                                                            </button>
                                                            <button class="btn btn-success btn-sm flex-fill"
                                                                wire:click="saveGrade({{ $submission->id }})"
                                                                style="border-radius: 20px;">
                                                                <i class="fas fa-save mr-1"></i>@lang('general.save')
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Feedback -->
                                                <div class="mt-3">
                                                    <div class="form-group mb-0">
                                                        <label class="small text-muted mb-1">@lang('general.feedback')</label>
                                                        <textarea class="form-control form-control-sm" wire:model.defer="grading.{{ $submission->id }}.feedback"
                                                            placeholder="@lang('general.feedback')..." rows="2" style="border-radius: 10px; resize: none;"></textarea>
                                                    </div>
                                                </div>

                                                <!-- Previous Feedback Display -->
                                                @if ($submission->score !== null && $submission->feedback)
                                                    <div class="mt-3 p-3 bg-light rounded"
                                                        style="border-left: 3px solid #28a745;">
                                                        <div class="small text-muted mb-1">
                                                            <i class="fas fa-comment mr-1"></i>@lang('general.previous_feedback'):
                                                        </div>
                                                        <div class="font-weight-bold">{{ $submission->feedback }}</div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Success/Error Messages -->
                                @if (session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert">
                                            <span>&times;</span>
                                        </button>
                                    </div>
                                @endif
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">@lang('general.no_submissions')</h5>
                                    <p class="text-muted">@lang('general.no_submissions_desc')</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Grading Guide -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white border-0">
                            <h6 class="mb-0">
                                <i class="fas fa-lightbulb mr-2"></i>@lang('general.grading_guide')
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                                        style="width: 30px; height: 30px; font-size: 14px;">1</div>
                                    <span class="small">@lang('general.click_view')</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                                        style="width: 30px; height: 30px; font-size: 14px;">2</div>
                                    <span class="small">@lang('general.enter_score')</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                                        style="width: 30px; height: 30px; font-size: 14px;">3</div>
                                    <span class="small">@lang('general.click_save')</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                                        style="width: 30px; height: 30px; font-size: 14px;">4</div>
                                    <span class="small">@lang('general.graded_status')</span>
                                </div>
                            </div>
                            <div class="alert alert-info p-3 mb-0" style="border-radius: 10px;">
                                <i class="fas fa-info-circle mr-2"></i>@lang('general.only_teachers')
                            </div>
                        </div>
                    </div>

                    <!-- Assignment Info -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-0">
                            <h6 class="mb-0 text-primary font-weight-bold">
                                <i class="fas fa-file-alt mr-2"></i>@lang('general.assignment_info')
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="small text-muted mb-1">@lang('general.assignment_title')</div>
                                <div class="font-weight-bold">{{ $assignment->title }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="small text-muted mb-1">@lang('general.class')</div>
                                <div class="font-weight-bold">{{ $assignment->classroom?->name ?? '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="small text-muted mb-1">@lang('general.deadline')</div>
                                <div class="font-weight-bold">
                                    {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}
                                </div>
                            </div>
                            <div>
                                <div class="small text-muted mb-1">@lang('general.description')</div>
                                <div class="text-muted small">
                                    {{ $assignment->description ?? __('general.no_description') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal xem nội dung bài nộp -->
    @if ($showModal && $selectedSubmission)
        <div class="modal fade show" style="display: block;" tabindex="-1" wire:ignore.self>
            <div class="modal-dialog modal-dialog-scrollable modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title">
                            <i class="fas fa-eye mr-2"></i>@lang('general.view_submission') của
                            {{ $selectedSubmission->student?->user?->name ?? 'N/A' }}
                        </h5>
                        <button type="button" class="close text-white" wire:click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>@lang('general.assignment_title'):</strong> {{ $selectedSubmission->assignment->title }}
                            </div>
                            <div class="col-md-6">
                                <strong>@lang('general.submission_type'):</strong>
                                <span class="badge badge-secondary">
                                    @switch($selectedSubmission->submission_type)
                                        @case('text')
                                            @lang('general.text')
                                        @break

                                        @case('essay')
                                            @lang('general.essay')
                                        @break

                                        @case('image')
                                            @lang('general.image')
                                        @break

                                        @case('audio')
                                            @lang('general.audio')
                                        @break

                                        @case('video')
                                            @lang('general.video')
                                        @break

                                        @default
                                            {{ $selectedSubmission->submission_type }}
                                    @endswitch
                                </span>
                            </div>
                        </div>

                        <!-- Đề bài -->
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-header bg-primary text-white border-0">
                                <h6 class="mb-0">
                                    <i class="fas fa-file-alt mr-2"></i>@lang('general.assignment_title')
                                </h6>
                            </div>
                            <div class="card-body">
                                <h6 class="font-weight-bold mb-2">{{ $selectedSubmission->assignment->title }}</h6>
                                @if ($selectedSubmission->assignment->description)
                                    <div class="text-muted">
                                        {!! nl2br(e($selectedSubmission->assignment->description)) !!}
                                    </div>
                                @else
                                    <div class="text-muted">@lang('general.no_description')</div>
                                @endif

                                @if ($selectedSubmission->assignment->attachment)
                                    <div class="mt-2">
                                        <strong>@lang('general.file_attachment'):</strong>
                                        <a href="{{ asset('storage/' . $selectedSubmission->assignment->attachment) }}"
                                            target="_blank" class="btn btn-sm btn-outline-primary ml-2">
                                            <i class="fas fa-download mr-1"></i>@lang('general.download_file')
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Đáp án của học viên -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-success text-white border-0">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-check mr-2"></i>@lang('general.student_answer') của
                                    {{ $selectedSubmission->student?->user?->name ?? 'N/A' }}
                                </h6>
                            </div>
                            <div class="card-body">
                                @if ($selectedSubmission->submission_type === 'text' || $selectedSubmission->submission_type === 'essay')
                                    <h6 class="font-weight-bold mb-2">
                                        @if ($selectedSubmission->submission_type === 'essay')
                                            @lang('general.essay_content'):
                                        @else
                                            @lang('general.text_content'):
                                        @endif
                                    </h6>
                                    <div class="border rounded p-3 bg-white">
                                        {!! nl2br(e($selectedSubmission->content)) !!}
                                    </div>
                                @elseif($selectedSubmission->submission_type === 'image' && $selectedSubmission->content)
                                    <h6 class="font-weight-bold mb-2">@lang('general.image_submission'):</h6>
                                    <div class="text-center">
                                        <img src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                            class="img-fluid rounded shadow-sm" style="max-height: 400px;"
                                            alt="@lang('general.image_submission')">
                                    </div>
                                @elseif($selectedSubmission->submission_type === 'video' && $selectedSubmission->content)
                                    <h6 class="font-weight-bold mb-2">@lang('general.video_submission'):</h6>
                                    <div class="text-center">
                                        <video controls class="w-100 rounded shadow-sm" style="max-height: 400px;">
                                            <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                type="video/mp4">
                                            @lang('general.browser_not_support')
                                        </video>
                                    </div>
                                @elseif($selectedSubmission->submission_type === 'audio' && $selectedSubmission->content)
                                    <h6 class="font-weight-bold mb-2">@lang('general.audio_submission'):</h6>
                                    <div class="text-center">
                                        <audio controls class="w-100">
                                            <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                type="audio/mpeg">
                                            @lang('general.browser_not_support')
                                        </audio>
                                    </div>
                                @elseif($selectedSubmission->content)
                                    <h6 class="font-weight-bold mb-2">@lang('general.file_attachment'):</h6>
                                    @if (in_array(pathinfo($selectedSubmission->content, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <!-- Hiển thị ảnh -->
                                        <div class="text-center">
                                            <img src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                class="img-fluid rounded shadow-sm" style="max-height: 400px;"
                                                alt="@lang('general.image_submission')">
                                        </div>
                                    @elseif(in_array(pathinfo($selectedSubmission->content, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov', 'wmv']))
                                        <!-- Hiển thị video -->
                                        <div class="text-center">
                                            <video controls class="w-100 rounded shadow-sm"
                                                style="max-height: 400px;">
                                                <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                    type="video/mp4">
                                                @lang('general.browser_not_support')
                                            </video>
                                        </div>
                                    @elseif(in_array(pathinfo($selectedSubmission->content, PATHINFO_EXTENSION), ['mp3', 'wav', 'ogg']))
                                        <!-- Hiển thị audio -->
                                        <div class="text-center">
                                            <audio controls class="w-100">
                                                <source src="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                    type="audio/mpeg">
                                                @lang('general.browser_not_support')
                                            </audio>
                                        </div>
                                    @else
                                        <!-- File khác -->
                                        <div class="text-center">
                                            <i class="fas fa-file fa-3x text-muted mb-2"></i>
                                            <p class="text-muted">{{ basename($selectedSubmission->content) }}</p>
                                            <a href="{{ asset('storage/' . $selectedSubmission->content) }}"
                                                target="_blank" class="btn btn-primary">
                                                <i class="fas fa-download mr-2"></i>@lang('general.download')
                                            </a>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center text-muted">
                                        <i class="fas fa-file-times fa-3x mb-2"></i>
                                        <p>@lang('general.no_content')</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="fas fa-times-circle mr-2"></i>@lang('general.close')
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <style>
        .hover-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        }

        .gap-1 {
            gap: 0.25rem;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-3 {
            gap: 1rem;
        }
    </style>
</x-layouts.dash-admin>
