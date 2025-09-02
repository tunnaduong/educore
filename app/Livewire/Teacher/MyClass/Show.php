<?php

namespace App\Livewire\Teacher\MyClass;

use App\Models\Attendance;
use App\Models\Classroom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public $activeTab = 'overview';

    public $showAddStudentModal = false;

    public $showAddLessonModal = false;

    public $showAddAssignmentModal = false;

    public function mount($classroomId)
    {
        $teacher = Auth::user();

        $this->classroom = Classroom::whereHas('users', function ($query) use ($teacher) {
            $query->where('user_id', $teacher->id)
                ->where('class_user.role', 'teacher');
        })
            ->with(['students', 'lessons', 'assignments'])
            ->findOrFail($classroomId);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function showAddStudentModal()
    {
        $this->showAddStudentModal = true;
    }

    public function showAddLessonModal()
    {
        $this->showAddLessonModal = true;
    }

    public function showAddAssignmentModal()
    {
        $this->showAddAssignmentModal = true;
    }

    public function closeModals()
    {
        $this->showAddStudentModal = false;
        $this->showAddLessonModal = false;
        $this->showAddAssignmentModal = false;
    }

    public function render()
    {
        // Tính tổng hợp lịch sử điểm danh theo ngày (mỗi ngày 1 dòng)
        $attendanceSessions = Attendance::forClass($this->classroom->id)
            ->orderByDesc('date')
            ->get()
            ->groupBy('date')
            ->map(function ($records, $date) {
                return [
                    'date' => Carbon::parse($date),
                    'present_count' => $records->where('present', true)->count(),
                    'absent_count' => $records->where('present', false)->count(),
                ];
            })
            ->values();

        return view('teacher.my-class.show', [
            'classroom' => $this->classroom,
            'teacher' => Auth::user(),
            'attendanceSessions' => $attendanceSessions,
        ]);
    }
}
