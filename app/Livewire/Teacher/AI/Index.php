<?php

namespace App\Livewire\Teacher\AI;

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
        $this->recentSubmissions = AssignmentSubmission::whereHas('assignment', function ($query) use ($teacher) {
            $query->whereHas('classroom', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            });
        })
            ->where('status', 'submitted')
            ->with(['assignment', 'student'])
            ->latest()
            ->take(5)
            ->get();

        // Lấy các bài tập có thể tạo quiz từ đó
        $this->availableAssignments = Assignment::whereHas('classroom', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })
            ->with('classroom')
            ->latest()
            ->take(10)
            ->get();

        // Lấy các lớp học của giáo viên
        $this->classrooms = Classroom::where('teacher_id', $teacher->id)
            ->with('students')
            ->get();
    }

    public function render()
    {
        return view('teacher.ai.index', [
            'recentSubmissions' => $this->recentSubmissions,
            'availableAssignments' => $this->availableAssignments,
            'classrooms' => $this->classrooms,
        ])->layout('components.layouts.dash-teacher', ['active' => 'ai', 'title' => 'Trợ lý AI']);
    }
}
