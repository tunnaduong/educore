<x-layouts.dash-admin active="classrooms" title="@lang('general.manage_classrooms')">
    @include('components.language')
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
                                <option value="active">@lang('general.active')</option>
                                <option value="inactive">@lang('general.inactive')</option>
                            </select>
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
                                                    <small class="text-muted">{{ $classroom->notes }}</small>
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
                                            <span
                                                class="badge badge-{{ $classroom->status == 'active' ? 'success' : 'secondary' }}">
                                                {{ $classroom->status == 'active' ? __('general.active') : __('general.inactive') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <a href="{{ route('classrooms.show', $classroom) }}" wire:navigate
                                                    class="btn btn-sm btn-outline-secondary" title="@lang('general.view_details')">
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
                                                <button type="button" data-toggle="modal"
                                                    data-target="#deleteModal{{ $classroom->id }}"
                                                    class="btn btn-sm btn-outline-danger" title="@lang('general.delete')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteModal{{ $classroom->id }}" tabindex="-1"
                                        aria-labelledby="deleteModalLabel{{ $classroom->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $classroom->id }}">
                                                        @lang('general.confirm_delete_classroom')
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @lang('general.delete_classroom_message', ['name' => $classroom->name])
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">@lang('general.cancel')</button>
                                                    <button type="button" class="btn btn-danger" id="confirmDelete"
                                                        wire:click="delete({{ $classroom->id }})"
                                                        data-dismiss="modal">@lang('general.delete')</button>
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
</x-layouts.dash-admin>
