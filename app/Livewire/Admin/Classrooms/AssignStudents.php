<?php

namespace App\Livewire\Admin\Classrooms;

use App\Helpers\ScheduleConflictHelper;
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

    public $scheduleConflicts = [];

    public $showConflictModal = false;

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
        // Kiểm tra trùng lịch trước khi gán học sinh
        $conflictCheck = ScheduleConflictHelper::checkMultipleStudentsScheduleConflict(
            $this->selectedStudents,
            $this->classroom
        );

        if ($conflictCheck['hasConflict']) {
            $this->scheduleConflicts = $conflictCheck['conflicts'];
            $this->showConflictModal = true;

            return;
        }

        // Nếu không có trùng lịch, tiến hành gán học sinh
        $this->performAssignment();
    }

    public function performAssignment()
    {
        // Đồng bộ danh sách học viên theo lựa chọn hiện tại
        // - Không dùng detach/attach để tránh reset created_at của các học viên cũ
        // - Sử dụng sync để:
        //   + Giữ nguyên các bản ghi đang tồn tại (bảo toàn created_at)
        //   + Thêm mới các học viên được chọn (tạo created_at mới)
        //   + Gỡ các học viên bị bỏ chọn khỏi lớp
        $studentData = [];
        foreach ($this->selectedStudents as $studentId) {
            $studentData[$studentId] = ['role' => 'student'];
        }

        // Gọi sync trên quan hệ students() để chỉ tác động đến role = 'student'
        // (không ảnh hưởng đến giáo viên trong bảng pivot class_user)
        $this->classroom->students()->sync($studentData);

        $this->showModal = false;
        $this->showConflictModal = false;
        session()->flash('message', 'Đã cập nhật danh sách học viên thành công!');
    }

    public function forceAssignStudents()
    {
        // Gán học sinh bất chấp trùng lịch
        $this->performAssignment();
        session()->flash('warning', 'Đã gán học sinh bất chấp trùng lịch. Vui lòng kiểm tra lại lịch học!');
    }

    public function closeConflictModal()
    {
        $this->showConflictModal = false;
        $this->scheduleConflicts = [];
    }

    public function getAvailableStudentsQuery()
    {
        return User::where('role', 'student')
            ->where('is_active', true)
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%');
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

        return view('admin.classrooms.assign-students', [
            'availableStudents' => $availableStudents,
            'enrolledStudents' => $enrolledStudents,
            'classroom' => $this->classroom,
            'selectedStudents' => $this->selectedStudents,
        ]);
    }
}
