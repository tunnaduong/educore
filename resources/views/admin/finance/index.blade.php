<x-layouts.dash-admin active="finance" title="{{ __('views.finance_overview') }}">
    @include('components.language')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 text-success fs-4">
                    <i class="bi bi-cash-coin mr-2"></i>{{ __('views.finance_overview') }}
                </h4>
                <p class="text-muted mb-0">{{ __('views.finance_overview_description') }}</p>
            </div>
            <div>
                <a href="{{ route('admin.finance.expenses') }}" class="btn btn-danger btn-lg shadow-sm">
                    <i class="bi bi-wallet2 mr-2"></i>{{ __('views.manage_expenses_button') }}
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
