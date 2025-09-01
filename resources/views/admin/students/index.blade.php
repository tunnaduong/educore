<x-layouts.dash-admin active="students">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-people-fill mr-2"></i>{{ __('general.student_management') }}
            </h4>
            <a href="{{ route('students.create') ?? '#' }}" class="btn btn-primary">
                <i class="bi bi-plus-circle mr-2"></i>{{ __('general.add_student') }}
            </a>
        </div>

        <!-- Success/Error Alerts -->
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle mr-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('message'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle mr-2"></i>
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Search and Filter Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-search"></i>
                            </span>
                            <input wire:model.live="search" type="text" class="form-control"
                                placeholder="{{ __('general.search_by_name_email_phone') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="statusFilter" class="form-control">
                            <option value="">{{ __('general.status_filter') }}</option>
                            <option value="new">{{ __('general.new_registration') }}</option>
                            <option value="active">{{ __('general.studying') }}</option>
                            <option value="paused">{{ __('general.paused') }}</option>
                            <option value="suspended">{{ __('general.suspended') }}</option>
                            <option value="dropped">{{ __('general.reserved') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="classroomFilter" class="form-control">
                            <option value="">{{ __('general.classroom_filter') }}</option>
                            @foreach ($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button wire:click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-arrow-clockwise mr-1"></i>{{ __('general.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>{{ __('general.student') }}</th>
                                <th>{{ __('general.contact_information') }}</th>
                                <th>{{ __('general.enrolled_classes') }}</th>
                                <th class="text-center">{{ __('general.status') }}</th>
                                <th class="text-center">{{ __('general.progress') }}</th>
                                <th class="text-end">{{ __('general.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $index => $student)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm mr-3">
                                                <i class="bi bi-person-circle fs-4 text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $student->name }}</div>
                                                <small class="text-muted">
                                                    @if ($student->studentProfile && $student->studentProfile->date_of_birth)
                                                        {{ \Carbon\Carbon::parse($student->studentProfile->date_of_birth)->format('d/m/Y') }}
                                                    @else
                                                        {{ __('general.not_available') }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="small">
                                                <i class="bi bi-envelope mr-1"></i>{{ $student->email }}
                                            </div>
                                            <div class="small text-muted">
                                                <i
                                                    class="bi bi-telephone mr-1"></i>{{ $student->phone ?? __('general.not_available') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($student->enrolledClassrooms->count() > 0)
                                            @foreach ($student->enrolledClassrooms->take(2) as $classroom)
                                                <span class="badge bg-info mr-1">{{ $classroom->name }}</span>
                                            @endforeach
                                            @if ($student->enrolledClassrooms->count() > 2)
                                                <span
                                                    class="badge bg-secondary">+{{ $student->enrolledClassrooms->count() - 2 }}</span>
                                            @endif
                                        @else
                                            <span
                                                class="text-muted small">{{ __('general.no_classes_registered') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($student->studentProfile)
                                            @php
                                                $statusColors = [
                                                    'new' => 'info',
                                                    'active' => 'success',
                                                    'paused' => 'warning',
                                                    'suspended' => 'secondary',
                                                    'dropped' => 'danger',
                                                ];
                                                $statusLabels = [
                                                    'new' => __('general.new_registration'),
                                                    'active' => __('general.studying'),
                                                    'paused' => __('general.paused'),
                                                    'suspended' => __('general.suspended'),
                                                    'dropped' => __('general.reserved'),
                                                ];
                                                $color = $statusColors[$student->studentProfile->status] ?? 'secondary';
                                                $label =
                                                    $statusLabels[$student->studentProfile->status] ??
                                                    __('general.undefined');
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('general.not_available') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="small">
                                            <div class="text-muted">{{ __('general.study_sessions') }}: <span
                                                    class="fw-medium">0</span></div>
                                            <div class="text-muted">{{ __('general.average_score') }}: <span
                                                    class="fw-medium">-</span></div>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('students.show', $student) }}"
                                                class="btn btn-sm btn-outline-info"
                                                title="{{ __('general.view_details') }}">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}"
                                                class="btn btn-sm btn-outline-primary"
                                                title="{{ __('general.edit') }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            @if (!$student->studentProfile || !in_array($student->studentProfile->status, ['active', 'suspended']))
                                                <button type="button" data-toggle="modal"
                                                    data-target="#deleteModal{{ $student->id }}"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="{{ __('general.delete') }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    title="{{ __('general.cannot_delete_active_or_suspended_student') }}" disabled>
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1"
                                    aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">
                                                    {{ __('general.confirm_delete_student') }}</h5>
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                {{ __('general.confirm_delete_student_message', ['name' => $student->name]) }}
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">{{ __('general.cancel') }}</button>
                                                <button type="button" class="btn btn-danger"
                                                    wire:click="delete({{ $student->id }})"
                                                    data-dismiss="modal">{{ __('general.delete') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                                            {{ __('general.no_students_found') }}
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        {{ __('general.showing') }} {{ $students->firstItem() ?? 0 }} -
                        {{ $students->lastItem() ?? 0 }}
                        {{ __('general.of_total_students', ['total' => $students->total() ?? 0]) }}
                    </div>
                    <div>
                        {{ $students->links('vendor.pagination.bootstrap-5') }}
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
