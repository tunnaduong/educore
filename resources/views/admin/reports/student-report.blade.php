<x-layouts.dash-admin active="reports">
    <div class="mb-4">
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại báo cáo tổng hợp
        </a>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="mb-3 text-primary"><i class="bi bi-person-circle mr-2"></i>Báo cáo chi tiết học viên</h4>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="fw-bold">Họ tên:</div>
                    <div>{{ $student->user->name }}</div>
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Lớp:</div>
                    @if (isset($classNames) && count($classNames))
                        @foreach ($classNames as $cname)
                            <span class="badge bg-secondary mr-1">{{ $cname }}</span>
                        @endforeach
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="fw-bold">Trạng thái:</div>
                    <span class="badge {{ $needSupport ? 'bg-danger' : 'bg-success' }}">
                        {{ $needSupport ? 'Cần hỗ trợ' : 'Ổn định' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">Tiến độ học</div>
                    <div class="display-6">{{ $progress }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">Điểm trung bình</div>
                    <div class="display-6">{{ $avgScore }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">Tỷ lệ nộp bài</div>
                    <div class="display-6">{{ $submitRate }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="fw-bold text-muted">Số buổi tham gia</div>
                    <div class="display-6">{{ $attendanceCount }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header fw-bold">Danh sách bài tập chưa nộp</div>
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                @forelse($notSubmittedAssignments as $assignment)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $assignment->title }}
                        <span class="badge bg-warning">Chưa nộp</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Tất cả bài tập đã được nộp</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="alert alert-info">
        <i class="bi bi-lightbulb mr-2"></i>
        <b>Gợi ý hỗ trợ:</b>
        @if ($needSupport)
            Học viên này cần được quan tâm thêm do tiến độ, điểm hoặc tỷ lệ nộp bài thấp.
        @else
            Học viên đang có tiến độ ổn định.
        @endif
    </div>
</x-layouts.dash-admin>
