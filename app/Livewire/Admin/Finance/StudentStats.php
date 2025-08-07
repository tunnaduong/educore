<?php

namespace App\Livewire\Admin\Finance;

use Livewire\Component;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Payment;

class StudentStats extends Component
{
    public $students;
    public $filterClass = '';
    public $filterStatus = '';
    public $filterCourse = '';

    public function mount()
    {
        $this->loadStudents();
    }

    public function updatedFilterClass()
    {
        $this->loadStudents();
    }
    public function updatedFilterStatus()
    {
        $this->loadStudents();
    }
    public function updatedFilterCourse()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $query = User::where('role', 'student');
        if ($this->filterClass) {
            $query->whereHas('enrolledClassrooms', function ($q) {
                $q->where('name', $this->filterClass);
            });
        }
        $students = $query->with('enrolledClassrooms')->get();
        $this->students = $students->map(function ($student) {
            $classrooms = $student->enrolledClassrooms->map(function ($class) use ($student) {
                $totalPaid = Payment::where('user_id', $student->id)
                    ->where('class_id', $class->id)
                    ->where('status', 'paid')
                    ->sum('amount');
                $required = 5000000; // Giả sử học phí chuẩn là 5 triệu
                $status = $totalPaid >= $required ? 'paid' : ($totalPaid > 0 ? 'partial' : 'unpaid');
                return [
                    'class_id' => $class->id,
                    'class_name' => $class->name,
                    'status' => $status,
                ];
            });
            return [
                'id' => $student->id,
                'name' => $student->name,
                'classes' => $classrooms,
            ];
        });
    }

    public function render()
    {
        return view('admin.finance.student-stats');
    }
}
