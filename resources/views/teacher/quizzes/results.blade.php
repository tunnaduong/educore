<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-graph-up me-2"></i>Kết quả bài kiểm tra
                    </h4>
                    <p class="text-muted mb-0">{{ $quiz->title }} - {{ $quiz->classroom->name ?? 'N/A' }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>Xem chi tiết
                    </a>
                    <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Quay lại
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
                                <h6 class="card-title">Tổng số bài làm</h6>
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
                                <h6 class="card-title">Điểm trung bình</h6>
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
                            <div>
                                <h6 class="card-title">Tỷ lệ đạt</h6>
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
                                <h6 class="card-title">Điểm cao nhất</h6>
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
                        <label class="form-label">Tìm kiếm học sinh</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="Tìm theo tên hoặc email...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Lọc theo điểm</label>
                        <select class="form-control" wire:model.live="filterScore">
                            <option value="">Tất cả</option>
                            <option value="excellent">Xuất sắc (≥90%)</option>
                            <option value="good">Tốt (70-89%)</option>
                            <option value="average">Trung bình (50-69%)</option>
                            <option value="poor">Yếu (<50%)< /option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise me-2"></i>Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách kết quả -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>Danh sách kết quả ({{ $totalResults }})
                </h6>
            </div>
            <div class="card-body">
                @if ($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Học sinh</th>
                                    <th>Điểm</th>
                                    <th>Tỷ lệ</th>
                                    <th>Thời gian làm</th>
                                    <th>Ngày hoàn thành</th>
                                    <th>Trạng thái</th>
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
                                            $percentage >= 90 => ['text' => 'Xuất sắc', 'class' => 'bg-success'],
                                            $percentage >= 70 => ['text' => 'Tốt', 'class' => 'bg-info'],
                                            $percentage >= 50 => ['text' => 'Trung bình', 'class' => 'bg-warning'],
                                            default => ['text' => 'Yếu', 'class' => 'bg-danger'],
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
                                                <div class="fw-medium">{{ $result->duration }} phút</div>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($result->completed_at)
                                                <div class="fw-medium">{{ $result->completed_at->format('d/m/Y') }}
                                                </div>
                                                <small
                                                    class="text-muted">{{ $result->completed_at->format('H:i') }}</small>
                                            @else
                                                <span class="text-muted">Chưa hoàn thành</span>
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
                        <h5 class="text-muted">Không có kết quả nào</h5>
                        <p class="text-muted">Chưa có học sinh nào làm bài kiểm tra này.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Flash Message -->
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
    </div>
</x-layouts.dash-teacher>
