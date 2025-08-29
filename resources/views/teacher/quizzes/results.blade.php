<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-graph-up mr-2"></i>{{ __('general.quiz_results') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $quiz->title }} - {{ $quiz->classroom->name ?? __('general.not_available') }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye mr-2"></i>{{ __('general.view_details') }}
                    </a>
                    <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left mr-2"></i>{{ __('general.back') }}
                    </a>
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
                                <h6 class="card-title">{{ __('general.total_results') }}</h6>
                                <h3 class="mb-0">{{ $totalResults }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-people fs-1"></i>
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
                                <h6 class="card-title">{{ __('general.average_score') }}</h6>
                                <h3 class="mb-0">{{ number_format($avgScore, 1) }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-graph-up fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="flex-fill d-flex flex-column">
                                <h6 class="card-title">{{ __('general.pass_rate') }}</h6>
                                <h3 class="mb-0">{{ number_format($passRate, 1) }}%</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-percent fs-1"></i>
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
                                <h6 class="card-title">{{ __('general.highest_score') }}</h6>
                                <h3 class="mb-0">{{ $maxScore }}/{{ $quiz->getMaxScore() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-trophy fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ __('general.search_students') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ __('general.search_by_name_or_email') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('general.filter_by_score') }}</label>
                        <select class="form-control" wire:model.live="filterScore">
                            <option value="">{{ __('general.all') }}</option>
                            <option value="excellent">{{ __('general.excellent_range') }}</option>
                            <option value="good">{{ __('general.good_range') }}</option>
                            <option value="average">{{ __('general.average_range') }}</option>
                            <option value="poor">{{ __('general.poor_range') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise mr-2"></i>{{ __('general.reset') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách kết quả -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul mr-2"></i>{{ __('general.results_list') }} ({{ $totalResults }})
                </h6>
            </div>
            <div class="card-body">
                @if ($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('general.no') }}</th>
                                    <th>{{ __('general.student') }}</th>
                                    <th>{{ __('general.score') }}</th>
                                    <th>{{ __('general.percentage') }}</th>
                                    <th>{{ __('general.duration') }}</th>
                                    <th>{{ __('general.completed_date') }}</th>
                                    <th>{{ __('general.status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $index => $result)
                                    @php
                                        $percentage =
                                            $quiz->getMaxScore() > 0
                                                ? ($result->score / $quiz->getMaxScore()) * 100
                                                : 0;
                                        $status = match (true) {
                                            $percentage >= 90 => ['text' => __('general.excellent'), 'class' => 'bg-success'],
                                            $percentage >= 70 => ['text' => __('general.good'), 'class' => 'bg-info'],
                                            $percentage >= 50 => ['text' => __('general.average'), 'class' => 'bg-warning'],
                                            default => ['text' => __('general.poor'), 'class' => 'bg-danger'],
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 + ($results->currentPage() - 1) * $results->perPage() }}</td>
                                        <td>
                                            <div class="fw-medium">{{ $result->student->user->name ?? 'N/A' }}</div>
                                            <small
                                                class="text-muted">{{ $result->student->user->email ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $result->score }}/{{ $quiz->getMaxScore() }}</div>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $status['class'] }}"
                                                    style="width: {{ $percentage }}%">
                                                    {{ number_format($percentage, 1) }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($result->duration)
                                                <div class="fw-medium">{{ $result->duration }} {{ __('general.minutes') }}</div>
                                            @else
                                                <span class="text-muted">{{ __('general.not_available') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->completed_at)
                                                <div class="fw-medium">{{ $result->completed_at->format('d/m/Y') }}
                                                </div>
                                                <small
                                                    class="text-muted">{{ $result->completed_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">{{ __('general.not_completed') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge {{ $status['class'] }}">{{ $status['text'] }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div>
                        {{ $results->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-graph-down fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('general.no_results') }}</h5>
                        <p class="text-muted">{{ __('general.no_students_have_taken_quiz') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-check-circle mr-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-exclamation-triangle mr-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
