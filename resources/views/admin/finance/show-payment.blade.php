<x-layouts.dash-admin title="Chi tiết học phí">
    <div class="container py-4">
        <h4 class="mb-3 text-success">Thông tin học viên</h4>
        <div class="card mb-4">
            <div class="card-body">
                <strong>Họ tên:</strong> {{ $user->name }}<br>
                <strong>Email:</strong> {{ $user->email }}<br>
                <strong>Mã học viên:</strong> {{ $user->id }}
            </div>
        </div>

        <h5 class="mb-3">Lịch sử thanh toán học phí</h5>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Số tiền</th>
                        <th>Loại</th>
                        <th>Trạng thái</th>
                        <th>Ngày thanh toán</th>
                        <th>Minh chứng</th>
                        <th>Cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ number_format($payment->amount) }}₫</td>
                            <td>{{ $payment->type }}</td>
                            <td>
                                @if ($payment->status === 'paid')
                                    <span class="badge badge-success">Đã đóng đủ</span>
                                @elseif($payment->status === 'partial')
                                    <span class="badge badge-warning">Còn thiếu</span>
                                @else
                                    <span class="badge badge-danger">Chưa đóng</span>
                                @endif
                            </td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                @if ($payment->proof_path)
                                    <a href="{{ Storage::url($payment->proof_path) }}" target="_blank">
                                        <img src="{{ Storage::url($payment->proof_path) }}" width="80">
                                    </a>
                                @endif
                                <form wire:submit.prevent="uploadProof({{ $payment->id }})" class="mt-2">
                                    <input type="file" wire:model="proof" class="form-control-file mb-1">
                                    <button type="submit" class="btn btn-sm btn-primary">Tải lên minh chứng</button>
                                </form>
                                @error('proof')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button wire:click="updateStatus({{ $payment->id }}, 'paid')"
                                        class="btn btn-success">Đã đóng đủ</button>
                                    <button wire:click="updateStatus({{ $payment->id }}, 'partial')"
                                        class="btn btn-warning">Còn thiếu</button>
                                    <button wire:click="updateStatus({{ $payment->id }}, 'unpaid')"
                                        class="btn btn-danger">Chưa đóng</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Chưa có giao dịch học phí nào</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <a href="{{ route('admin.finance.index') }}" class="btn btn-secondary">Quay lại thống kê</a>
    </div>
</x-layouts.dash-admin>
