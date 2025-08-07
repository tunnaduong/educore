<x-layouts.dash-admin active="finance" title="Thống kê thu chi">
    @include('components.language')
    <div class="container-fluid">
        <div class="mb-4">
            <h4 class="mb-0 text-success fs-4">
                <i class="bi bi-cash-coin mr-2"></i>Thống kê thu chi
            </h4>
            <p class="text-muted mb-0">Quản lý, tổng hợp và theo dõi các khoản thu (học phí, tài liệu...) và chi (lương,
                vận hành...) cho trung tâm.</p>
        </div>
        @livewire('admin.finance.overview')
        <hr>
        @livewire('admin.finance.student-stats')
        <hr>
        @livewire('admin.finance.transaction-list')
    </div>
</x-layouts.dash-admin>
