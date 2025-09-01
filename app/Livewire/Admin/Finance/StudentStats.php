<?php

namespace App\Livewire\Admin\Finance;

use App\Models\Payment;
use App\Models\User;
use Livewire\Component;

class StudentStats extends Component
{
    public $students;

    public $filterClass = '';

    public $filterStatus = '';

    public $filterCourse = '';

    public $searchTerm = '';

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

    public function updatedSearchTerm()
    {
        $this->loadStudents();
    }

    public function resetFilters()
    {
        $this->filterClass = '';
        $this->filterStatus = '';
        $this->searchTerm = '';
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $query = User::where('role', 'student');

        // Tìm kiếm theo tên học viên
        if ($this->searchTerm) {
            $query->where('name', 'like', '%'.$this->searchTerm.'%');
        }

        // Lọc theo lớp học
        if ($this->filterClass) {
            $query->whereHas('enrolledClassrooms', function ($q) {
                $q->where('name', $this->filterClass);
            });
        }

        $students = $query->with('enrolledClassrooms')->get();

        $this->students = $students->map(function ($student) {
            $classrooms = $student->enrolledClassrooms->map(function ($class) use ($student) {
                // Ưu tiên trạng thái theo mức độ: paid > partial > unpaid
                $hasPaid = Payment::where('user_id', $student->id)
                    ->where('class_id', $class->id)
                    ->where('status', 'paid')
                    ->exists();

                $hasPartial = Payment::where('user_id', $student->id)
                    ->where('class_id', $class->id)
                    ->where('status', 'partial')
                    ->exists();

                // Nếu có bất kỳ bản ghi 'paid' => paid
                // Nếu không có 'paid' nhưng có 'partial' => partial
                // Ngược lại => unpaid
                $status = $hasPaid ? 'paid' : ($hasPartial ? 'partial' : 'unpaid');

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

        // Lọc theo trạng thái thanh toán
        if ($this->filterStatus) {
            $this->students = $this->students->filter(function ($student) {
                return $student['classes']->contains('status', $this->filterStatus);
            })->values();
        }
    }

    public function render()
    {
        return view('admin.finance.student-stats');
    }
}
