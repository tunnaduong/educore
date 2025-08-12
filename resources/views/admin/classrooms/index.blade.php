<x-layouts.dash-admin active="classrooms" title="@lang('general.manage_classrooms')">
    @include('components.language')

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">
                    <i class="fas fa-graduation-cap mr-2"></i>@lang('general.manage_classrooms')
                </h4>
                <a href="{{ route('classrooms.create') ?? '#' }}" wire:navigate class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>@lang('general.add_classroom')
                </a>
            </div>

            <!-- Search Bar & Filters -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('general.search_and_filter')</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input wire:model.live="search" type="text" class="form-control"
                                    placeholder="@lang('general.search_classroom_placeholder')">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterTeacher" class="form-control">
                                <option value="">@lang('general.all_teachers')</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select wire:model.live="filterStatus" class="form-control">
                                <option value="">@lang('general.all_status')</option>
                                <option value="draft">@lang('general.draft')</option>
                                <option value="active">@lang('general.active')</option>
                                <option value="inactive">@lang('general.inactive')</option>
                                <option value="completed">@lang('general.completed')</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.live="showTrashed"
                                    id="showTrashed">
                                <label class="form-check-label" for="showTrashed">
                                    Hiện lớp đã ẩn
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.live="hideCompleted"
                                    id="hideCompleted">
                                <label class="form-check-label" for="hideCompleted">
                                    Ẩn lớp đã hoàn thành
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Classrooms Table -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('general.classroom_list')</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 50px;">#</th>
                                    <th>@lang('general.classroom_name')</th>
                                    <th>@lang('general.teacher')</th>
                                    <th class="text-center" style="width: 100px;">@lang('general.student_count')</th>
                                    <th class="text-center" style="width: 120px;">@lang('general.status')</th>
                                    <th class="text-center" style="width: 200px;">@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classrooms as $index => $classroom)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3">
                                                    <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">{{ $classroom->name }}</div>
                                                    @if (isset($classroom->notes) && $classroom->notes)
                                                        <small class="text-muted">{{ $classroom->notes }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($classroom->teachers->count())
                                                @foreach ($classroom->teachers as $teacher)
                                                    <span class="badge badge-secondary">{{ $teacher->name }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">@lang('general.no_teacher')</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info">{{ $classroom->students_count }}</span>
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $statusClass = match ($classroom->status) {
                                                    'draft' => 'light',
                                                    'active' => 'success',
                                                    'inactive' => 'secondary',
                                                    'completed' => 'warning',
                                                    default => 'secondary',
                                                };

                                                $statusText = match ($classroom->status) {
                                                    'draft' => __('general.draft'),
                                                    'active' => __('general.active'),
                                                    'inactive' => __('general.inactive'),
                                                    'completed' => __('general.completed'),
                                                    default => __('general.inactive'),
                                                };
                                            @endphp
                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('classrooms.show', $classroom) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="@lang('general.view_details')">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('classrooms.attendance', $classroom) }}"
                                                    wire:navigate class="btn btn-sm btn-outline-info"
                                                    title="@lang('general.take_attendance')">
                                                    <i class="fas fa-calendar-check"></i>
                                                </a>
                                                <a href="{{ route('classrooms.attendance-history', $classroom) }}"
                                                    wire:navigate class="btn btn-sm btn-outline-secondary"
                                                    title="@lang('general.attendance_history')">
                                                    <i class="fas fa-calendar-week"></i>
                                                </a>
                                                <a href="{{ route('classrooms.assign-students', $classroom) }}"
                                                    wire:navigate class="btn btn-sm btn-outline-success"
                                                    title="@lang('general.assign_students')">
                                                    <i class="fas fa-user-plus"></i>
                                                </a>
                                                <a href="{{ route('classrooms.edit', $classroom) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-primary" title="@lang('general.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                @if ($showTrashed && !is_null($classroom->deleted_at))
                                                    <button type="button" class="btn btn-sm btn-outline-success"
                                                        wire:click="restore({{ $classroom->id }})"
                                                        title="Khôi phục lớp học">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                @else
                                                    @if ($classroom->status === 'active')
                                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                                            title="@lang('general.cannot_delete_active_classroom')" disabled>
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    @elseif ($classroom->students_count > 0)
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#deleteModal{{ $classroom->id }}"
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="@lang('general.hide_classroom_with_students')">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    @elseif ($classroom->status === 'draft' && $classroom->students_count == 0)
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#deleteModal{{ $classroom->id }}"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="@lang('general.delete_draft_classroom')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @elseif ($classroom->status === 'completed')
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#deleteModal{{ $classroom->id }}"
                                                            class="btn btn-sm btn-outline-warning"
                                                            title="Ẩn lớp đã hoàn thành">
                                                            <i class="fas fa-eye-slash"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#deleteModal{{ $classroom->id }}"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="@lang('general.delete')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $classroom->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $classroom->id }}" aria-hidden="true">
                                        <div
                                            class="modal-dialog modal-dialog-scrollable modal-dialog modal-dialog-scrollable-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="deleteModalLabel{{ $classroom->id }}">
                                                        @if ($classroom->status === 'draft' && $classroom->students_count == 0)
                                                            @lang('general.confirm_delete_draft_classroom')
                                                        @elseif ($classroom->students_count > 0)
                                                            @lang('general.confirm_hide_classroom')
                                                        @else
                                                            @lang('general.confirm_delete_classroom')
                                                        @endif
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if ($classroom->status === 'draft' && $classroom->students_count == 0)
                                                        @lang('general.delete_draft_classroom_message', ['name' => $classroom->name])
                                                    @elseif ($classroom->students_count > 0)
                                                        @lang('general.hide_classroom_message', ['name' => $classroom->name])
                                                    @else
                                                        @lang('general.delete_classroom_message', ['name' => $classroom->name])
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">@lang('general.cancel')</button>
                                                    <button type="button"
                                                        class="btn btn-{{ ($classroom->status === 'draft' && $classroom->students_count == 0) || $classroom->students_count == 0 ? 'danger' : 'warning' }}"
                                                        id="confirmDelete{{ $classroom->id }}"
                                                        wire:click="delete({{ $classroom->id }})"
                                                        onclick="closeModal({{ $classroom->id }})">
                                                        @if ($classroom->status === 'draft' && $classroom->students_count == 0)
                                                            @lang('general.delete')
                                                        @elseif ($classroom->students_count > 0)
                                                            @lang('general.hide')
                                                        @else
                                                            @lang('general.delete')
                                                        @endif
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            @lang('general.showing_results', [
                                'from' => $classrooms->firstItem() ?? 0,
                                'to' => $classrooms->lastItem() ?? 0,
                                'total' => $classrooms->total() ?? 0,
                            ])
                        </div>
                        <div>
                            {{ $classrooms->links('vendor.pagination.bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function closeModal(classroomId) {
            setTimeout(function() {
                $('#deleteModal' + classroomId).modal('hide');
            }, 100);
        }

        // Lắng nghe sự kiện refresh từ Livewire
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('refresh', () => {
                window.location.reload();
            });
        });
    </script>
</x-layouts.dash-admin>
