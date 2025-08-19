<x-layouts.dash-admin active="reports">
    <div class="mb-4">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại báo cáo tổng hợp
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-3 text-primary"><i class="bi bi-diagram-3 mr-2"></i>{{ __('views.class_report') }}</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="fw-bold">{{ __('views.class_name') }}:</div>
                    <div>{{ $classroom->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold">{{ __('views.teacher') }}:</div>
                    <div>{{ $classroom->getFirstTeacher() ? $classroom->getFirstTeacher()->name : '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header fw-bold">{{ __('views.student_statistics') }}</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('views.student') }}</th>
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
                                <td colspan="7" class="text-center py-4 text-muted">{{ __('views.no_data_available') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
