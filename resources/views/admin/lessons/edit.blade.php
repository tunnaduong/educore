<x-layouts.dash-admin active="lessons">
    @include('components.language')
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ route('lessons.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
            </a>
            <h4 class="mb-0 text-success fs-4">
                <i class="bi bi-folder-symlink-fill mr-2"></i>{{ __('views.edit_lesson_title') }}
            </h4>
        </div>
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit.prevent="update">
                        <div class="mb-4">
                            <h5 class="text-success mb-3">{{ __('general.lesson_info') }}</h5>
                            <div class="mb-3">
                                <label for="number" class="form-label">{{ __('general.lesson_number') }} <span
                                        class="text-danger">*</span></label>
                                <input wire:model="number" type="text"
                                    class="form-control @error('number') is-invalid @enderror" id="number"
                                    placeholder="{{ __('views.example_lesson_number') }}">
                                @error('number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="title" class="form-label">{{ __('general.title') }} <span
                                        class="text-danger">*</span></label>
                                <input wire:model="title" type="text"
                                    class="form-control @error('title') is-invalid @enderror" id="title"
                                    placeholder="{{ __('views.enter_lesson_title') }}">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="classroom_id" class="form-label">{{ __('general.classroom') }} <span
                                        class="text-danger">*</span></label>
                                <select wire:model="classroom_id"
                                    class="form-control @error('classroom_id') is-invalid @enderror" id="classroom_id">
                                    <option value="">{{ __('views.select_classroom') }}</option>
                                    @foreach ($classrooms as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                                @error('classroom_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">{{ __('general.description') }}</label>
                                <div wire:ignore>
                                    <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" id="description"
                                        rows="3" placeholder="{{ __('views.enter_lesson_description') }}"></textarea>
                                </div>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="video" class="form-label">{{ __('views.video_link_label') }}</label>
                                <input wire:model="video" type="text"
                                    class="form-control @error('video') is-invalid @enderror" id="video"
                                    placeholder="{{ __('views.paste_lesson_video_link') }}">
                                @error('video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="attachment" class="form-label">{{ __('views.attachment_label') }}</label>
                                <input wire:model="attachment" type="file"
                                    class="form-control @error('attachment') is-invalid @enderror" id="attachment">
                                @if ($oldAttachment)
                                    <div class="mt-2"><a href="{{ asset('storage/' . $oldAttachment) }}"
                                            target="_blank">{{ __('views.current_document') }}</a></div>
                                @endif
                                @error('attachment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('lessons.index') }}"
                                class="btn btn-light">{{ __('general.cancel') }}</a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save mr-2"></i>{{ __('views.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="{{ __('views.edit_lesson_title') }}" class="mb-3"
                        style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-success fw-bold mb-2">{{ __('views.edit_lesson_title') }}</h6>
                        <p class="text-muted small mb-0">{{ __('views.update_lesson_info_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
