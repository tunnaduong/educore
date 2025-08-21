<?php

namespace App\Livewire\Student\Reports;

use App\Models\AssignmentSubmission;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $assignmentSubmissions = [];

    public $quizResults = [];

    public $avgAssignmentScore = 0;

    public $avgQuizScore = 0;

    public $attendancePresent = 0;

    public $attendanceAbsent = 0;

    public function mount()
    {
        $user = Auth::user();
        $student = $user->studentProfile ?? $user->student;
        if ($student) {
            $this->assignmentSubmissions = AssignmentSubmission::with(['assignment.classroom'])
                ->where('student_id', $student->id)
                ->orderByDesc('submitted_at')
                ->get();
            $this->quizResults = QuizResult::with(['quiz.classroom'])
                ->where('student_id', $student->id)
                ->orderByDesc('submitted_at')
                ->get();
            // Tính điểm trung bình bài tập
            $scores = $this->assignmentSubmissions->pluck('score')->filter();
            $this->avgAssignmentScore = $scores->count() ? round($scores->avg(), 2) : 0;
            // Tính điểm trung bình kiểm tra
            $quizScores = $this->quizResults->pluck('score')->filter();
            $this->avgQuizScore = $quizScores->count() ? round($quizScores->avg(), 2) : 0;
            // Điểm danh
            $attendances = $student->attendances;
            $this->attendancePresent = $attendances->where('present', true)->count();
            $this->attendanceAbsent = $attendances->where('present', false)->count();
        }
    }

    public function render()
    {
        return view('student.reports.index');
    }
}
