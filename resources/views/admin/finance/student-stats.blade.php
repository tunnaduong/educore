<div class="container-fluid">
    @include('components.language')
    <!-- {{ __('views.student_filter_title') }} -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-filter-circle-fill mr-2"></i>{{ __('views.student_filter_title') }}
                @if ($filterClass || $filterStatus || $searchTerm)
                    <span class="badge bg-primary ms-2">{{ __('general.active_filters') }}</span>
                @endif
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-building text-primary mr-1"></i>{{ __('general.filter_by_class') }}
                    </label>
                    <select class="form-control form-control-lg" wire:model.live="filterClass">
                        <option value="">{{ __('general.all_classes') }}</option>
                        @foreach ($students->flatMap(fn($s) => $s['classes'])->unique('class_id') as $class)
                            <option value="{{ $class['class_name'] }}">{{ $class['class_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-cash text-success mr-1"></i>{{ __('views.tuition_status_label') }}
                    </label>
                    <select class="form-control form-control-lg" wire:model.live="filterStatus">
                        <option value="">{{ __('views.all_statuses') }}</option>
                        <option value="paid">
                            <i class="bi bi-check-circle-fill"></i> {{ __('views.paid_full') }}
                        </option>
                        <option value="partial">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ __('views.partial') }}
                        </option>
                        <option value="unpaid">
                            <i class="bi bi-x-circle-fill"></i> {{ __('views.unpaid') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-search text-info mr-1"></i>{{ __('general.search') }}
                    </label>
                    <input type="text" class="form-control form-control-lg"
                        wire:model.live.debounce.300ms="searchTerm" placeholder="{{ __('general.search_students') }}">
                </div>
                <div class="col-md-12 d-flex align-items-end mt-3">
                    <div class="text-muted small mr-auto">
                        <i class="bi bi-info-circle mr-1"></i>
                        {{ __('views.total_students', ['count' => count($students)]) }}
                        @if ($filterClass || $filterStatus || $searchTerm)
                            <span class="text-primary">({{ __('general.filtered_results') }})</span>
                        @endif
                    </div>
                    @if ($filterClass || $filterStatus || $searchTerm)
                        <button class="btn btn-outline-secondary btn-sm" wire:click="resetFilters"
                            wire:loading.attr="disabled">
                            <i class="bi bi-arrow-clockwise mr-1"></i>
                            <span wire:loading.remove>{{ __('general.reset_filters') }}</span>
                            <span wire:loading>{{ __('general.loading') }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('views.student_list_section') }} -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="bi bi-people-fill mr-2"></i>{{ __('views.student_list_and_tuition_status') }}
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="8%">#</th>
                        <th width="25%">
                            <i class="bi bi-person-fill mr-1"></i>{{ __('general.student') }}
                        </th>
                        <th width="30%">
                            <i class="bi bi-building mr-1"></i>{{ __('views.joined_classes') }}
                        </th>
                        <th width="27%">
                            <i class="bi bi-cash mr-1"></i>{{ __('views.tuition_status') }}
                        </th>
                        <th class="text-center" width="10%">
                            <i class="bi bi-gear mr-1"></i>{{ __('general.actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody wire:loading.class="opacity-50">
                    @forelse($students as $student)
                        <tr class="align-middle">
                            <td class="text-center fw-bold text-primary">{{ $student['id'] }}</td>
                            <td>
                                <div class="d-flex align-items-center" style="gap: 10px">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 mr-3 d-flex align-items-center justify-content-center"
                                        style="width: 40px">
                                        <i class="bi bi-person-fill text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $student['name'] }}</div>
                                        <small class="text-muted">ID: {{ $student['id'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach ($student['classes'] as $class)
                                    <span class="badge bg-light text-dark border mr-1 mb-1">
                                        <i class="bi bi-building-fill mr-1"></i>{{ $class['class_name'] }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($student['classes'] as $class)
                                    <div class="mb-1">
                                        @if ($class['status'] === 'paid')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill mr-1"></i>{{ $class['class_name'] }}:
                                                {{ __('views.paid_full') }}
                                            </span>
                                        @elseif ($class['status'] === 'partial')
                                            <span class="badge bg-warning">
                                                <i
                                                    class="bi bi-exclamation-triangle-fill mr-1"></i>{{ $class['class_name'] }}:
                                                {{ __('views.partial') }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle-fill mr-1"></i>{{ $class['class_name'] }}:
                                                {{ __('views.unpaid') }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.finance.payment.show', $student['id']) }}"
                                    class="btn btn-primary btn-sm shadow-sm">
                                    <i class="bi bi-eye-fill mr-1"></i>{{ __('general.details') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                                <div class="mt-2 text-muted fs-5">
                                    @if ($filterClass || $filterStatus || $searchTerm)
                                        {{ __('views.no_students_found_with_filters') }}
                                    @else
                                        {{ __('views.no_students') }}
                                    @endif
                                </div>
                                @if ($filterClass || $filterStatus || $searchTerm)
                                    <small class="text-muted">{{ __('views.try_change_filters') }}</small>
                                    <div class="mt-2">
                                        <button class="btn btn-outline-primary btn-sm" wire:click="resetFilters">
                                            <i class="bi bi-arrow-clockwise mr-1"></i>{{ __('general.reset_filters') }}
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
