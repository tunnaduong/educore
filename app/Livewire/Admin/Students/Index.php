<?php

namespace App\Livewire\Admin\Students;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = '';

    public $classroomFilter = '';

    protected $queryString = ['search', 'statusFilter', 'classroomFilter'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function updatedClassroomFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->classroomFilter = '';
        $this->resetPage();
    }

    public function delete($studentId)
    {
        $student = User::findOrFail($studentId);

        // Kiểm tra trạng thái học viên
        if ($student->studentProfile && $student->studentProfile->status === 'active') {
            session()->flash('error', 'Không thể xóa học viên đang học. Vui lòng chuyển trạng thái sang "Nghỉ" hoặc "Bảo lưu" trước khi xóa.');
            return;
        }

        // Kiểm tra xem học viên có đang tham gia lớp học nào không
        if ($student->enrolledClassrooms()->where('status', 'active')->exists()) {
            session()->flash('error', 'Không thể xóa học viên đang tham gia lớp học. Vui lòng rút học viên khỏi lớp trước khi xóa.');
            return;
        }

        // Xóa studentProfile trước (nếu có)
        if ($student->studentProfile) {
            $student->studentProfile->delete();
        }

        // Xóa user
        $student->delete();
        session()->flash('message', 'Đã xóa học viên thành công!');
    }

    public function render()
    {
        $query = User::where('role', 'student')
            ->with(['studentProfile', 'enrolledClassrooms']);

        // Tìm kiếm
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('phone', 'like', '%'.$this->search.'%');
            });
        }

        // Lọc theo trạng thái
        if ($this->statusFilter) {
            $query->whereHas('studentProfile', function ($q) {
                $q->where('status', $this->statusFilter);
            });
        }

        // Lọc theo lớp học
        if ($this->classroomFilter) {
            $query->whereHas('enrolledClassrooms', function ($q) {
                $q->where('classrooms.id', $this->classroomFilter);
            });
        }

        $students = $query->orderBy('name')->paginate(10);

        // Lấy danh sách lớp học để filter
        $classrooms = \App\Models\Classroom::where('status', 'active')->get();

        return view('admin.students.index', [
            'students' => $students,
            'classrooms' => $classrooms,
        ]);
    }
}
