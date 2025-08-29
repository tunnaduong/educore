<x-layouts.dash-student active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary">
                        <i class="bi bi-journal-text mr-2"></i>{{ $assignment->title }}
                    </h4>
                    <p class="text-muted mb-0">{{ $assignment->classroom?->name ?? __('general.not_available') }} -
                        @if ($assignment->classroom->teachers->count())
                            {{ $assignment->classroom->teachers->pluck('name')->join(', ') }}
                        @else
                            {{ __('general.no_teacher') }}
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    @php
                        $status = $this->getStatusBadge();
                    @endphp
                    <span
                        class="badge {{ $status['class'] === 'bg-green-100 text-green-800'
                            ? 'badge-success'
                            : ($status['class'] === 'bg-red-100 text-red-800'
                                ? 'badge-danger'
                                : 'badge-warning') }}">
                        {{ $status['text'] }}
                    </span>
                    <div class="small text-muted mt-1">{{ $this->getTimeRemaining() }}</div>
                </div>
            </div>
        </div>

        <!-- Assignment Details -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle mr-2"></i>{{ __('views.assignment_details') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="mb-4">
                            <h6 class="font-weight-bold mb-2">{{ __('general.description') }}:</h6>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($assignment->description)) !!}
                            </div>
                        </div>

                        @if ($assignment->types)
                            <div class="mb-4">
                                <h6 class="font-weight-bold mb-2">{{ __('general.assignment_type') }}:</h6>
                                <div class="d-flex flex-wrap">
                                    @foreach ($assignment->types as $type)
                                        <span class="badge badge-primary mr-2 mb-2">
                                            @switch($type)
                                                @case('text')
                                                    {{ __('general.text') }}
                                                @break

                                                @case('essay')
                                                    {{ __('general.essay') }}
                                                @break

                                                @case('image')
                                                    {{ __('general.image') }}
                                                @break

                                                @case('audio')
                                                    {{ __('general.audio') }}
                                                @break

                                                @case('video')
                                                    {{ __('general.video') }}
                                                @break

                                                @default
                                                    {{ $type }}
                                            @endswitch
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-4">
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <h6 class="card-title font-weight-bold">{{ __('views.assignment_information') }}</h6>
                                <br>
                                <br>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-3">
                                        <i class="bi bi-calendar3 mr-2 text-primary"></i>
                                        <strong>{{ __('general.deadline') }}:</strong><br>
                                        <span class="text-dark">{{ $assignment->deadline->format('d/m/Y H:i') }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-people mr-2 text-primary"></i>
                                        <strong>{{ __('general.classroom') }}:</strong><br>
                                        <span class="text-dark">{{ $assignment->classroom?->name ?? __('general.not_available') }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <i class="bi bi-person mr-2 text-primary"></i>
                                        <strong>{{ __('general.teacher') }}:</strong><br>
                                        <span class="text-dark">
                                            @if ($assignment->classroom->teachers->count())
                                                {{ $assignment->classroom->teachers->pluck('name')->join(', ') }}
                                            @else
                                                {{ __('general.no_teacher') }}
                                            @endif
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if ($assignment->attachment_path)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h6 class="card-title font-weight-bold">{{ __('general.attachment') }}</h6>
                                    <br>
                                    <a href="{{ Storage::url($assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download mr-2"></i>{{ __('general.download_file') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($assignment->video_path)
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title font-weight-bold">{{ __('general.lesson_video') }}</h6>
                                    <br>
                                    <video controls class="w-100 rounded">
                                        <source src="{{ Storage::url($assignment->video_path) }}" type="video/mp4">
                                        {{ __('general.browser_not_support') }}
                                    </video>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submission Section -->
        @if ($submissions && $submissions->count())
            @foreach ($submissions as $submission)
                <div class="card shadow-sm mb-4 border-success">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="bi bi-check-circle mr-2"></i>{{ __('general.your_submission') }} -
                            @switch($submission->submission_type)
                                @case('text')
                                    {{ __('general.text') }}
                                @break

                                @case('essay')
                                    {{ __('general.essay') }}
                                @break

                                @case('image')
                                    {{ __('general.image') }}
                                @break

                                @case('audio')
                                    {{ __('general.audio') }}
                                @break

                                @case('video')
                                    {{ __('general.video') }}
                                @break

                                @default
                                    {{ $submission->submission_type }}
                            @endswitch
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="mb-4">
                                    <h6 class="font-weight-bold mb-2">{{ __('general.submission_content') }}</h6>
                                    @php
                                        $content = $submission->content;
                                        $isFile =
                                            filter_var($content, FILTER_VALIDATE_URL) ||
                                            str_starts_with($content, 'assignments/');
                                    @endphp

                                    @if ($isFile)
                                        @php
                                            $extension = pathinfo($content, PATHINFO_EXTENSION);
                                        @endphp

                                        @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                            <div class="border rounded p-3 bg-light">
                                                <h6 class="font-weight-bold mb-2">{{ __('general.image_submission') }}</h6>
                                                <img src="{{ Storage::url($content) }}" alt="Bài viết"
                                                    class="img-fluid rounded shadow-sm">
                                            </div>
                                        @elseif(in_array($extension, ['mp3', 'wav', 'm4a']))
                                            <div class="border rounded p-3 bg-light">
                                                <h6 class="font-weight-bold mb-2">{{ __('general.audio_submission') }}</h6>
                                                <audio controls class="w-100">
                                                    <source src="{{ Storage::url($content) }}"
                                                        type="audio/{{ $extension }}">
                                                    {{ __('general.browser_not_support') }}
                                                </audio>
                                            </div>
                                        @elseif(in_array($extension, ['mp4', 'avi', 'mov']))
                                            <div class="border rounded p-3 bg-light">
                                                <h6 class="font-weight-bold mb-2">{{ __('general.video_submission') }}</h6>
                                                <video controls class="w-100 rounded shadow-sm">
                                                    <source src="{{ Storage::url($content) }}"
                                                        type="video/{{ $extension }}">
                                                    {{ __('general.browser_not_support') }}
                                                </video>
                                            </div>
                                        @endif
                                    @else
                                        <div class="border rounded p-3 bg-light">
                                            @if ($submission->submission_type === 'essay')
                                                <div class="prose">
                                                    {!! nl2br(e($content)) !!}
                                                </div>
                                            @else
                                                {!! nl2br(e($content)) !!}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title font-weight-bold">{{ __('general.submission_information') }}</h6>
                                        <br>
                                        <br>
                                        <ul class="list-unstyled mb-0">
                                            <li class="mb-3">
                                                <i class="bi bi-clock mr-2 text-success"></i>
                                                <strong>{{ __('general.submitted_at') }}:</strong><br>
                                                <span
                                                    class="text-dark">{{ $submission->submitted_at->format('d/m/Y H:i') }}</span>
                                            </li>
                                            <li class="mb-3">
                                                <i class="bi bi-file-earmark mr-2 text-success"></i>
                                                <strong>{{ __('general.submission_type') }}:</strong><br>
                                                <span class="text-dark">
                                                    @switch($submission->submission_type)
                                                        @case('text')
                                                            {{ __('general.text') }}
                                                        @break

                                                        @case('essay')
                                                            {{ __('general.essay') }}
                                                        @break

                                                        @case('image')
                                                            {{ __('general.image') }}
                                                        @break

                                                        @case('audio')
                                                            {{ __('general.audio') }}
                                                        @break

                                                        @case('video')
                                                            {{ __('general.video') }}
                                                        @break

                                                        @default
                                                            {{ $submission->submission_type }}
                                                    @endswitch
                                                </span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                @if ($submission->score !== null)
                                    <div class="card border-primary">
                                        <div class="card-body text-center">
                                            <h6 class="font-weight-bold">{{ __('general.score') }}</h6>
                                            <div class="display-4 text-primary font-weight-bold">
                                                {{ $submission->score }}/10
                                            </div>
                                            @if ($submission->feedback)
                                                <div class="mt-3">
                                                    <small class="text-muted font-weight-bold">{{ __('general.feedback') }}:</small><br>
                                                    <small class="text-dark">{{ $submission->feedback }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="card border-warning">
                                        <div class="card-body text-center">
                                            <i class="bi bi-hourglass-split display-4 text-warning mb-3"></i>
                                            <br>
                                            <br>
                                            <h6 class="font-weight-bold text-center">{{ __('general.ungraded') }}</h6>
                                            <small class="text-muted">{{ __('general.pending_grading') }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Action Buttons -->
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    @if ($this->canSubmit())
                        <a href="{{ route('student.assignments.submit', $assignment->id) }}"
                            class="btn btn-primary btn-lg">
                            <i class="bi bi-pencil mr-2"></i>{{ __('general.do_assignment') }}
                        </a>
                    @elseif($this->isOverdue())
                        <div class="text-center">
                            <i class="bi bi-exclamation-triangle display-4 text-danger mb-3"></i>
                            <h5 class="text-danger font-weight-bold">{{ __('general.overdue_status') }}</h5>
                            <p class="text-muted">{{ __('general.cannot_submit_anymore') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($submissions->count() > 0 && $this->canRedo())
            <div class="text-center mb-4">
                <button class="btn btn-warning btn-lg" wire:click="redoSubmission">
                    <i class="bi bi-arrow-clockwise mr-2"></i>{{ __('general.redo_submission') }}
                </button>
            </div>
        @endif

        <!-- Back Button -->
        <div class="text-center mt-4 mb-4">
            <a href="{{ route('student.assignments.overview') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back_to_list') }}
            </a>
        </div>
    </div>
</x-layouts.dash-student>
