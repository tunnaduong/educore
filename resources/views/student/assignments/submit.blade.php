<x-layouts.dash-student active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary">
                        <i class="bi bi-pencil mr-2"></i>{{ __('general.do_assignment') }}: {{ $assignment->title }}
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
                    <div class="badge badge-warning">{{ __('general.deadline') }}: {{ $assignment->deadline->format('d/m/Y H:i') }}
                    </div>
                    <div class="small text-muted mt-1">{{ $this->getTimeRemaining() }}</div>
                </div>
            </div>
        </div>

        <!-- Assignment Info -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle mr-2"></i>{{ __('views.assignment_information') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <h6>{{ __('general.description') }}:</h6>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($assignment->description)) !!}
                            </div>
                        </div>

                        @if ($assignment->types)
                            <div class="mb-3">
                                <h6>{{ __('general.assignment_type') }}:</h6>
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
                    <div class="col-md-4">
                        @if ($assignment->attachment_path)
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">{{ __('general.attachment') }}</h6>
                                    <br>
                                    <a href="{{ Storage::url($assignment->attachment_path) }}" target="_blank"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-download mr-2"></i>{{ __('general.download_file') }}
                                    </a>
                                </div>
                            </div>
                        @endif

                        @if ($assignment->video_path)
                            <div class="card mt-3">
                                <div class="card-body">
                                    <h6 class="card-title">{{ __('general.lesson_video') }}</h6>
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

        <!-- Submission Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bi bi-upload mr-2"></i>{{ __('general.submit_assignment') }}
                </h6>
            </div>
            <div class="card-body">
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle mr-2"></i>
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <!-- Current submission status -->
                @php
                    $status = $this->getSubmissionStatus();
                @endphp
                @if ($status['required_count'] > 1)
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle mr-2"></i>{{ __('general.current_submission_status') }}
                        </h6>
                        <div class="mb-2">
                            <strong>{{ __('general.progress') }}:</strong> {{ $status['submitted_count'] }}/{{ $status['required_count'] }} {{ __('general.assignment_types') }}
                        </div>
                        <div class="mb-2">
                            <strong>{{ __('general.submitted') }}:</strong>
                            @if (count($status['submitted_types']) > 0)
                                @foreach ($status['submitted_types'] as $type)
                                    <span class="badge bg-success mr-1">{{ $this->getTypeLabel($type) }}</span>
                                @endforeach
                            @else
                                <span class="text-white">{{ __('general.no_type_submitted') }}</span>
                            @endif
                        </div>
                        <div>
                            <strong>{{ __('general.missing') }}:</strong>
                            @if (count($status['missing_types']) > 0)
                                @foreach ($status['missing_types'] as $type)
                                    <span class="badge bg-warning mr-1">{{ $this->getTypeLabel($type) }}</span>
                                @endforeach
                            @else
                                <span class="text-success">{{ __('general.submitted_all_types') }}</span>
                            @endif
                        </div>
                    </div>
                @endif

                <form wire:submit="submitAssignment">
                    <!-- Submission Type Selection -->
                    @if (count($status['missing_types']) > 0)
                        <div class="mb-4">
                            <label class="font-weight-bold">{{ __('general.select_submission_type') }}</label>
                            <div class="row">
                                @if (in_array('text', $assignment->types))
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="border rounded p-3 h-100 position-relative {{ $this->isTypeSubmitted('text') ? 'bg-light' : '' }}">
                                            <input class="form-check-input position-absolute" type="radio"
                                                wire:model.live="submissionType" id="type_text" value="text"
                                                style="top: 15px; left: 30px;"
                                                {{ $this->isTypeSubmitted('text') ? 'disabled' : '' }}>
                                            <label class="form-check-label d-block pl-3" for="type_text">
                                                <i class="bi bi-pencil-square text-primary mr-2"></i>
                                                <strong>{{ __('general.text') }}</strong>
                                                <div class="small text-muted mt-1">{{ __('general.short_answer') }}</div>
                                                @if ($this->isTypeSubmitted('text'))
                                                    <div class="small text-success mt-1">
                                                        <i class="bi bi-check-circle"></i> {{ __('general.submitted') }}
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('essay', $assignment->types))
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="border rounded p-3 h-100 position-relative {{ $this->isTypeSubmitted('essay') ? 'bg-light' : '' }}">
                                            <input class="form-check-input position-absolute" type="radio"
                                                wire:model.live="submissionType" id="type_essay" value="essay"
                                                style="top: 15px; left: 30px;"
                                                {{ $this->isTypeSubmitted('essay') ? 'disabled' : '' }}>
                                            <label class="form-check-label d-block pl-3" for="type_essay">
                                                <i class="bi bi-file-text text-success mr-2"></i>
                                                <strong>{{ __('general.essay') }}</strong>
                                                <div class="small text-muted mt-1">{{ __('general.long_essay') }}</div>
                                                @if ($this->isTypeSubmitted('essay'))
                                                    <div class="small text-success mt-1">
                                                        <i class="bi bi-check-circle"></i> {{ __('general.submitted') }}
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('image', $assignment->types))
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="border rounded p-3 h-100 position-relative {{ $this->isTypeSubmitted('image') ? 'bg-light' : '' }}">
                                            <input class="form-check-input position-absolute" type="radio"
                                                wire:model.live="submissionType" id="type_image" value="image"
                                                style="top: 15px; left: 30px;"
                                                {{ $this->isTypeSubmitted('image') ? 'disabled' : '' }}>
                                            <label class="form-check-label d-block pl-3" for="type_image">
                                                <i class="bi bi-image text-info mr-2"></i>
                                                <strong>{{ __('general.image') }}</strong>
                                                <div class="small text-muted mt-1">{{ __('general.image_file_hint') }}</div>
                                                @if ($this->isTypeSubmitted('image'))
                                                    <div class="small text-success mt-1">
                                                        <i class="bi bi-check-circle"></i> {{ __('general.submitted') }}
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('audio', $assignment->types))
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="border rounded p-3 h-100 position-relative {{ $this->isTypeSubmitted('audio') ? 'bg-light' : '' }}">
                                            <input class="form-check-input position-absolute" type="radio"
                                                wire:model.live="submissionType" id="type_audio" value="audio"
                                                style="top: 15px; left: 30px;"
                                                {{ $this->isTypeSubmitted('audio') ? 'disabled' : '' }}>
                                            <label class="form-check-label d-block pl-3" for="type_audio">
                                                <i class="bi bi-mic text-warning mr-2"></i>
                                                <strong>{{ __('general.audio') }}</strong>
                                                <div class="small text-muted mt-1">{{ __('general.audio_file_hint_short') }}</div>
                                                @if ($this->isTypeSubmitted('audio'))
                                                    <div class="small text-success mt-1">
                                                        <i class="bi bi-check-circle"></i> {{ __('general.submitted') }}
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif

                                @if (in_array('video', $assignment->types))
                                    <div class="col-md-4 mb-3">
                                        <div
                                            class="border rounded p-3 h-100 position-relative {{ $this->isTypeSubmitted('video') ? 'bg-light' : '' }}">
                                            <input class="form-check-input position-absolute" type="radio"
                                                wire:model.live="submissionType" id="type_video" value="video"
                                                style="top: 15px; left: 30px;"
                                                {{ $this->isTypeSubmitted('video') ? 'disabled' : '' }}>
                                            <label class="form-check-label d-block pl-3" for="type_video">
                                                <i class="bi bi-camera-video text-danger mr-2"></i>
                                                <strong>{{ __('general.video') }}</strong>
                                                <div class="small text-muted mt-1">{{ __('general.video_file_hint_short') }}</div>
                                                @if ($this->isTypeSubmitted('video'))
                                                    <div class="small text-success mt-1">
                                                        <i class="bi bi-check-circle"></i> {{ __('general.submitted') }}
                                                    </div>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @error('submissionType')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <!-- Content Input Based on Type -->
                    @if ($submissionType && count($status['missing_types']) > 0)
                        <div class="mb-4">
                            @switch($submissionType)
                                @case('text')
                                    <label for="content" class="font-weight-bold">{{ __('general.submission_content') }}:</label>
                                    <textarea wire:model.live="content" id="content" rows="4"
                                        class="form-control @error('content') is-invalid @enderror" placeholder="{{ __('general.enter_your_answer') }}"></textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @break

                                @case('essay')
                                    <label for="essay" class="font-weight-bold">{{ __('general.essay') }}:</label>
                                    <textarea wire:model.live="essay" id="essay" rows="12"
                                        class="form-control @error('essay') is-invalid @enderror" placeholder="{{ __('general.write_your_essay') }}"></textarea>
                                    <div class="form-text">{{ __('general.essay_detail_hint') }}</div>
                                    @error('essay')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                @break

                                @case('image')
                                    <label for="imageFile" class="font-weight-bold">{{ __('general.upload_image_submission') }}</label>
                                    <input type="file" wire:model.live="imageFile" id="imageFile" accept="image/*"
                                        class="form-control @error('imageFile') is-invalid @enderror">
                                    <div class="form-text">{{ __('general.image_file_hint_long') }}</div>
                                    @error('imageFile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($imageFile)
                                        <div class="mt-3">
                                            <img src="{{ $imageFile->temporaryUrl() }}" alt="Preview"
                                                class="img-fluid rounded" style="max-height: 300px;">
                                        </div>
                                    @endif
                                @break

                                @case('audio')
                                    <label for="audioFile" class="font-weight-bold">{{ __('general.upload_audio_file') }}</label>
                                    <input type="file" wire:model.live="audioFile" id="audioFile" accept=".mp3,.wav,.m4a,.mp4,.aac,.ogg,.webm,.weba,audio/*"
                                        class="form-control @error('audioFile') is-invalid @enderror">
                                    <div class="form-text">{{ __('general.audio_file_hint_long') }}</div>
                                    @error('audioFile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($audioFile)
                                        <div class="mt-3">
                                            <audio controls class="w-100">
                                                <source src="{{ $audioFile->temporaryUrl() }}"
                                                    type="audio/{{ $audioFile->getClientOriginalExtension() }}">
                                                {{ __('general.browser_not_support') }}
                                            </audio>
                                        </div>
                                    @endif
                                @break

                                @case('video')
                                    <label for="videoFile" class="font-weight-bold">{{ __('general.upload_video_file') }}</label>
                                    <input type="file" wire:model.live="videoFile" id="videoFile" accept="video/*"
                                        class="form-control @error('videoFile') is-invalid @enderror">
                                    <div class="form-text">{{ __('general.video_file_hint_long') }}</div>
                                    @error('videoFile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($videoFile)
                                        <div class="mt-3">
                                            <video controls class="w-100 rounded">
                                                <source src="{{ $videoFile->temporaryUrl() }}"
                                                    type="video/{{ $videoFile->getClientOriginalExtension() }}">
                                                {{ __('general.browser_not_support') }}
                                            </video>
                                        </div>
                                    @endif
                                @break
                            @endswitch
                        </div>
                    @endif

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('student.assignments.show', $assignment->id) }}"
                            class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
                        </a>
                        @if (count($status['missing_types']) > 0)
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="bi bi-upload mr-2"></i>{{ __('general.submit') }}
                                </span>
                                <span wire:loading>
                                    <i class="bi bi-hourglass-split mr-2"></i>{{ __('general.loading') }}
                                </span>
                            </button>
                        @else
                            <div class="text-center">
                                <button type="button" class="btn btn-success" disabled>
                                    <i class="bi bi-check-circle mr-2"></i>{{ __('general.submitted_all_assignments') }}
                                </button>
                                <div class="small text-muted mt-1">{{ __('general.completed_all_required_types') }}</div>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-student>
