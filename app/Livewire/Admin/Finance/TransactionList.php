<?php

namespace App\Livewire\Admin\Finance;

use App\Models\Expense;
use App\Models\Payment;
use Livewire\Component;

class TransactionList extends Component
{
    public $transactions;

    public function mount()
    {
        $this->loadTransactions();
    }

    public function loadTransactions()
    {
        $payments = Payment::with('user')->get()->map(function ($p) {
            return [
                'id' => $p->id,
                'type' => 'income',
                'user_code' => optional($p->user)->code ?? $p->user_id,
                'item' => $p->type,
                'amount' => $p->amount,
                'created_at' => $p->paid_at,
                'operator' => $p->operator ?? 'Hệ thống',
                'note' => $p->note,
            ];
        });
        $expenses = Expense::with('staff')->get()->map(function ($e) {
            return [
                'id' => $e->id,
                'type' => 'expense',
                'user_code' => optional($e->staff)->code ?? $e->staff_id,
                'item' => $e->type,
                'amount' => $e->amount,
                'created_at' => $e->spent_at,
                'operator' => optional($e->staff)->name ?? 'Hệ thống',
                'note' => $e->note,
            ];
        });
        $this->transactions = $payments->concat($expenses)->sortByDesc('created_at')->values();
    }

    public function render()
    {
        return view('admin.finance.transaction-list');
    }
}
