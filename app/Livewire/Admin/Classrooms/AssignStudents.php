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

    protected $paginationTheme = 'bootstrap';

    public $classroomId;

    public $classroom;

    public $search = '';

    public $selectedStudents = [];

    public $showModal = false;

    public $scheduleConflicts = [];

    public $showConflictModal = false;

    protected $queryString = ['search', 'selectedStudents'];

    public function mount($classroom)
    {
        $this->classroomId = $classroom;
        $this->classroom = Classroom::findOrFail($this->classroomId);

        // Chỉ load selected students nếu chưa có từ queryString
        if (empty($this->selectedStudents)) {
            $this->loadSelectedStudents();
        } else {
            // Đảm bảo selectedStudents là array và chứa integers
            $this->selectedStudents = array_map('intval', (array) $this->selectedStudents);
            $this->selectedStudents = array_values($this->selectedStudents);
        }
    }

    public function loadSelectedStudents()
    {
        $this->selectedStudents = $this->classroom->students()->pluck('users.id')->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatingPage()
    {
        // Đảm bảo selectedStudents được giữ lại khi chuyển trang
        // Không cần làm gì đặc biệt vì đã thêm vào queryString
    }

    public function toggleStudent($studentId)
    {
        // Đảm bảo selectedStudents là array
        if (! is_array($this->selectedStudents)) {
            $this->selectedStudents = [];
        }

        // Convert to integer để tránh type mismatch
        $studentId = (int) $studentId;

        if (in_array($studentId, $this->selectedStudents)) {
            $this->selectedStudents = array_diff($this->selectedStudents, [$studentId]);
        } else {
            $this->selectedStudents[] = $studentId;
        }

        // Re-index array để tránh gaps
        $this->selectedStudents = array_values($this->selectedStudents);
    }

    public function selectAll()
    {
        // Đảm bảo selectedStudents là array
        if (! is_array($this->selectedStudents)) {
            $this->selectedStudents = [];
        }

        $availableStudents = $this->getAvailableStudentsQuery()->pluck('id')->toArray();
        $this->selectedStudents = array_unique(array_merge($this->selectedStudents, $availableStudents));
        $this->selectedStudents = array_values($this->selectedStudents);
    }

    public function deselectAll()
    {
        $this->selectedStudents = [];
    }

    public function toggleSelectAll()
    {
        // Đảm bảo selectedStudents là array
        if (! is_array($this->selectedStudents)) {
            $this->selectedStudents = [];
        }

        $availableStudentIds = $this->getAvailableStudentsQuery()->pluck('id')->toArray();

        // Nếu tất cả học viên đã được chọn thì bỏ chọn tất cả
        if (count(array_intersect($availableStudentIds, $this->selectedStudents)) == count($availableStudentIds)) {
            $this->selectedStudents = array_diff($this->selectedStudents, $availableStudentIds);
        } else {
            // Nếu chưa chọn tất cả thì chọn tất cả
            $this->selectedStudents = array_unique(array_merge($this->selectedStudents, $availableStudentIds));
        }

        $this->selectedStudents = array_values($this->selectedStudents);
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

    public function getSelectedStudentsData()
    {
        // Đảm bảo selectedStudents là array và có giá trị
        if (! is_array($this->selectedStudents) || empty($this->selectedStudents)) {
            return collect([]);
        }

        // Lấy thông tin đầy đủ của tất cả học viên đã chọn
        return User::where('role', 'student')
            ->whereIn('id', $this->selectedStudents)
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        $availableStudents = $this->getAvailableStudentsQuery()
            ->orderBy('name')
            ->paginate(10);

        $enrolledStudents = $this->classroom->students()
            ->orderBy('name')
            ->get();

        // Lấy thông tin đầy đủ của tất cả học viên đã chọn (từ mọi trang)
        $selectedStudentsData = $this->getSelectedStudentsData();

        return view('admin.classrooms.assign-students', [
            'availableStudents' => $availableStudents,
            'enrolledStudents' => $enrolledStudents,
            'selectedStudentsData' => $selectedStudentsData,
            'classroom' => $this->classroom,
            'selectedStudents' => $this->selectedStudents,
        ]);
    }
}
