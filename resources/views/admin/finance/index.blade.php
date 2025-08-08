<x-layouts.dash-admin active="finance" title="Thống kê thu chi">
    @include('components.language')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 text-success fs-4">
                    <i class="bi bi-cash-coin mr-2"></i>Thống kê thu chi
                </h4>
                <p class="text-muted mb-0">Quản lý, tổng hợp và theo dõi các khoản thu (học phí, tài liệu...) và chi (lương,
                    vận hành...) cho trung tâm.</p>
            </div>
            <div>
                <a href="{{ route('admin.finance.expenses') }}" class="btn btn-danger btn-lg shadow-sm" wire:navigate>
                    <i class="bi bi-wallet2 mr-2"></i>Quản lý chi tiêu
                </a>
            </div>
        </div>
        @livewire('admin.finance.overview')
        <hr>
        @livewire('admin.finance.student-stats')
        <hr>
        @livewire('admin.finance.transaction-list')
    </div>
</x-layouts.dash-admin>
