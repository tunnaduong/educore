<div class="container-fluid">
    <!-- Thống kê theo khóa học -->
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-success text-white">
            <h6 class="mb-0">
                <i class="bi bi-graph-up-arrow mr-2"></i>Báo cáo hiệu quả theo khóa học
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="8%">#</th>
                        <th width="25%">
                            <i class="bi bi-book-half mr-1"></i>Khóa học
                        </th>
                        <th class="text-center" width="12%">
                            <i class="bi bi-people-fill mr-1"></i>Tổng HV
                        </th>
                        <th class="text-center" width="15%">
                            <i class="bi bi-check-circle-fill mr-1"></i>Đã hoàn thành
                        </th>
                        <th width="15%">
                            <i class="bi bi-arrow-down-circle mr-1"></i>Tổng thu
                        </th>
                        <th width="15%">
                            <i class="bi bi-arrow-up-circle mr-1"></i>Chi phí
                        </th>
                        <th width="10%">
                            <i class="bi bi-graph-up mr-1"></i>Lợi nhuận
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr class="align-middle">
                            <td class="text-center fw-bold text-primary">{{ $course['id'] }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                                        <i class="bi bi-book-fill text-success"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $course['name'] }}</div>
                                        <small class="text-muted">Khóa #{{ $course['id'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary bg-opacity-20 text-primary px-3 py-2">
                                    {{ $course['total_students'] }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php 
                                    $completion_rate = $course['total_students'] > 0 ? ($course['students_paid'] / $course['total_students']) * 100 : 0;
                                @endphp
                                <div class="d-flex flex-column align-items-center">
                                    <span class="badge bg-success mb-1">{{ $course['students_paid'] }}/{{ $course['total_students'] }}</span>
                                    <div class="progress" style="width: 60px; height: 4px;">
                                        <div class="progress-bar bg-success" style="width: {{ $completion_rate }}%"></div>
                                    </div>
                                    <small class="text-muted">{{ number_format($completion_rate, 1) }}%</small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($course['total_income']) }}₫</span>
                            </td>
                            <td>
                                <span class="fw-bold text-danger">{{ number_format($course['total_expense']) }}₫</span>
                            </td>
                            <td>
                                @php $profit_color = $course['profit'] >= 0 ? 'success' : 'danger'; @endphp
                                <span class="fw-bold text-{{ $profit_color }}">
                                    {{ $course['profit'] >= 0 ? '+' : '' }}{{ number_format($course['profit']) }}₫
                                </span>
                                <div class="mt-1">
                                    @if($course['profit'] >= 0)
                                        <i class="bi bi-trending-up text-success"></i>
                                    @else
                                        <i class="bi bi-trending-down text-danger"></i>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                                <div class="mt-2 text-muted fs-5">Chưa có khóa học nào</div>
                                <small class="text-muted">Thống kê sẽ hiển thị khi có khóa học được tạo</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #198754 0%, #20c997 100%) !important;
}
</style>
