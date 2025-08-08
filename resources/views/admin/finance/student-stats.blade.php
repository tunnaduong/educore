<div class="container-fluid">
    <!-- Bộ lọc học viên -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-filter-circle-fill mr-2"></i>Bộ lọc học viên
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-building text-primary mr-1"></i>Lọc theo lớp
                    </label>
                    <select class="form-control form-control-lg" wire:model="filterClass">
                        <option value="">Tất cả lớp học</option>
                        @foreach ($students->flatMap(fn($s) => $s['classes'])->unique('class_id') as $class)
                            <option value="{{ $class['class_name'] }}">{{ $class['class_name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-cash text-success mr-1"></i>Trạng thái học phí
                    </label>
                    <select class="form-control form-control-lg" wire:model="filterStatus">
                        <option value="">Tất cả trạng thái</option>
                        <option value="paid">
                            <i class="bi bi-check-circle-fill"></i> Đã đóng đủ
                        </option>
                        <option value="partial">
                            <i class="bi bi-exclamation-triangle-fill"></i> Còn thiếu
                        </option>
                        <option value="unpaid">
                            <i class="bi bi-x-circle-fill"></i> Chưa đóng
                        </option>
                    </select>
                </div>
                <div class="col-md-12 d-flex align-items-end">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle mr-1"></i>
                        Tổng cộng {{ count($students) }} học viên
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng danh sách học viên -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h6 class="mb-0">
                <i class="bi bi-people-fill mr-2"></i>Danh sách học viên và trạng thái học phí
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="8%">#</th>
                        <th width="25%">
                            <i class="bi bi-person-fill mr-1"></i>Học viên
                        </th>
                        <th width="30%">
                            <i class="bi bi-building mr-1"></i>Lớp tham gia
                        </th>
                        <th width="27%">
                            <i class="bi bi-cash mr-1"></i>Trạng thái học phí
                        </th>
                        <th class="text-center" width="10%">
                            <i class="bi bi-gear mr-1"></i>Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr class="align-middle">
                            <td class="text-center fw-bold text-primary">{{ $student['id'] }}</td>
                            <td>
                                <div class="d-flex align-items-center" style="gap: 10px">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 d-flex align-items-center justify-content-center"
                                        style="width: 40px">
                                        <i class="bi bi-person-fill text-white"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $student['name'] }}</div>
                                        <small class="text-muted">ID: {{ $student['id'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @foreach ($student['classes'] as $class)
                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                        <i class="bi bi-building-fill mr-1"></i>{{ $class['class_name'] }}
                                    </span>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($student['classes'] as $class)
                                    <div class="mb-1">
                                        @if ($class['status'] === 'paid')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle-fill mr-1"></i>{{ $class['class_name'] }}:
                                                Đã đóng đủ
                                            </span>
                                        @elseif ($class['status'] === 'partial')
                                            <span class="badge bg-warning">
                                                <i
                                                    class="bi bi-exclamation-triangle-fill mr-1"></i>{{ $class['class_name'] }}:
                                                Còn thiếu
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-x-circle-fill mr-1"></i>{{ $class['class_name'] }}: Chưa
                                                đóng
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.finance.payment.show', $student['id']) }}"
                                    class="btn btn-primary btn-sm shadow-sm">
                                    <i class="bi bi-eye-fill mr-1"></i>Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                                <div class="mt-2 text-muted fs-5">Không có học viên nào</div>
                                <small class="text-muted">Thử thay đổi bộ lọc để xem kết quả khác</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
