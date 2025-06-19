<?php

namespace App\Livewire\Classrooms;

use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class AssignStudents extends Component
{
    use WithPagination;

    public $classroomId;
    public $classroom;
    public $search = '';
    public $selectedStudents = [];
    public $showModal = false;

    protected $queryString = ['search'];

    public function mount($classroom)
    {
        $this->classroomId = $classroom;
        $this->classroom = Classroom::findOrFail($this->classroomId);
        $this->loadSelectedStudents();
    }

    public function loadSelectedStudents()
    {
        $this->selectedStudents = $this->classroom->students()->pluck('users.id')->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleStudent($studentId)
    {
        if (in_array($studentId, $this->selectedStudents)) {
            $this->selectedStudents = array_diff($this->selectedStudents, [$studentId]);
        } else {
            $this->selectedStudents[] = $studentId;
        }
    }

    public function selectAll()
    {
        $availableStudents = $this->getAvailableStudentsQuery()->pluck('id')->toArray();
        $this->selectedStudents = array_unique(array_merge($this->selectedStudents, $availableStudents));
    }

    public function deselectAll()
    {
        $this->selectedStudents = [];
    }

    public function toggleSelectAll()
    {
        $availableStudentIds = $this->getAvailableStudentsQuery()->pluck('id')->toArray();
        
        // Nếu tất cả học viên đã được chọn thì bỏ chọn tất cả
        if (count(array_intersect($availableStudentIds, $this->selectedStudents)) == count($availableStudentIds)) {
            $this->selectedStudents = array_diff($this->selectedStudents, $availableStudentIds);
        } else {
            // Nếu chưa chọn tất cả thì chọn tất cả
            $this->selectedStudents = array_unique(array_merge($this->selectedStudents, $availableStudentIds));
        }
    }

    public function assignStudents()
    {
        // Xóa tất cả học viên hiện tại khỏi lớp
        $this->classroom->students()->detach();

        // Gán học viên mới được chọn
        if (!empty($this->selectedStudents)) {
            $studentData = [];
            foreach ($this->selectedStudents as $studentId) {
                $studentData[$studentId] = ['role' => 'student'];
            }
            $this->classroom->students()->attach($studentData);
        }

        $this->showModal = false;
        session()->flash('message', 'Đã cập nhật danh sách học viên thành công!');
    }

    public function getAvailableStudentsQuery()
    {
        return User::where('role', 'student')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
    }

    public function render()
    {
        $availableStudents = $this->getAvailableStudentsQuery()
            ->orderBy('name')
            ->paginate(10);

        $enrolledStudents = $this->classroom->students()
            ->orderBy('name')
            ->get();

        return view('livewire.classrooms.assign-students', [
            'availableStudents' => $availableStudents,
            'enrolledStudents' => $enrolledStudents,
            'classroom' => $this->classroom,
            'selectedStudents' => $this->selectedStudents,
        ]);
    }
}
