<?php

namespace App\Livewire\Admin\Finance;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\Classroom;
use App\Models\Payment;
use App\Models\Expense;

class CourseStats extends Component
{
    public $courses;

    public function mount()
    {
        $this->loadCourses();
    }

    public function loadCourses()
    {
        $this->courses = Classroom::get()->map(function ($class) {
            $studentIds = $class->students()->pluck('users.id');
            $totalIncome = Payment::whereIn('user_id', $studentIds)
                ->where('class_id', $class->id)
                ->where('status', 'paid')
                ->sum('amount');
            $studentsPaid = Payment::whereIn('user_id', $studentIds)
                ->where('class_id', $class->id)
                ->where('status', 'paid')
                ->distinct('user_id')->count('user_id');
            $totalExpense = Expense::where('class_id', $class->id)->sum('amount');
            $profit = $totalIncome - $totalExpense;
            return [
                'id' => $class->id,
                'name' => $class->name,
                'total_students' => $studentIds->count(),
                'students_paid' => $studentsPaid,
                'total_income' => $totalIncome,
                'total_expense' => $totalExpense,
                'profit' => $profit,
            ];
        });
    }

    public function render()
    {
        return view('admin.finance.course-stats');
    }
}
