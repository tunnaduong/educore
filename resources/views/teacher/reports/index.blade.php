<x-layouts.dash-teacher active="reports">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-bar-chart mr-2"></i>Báo cáo & Thống kê học tập
            </h4>
            <p class="text-muted mb-0">Tổng hợp tiến độ, điểm, tỷ lệ nộp bài, số buổi tham gia của học viên các lớp bạn đang dạy</p>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Chọn lớp học</label>
                    <div class="input-group">
                        <select wire:model.live="selectedClass" class="form-control">
                            <option value="">Tất cả lớp</option>
                            @foreach ($classrooms as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Chọn học viên</label>
                    <select wire:model.live="selectedStudent" class="form-control">
                        <option value="">Tất cả học viên</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}">{{ $student->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a wire:navigate href="{{ route('teacher.reports.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="bi bi-arrow-clockwise mr-1"></i>Reset
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
                            <th>Học viên</th>
                            <th>Lớp</th>
                            <th>Tiến độ học</th>
                            <th>Điểm trung bình</th>
                            <th>Tỷ lệ nộp bài</th>
                            <th>Số buổi tham gia</th>
                            <th>Gợi ý hỗ trợ</th>
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
                                        <span class="badge bg-danger">Cần hỗ trợ</span>
                                    @else
                                        <span class="badge bg-success">Ổn định</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Không có dữ liệu thống kê</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.dash-teacher>
