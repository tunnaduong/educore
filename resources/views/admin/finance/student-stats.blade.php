<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-3">
            <label>Lọc theo lớp</label>
            <select class="form-control" wire:model="filterClass">
                <option value="">Tất cả</option>
                <option value="Lớp 1">Lớp 1</option>
                <option value="Lớp 2">Lớp 2</option>
            </select>
        </div>
        <div class="col-md-3">
            <label>Trạng thái học phí</label>
            <select class="form-control" wire:model="filterStatus">
                <option value="">Tất cả</option>
                <option value="paid">✅ Đã đóng đủ</option>
                <option value="partial">⚠️ Còn thiếu</option>
                <option value="unpaid">❌ Chưa đóng</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-hover bg-white">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Họ tên</th>
                        <th>Lớp</th>
                        <th>Trạng thái học phí</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ $student['id'] }}</td>
                            <td>{{ $student['name'] }}</td>
                            <td>{{ $student['class'] }}</td>
                            <td>
                                @if ($student['status'] === 'paid')
                                    <span class="badge badge-success">✅ Đã đóng đủ</span>
                                @elseif($student['status'] === 'partial')
                                    <span class="badge badge-warning">⚠️ Còn thiếu</span>
                                @else
                                    <span class="badge badge-danger">❌ Chưa đóng</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.finance.payment.show', $student['id']) }}"
                                    class="btn btn-sm btn-info">Xem chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
