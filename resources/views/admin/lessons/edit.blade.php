<x-layouts.dash-admin active="lessons">
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
        <div class="mb-4">
            <a href="{{ route('lessons.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
            </a>
            <h4 class="mb-0 text-success fs-4">
                <i class="bi bi-folder-symlink-fill mr-2"></i>{{ __('views.edit_lesson_title') }}
            </h4>
        </div>
        <!-- Success/Error Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

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
                            <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                                <i class="bi bi-save mr-2"></i>
                                <span wire:loading.remove>{{ __('views.save_changes') }}</span>
                                <span wire:loading>{{ __('views.updating') }}...</span>
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

    <!-- Toast Notifications -->
    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismissible="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif
</x-layouts.dash-admin>
