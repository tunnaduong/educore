<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <table class="table table-bordered table-hover bg-white">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Loại giao dịch</th>
                        <th>Mã HV/NV</th>
                        <th>Tên khoản mục</th>
                        <th>Số tiền</th>
                        <th>Ngày tạo</th>
                        <th>Người thực hiện</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tran)
                        <tr>
                            <td>{{ $tran['id'] }}</td>
                            <td>
                                @if ($tran['type'] === 'income')
                                    <span class="badge badge-success">Thu</span>
                                @else
                                    <span class="badge badge-danger">Chi</span>
                                @endif
                            </td>
                            <td>{{ $tran['user_code'] }}</td>
                            <td>
                                @php
                                    $typeMap = ['tuition' => 'Học phí', 'material' => 'Tài liệu', 'other' => 'Khác'];
                                @endphp
                                {{ $typeMap[$tran['item']] ?? $tran['item'] }}
                            </td>
                            <td>{{ number_format($tran['amount']) }}₫</td>
                            <td>{{ $tran['created_at'] }}</td>
                            <td>{{ $tran['operator'] }}</td>
                            <td>{{ $tran['note'] ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có dữ liệu</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
