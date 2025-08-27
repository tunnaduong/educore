<x-layouts.dash-teacher active="quizzes">
    @include('components.language')
    @php
        $t = function ($vi, $en, $zh) {
            $l = app()->getLocale();
            return $l === 'vi' ? $vi : ($l === 'zh' ? $zh : $en);
        };
    @endphp
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-graph-up me-2"></i>{{ $t('Kết quả bài kiểm tra', 'Quiz Results', '测验结果') }}
                    </h4>
                    <p class="text-muted mb-0">{{ $quiz->title }} - {{ $quiz->classroom->name ?? 'N/A' }}</p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn btn-outline-primary">
                        <i class="bi bi-eye me-2"></i>{{ $t('Xem chi tiết', 'View details', '查看详情') }}
                    </a>
                    <a href="{{ route('teacher.quizzes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>{{ $t('Quay lại', 'Back', '返回') }}
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
                                <h6 class="card-title">{{ $t('Tổng số bài làm', 'Total submissions', '提交总数') }}</h6>
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
                                <h6 class="card-title">{{ $t('Điểm trung bình', 'Average score', '平均分') }}</h6>
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
                                <h6 class="card-title">{{ $t('Tỷ lệ đạt', 'Pass rate', '通过率') }}</h6>
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
                                <h6 class="card-title">{{ $t('Điểm cao nhất', 'Highest score', '最高分') }}</h6>
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
                        <label class="form-label">{{ $t('Tìm kiếm học sinh', 'Search students', '搜索学生') }}</label>
                        <input type="text" class="form-control" wire:model.live="search"
                            placeholder="{{ $t('Tìm theo tên hoặc email...', 'Search by name or email...', '按姓名或邮箱搜索...') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ $t('Lọc theo điểm', 'Filter by score', '按分数筛选') }}</label>
                        <select class="form-control" wire:model.live="filterScore">
                            <option value="">{{ $t('Tất cả', 'All', '全部') }}</option>
                            <option value="excellent">{{ $t('Xuất sắc', 'Excellent', '优秀') }} (≥90%)</option>
                            <option value="good">{{ $t('Tốt', 'Good', '良好') }} (70-89%)</option>
                            <option value="average">{{ $t('Trung bình', 'Average', '中等') }} (50-69%)</option>
                            <option value="poor">{{ $t('Yếu', 'Poor', '较差') }} (<50%)</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" wire:click="$set('search', '')">
                            <i class="bi bi-arrow-clockwise me-2"></i>{{ $t('Làm mới', 'Reset', '重置') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách kết quả -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>{{ $t('Danh sách kết quả', 'Results list', '结果列表') }} ({{ $totalResults }})
                </h6>
            </div>
            <div class="card-body">
                @if ($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ $t('STT', 'No.', '序号') }}</th>
                                    <th>{{ $t('Học sinh', 'Student', '学生') }}</th>
                                    <th>{{ $t('Điểm', 'Score', '分数') }}</th>
                                    <th>{{ $t('Tỷ lệ', 'Percentage', '百分比') }}</th>
                                    <th>{{ $t('Thời gian làm', 'Duration', '用时') }}</th>
                                    <th>{{ $t('Ngày hoàn thành', 'Completed on', '完成日期') }}</th>
                                    <th>{{ $t('Trạng thái', 'Status', '状态') }}</th>
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
                                            $percentage >= 90 => ['text' => $t('Xuất sắc', 'Excellent', '优秀'), 'class' => 'bg-success'],
                                            $percentage >= 70 => ['text' => $t('Tốt', 'Good', '良好'), 'class' => 'bg-info'],
                                            $percentage >= 50 => ['text' => $t('Trung bình', 'Average', '中等'), 'class' => 'bg-warning'],
                                            default => ['text' => $t('Yếu', 'Poor', '较差'), 'class' => 'bg-danger'],
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
                                                <div class="fw-medium">{{ $result->duration }} {{ $t('phút', 'minutes', '分钟') }}</div>
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
                                                <span class="text-muted">{{ $t('Chưa hoàn thành', 'Not completed', '未完成') }}</span>
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
                    <div class="d-flex justify-content-center mt-4">
                        {{ $results->links('vendor.pagination.bootstrap-5') }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-graph-down fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ $t('Không có kết quả nào', 'No results yet', '暂无结果') }}</h5>
                        <p class="text-muted">{{ $t('Chưa có học sinh nào làm bài kiểm tra này.', 'No student has taken this quiz yet.', '尚无学生参加此测验。') }}</p>
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
