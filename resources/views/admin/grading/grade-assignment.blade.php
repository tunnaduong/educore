<x-layouts.dash-admin active="submissions" title="@lang('general.grade_assignment')">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('grading.list') }}" wire:navigate
                class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="fas fa-arrow-left mr-2"></i>@lang('general.back_to_list')
            </a>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-outline card-primary mb-3">
                        <div class="card-body">
                            <h4 class="card-title mb-2 text-primary">
                                <i class="fas fa-check-circle mr-2"></i>@lang('general.grade_assignment'): <span
                                    class="font-weight-bold">{{ $assignment->title }}</span>
                            </h4>
                            <div class="mb-2">
                                <span class="badge badge-info mr-2"><i class="fas fa-graduation-cap"></i> @lang('general.class'):
                                    {{ $assignment->classroom?->name ?? '-' }}</span>
                                <span class="badge badge-warning"><i class="fas fa-calendar-alt"></i> @lang('general.deadline'):
                                    {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card card-outline card-primary">
                        <div class="card-header bg-light d-flex align-items-center">
                            <i class="fas fa-users mr-2 text-primary"></i>
                            <span class="mb-0 text-primary">@lang('general.submission_list')</span>
                        </div>
                        <div class="card-body p-0">
                            @if ($submissions->count() > 0)
                                <div class="row">
                                    @foreach ($submissions as $submission)
                                        <div class="col-12">
                                            <div
                                                class="card mb-2 shadow-sm border border-2 {{ $submission->score !== null ? 'border-success' : 'border-secondary' }}">
                                                <div class="card-body d-flex flex-column flex-xxl-row gap-3">
                                                    <div class="d-flex align-items-center mb-3 mb-md-0"
                                                        style="min-width:220px">
                                                        <div class="mr-3">
                                                            <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="font-weight-bold fs-5">
                                                                {{ $submission->student?->user?->name ?? '-' }}</div>
                                                            <div>
                                                                <span class="badge badge-secondary">
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
                                                                    <span class="badge badge-info ml-1"><i
                                                                            class="fas fa-clock"></i>
                                                                        {{ $submission->submitted_at->format('d/m/Y H:i') }}</span>
                                                                @endif
                                                                @if ($submission->score !== null)
                                                                    <span class="badge badge-success ml-1"><i
                                                                            class="fas fa-check-circle"></i> @lang('general.graded')</span>
                                                                @else
                                                                    <span class="badge badge-secondary ml-1"><i
                                                                            class="fas fa-hourglass-half"></i> @lang('general.not_graded')</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <style>
                                                        @media (min-width: 1400px) {
                                                            .btn-xxl-h70 {
                                                                height: 70px !important;
                                                            }
                                                        }
                                                    </style>
                                                    <div
                                                        class="d-flex flex-column flex-xxl-row align-items-stretch align-items-xxl-center gap-2 flex-grow-1 justify-content-md-end flex-fill">

                                                        <div class="d-flex flex-row flex-xxl-column gap-2">
                                                            <input type="number" min="0" max="10"
                                                                step="0.1"
                                                                class="form-control form-control-sm text-center mb-2 mb-md-0"
                                                                wire:model.defer="grading.{{ $submission->id }}.score"
                                                                placeholder="@lang('general.score') (0-10)"
                                                                oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;">
                                                            <input type="text"
                                                                class="form-control form-control-sm text-center mb-2 mb-md-0"
                                                                wire:model.defer="grading.{{ $submission->id }}.feedback"
                                                                placeholder="@lang('general.feedback')...">
                                                        </div>
                                                        <div class="row gx-2">
                                                            <div class="col-6">
                                                                <button
                                                                    class="btn btn-sm btn-outline-primary mb-2 mb-md-0 btn-xxl-h70 w-100"
                                                                    wire:click="viewSubmission({{ $submission->id }})">
                                                                    <i class="fas fa-eye"></i><br
                                                                        class="d-none d-xxl-inline">
                                                                    @lang('general.view_submission')
                                                                </button>
                                                            </div>
                                                            <div class="col-6">
                                                                <button
                                                                    class="btn btn-sm btn-success px-3 mb-2 mb-md-0 btn-xxl-h70 w-100"
                                                                    wire:click="saveGrade({{ $submission->id }})">
                                                                    <i class="fas fa-save"></i><br
                                                                        class="d-none d-xxl-inline">
                                                                    @lang('general.save')
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($submission->score !== null && $submission->feedback)
                                                    <div class="card-footer bg-light text-success small pl-5">
                                                        <i class="fas fa-comment"></i> @lang('general.feedback'): <span
                                                            class="font-weight-bold">{{ $submission->feedback }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @if (session()->has('success'))
                                    <div class="alert alert-success mt-3 text-center font-weight-bold shadow-sm">
                                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                                    </div>
                                @endif

                                @if (session()->has('error'))
                                    <div class="alert alert-danger mt-3 text-center font-weight-bold shadow-sm">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                                    </div>
                                @endif
                            @else
                                <div class="alert alert-info text-center m-0 py-4">
                                    <i class="fas fa-info-circle fa-3x"></i><br>@lang('general.no_submissions')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-none d-lg-block">
                    <div class="card card-outline card-primary mb-3">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-lightbulb mr-2"></i>@lang('general.grading_guide')
                        </div>
                        <div class="card-body small">
                            <ul class="mb-2 pl-3">
                                <li>@lang('general.click_view')</li>
                                <li>@lang('general.enter_score')</li>
                                <li>@lang('general.click_save')</li>
                                <li>@lang('general.graded_status')</li>
                            </ul>
                            <div class="alert alert-info p-2 mb-0">
                                <i class="fas fa-info-circle"></i> @lang('general.only_teachers')
                            </div>
                        </div>
                    </div>
                    <div class="card card-outline card-primary">
                        <div class="card-header bg-light">
                            <i class="fas fa-file-alt mr-2"></i>@lang('general.assignment_info')
                        </div>
                        <div class="card-body small">
                            <div><b>@lang('general.assignment_title'):</b> {{ $assignment->title }}</div>
                            <div><b>@lang('general.class'):</b> {{ $assignment->classroom?->name ?? '-' }}</div>
                            <div><b>@lang('general.deadline'):</b>
                                {{ $assignment->deadline ? $assignment->deadline->format('d/m/Y H:i') : '-' }}</div>
                            <div><b>@lang('general.description'):</b> <span
                                    class="text-muted">{{ $assignment->description ?? @lang('general.no_description') }}</span>
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
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-eye mr-2"></i>@lang('general.view_submission') của
                            {{ $selectedSubmission->student->user->name }}
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
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
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
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-user-check mr-2"></i>@lang('general.student_answer') của
                                    {{ $selectedSubmission->student->user->name }}
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
                                            class="img-fluid rounded" style="max-height: 400px;" alt="@lang('general.image_submission')">
                                    </div>
                                @elseif($selectedSubmission->submission_type === 'video' && $selectedSubmission->content)
                                    <h6 class="font-weight-bold mb-2">@lang('general.video_submission'):</h6>
                                    <div class="text-center">
                                        <video controls class="w-100" style="max-height: 400px;">
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
                                                class="img-fluid rounded" style="max-height: 400px;"
                                                alt="@lang('general.image_submission')">
                                        </div>
                                    @elseif(in_array(pathinfo($selectedSubmission->content, PATHINFO_EXTENSION), ['mp4', 'avi', 'mov', 'wmv']))
                                        <!-- Hiển thị video -->
                                        <div class="text-center">
                                            <video controls class="w-100" style="max-height: 400px;">
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
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="fas fa-times-circle mr-2"></i>@lang('general.close')
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-admin>
