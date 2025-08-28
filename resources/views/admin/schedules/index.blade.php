<x-layouts.dash-admin active="schedules">
    @include('components.language')

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="d-flex align-items-center fs-4 text-primary mb-0">
                <i class="bi bi-calendar3 mr-2 text-primary"></i>
                {{ __('general.schedule_management') }}
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('schedules.calendar') }}" class="btn btn-success">
                    <i class="bi bi-calendar-week mr-2"></i>{{ __('views.view_teaching_schedule') }}
                </a>
                <a href="{{ route('schedules.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_schedule') }}
                </a>
            </div>
        </div>

        <!-- Bộ lọc -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">{{ __('general.search_classroom') }}</label>
                        <input type="text" wire:model.live="search" class="form-control" id="search"
                            placeholder="{{ __('general.enter_classroom_name') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="filterLevel" class="form-label">{{ __('general.level') }}</label>
                        <select wire:model.live="filterLevel" class="form-control" id="filterLevel">
                            <option value="">{{ __('general.all_levels') }}</option>
                            @foreach ($levels as $level)
                                <option value="{{ $level }}">{{ $level }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterTeacher" class="form-label">{{ __('general.teacher') }}</label>
                        <select wire:model.live="filterTeacher" class="form-control" id="filterTeacher">
                            <option value="">{{ __('general.all_teachers') }}</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->name }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise mr-1"></i>{{ __('general.refresh') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng lịch học -->
        <div class="card">
            <div class="card-body">
                @if ($classrooms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.classroom') }}</th>
                                    <th>{{ __('general.level') }}</th>
                                    <th>{{ __('general.teacher') }}</th>
                                    <th>{{ __('general.schedule') }}</th>
                                    <th>{{ __('general.student_count') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                    <th>{{ __('general.operations') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classrooms as $classroom)
                                    <tr>
                                        <td>
                                            <strong>{{ $classroom->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $classroom->level }}</span>
                                        </td>
                                        <td>
                                            @if ($classroom->teachers->count())
                                                <div class="mb-2">
                                                    <i class="bi bi-person-circle mr-2"></i>
                                                    {{ $classroom->teachers->pluck('name')->join(', ') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-calendar3 mr-2 text-primary"></i>
                                                <span>{{ $this->formatSchedule($classroom->schedule) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $classroom->students->count() }}
                                                {{ __('general.students') }}</span>
                                        </td>
                                        <td>
                                            @if ($classroom->status === 'active')
                                                <span class="badge bg-success">{{ __('general.active_status') }}</span>
                                            @elseif($classroom->status === 'inactive')
                                                <span
                                                    class="badge bg-warning">{{ __('general.inactive_status') }}</span>
                                            @elseif($classroom->status === 'draft')
                                                <span class="badge bg-secondary">{{ __('general.draft') }}</span>
                                            @elseif($classroom->status === 'completed')
                                                <span class="badge bg-info">{{ __('general.completed') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('general.' . $classroom->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-x-2">
                                                <a href="{{ route('schedules.show', $classroom) }}"
                                                    class="btn btn-sm btn-outline-primary"
                                                    title="{{ __('general.view_details') }}">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="{{ route('schedules.edit', $classroom) }}"
                                                    class="btn btn-sm btn-outline-warning"
                                                    title="{{ __('general.edit') }}">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang -->
                    <div>
                        {{ $classrooms->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                        <h5 class="mt-3 text-muted">{{ __('general.no_schedules_found') }}</h5>
                        <p class="text-muted">{{ __('general.try_changing_filters') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
