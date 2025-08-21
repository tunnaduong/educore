<x-layouts.dash-admin active="assignments">
    @include('components.language')
    <div class="container-fluid">
        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-journal-text mr-2"></i>{{ __('general.assignment_list') }}
                    </h4>
                    <p class="text-muted mb-0">{{ __('general.manage_all_assignments') }}</p>
                </div>
                <div>
                    <a href="{{ route('assignments.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle mr-2"></i>{{ __('general.create_new_assignment') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">{{ __('general.search') }}</label>
                        <input type="text" class="form-control" id="search" wire:model.live="search"
                            placeholder="{{ __('general.search_by_assignment_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="statusFilter" class="form-label">{{ __('general.status') }}</label>
                        <select class="form-control" id="statusFilter" wire:model.live="statusFilter">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="active">{{ __('general.active') }}</option>
                            <option value="overdue">{{ __('general.overdue') }}</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="classroomFilter" class="form-label">{{ __('general.classroom') }}</label>
                        <select class="form-control" id="classroomFilter" wire:model.live="classroomFilter">
                            <option value="">{{ __('general.all_classes') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-outline-secondary w-100" wire:click="clearFilters">
                            <i class="bi bi-x-circle mr-2"></i>{{ __('general.clear_filters') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments List -->
        <div class="card shadow-sm">
            <div class="card-body">
                @if ($assignments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.title') }}</th>
                                    <th>{{ __('general.classroom') }}</th>
                                    <th>{{ __('general.deadline') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.submissions') }}</th>
                                    <th>{{ __('general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assignments as $assignment)
                                    <tr>
                                        <td>
                                            <div>
                                                <strong>{{ $assignment->title }}</strong>
                                                @if ($assignment->types)
                                                    <div class="small">
                                                        @foreach ($assignment->types as $type)
                                                            <span class="badge bg-secondary mr-1">
                                                                @switch($type)
                                                                    @case('text')
                                                                        Điền từ
                                                                    @break

                                                                    @case('essay')
                                                                        Tự luận
                                                                    @break

                                                                    @case('image')
                                                                        Ảnh
                                                                    @break

                                                                    @case('audio')
                                                                        Âm thanh
                                                                    @break

                                                                    @case('video')
                                                                        Video
                                                                    @break

                                                                    @default
                                                                        {{ $type }}
                                                                @endswitch
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $assignment->classroom?->name ?? 'N/A' }}</td>
                                        <td>
                                            <div>{{ $assignment->deadline->format('d/m/Y H:i') }}</div>
                                            <small
                                                class="text-muted">{{ $assignment->deadline->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            @if ($assignment->deadline > now())
                                                <span class="badge bg-success">Đang hoạt động</span>
                                            @else
                                                <span class="badge bg-danger">Quá hạn</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $assignment->submissions->count() }} bài
                                                nộp</span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('assignments.show', $assignment->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('assignments.edit', $assignment->id) }}"
                                                    class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    wire:click="deleteAssignment({{ $assignment->id }})"
                                                    wire:confirm="Bạn có chắc chắn muốn xóa bài tập '{{ $assignment->title }}'? Hành động này không thể hoàn tác.">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $assignments->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-journal-x fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">Không có bài tập nào</h5>
                        <p class="text-muted">Tạo bài tập đầu tiên để bắt đầu</p>
                        <a href="{{ route('assignments.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle mr-2"></i>Giao bài tập mới
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-4">
            <a href="{{ route('assignments.overview') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại tổng quan
            </a>
        </div>
    </div>
</x-layouts.dash-admin>
