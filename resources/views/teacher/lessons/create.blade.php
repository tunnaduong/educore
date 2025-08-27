<x-layouts.dash-teacher active="lessons">
    @include('components.language')
    
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-success fs-4">
                        <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_lesson') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.create_lesson_and_resources_desc') }}</p>
                </div>
                <div>
                    <a href="{{ route('teacher.lessons.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left mr-1"></i>{{ __('general.back') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-file-earmark-plus mr-2"></i>{{ __('general.lesson_info') }}
                </h6>
            </div>
            <div class="card-body">
                @if (session()->has('error'))
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif
                <form wire:submit="save">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">{{ __('general.title') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" wire:model="title" placeholder="{{ __('general.enter_lesson_title') }}">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="number" class="form-label">{{ __('general.lesson_number') }}</label>
                            <input type="text" class="form-control @error('number') is-invalid @enderror"
                                id="number" wire:model="number" placeholder="{{ __('general.lesson_number_example') }}">
                            @error('number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label for="classroom_id" class="form-label">{{ __('general.classroom') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-control @error('classroom_id') is-invalid @enderror" id="classroom_id"
                                wire:model="classroom_id">
                                <option value="">{{ __('general.select_class') }}</option>
                                @foreach ($classrooms as $classroom)
                                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                                @endforeach
                            </select>
                            @error('classroom_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($classrooms->isEmpty())
                                <div class="alert alert-warning mt-2">
                                    <i class="bi bi-exclamation-triangle mr-2"></i>
                                    {{ __('general.no_classes_assigned_contact_admin') }}
                                </div>
                            @else
                                <small class="form-text text-muted">{{ __('general.found_classes', ['count' => $classrooms->count()]) }}</small>
                            @endif
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">{{ __('general.description') }}</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"
                                rows="4" placeholder="{{ __('general.lesson_description_placeholder') }}"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="video" class="form-label">{{ __('general.video_link') }}</label>
                            <input type="url" class="form-control @error('video') is-invalid @enderror"
                                id="video" wire:model="video" placeholder="https://youtube.com/...">
                            @error('video')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <strong>{{ __('general.supported') }}:</strong><br>
                                • YouTube: https://youtube.com/watch?v=VIDEO_ID {{ __('general.or') }} https://youtu.be/VIDEO_ID<br>
                                • Google Drive: https://drive.google.com/file/d/FILE_ID/view<br>
                                • {{ __('general.vimeo_and_other_platforms') }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <label for="attachment" class="form-label">{{ __('general.attachment') }}</label>
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror"
                                id="attachment" wire:model="attachment"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt">
                            @error('attachment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">{{ __('general.supported') }}: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX, TXT ({{ __('general.attachment_supported_max_10mb') }})</small>

                            @if ($attachment)
                                <div class="mt-2">
                                    <div class="alert alert-info">
                                        <i class="bi bi-file-earmark mr-2"></i>
                                        <strong>{{ __('general.selected_file') }}:</strong> {{ $attachment->getClientOriginalName() }}
                                        <br>
                                        <small>{{ __('general.size') }}: {{ number_format($attachment->getSize() / 1024, 2) }}
                                            KB</small>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 d-flex justify-content-end gap-2">
                        <a href="{{ route('teacher.lessons.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle mr-1"></i>{{ __('general.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle mr-1"></i>{{ __('general.save_lesson') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
