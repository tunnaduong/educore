<x-layouts.dash-teacher active="assignments">
    @include('components.language')
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('teacher.assignments.index') }}" class="text-decoration-none text-secondary">
                <i class="bi bi-arrow-left mr-1"></i>{{ __('general.back') }}
            </a>
            <h4 class="mt-2 text-primary fs-4"><i class="bi bi-journal-text mr-2"></i>{{ __('general.edit_assignment') }}
            </h4>
        </div>

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form wire:submit.prevent="updateAssignment">
                    <!-- Bài tập -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label for="title" class="form-label fw-semibold">{{ __('general.title') }} *</label>
                            <input wire:model.defer="title" type="text"
                                class="form-control @error('title') is-invalid @enderror" id="title"
                                placeholder="{{ __('general.assignment_title_example') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="class_id" class="form-label fw-semibold">{{ __('general.classroom') }} *</label>
                            <select wire:model.defer="class_id"
                                class="form-control @error('class_id') is-invalid @enderror" id="class_id">
                                <option value="">{{ __('general.select_class') }}</option>
                                @foreach ($classrooms as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->level }})
                                    </option>
                                @endforeach
                            </select>
                            @error('class_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Hạn nộp & điểm -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="deadline" class="form-label fw-semibold">{{ __('general.deadline') }} *</label>
                            <input wire:model.defer="deadline" type="datetime-local"
                                class="form-control @error('deadline') is-invalid @enderror" id="deadline">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="max_score" class="form-label fw-semibold">{{ __('general.max_score_optional') }}</label>
                            <input wire:model.defer="max_score" type="number" class="form-control" id="max_score"
                                placeholder="{{ __('general.eg_10') }}" min="0" max="10" step="0.1"
                                oninput="if(this.value > 10) this.value = 10; if(this.value < 0) this.value = 0;"
                                onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode === 46 || event.charCode === 8 || event.charCode === 9">
                        </div>
                    </div>

                    <!-- Loại bài tập -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">{{ __('general.assignment_types') }} *</label>
                        <div class="d-flex flex-wrap gap-3">
                            @foreach ($allTypes as $key => $label)
                                <div class="form-check">
                                    <input wire:model.defer="types" class="form-check-input" type="checkbox"
                                        value="{{ $key }}" id="type_{{ $key }}">
                                    <label class="form-check-label"
                                        for="type_{{ $key }}">{{ __($label) }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('types')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- File & mô tả -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="attachment" class="form-label">{{ __('general.attachment') }}</label>
                            @if ($old_attachment_path)
                                <div class="small text-success mt-1">
                                    <a href="{{ asset('storage/' . $old_attachment_path) }}" target="_blank"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-file-earmark-arrow-down"></i> {{ __('general.current_file') }}
                                    </a>
                                </div>
                            @endif
                            <input wire:model="attachment" type="file"
                                class="form-control @error('attachment') is-invalid @enderror" id="attachment">
                            @if ($attachment)
                                <div class="small text-success mt-1">{{ __('general.file_type') }}: {{ $attachment->getClientOriginalName() }}
                                </div>
                            @endif
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="video" class="form-label">{{ __('general.video_max_100mb') }}</label>
                            @if ($old_video_path)
                                <div class="small text-success mt-1">
                                    <video width="240" height="135" controls>
                                        <source src="{{ asset('storage/' . $old_video_path) }}" type="video/mp4">
                                        {{ __('general.browser_not_support') }}
                                    </video>
                                </div>
                            @endif
                            <input wire:model="video" type="file" accept="video/*"
                                class="form-control @error('video') is-invalid @enderror" id="video">
                            @if ($video)
                                <div class="small text-success mt-1">{{ __('general.video') }}: {{ $video->getClientOriginalName() }}</div>
                            @endif
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-4">
                        <label for="description" class="form-label">{{ __('general.instructions_or_description') }}</label>
                        <textarea wire:model.defer="description" rows="4" class="form-control @error('description') is-invalid @enderror"
                            id="description" placeholder="{{ __('general.assignment_description_example') }}">{{ $description }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.assignments.index') }}" class="btn btn-outline-secondary">{{ __('general.cancel') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send mr-1"></i> {{ __('general.update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
