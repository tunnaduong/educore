<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <label>Từ ngày</label>
            <input type="date" class="form-control" wire:model="fromDate">
        </div>
        <div class="col-md-3">
            <label>Đến ngày</label>
            <input type="date" class="form-control" wire:model="toDate">
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-success h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Tổng thu</h5>
                    <p class="card-text display-4">{{ number_format($totalIncome) }}₫</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-danger h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Tổng chi</h5>
                    <p class="card-text display-4">{{ number_format($totalExpense) }}₫</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white bg-primary h-100">
                <div class="card-body text-center">
                    <h5 class="card-title">Lợi nhuận</h5>
                    <p class="card-text display-4">{{ number_format($profit) }}₫</p>
                </div>
            </div>
        </div>
    </div>
</div>
