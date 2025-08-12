<?php

namespace App\Livewire\Admin\AI;

use Livewire\Component;
use App\Models\AssignmentSubmission;
use App\Models\Assignment;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $recentSubmissions = [];
    public $availableAssignments = [];
    public $classrooms = [];

    public function mount()
    {
        $teacher = Auth::user();

        // Lấy các bài nộp gần đây có thể chấm điểm bằng AI
        $teacherClassroomIds = $teacher->teachingClassrooms()->pluck('classrooms.id');

        $this->recentSubmissions = AssignmentSubmission::whereHas('assignment', function ($query) use ($teacherClassroomIds) {
            $query->whereIn('class_id', $teacherClassroomIds);
        })
            ->whereNotNull('submitted_at')
            ->where(function ($query) {
                $query->whereNull('score')
                    ->whereNull('ai_score');
            })
            ->with(['assignment', 'student'])
            ->latest()
            ->take(5)
            ->get();

        // Lấy các bài tập có thể tạo quiz từ đó
        $this->availableAssignments = Assignment::whereIn('class_id', $teacherClassroomIds)
            ->with('classroom')
            ->latest()
            ->take(10)
            ->get();

        // Lấy các lớp học của giáo viên
        $this->classrooms = $teacher->teachingClassrooms()->with('students')->get();
    }

    public function render()
    {
        return view('admin.ai.index', [
            'recentSubmissions' => $this->recentSubmissions,
            'availableAssignments' => $this->availableAssignments,
            'classrooms' => $this->classrooms,
        ]);
    }
}
