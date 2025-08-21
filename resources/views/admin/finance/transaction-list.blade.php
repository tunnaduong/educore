<div class="container-fluid">
    @include('components.language')
    <!-- Bảng danh sách giao dịch -->
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-primary text-white">
            <h6 class="mb-0">
                <i class="bi bi-list-ul mr-2"></i>Lịch sử giao dịch gần đây
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="8%">#</th>
                        <th width="12%">
                            <i class="bi bi-arrow-down-up mr-1"></i>Loại
                        </th>
                        <th width="12%">
                            <i class="bi bi-person-badge mr-1"></i>Mã
                        </th>
                        <th width="18%">
                            <i class="bi bi-tag-fill mr-1"></i>Khoản mục
                        </th>
                        <th width="15%">
                            <i class="bi bi-cash-stack mr-1"></i>Số tiền
                        </th>
                        <th width="15%">
                            <i class="bi bi-calendar-event mr-1"></i>Ngày tạo
                        </th>
                        <th width="12%">
                            <i class="bi bi-person-fill mr-1"></i>Người thực hiện
                        </th>
                        <th width="8%">
                            <i class="bi bi-chat-left-text mr-1"></i>Ghi chú
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tran)
                        <tr class="align-middle">
                            <td class="text-center fw-bold text-primary">{{ $tran['id'] }}</td>
                            <td>
                                @if ($tran['type'] === 'income')
                                    <span class="badge bg-success bg-opacity-90 shadow-sm">
                                        <i class="bi bi-arrow-down-circle-fill mr-1"></i>Thu
                                    </span>
                                @else
                                    <span class="badge bg-danger bg-opacity-90 shadow-sm">
                                        <i class="bi bi-arrow-up-circle-fill mr-1"></i>Chi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <code class="bg-light px-2 py-1 rounded">{{ $tran['user_code'] }}</code>
                            </td>
                            <td>
                                @php
                                    $typeMap = [
                                        'tuition' => [
                                            'label' => 'Học phí',
                                            'icon' => 'bi-mortarboard-fill',
                                            'color' => 'primary',
                                        ],
                                        'material' => [
                                            'label' => 'Tài liệu',
                                            'icon' => 'bi-book-fill',
                                            'color' => 'info',
                                        ],
                                        'other' => [
                                            'label' => 'Khác',
                                            'icon' => 'bi-three-dots',
                                            'color' => 'secondary',
                                        ],
                                    ];
                                    $item = $typeMap[$tran['item']] ?? [
                                        'label' => $tran['item'],
                                        'icon' => 'bi-question-circle',
                                        'color' => 'secondary',
                                    ];
                                @endphp
                                <span
                                    class="badge bg-{{ $item['color'] }} bg-opacity-10 text-{{ $item['color'] }} border border-{{ $item['color'] }}">
                                    <i class="bi {{ $item['icon'] }} mr-1"></i>{{ $item['label'] }}
                                </span>
                            </td>
                            <td>
                                @if ($tran['type'] === 'income')
                                    <span class="fw-bold text-success">+{{ number_format($tran['amount']) }}₫</span>
                                @else
                                    <span class="fw-bold text-danger">-{{ number_format($tran['amount']) }}₫</span>
                                @endif
                            </td>
                            <td>
                                <div class="small">{{ $tran['created_at'] }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <small class="fw-semibold">{{ $tran['operator'] }}</small>
                                </div>
                            </td>
                            <td>
                                @if ($tran['note'])
                                    <i class="bi bi-chat-left-fill text-primary" title="{{ $tran['note'] }}"></i>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-journal-x text-muted" style="font-size: 3rem;"></i>
                                <div class="mt-2 text-muted fs-5">Chưa có giao dịch nào</div>
                                <small class="text-muted">Các giao dịch thu chi sẽ hiển thị tại đây</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%) !important;
        }
    </style>
</div>
