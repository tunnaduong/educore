<x-layouts.dash-teacher active="evaluations-report">
    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center">
<div>
                    <h4 class="mb-0 text-primary fs-4">
                        <i class="bi bi-bar-chart-line mr-2"></i>Báo cáo đánh giá chất lượng học viên
                    </h4>
                    <p class="text-muted mb-0">Tổng hợp đánh giá chất lượng học tập từ học viên</p>
                </div>
            </div>
        </div>
        <div class="alert alert-info mb-4">
            <strong>Hướng dẫn tính điểm:</strong><br>
            <ul class="mb-1">
                <li><b>Điểm TB đánh giá giáo viên</b>: Trung bình cộng các câu hỏi nhóm 1 (1-5 điểm/câu).</li>
                <li><b>Điểm TB đánh giá khóa học</b>: Trung bình cộng các câu hỏi nhóm 2 (1-5 điểm/câu).</li>
                <li><b>Điểm hài lòng cá nhân</b>: Điểm câu hỏi số 10 (1-5 điểm).</li>
            </ul>
            <span class="text-muted">Điểm càng cao mức độ hài lòng càng lớn.</span>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form wire:submit.prevent="loadEvaluations" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="classroomId" class="form-label">Lọc theo lớp học</label>
                        <select wire:model="classroomId" id="classroomId" class="form-control">
                            <option value="">Tất cả lớp</option>
                            @foreach ($classrooms as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="roundId" class="form-label">Lọc theo đợt đánh giá</label>
                        <select wire:model="roundId" id="roundId" class="form-control">
                            <option value="">Tất cả đợt</option>
                            @foreach ($rounds as $r)
                                <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->start_date->format('d/m') }} - {{ $r->end_date->format('d/m') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Lọc</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Thống kê mức độ hài lòng -->
        <div class="card mb-4">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-emoji-smile"></i> Thống kê mức độ hài lòng của sinh viên
            </div>
            <div class="card-body">
                @php
                    $satisfactionStats = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
                    foreach ($evaluations as $eva) {
                        if ($eva->personal_satisfaction) {
                            $satisfactionStats[$eva->personal_satisfaction]++;
                        }
                    }
                    $totalEva = $total > 0 ? $total : 1;
                @endphp
                <div class="row text-center">
                    @foreach ($satisfactionStats as $level => $count)
                        <div class="col">
                            <div class="fw-bold">{{ $level }}</div>
                            <div class="progress mb-1" style="height: 18px;">
                                <div class="progress-bar bg-{{ $level == 5 ? 'success' : ($level == 1 ? 'danger' : ($level >= 4 ? 'info' : 'warning')) }}"
                                    role="progressbar" style="width: {{ round(($count / $totalEva) * 100) }}%">
                                    {{ round(($count / $totalEva) * 100) }}%
                                </div>
                            </div>
                            <small class="text-muted">{{ $count }} lượt</small>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-2 text-muted small">1: Rất không hài lòng &nbsp; 5: Rất hài lòng</div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold text-success fs-3">{{ number_format($avgTeacher, 1) }}</div>
                        <div class="text-muted">Điểm TB đánh giá giáo viên</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold text-info fs-3">{{ number_format($avgCourse, 1) }}</div>
                        <div class="text-muted">Điểm TB đánh giá khóa học</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center shadow-sm">
                    <div class="card-body">
                        <div class="fw-bold text-warning fs-3">{{ number_format($avgPersonal, 1) }}</div>
                        <div class="text-muted">Điểm TB hài lòng cá nhân</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">
                <i class="bi bi-list-check"></i> Danh sách đánh giá ({{ $total }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Học viên</th>
                                <th>Điểm giáo viên</th>
                                <th>Điểm khóa học</th>
                                <th>Hài lòng</th>
                                <th>Đề xuất</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($evaluations as $eva)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $eva->student->user->name ?? 'N/A' }}</span><br>
                                        <small class="text-muted">ID: {{ $eva->student_id }}</small>
                                    </td>
                                    <td>{{ number_format($eva->getTeacherAverageRating(), 1) }}</td>
                                    <td>{{ number_format($eva->getCourseAverageRating(), 1) }}</td>
                                    <td>{{ $eva->personal_satisfaction }}</td>
                                    <td>
                                        @if ($eva->suggestions)
                                            <span class="text-dark">{{ $eva->suggestions }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-info"
                                            wire:click="showEvaluationDetail({{ $eva->id }})">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Chưa có đánh giá nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal chi tiết đánh giá -->
        @if ($selectedEvaluation)
            <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5); z-index: 1050;"
                tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold text-primary fs-4">Chi tiết đánh giá của
                                {{ $selectedEvaluation->student->user->name ?? 'Học viên' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeEvaluationDetail">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>Nhóm 1 - Đánh giá giáo viên:</strong>
                                <ul class="mb-2">
                                    @foreach ($selectedEvaluation->teacher_ratings ?? [] as $k => $v)
                                        @php
                                            $question = $questions
                                                ->where('category', 'teacher')
                                                ->where('order', $k)
                                                ->first();
                                        @endphp
                                        <li>
                                            {{ $question ? $question->question : 'Câu hỏi ' . $k }}:
                                            <b>{{ $v }}/5</b>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-3">
                                <strong>Nhóm 2 - Đánh giá khóa học:</strong>
                                <ul class="mb-2">
                                    @foreach ($selectedEvaluation->course_ratings ?? [] as $k => $v)
                                        @php
                                            $question = $questions
                                                ->where('category', 'course')
                                                ->where('order', $k)
                                                ->first();
                                        @endphp
                                        <li>
                                            {{ $question ? $question->question : 'Câu hỏi ' . $k }}:
                                            <b>{{ $v }}/5</b>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="mb-3">
                                <strong>Nhóm 3 - Hài lòng cá nhân:</strong>
                                @php
                                    $personalQuestion = $questions->where('category', 'personal')->first();
                                @endphp
                                <div>{{ $personalQuestion ? $personalQuestion->question : 'Mức độ hài lòng cá nhân' }}:
                                    <b>{{ $selectedEvaluation->personal_satisfaction }}/5</b></div>
                            </div>
                            <div class="mb-3">
                                <strong>Đề xuất/Góp ý:</strong>
                                <div>{{ $selectedEvaluation->suggestions ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeEvaluationDetail">
                                Đóng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
</div>

    @if (session()->has('success'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1060;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
</x-layouts.dash-teacher>
