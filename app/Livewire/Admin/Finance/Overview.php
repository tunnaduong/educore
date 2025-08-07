<?php

namespace App\Livewire\Admin\Finance;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class Overview extends Component
{
    public $fromDate;
    public $toDate;
    public $totalIncome;
    public $totalExpense;
    public $profit;

    public function mount()
    {
        $this->fromDate = Carbon::now()->startOfMonth()->toDateString();
        $this->toDate = Carbon::now()->endOfMonth()->toDateString();
        $this->loadStats();
    }

    public function updatedFromDate()
    {
        $this->loadStats();
    }

    public function updatedToDate()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalIncome = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$this->fromDate, $this->toDate])
            ->sum('amount');
        $this->totalExpense = Expense::whereBetween('spent_at', [$this->fromDate, $this->toDate])
            ->sum('amount');
        $this->profit = $this->totalIncome - $this->totalExpense;
    }

    public function render()
    {
        return view('admin.finance.overview');
    }
}
