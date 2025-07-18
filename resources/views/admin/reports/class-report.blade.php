<x-layouts.dash-admin active="reports">
    <div class="mb-4">
        <a wire:navigate href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại báo cáo tổng hợp
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-3 text-primary"><i class="bi bi-diagram-3 me-2"></i>Báo cáo chi tiết lớp học</h4>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="fw-bold">Tên lớp:</div>
                    <div>{{ $classroom->name }}</div>
                </div>
                <div class="col-md-6">
                    <div class="fw-bold">Giáo viên:</div>
                    <div>{{ $classroom->getFirstTeacher() ? $classroom->getFirstTeacher()->name : '-' }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header fw-bold">Thống kê học viên trong lớp</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Học viên</th>
                            <th>Tiến độ học</th>
                            <th>Điểm trung bình</th>
                            <th>Tỷ lệ nộp bài</th>
                            <th>Số buổi tham gia</th>
                            <th>Gợi ý hỗ trợ</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $row)
                            <tr>
                                <td>{{ $row['student_name'] }}</td>
                                <td>
                                    <div class="progress" style="height: 18px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $row['progress'] }}%">{{ $row['progress'] }}%</div>
                                    </div>
                                </td>
                                <td><span class="fw-bold">{{ $row['avg_score'] }}</span></td>
                                <td>{{ $row['submit_rate'] }}%</td>
                                <td>{{ $row['attendance_count'] }}</td>
                                <td>
                                    @if($row['need_support'])
                                        <span class="badge bg-danger">Cần hỗ trợ</span>
                                    @else
                                        <span class="badge bg-success">Ổn định</span>
                                    @endif
                                </td>
                                <td>
                                    <a wire:navigate href="{{ route('reports.student', $row['student_id']) }}" class="btn btn-sm btn-outline-primary">Xem</a>
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
</x-layouts.dash-admin>
