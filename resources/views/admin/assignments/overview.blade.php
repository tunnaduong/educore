<x-layouts.dash-admin active="assignments" title="{{ __('views.assignment_overview') }}">
    @include('components.language')
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('assignments.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle mr-2"></i>{{ __('views.create_new_assignment') }}
        </a>
    </div>
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="card card-outline card-primary mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-chart-bar mr-2"></i>{{ __('views.assignment_overview') }}
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <select wire:model.live="selectedMonth" class="form-control mr-2"
                                    style="max-width: 150px;">
                                    @for ($month = 1; $month <= 12; $month++)
                                        <option value="{{ $month }}">{{ $this->getMonthName($month) }}</option>
                                    @endfor
                                </select>
                                <select wire:model.live="selectedYear" class="form-control" style="max-width: 120px;">
                                    @for ($year = date('Y') - 2; $year <= date('Y') + 1; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thống kê tổng quan -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('views.total_assignments') }}</h6>
                                    <h3 class="mb-0">{{ $overviewStats['total_assignments'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-alt fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('views.classes_with_assignments') }}</h6>
                                    <h3 class="mb-0">{{ $overviewStats['total_classes'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-graduation-cap fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('views.total_submissions') }}</h6>
                                    <h3 class="mb-0">{{ $overviewStats['total_submissions'] ?? 0 }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-upload fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title mb-0">{{ __('views.submission_rate') }}</h6>
                                    <h3 class="mb-0">{{ $overviewStats['submission_rate'] ?? 0 }}%</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-percentage fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách lớp nhiều bài tập nhất -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card card-outline card-primary mb-4">
                        <div class="card-header">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-trophy mr-2"></i>{{ __('views.top_classes_with_most_assignments') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($topClasses->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('views.classroom') }}</th>
                                                <th class="text-center">{{ __('views.assignment_count') }}</th>
                                                <th class="text-center">{{ __('views.submission_rate') }}</th>
                                                <th class="text-center">{{ __('views.actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($topClasses as $classData)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="mr-3">
                                                                <i class="fas fa-graduation-cap fa-2x text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="font-weight-bold">
                                                                    {{ $classData['classroom']->name ?? '-' }}</div>
                                                                <small
                                                                    class="text-muted">{{ $classData['classroom']->level ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-primary">{{ $classData['total_assignments'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-secondary">-</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('assignments.list', ['classroomFilter' => $classData['classroom']->id ?? '']) }}"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye mr-1"></i>{{ __('views.view_details') }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">{{ __('views.no_assignment_data') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-chart-pie mr-2"></i>{{ __('views.monthly_statistics') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($monthlyStats->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>{{ __('views.month') }}</th>
                                                <th class="text-center">{{ __('views.assignments') }}</th>
                                                <th class="text-center">{{ __('views.submissions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($monthlyStats as $stat)
                                                <tr>
                                                    <td>{{ $stat['month_name'] }}</td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-primary">{{ $stat['assignments_count'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge badge-info">{{ $stat['submissions_count'] }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small">{{ __('views.no_statistics_data') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
