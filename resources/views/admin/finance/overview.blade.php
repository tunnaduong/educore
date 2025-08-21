<div class="container-fluid">
    @include('components.language')
    <!-- Bộ lọc ngày tháng -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0 text-primary">
                <i class="bi bi-funnel-fill mr-2"></i>Bộ lọc thời gian
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-event text-success mr-1"></i>Từ ngày
                    </label>
                    <input type="date" class="form-control form-control-lg" wire:model.lazy="fromDate">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-calendar-event text-danger mr-1"></i>Đến ngày
                    </label>
                    <input type="date" class="form-control form-control-lg" wire:model.lazy="toDate">
                </div>
                <div class="col-md-12">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle mr-1"></i>
                        Dữ liệu sẽ được cập nhật tự động khi thay đổi ngày
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row g-4 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-success">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="small fw-bold text-white-50 text-uppercase mb-1">Tổng thu</div>
                            <div class="h4 mb-0 text-white">{{ number_format($totalIncome) }}₫</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-cash-coin text-white-50" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-opacity-10 border-0 py-2">
                    <div class="small text-white-50">
                        <i class="bi bi-arrow-up mr-1"></i>Doanh thu từ học phí, tài liệu
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 bg-gradient-danger">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="small fw-bold text-white-50 text-uppercase mb-1">Tổng chi</div>
                            <div class="h4 mb-0 text-white">{{ number_format($totalExpense) }}₫</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-wallet2 text-white-50" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-opacity-10 border-0 py-2">
                    <div class="small text-white-50">
                        <i class="bi bi-arrow-down mr-1"></i>Chi phí vận hành, lương
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-12">
            <div
                class="card border-0 shadow-sm h-100 {{ $profit >= 0 ? 'bg-gradient-primary' : 'bg-gradient-warning' }}">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="small fw-bold text-white-50 text-uppercase mb-1">
                                {{ $profit >= 0 ? 'Lợi nhuận' : 'Thua lỗ' }}
                            </div>
                            <div class="h4 mb-0 text-white">{{ number_format(abs($profit)) }}₫</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi {{ $profit >= 0 ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }} text-white-50"
                                style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-opacity-10 border-0 py-2">
                    <div class="small text-white-50">
                        <i class="bi {{ $profit >= 0 ? 'bi-trophy' : 'bi-exclamation-triangle' }} mr-1"></i>
                        {{ $profit >= 0 ? 'Tình hình kinh doanh tốt' : 'Cần xem xét chi phí' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-success {
            background: linear-gradient(135deg, #198754 0%, #20c997 100%) !important;
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%) !important;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #6f42c1 100%) !important;
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%) !important;
        }
    </style>
</div>
