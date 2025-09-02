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

        // Tính toán thống kê cho mỗi học sinh
        foreach ($students as $student) {
            if ($student->studentProfile) {
                $student->stats = $this->calculateStudentStats($student);
            } else {
                $student->stats = [
                    'studySessions' => 0,
                    'averageScore' => 0,
                    'completedAssignments' => 0,
                    'attendanceRate' => 0,
                ];
            }
        }

        // Lấy danh sách lớp học để filter
        $classrooms = \App\Models\Classroom::where('status', 'active')->get();

        return view('admin.students.index', [
            'students' => $students,
            'classrooms' => $classrooms,
        ]);
    }

    private function calculateStudentStats($student)
    {
        if (! $student->studentProfile) {
            return [
                'studySessions' => 0,
                'averageScore' => 0,
                'completedAssignments' => 0,
                'attendanceRate' => 0,
            ];
        }

        $studentId = $student->studentProfile->id;
        $classIds = $student->enrolledClassrooms->pluck('id')->toArray();

        if (empty($classIds)) {
            return [
                'studySessions' => 0,
                'averageScore' => 0,
                'completedAssignments' => 0,
                'attendanceRate' => 0,
            ];
        }

        // Tính số buổi học (tổng số lần điểm danh)
        $studySessions = \App\Models\Attendance::where('student_id', $studentId)
            ->whereIn('class_id', $classIds)
            ->count();

        // Tính điểm trung bình từ quiz
        $quizResults = \App\Models\QuizResult::where('student_id', $studentId)
            ->whereHas('quiz', function ($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })
            ->get();

        $averageScore = $quizResults->count() > 0
            ? round($quizResults->avg('score'), 2)
            : 0;

        // Tính số bài tập đã hoàn thành
        $completedAssignments = \App\Models\AssignmentSubmission::where('student_id', $studentId)
            ->whereHas('assignment', function ($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })
            ->whereNotNull('score')
            ->count();

        // Tính tỷ lệ điểm danh
        $totalAttendanceDays = \App\Models\Attendance::where('student_id', $studentId)
            ->whereIn('class_id', $classIds)
            ->count();

        $presentDays = \App\Models\Attendance::where('student_id', $studentId)
            ->whereIn('class_id', $classIds)
            ->where('present', true)
            ->count();

        $attendanceRate = $totalAttendanceDays > 0
            ? round(($presentDays / $totalAttendanceDays) * 100, 1)
            : 0;

        return [
            'studySessions' => $studySessions,
            'averageScore' => $averageScore,
            'completedAssignments' => $completedAssignments,
            'attendanceRate' => $attendanceRate,
        ];
    }
}
