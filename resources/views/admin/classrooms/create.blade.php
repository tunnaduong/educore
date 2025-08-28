<x-layouts.dash-admin active="classrooms">
    @include('components.language')
    <style>
        /* Days Selector Styling */
        .days-selector {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .day-item {
            position: relative;
        }

        .day-checkbox {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .day-label {
            display: inline-block;
            padding: 8px 16px;
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            color: #6c757d;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            user-select: none;
            min-width: 80px;
            text-align: center;
        }

        .day-label:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .day-checkbox:checked+.day-label {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
        }

        .day-checkbox:checked+.day-label:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .day-checkbox:focus+.day-label {
            outline: 2px solid #80bdff;
            outline-offset: 2px;
        }

        /* Animation when checking */
        .day-checkbox:checked+.day-label {
            animation: checkBounce 0.3s ease;
        }

        @keyframes checkBounce {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .days-selector {
                gap: 6px;
            }

            .day-label {
                padding: 6px 12px;
                font-size: 13px;
                min-width: 70px;
            }
        }

        /* Teacher Dropdown Styling */
        .dropdown-menu {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 8px 16px;
            transition: background-color 0.2s ease;
            cursor: pointer;
            color: #495057 !important;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa !important;
            color: #495057 !important;
        }

        .dropdown-item:focus {
            background-color: #f8f9fa !important;
            color: #495057 !important;
        }

        .dropdown-item:active {
            background-color: #e9ecef !important;
            color: #495057 !important;
        }

        .dropdown-item input[type="checkbox"] {
            transform: scale(1.1);
            margin-right: 8px;
        }

        .dropdown-toggle::after {
            display: none;
        }

        /* Custom button styling for teacher selector */
        .btn-outline-secondary {
            border-color: #ced4da;
            color: #495057;
        }

        .btn-outline-secondary:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
        }

        .btn-outline-secondary:focus {
            box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
        }

        /* Rotation animation for chevron */
        .fa-chevron-down {
            transition: transform 0.3s ease;
        }

        .fa-rotate-180 {
            transform: rotate(180deg);
        }
    </style>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('classrooms.index') }}" class="text-decoration-none text-secondary d-inline-block mb-3">
                <i class="bi bi-arrow-left mr-2"></i>@lang('general.back')
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-book mr-2"></i>@lang('general.create_new_classroom')
            </h4>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form Card Centered with Illustration -->
        <div class="card shadow-sm p-0">
            <div class="row g-0 align-items-stretch">
                <div class="col-md-7 p-4">
                    <form wire:submit="save">
                        <!-- Thông tin cơ bản -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">@lang('general.classroom_information')</h5>
                            <div class="mb-3">
                                <label for="name" class="form-label">@lang('general.classroom_name') <span
                                        class="text-danger">*</span></label>
                                <input wire:model="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" id="name"
                                    placeholder="@lang('general.example_class_name')">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="level" class="form-label">@lang('general.level') <span
                                        class="text-danger">*</span></label>
                                <select wire:model="level" class="form-control @error('level') is-invalid @enderror"
                                    id="level">
                                    <option value="">@lang('general.choose_level')</option>
                                    <option value="HSK1">HSK 1</option>
                                    <option value="HSK2">HSK 2</option>
                                    <option value="HSK3">HSK 3</option>
                                    <option value="HSK4">HSK 4</option>
                                    <option value="HSK5">HSK 5</option>
                                    <option value="HSK6">HSK 6</option>
                                    <option value="Sơ cấp 1">@lang('general.basic1')</option>
                                    <option value="Sơ cấp 2">@lang('general.basic2')</option>
                                    <option value="Trung cấp 1">@lang('general.intermediate1')</option>
                                    <option value="Trung cấp 2">@lang('general.intermediate2')</option>
                                    <option value="Cao cấp 1">@lang('general.advanced1')</option>
                                    <option value="Cao cấp 2">@lang('general.advanced2')</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="teacher_ids" class="form-label">@lang('general.teacher') <span
                                        class="text-danger">*</span></label>
                                <div class="dropdown" x-data="{ open: false }" @click.away="open = false">
                                    <button
                                        class="form-control w-100 d-flex justify-content-between align-items-center text-left"
                                        type="button" @click="open = !open" aria-haspopup="true" style="height: 48px;">
                                        <span class="text-truncate">
                                            @if (count($teacher_ids))
                                                {{ collect($teachers)->whereIn('id', $teacher_ids)->pluck('name')->join(', ') }}
                                            @else
                                                @lang('general.select_teacher')
                                            @endif
                                        </span>
                                        <span class="ml-2"><i class="fas fa-chevron-down"
                                                :class="{ 'fa-rotate-180': open }"></i></span>
                                    </button>
                                    <div class="dropdown-menu w-100" :class="{ 'show': open }"
                                        style="max-height: 300px; overflow-y: auto;" @click.stop>
                                        @foreach ($teachers as $teacher)
                                            <label class="dropdown-item mb-0 d-flex align-items-center" @click.stop>
                                                <input type="checkbox" value="{{ $teacher->id }}"
                                                    wire:model.live="teacher_ids" class="mr-2" @click.stop>
                                                <span>{{ $teacher->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('teacher_ids')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">@lang('general.status') <span
                                        class="text-danger">*</span></label>
                                <select wire:model="status" class="form-control @error('status') is-invalid @enderror"
                                    id="status">
                                    <option value="draft">@lang('general.draft')</option>
                                    <option value="active">@lang('general.active')</option>
                                    <option value="inactive">@lang('general.inactive')</option>
                                    <option value="completed">@lang('general.completed')</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">@lang('general.study_days') <span class="text-danger">*</span></label>
                                <div class="days-selector">
                                    @foreach ([
        'Monday' => __('general.monday'),
        'Tuesday' => __('general.tuesday'),
        'Wednesday' => __('general.wednesday'),
        'Thursday' => __('general.thursday'),
        'Friday' => __('general.friday'),
        'Saturday' => __('general.saturday'),
        'Sunday' => __('general.sunday'),
    ] as $value => $label)
                                        <div class="day-item">
                                            <input wire:model="days" class="day-checkbox" type="checkbox"
                                                value="{{ $value }}" id="day_{{ $value }}">
                                            <label class="day-label" for="day_{{ $value }}">
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('days')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Thời gian học -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="startTime" class="form-label">@lang('general.start_time') <span
                                            class="text-danger">*</span></label>
                                    <input type="time" wire:model="startTime"
                                        class="form-control @error('startTime') is-invalid @enderror" id="startTime">
                                    @error('startTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="endTime" class="form-label">@lang('general.end_time') <span
                                            class="text-danger">*</span></label>
                                    <input type="time" wire:model="endTime"
                                        class="form-control @error('endTime') is-invalid @enderror" id="endTime">
                                    @error('endTime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cảnh báo trùng lịch real-time -->
                            @if($realTimeValidation && !empty($teacherConflicts))
                                <div class="mb-3">
                                    <div class="alert alert-warning border-warning">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-exclamation-triangle-fill text-warning me-2 mt-1"></i>
                                            <div>
                                                <strong>@lang('general.schedule_conflict_warning')</strong>
                                                <div class="small mt-1">
                                                    @lang('general.conflict_detected_message')
                                                </div>
                                                <div class="mt-2">
                                                    @foreach($teacherConflicts as $teacherId => $conflictData)
                                                        <div class="small text-danger mb-1">
                                                            <strong>{{ $conflictData['teacher']->name }}:</strong>
                                                            @foreach($conflictData['conflicts'] as $conflict)
                                                                {{ $conflict['message'] }}
                                                                @if($conflict['overlapTime'])
                                                                    (@lang('general.overlap_time'): {{ $conflict['overlapTime'] }})
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="notes" class="form-label">@lang('general.description')</label>
                                <textarea wire:model="notes" class="form-control @error('notes') is-invalid @enderror" id="notes" rows="3"
                                    placeholder="@lang('general.enter_classroom_description')"></textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('classrooms.index') }}" class="btn btn-light">@lang('general.cancel')</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-book mr-2"></i>@lang('general.create_classroom')
                            </button>
                        </div>
                    </form>
                </div>
                <div
                    class="col-md-5 d-flex flex-column justify-content-center align-items-center bg-light border-start rounded-end p-4">
                    <img src="/educore-logo.png" alt="@lang('general.create_new_classroom')" class="mb-3" style="max-width: 90px;">
                    <div class="text-center">
                        <h6 class="text-primary fw-bold mb-2">@lang('general.create_new_classroom')</h6>
                        <p class="text-muted small mb-0">@lang('general.create_classroom_description')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cảnh báo trùng lịch giáo viên -->
    @if($showConflictModal)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle-fill mr-2"></i>
                        @lang('general.teacher_conflict_warning_title')
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeConflictModal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <strong>@lang('general.detected_teacher_conflicts_title')</strong> @lang('general.detected_teacher_conflicts_desc')
                    </div>

                    <div class="conflicts-list" style="max-height: 400px; overflow-y: auto;">
                        @foreach($teacherConflicts as $teacherId => $conflictData)
                            <div class="card border-warning mb-3">
                                <div class="card-header bg-warning bg-opacity-10">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-circle text-warning me-2"></i>
                                        <strong>{{ $conflictData['teacher']->name }}</strong>
                                        <span class="badge bg-warning text-dark ml-auto">@lang('general.teacher')</span>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h6 class="text-danger mb-2">@lang('general.conflicting_classes')</h6>
                                    @foreach($conflictData['conflicts'] as $conflict)
                                        <div class="alert alert-danger py-2 mb-2">
                                            <div class="d-flex align-items-start">
                                                <i class="bi bi-calendar-x text-danger me-2 mt-1"></i>
                                                <div>
                                                    <strong>{{ $conflict['classroom']->name }}</strong>
                                                    <div class="small text-muted mt-1">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $conflict['message'] }}
                                                    </div>
                                                    @if($conflict['overlapTime'])
                                                        <div class="small text-danger mt-1">
                                                            <i class="bi bi-exclamation-circle mr-1"></i>
                                                            @lang('general.overlap_time'): {{ $conflict['overlapTime'] }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle mr-2"></i>
                        <strong>@lang('general.note')</strong> @lang('general.you_may_force_create_warning')
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeConflictModal">
                        <i class="bi bi-x-circle mr-2"></i>@lang('general.cancel')
                    </button>
                    <button type="button" class="btn btn-warning" wire:click="forceCreate">
                        <i class="bi bi-exclamation-triangle mr-2"></i>@lang('general.force_create_classroom')
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show"></div>
    @endif
</x-layouts.dash-admin>
