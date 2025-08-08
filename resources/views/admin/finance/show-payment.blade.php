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

        <button class="btn btn-primary mb-4" wire:click="openCreateModal">Tạo giao dịch học phí mới</button>

        <!-- Modal tạo payment -->
        @if ($showCreateModal)
            <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.3);">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Tạo giao dịch học phí mới</h5>
                            <button type="button" class="close"
                                wire:click="closeCreateModal"><span>&times;</span></button>
                        </div>
                        <form wire:submit.prevent="createPayment">
                            <div class="modal-body">
                                <div class="form-group mb-2">
                                    <label>Lớp học</label>
                                    <select wire:model.defer="newClassId" class="form-control" required>
                                        <option value="">-- Chọn lớp --</option>
                                        @foreach ($this->classrooms as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('newClassId')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>Số tiền</label>
                                    <input type="number" wire:model.defer="newAmount" class="form-control"
                                        min="0" required autocomplete="off">
                                    @error('newAmount')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>Loại</label>
                                    <select wire:model.defer="newType" class="form-control">
                                        <option value="tuition">Học phí</option>
                                        <option value="material">Tài liệu</option>
                                        <option value="other">Khác</option>
                                    </select>
                                    @error('newType')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>Trạng thái</label>
                                    <select wire:model.defer="newStatus" class="form-control">
                                        <option value="unpaid">Chưa đóng</option>
                                        <option value="partial">Còn thiếu</option>
                                        <option value="paid">Đã đóng đủ</option>
                                    </select>
                                    @error('newStatus')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label>Ngày thanh toán</label>
                                    <input type="datetime-local" wire:model.defer="newPaidAt" class="form-control">
                                    @error('newPaidAt')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    wire:click="closeCreateModal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Tạo giao dịch</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

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
                            <td>{{ number_format($payment->amount, 0, ',', '.') }}₫</td>
                            <td>
                                @php
                                    $typeMap = ['tuition' => 'Học phí', 'material' => 'Tài liệu', 'other' => 'Khác'];
                                @endphp
                                {{ $typeMap[$payment->type] ?? $payment->type }}
                            </td>
                            <td>
                                @if ($payment->status === 'paid')
                                    <span class="badge badge-success">Đã đóng đủ</span>
                                @elseif($payment->status === 'partial')
                                    <span class="badge badge-warning">Còn thiếu</span>
                                @else
                                    <span class="badge badge-danger">Chưa đóng</span>
                                @endif
                            </td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}</td>
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
                                    <button class="btn btn-outline-danger" data-toggle="modal"
                                        data-target="#confirmDeleteModal{{ $payment->id }}">Xóa</button>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal xác nhận xóa -->
                        <div class="modal fade" id="confirmDeleteModal{{ $payment->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="confirmDeleteLabel{{ $payment->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirmDeleteLabel{{ $payment->id }}">Xác nhận xóa
                                            giao dịch</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn muốn xóa giao dịch này không?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Hủy</button>
                                        <button type="button" data-dismiss="modal" class="btn btn-danger"
                                            wire:click="deletePayment({{ $payment->id }})">Xóa</button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
