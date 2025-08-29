<x-layouts.dash-teacher active="lessons">
    @include('components.language')

    @push('styles')
        <style>
            .cke_notifications_area {
                display: none;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
        <script>
            let editor = null;

            function initCKEditor() {
                if (typeof CKEDITOR !== 'undefined' && !editor) {
                    editor = CKEDITOR.replace('description', {
                        height: 200,
                        removePlugins: 'elementspath,resize',
                        toolbar: [{
                                name: 'document',
                                items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-',
                                    'Templates'
                                ]
                            },
                            {
                                name: 'clipboard',
                                items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo',
                                    'Redo'
                                ]
                            },
                            {
                                name: 'editing',
                                items: ['Find', 'Replace', '-', 'SelectAll', '-', 'SpellChecker', 'Scayt']
                            },
                            '/',
                            {
                                name: 'basicstyles',
                                items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript',
                                    '-', 'RemoveFormat'
                                ]
                            },
                            {
                                name: 'paragraph',
                                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-',
                                    'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter',
                                    'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'
                                ]
                            },
                            {
                                name: 'links',
                                items: ['Link', 'Unlink', 'Anchor']
                            },
                            {
                                name: 'insert',
                                items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley',
                                    'SpecialChar', 'PageBreak', 'Iframe'
                                ]
                            },
                            '/',
                            {
                                name: 'styles',
                                items: ['Styles', 'Format', 'Font', 'FontSize']
                            },
                            {
                                name: 'colors',
                                items: ['TextColor', 'BGColor']
                            },
                            {
                                name: 'tools',
                                items: ['Maximize', 'ShowBlocks']
                            }
                        ]
                    });

                    // Đồng bộ dữ liệu từ CKEditor về Livewire
                    editor.on('change', function() {
                        @this.set('description', editor.getData());
                    });
                }
            }

            $(document).ready(function() {
                initCKEditor();
            });

            // Khôi phục CKEditor sau khi Livewire reload
            document.addEventListener('livewire:load', function() {
                initCKEditor();
            });

            document.addEventListener('livewire:update', function() {
                setTimeout(function() {
                    initCKEditor();
                }, 100);
            });
        </script>
    @endpush

    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-warning fs-4">
                        <i class="bi bi-pencil-square mr-2"></i>{{ __('general.edit_lesson') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.update_lesson_info_desc') }}</p>
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
                    <i class="bi bi-file-earmark-text mr-2"></i>{{ __('general.lesson_info') }}
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
                            <label for="title" class="form-label">{{ __('general.lesson_title') }} <span
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
                            <label for="classroom_id" class="form-label">{{ __('general.class') }} <span
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
                        </div>
                        <div class="col-12">
                            <label for="description" class="form-label">{{ __('general.description') }}</label>
                            <div wire:ignore>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"
                                    rows="4" placeholder="{{ __('general.lesson_description_placeholder') }}"></textarea>
                            </div>
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
                                • {{ __('general.youtube_video') }}: https://youtube.com/watch?v=VIDEO_ID {{ __('general.or') }} https://youtu.be/VIDEO_ID<br>
                                • {{ __('general.google_drive_video') }}: https://drive.google.com/file/d/FILE_ID/view<br>
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
                            <small class="form-text text-muted">{{ __('general.attachment_supported_max_10mb') }}</small>

                            @if ($currentAttachment)
                                <div class="mt-2">
                                    <small class="text-info">
                                        <i class="bi bi-file-earmark mr-1"></i>
                                        {{ __('general.current_file') }}: <a href="{{ asset('storage/' . $currentAttachment) }}"
                                            target="_blank">{{ basename($currentAttachment) }}</a>
                                    </small>
                                </div>
                            @endif

                            @if ($attachment)
                                <div class="mt-2">
                                    <div class="alert alert-info">
                                        <i class="bi bi-file-earmark mr-2"></i>
                                        <strong>{{ __('general.new_selected_file') }}:</strong> {{ $attachment->getClientOriginalName() }}
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
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-check-circle mr-1"></i>{{ __('general.update_lesson') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
