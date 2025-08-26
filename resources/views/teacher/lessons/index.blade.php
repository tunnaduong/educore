<x-layouts.dash-teacher active="lessons">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-success fs-4">
                        <i class="bi bi-folder-symlink-fill mr-2"></i>{{ __('general.lessons_resources') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.manage_store_search_lessons') }}</p>
                </div>
                <div>
                    <a href="{{ route('teacher.lessons.create') }}" class="btn btn-success"><i
                            class="bi bi-plus-circle mr-1"></i> {{ __('general.add_lesson') }}</a>
                </div>
            </div>
        </div>
        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-7">
                        <label class="form-label">{{ __('general.search') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('general.search_by_name_or_number') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">{{ __('general.filter_by_class') }}</label>
                        <select class="form-control" wire:model.live="filterClass">
                            <option value="">{{ __('general.all_classes') }}</option>
                            @foreach ($classrooms ?? [] as $classroom)
                                <option value="{{ $classroom->id }}" @selected($filterClass == $classroom->id)>{{ $classroom->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100"
                            wire:click="$set('search', ''); $set('filterClass', '')" type="button">
                            <i class="bi bi-arrow-clockwise mr-2"></i>{{ __('general.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Lesson List -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('general.lessons_resources_list') }}
                </h6>
            </div>
            <div class="card-body">
                @if ($lessons->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.lesson_number') }}</th>
                                    <th>{{ __('general.title') }}</th>
                                    <th>{{ __('general.class') }}</th>
                                    <th>{{ __('general.video') }}</th>
                                    <th>{{ __('general.document') }}</th>
                                    <th>{{ __('general.created_date') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lessons as $lesson)
                                    <tr>
                                        <td><span class="badge bg-info">{{ $lesson->number ?? '-' }}</span></td>
                                        <td>
                                            <div class="fw-medium">{{ $lesson->title }}</div>
                                            <small class="text-muted">{!! Str::limit(strip_tags($lesson->description), 50) !!}</small>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-primary">{{ $lesson->classroom->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if ($lesson->video)
                                                <a href="{{ $lesson->video }}" target="_blank"
                                                    class="badge bg-warning text-dark">Video</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($lesson->attachment)
                                                <a href="{{ asset('storage/' . $lesson->attachment) }}" target="_blank"
                                                    class="badge bg-success">Tài liệu</a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ $lesson->created_at?->format('d/m/Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher.lessons.show', $lesson->id) }}"
                                                class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('teacher.lessons.edit', $lesson->id) }}"
                                                class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                wire:click="confirmDelete({{ $lesson->id }}, '{{ addslashes($lesson->title) }}')"><i
                                                    class="bi bi-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $lessons->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-folder-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Không có bài học nào</h5>
                        <p class="text-muted">Bạn chưa có bài học hoặc tài nguyên nào.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal xác nhận xoá bài học -->
    @if ($showDeleteModal)
        <div class="modal fade show" tabindex="-1" style="display:block; background:rgba(0,0,0,0.3);" aria-modal="true"
            role="dialog">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Xác nhận xoá bài học</h5>
                        <button type="button" class="btn-close" wire:click="cancelDelete"></button>
                    </div>
                    <div class="modal-body">
                        <p>Bạn có chắc chắn muốn xoá bài học "<strong>{{ $lessonTitleToDelete }}</strong>"?<br>Hành
                            động này không thể hoàn tác.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelDelete">Huỷ</button>
                        <button type="button" class="btn btn-danger" wire:click="deleteLesson">Xoá</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-teacher>
