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
                // Tính tổng số tiền đã thanh toán (bao gồm cả paid và partial)
                $totalPaid = Payment::where('user_id', $student->id)
                    ->where('class_id', $class->id)
                    ->whereIn('status', ['paid', 'partial'])
                    ->sum('amount');
                
                // Tính tổng số tiền cần đóng (tất cả payments của học viên trong lớp)
                $totalRequired = Payment::where('user_id', $student->id)
                    ->where('class_id', $class->id)
                    ->sum('amount');
                
                // Xác định trạng thái dựa trên tỷ lệ đã đóng
                if ($totalRequired == 0) {
                    $status = 'unpaid'; // Chưa có payment nào
                } elseif ($totalPaid >= $totalRequired) {
                    $status = 'paid'; // Đã đóng đủ
                } elseif ($totalPaid > 0) {
                    $status = 'partial'; // Đã đóng một phần
                } else {
                    $status = 'unpaid'; // Chưa đóng gì
                }
                
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
