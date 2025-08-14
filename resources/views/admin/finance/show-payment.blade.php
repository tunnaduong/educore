<x-layouts.dash-admin title="Chi tiết học phí">
    <div class="container py-4">
        <!-- Header với thông tin học viên -->
        <div class="card border-0 shadow-sm mb-4 bg-gradient-info">
            <div class="card-body text-white p-4">
                <div class="row align-items-center">
                    <div class="col">
                        <h4 class="mb-2 text-white">
                            <i class="bi bi-person-circle mr-2"></i>{{ $user->name }}
                        </h4>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="small text-white-50 mb-1">Email</div>
                                <div class="fw-semibold">{{ $user->email }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-white-50 mb-1">Mã học viên</div>
                                <div class="fw-semibold">#{{ $user->id }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="small text-white-50 mb-1">Trạng thái</div>
                                <span class="badge bg-light text-primary">Đang hoạt động</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="bi bi-mortarboard-fill text-white-50" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nút tạo giao dịch -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">
                <i class="bi bi-credit-card-2-front text-primary mr-2"></i>Lịch sử thanh toán học phí
            </h5>
            <button class="btn btn-primary btn-lg shadow-sm" wire:click="openCreateModal">
                <i class="bi bi-plus-circle mr-2"></i>Tạo giao dịch mới
            </button>
        </div>

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
                                <div class="form-group mb-2">
                                    <label>Ghi chú</label>
                                    <textarea wire:model.defer="newNotes" class="form-control" rows="3" placeholder="Nhập ghi chú (tùy chọn)"></textarea>
                                    @error('newNotes')
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

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">#</th>
                            <th><i class="bi bi-building mr-1"></i>Lớp học</th>
                            <th><i class="bi bi-cash mr-1"></i>Số tiền</th>
                            <th><i class="bi bi-tag mr-1"></i>Loại</th>
                            <th class="text-center"><i class="bi bi-check-circle mr-1"></i>Trạng thái</th>
                            <th><i class="bi bi-calendar mr-1"></i>Ngày thanh toán</th>
                            <th class="text-center"><i class="bi bi-image mr-1"></i>Minh chứng</th>
                            <th class="text-center"><i class="bi bi-gear mr-1"></i>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr class="align-middle">
                                <td class="text-center fw-bold text-primary">{{ $payment->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $payment->classroom->name ?? '-' }}</div>
                                </td>
                                <td>
                                    <span
                                        class="fw-bold text-success">{{ number_format($payment->amount, 0, ',', '.') }}₫</span>
                                </td>
                                <td>
                                    @php
                                        $typeMap = [
                                            'tuition' => [
                                                'label' => 'Học phí',
                                                'icon' => 'bi-mortarboard',
                                                'color' => 'primary',
                                            ],
                                            'material' => [
                                                'label' => 'Tài liệu',
                                                'icon' => 'bi-book',
                                                'color' => 'info',
                                            ],
                                            'other' => [
                                                'label' => 'Khác',
                                                'icon' => 'bi-three-dots',
                                                'color' => 'secondary',
                                            ],
                                        ];
                                        $type = $typeMap[$payment->type] ?? [
                                            'label' => $payment->type,
                                            'icon' => 'bi-question',
                                            'color' => 'secondary',
                                        ];
                                    @endphp
                                    <span
                                        class="badge bg-{{ $type['color'] }} bg-opacity-10 text-{{ $type['color'] }} border border-{{ $type['color'] }}">
                                        <i class="bi {{ $type['icon'] }} mr-1"></i>{{ $type['label'] }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if ($payment->status === 'paid')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill mr-1"></i>Đã đóng đủ
                                        </span>
                                    @elseif($payment->status === 'partial')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-exclamation-triangle-fill mr-1"></i>Còn thiếu
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle-fill mr-1"></i>Chưa đóng
                                        </span>
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
                                        <button type="submit" class="btn btn-sm btn-primary">Tải lên minh
                                            chứng</button>
                                    </form>
                                    @error('proof')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-primary" data-toggle="modal"
                                            data-target="#editStatusModal{{ $payment->id }}">Sửa</button>
                                        <button class="btn btn-outline-danger" data-toggle="modal"
                                            data-target="#confirmDeleteModal{{ $payment->id }}">Xóa</button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal sửa trạng thái -->
                            <div class="modal fade" id="editStatusModal{{ $payment->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="editStatusLabel{{ $payment->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editStatusLabel{{ $payment->id }}">Sửa trạng
                                                thái thanh toán</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Đóng">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Giao dịch #{{ $payment->id }}</strong></p>
                                            <p>Số tiền:
                                                <strong>{{ number_format($payment->amount, 0, ',', '.') }}₫</strong>
                                            </p>
                                            <p>Lớp học: <strong>{{ $payment->classroom->name ?? '-' }}</strong></p>
                                            <p>Ghi chú: <strong>{{ $payment->note ?? '-' }}</strong></p>

                                            <div class="form-group">
                                                <label>Chọn trạng thái mới:</label>
                                                <div class="mt-2">
                                                    <button type="button" class="btn btn-success btn-block mb-2"
                                                        data-dismiss="modal"
                                                        wire:click="updateStatus({{ $payment->id }}, 'paid')">
                                                        <i class="fas fa-check-circle"></i> Đã đóng đủ
                                                    </button>
                                                    <button type="button" class="btn btn-warning btn-block mb-2"
                                                        data-dismiss="modal"
                                                        wire:click="updateStatus({{ $payment->id }}, 'partial')">
                                                        <i class="fas fa-exclamation-triangle"></i> Còn thiếu
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-block"
                                                        data-dismiss="modal"
                                                        wire:click="updateStatus({{ $payment->id }}, 'unpaid')">
                                                        <i class="fas fa-times-circle"></i> Chưa đóng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal xác nhận xóa -->
                            <div class="modal fade" id="confirmDeleteModal{{ $payment->id }}" tabindex="-1"
                                role="dialog" aria-labelledby="confirmDeleteLabel{{ $payment->id }}"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmDeleteLabel{{ $payment->id }}">Xác
                                                nhận xóa
                                                giao dịch</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Đóng">
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
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                                    <div class="mt-2 text-muted">Chưa có giao dịch học phí nào</div>
                                    <button class="btn btn-primary mt-3" wire:click="openCreateModal">
                                        <i class="bi bi-plus-circle mr-1"></i>Tạo giao dịch đầu tiên
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.finance.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-arrow-left mr-2"></i>Quay lại thống kê
            </a>
        </div>
    </div>
    <style>
        .bg-gradient-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #6f42c1 100%) !important;
        }
    </style>

</x-layouts.dash-admin>
