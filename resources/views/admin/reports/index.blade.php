<x-layouts.dash-admin active="reports">
    @include('components.language')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-bar-chart mr-2"></i>{{ __('general.reports_and_statistics') }}
            </h4>
            <p class="text-muted mb-0">{{ __('general.reports_description') }}</p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">{{ __('general.select_class') }}</label>
                    <div class="input-group">
                        <select wire:model.live="selectedClass" class="form-control">
                            <option value="">{{ __('general.all_classes') }}</option>
                            @foreach ($classrooms as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                        @if ($selectedClass)
                            <a href="{{ route('reports.class', $selectedClass) }}"
                                class="btn btn-outline-primary ml-2">{{ __('views.class_report_button') }}</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('views.search_student') }}</label>
                    <select wire:model.live="selectedStudent" class="form-control">
                        <option value="">{{ __('views.all_students') }}</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise mr-1"></i>{{ __('general.reset') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('views.student') }}</th>
                            <th>{{ __('views.class') }}</th>
                            <th>{{ __('views.learning_progress') }}</th>
                            <th>{{ __('views.average_score') }}</th>
                            <th>{{ __('views.submission_rate') }}</th>
                            <th>{{ __('views.attendance_count') }}</th>
                            <th>{{ __('views.support_suggestions') }}</th>
                            <th>{{ __('views.details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $row)
                            <tr>
                                <td>{{ $row['student_name'] }}</td>
                                <td>
                                    @if (isset($row['class_names']) && count($row['class_names']))
                                        @foreach ($row['class_names'] as $cname)
                                            <span class="badge bg-secondary mr-1">{{ $cname }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="height: 18px;">
                                        <div class="progress-bar bg-success" role="progressbar"
                                            style="width: {{ $row['progress'] }}%">{{ $row['progress'] }}%</div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">{{ $row['avg_score'] }}</span></td>
                                <td>{{ $row['submit_rate'] }}%</td>
                                <td>{{ $row['attendance_count'] }}</td>
                                <td>
                                    @if ($row['need_support'])
                                        <span class="badge bg-danger">{{ __('views.needs_support') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('views.stable') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('reports.student', $row['student_id']) }}"
                                        class="btn btn-sm btn-outline-primary">{{ __('views.view') }}</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">{{ __('views.no_data_available') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
