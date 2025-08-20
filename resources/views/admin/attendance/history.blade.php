<x-layouts.dash-admin active="attendances">
    @include('components.language')
    <div class="container-fluid">
        <div class="mb-4">
            <a href="{{ route('attendances.overview') }}" class="btn btn-light mb-2">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại tổng quan
            </a>
            <h4 class="mb-0 text-primary fs-4">
                <i class="bi bi-list-ul mr-2"></i>Lịch sử điểm danh
            </h4>
        </div>
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Ngày</th>
                                <th>Lớp học</th>
                                <th>Học viên</th>
                                <th class="text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td>
                                        <div class="fw-medium">{{ $attendance->date->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $attendance->date->format('D') }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $attendance->classroom?->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $attendance->classroom->level }}</small>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $attendance->student?->user?->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $attendance->student->user->email }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if ($attendance->present)
                                            <span class="badge bg-success">Có mặt</span>
                                        @else
                                            <span class="badge bg-danger">Vắng</span>
                                            @if ($attendance->reason)
                                                <br><small class="text-muted">{{ $attendance->reason }}</small>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">
                                        <i class="bi bi-calendar-x fs-1 mb-2"></i>
                                        <div>Chưa có dữ liệu điểm danh</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $attendances->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.dash-admin>
