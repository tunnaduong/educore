<?php

namespace App\Livewire\Admin\Finance;

use App\Models\Expense;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Livewire\Component;

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
        $fromDate = $this->fromDate ? Carbon::parse($this->fromDate)->startOfDay() : Carbon::now()->startOfMonth();
        $toDate = $this->toDate ? Carbon::parse($this->toDate)->endOfDay() : Carbon::now()->endOfMonth();

        $this->totalIncome = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$fromDate, $toDate])
            ->sum('amount');

        $this->totalExpense = Expense::whereBetween('spent_at', [$fromDate, $toDate])
            ->sum('amount');

        $this->profit = $this->totalIncome - $this->totalExpense;
    }

    public function render()
    {
        return view('admin.finance.overview');
    }
}
