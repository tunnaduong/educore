<?php

namespace App\Livewire\Admin\Finance;

use Livewire\Component;
use App\Models\Expense;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Carbon;

class ExpenseManagement extends Component
{
    public $expenses;
    public $showCreateModal = false;
    public $editingExpenseId = null;
    
    // Form fields
    public $amount;
    public $type = 'salary';
    public $note;
    public $spent_at;
    public $staff_id;
    public $class_id;
    
    // Filters
    public $filterType = '';
    public $filterMonth = '';
    public $filterStaff = '';

    protected $rules = [
        'amount' => 'required|numeric|min:1',
        'type' => 'required|string|in:salary,utilities,maintenance,supplies,marketing,training,other',
        'note' => 'nullable|string|max:500',
        'spent_at' => 'required|date',
        'staff_id' => 'nullable|exists:users,id',
        'class_id' => 'nullable|exists:classrooms,id',
    ];

    public function mount()
    {
        $this->spent_at = Carbon::now()->format('Y-m-d');
        $this->filterMonth = Carbon::now()->format('Y-m');
        $this->loadExpenses();
    }

    public function updatedFilterType() { $this->loadExpenses(); }
    public function updatedFilterMonth() { $this->loadExpenses(); }
    public function updatedFilterStaff() { $this->loadExpenses(); }

    public function loadExpenses()
    {
        $query = Expense::with(['staff', 'classroom']);
        
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }
        
        if ($this->filterMonth) {
            $query->whereYear('spent_at', Carbon::parse($this->filterMonth)->year)
                  ->whereMonth('spent_at', Carbon::parse($this->filterMonth)->month);
        }
        
        if ($this->filterStaff) {
            $query->where('staff_id', $this->filterStaff);
        }
        
        $this->expenses = $query->orderBy('spent_at', 'desc')->get();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->editingExpenseId = null;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->amount = '';
        $this->type = 'salary';
        $this->note = '';
        $this->spent_at = Carbon::now()->format('Y-m-d');
        $this->staff_id = null;
        $this->class_id = null;
    }

    public function createExpense()
    {
        $this->validate();
        
        Expense::create([
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note,
            'spent_at' => $this->spent_at,
            'staff_id' => $this->staff_id,
            'class_id' => $this->class_id,
        ]);
        
        $this->closeCreateModal();
        $this->loadExpenses();
        session()->flash('success', 'Thêm khoản chi mới thành công!');
    }

    public function editExpense($expenseId)
    {
        $expense = Expense::findOrFail($expenseId);
        $this->editingExpenseId = $expenseId;
        $this->amount = $expense->amount;
        $this->type = $expense->type;
        $this->note = $expense->note;
        $this->spent_at = $expense->spent_at->format('Y-m-d');
        $this->staff_id = $expense->staff_id;
        $this->class_id = $expense->class_id;
        $this->showCreateModal = true;
    }

    public function updateExpense()
    {
        $this->validate();
        
        $expense = Expense::findOrFail($this->editingExpenseId);
        $expense->update([
            'amount' => $this->amount,
            'type' => $this->type,
            'note' => $this->note,
            'spent_at' => $this->spent_at,
            'staff_id' => $this->staff_id,
            'class_id' => $this->class_id,
        ]);
        
        $this->closeCreateModal();
        $this->loadExpenses();
        session()->flash('success', 'Cập nhật khoản chi thành công!');
    }

    public function deleteExpense($expenseId)
    {
        $expense = Expense::findOrFail($expenseId);
        $expense->delete();
        $this->loadExpenses();
        session()->flash('success', 'Xóa khoản chi thành công!');
    }

    public function getStaffsProperty()
    {
        return User::where('role', 'admin')->orWhere('role', 'teacher')->get();
    }

    public function getClassroomsProperty()
    {
        return Classroom::all();
    }

    public function render()
    {
        return view('admin.finance.expense-management');
    }
}
