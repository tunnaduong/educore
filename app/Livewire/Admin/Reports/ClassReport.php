<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\QuizResult;

class ClassReport extends Component
{
    public $classroom;
    public $reportData = [];

    public function mount($classroom)
    {
        $this->classroom = Classroom::findOrFail($classroom);
        $students = $this->classroom->students()->with('studentProfile', 'studentProfile.assignmentSubmissions', 'studentProfile.quizResults')->get();
        $assignments = Assignment::where('class_id', $this->classroom->id)->get();
        $reportData = [];
        foreach ($students as $user) {
            $student = $user->studentProfile;
            if (!$student) continue;
            $submissions = $student->assignmentSubmissions;
            $attendanceCount = Attendance::where('student_id', $student->id)
                ->where('class_id', $this->classroom->id)
                ->where('present', true)->count();
            $quizResults = $student->quizResults;
            $avgScore = $quizResults->avg('score') ?? 0;
            $submitRate = $assignments->count() > 0
                ? round($submissions->whereIn('assignment_id', $assignments->pluck('id'))->count() / $assignments->count() * 100)
                : 0;
            // Tính tiến độ học tập dựa trên lesson_user
            $userModel = $user;
            $lessonIds = \App\Models\Lesson::where('classroom_id', $this->classroom->id)->pluck('id');
            $completedLessons = $userModel->lessons()->whereIn('lesson_id', $lessonIds)->whereNotNull('lesson_user.completed_at')->count();
            $totalLessons = $lessonIds->count();
            $progress = $totalLessons > 0 ? round($completedLessons / $totalLessons * 100) : 0;
            $needSupport = $avgScore < 5 || $submitRate < 60 || $progress < 60;
            $reportData[] = [
                'student_id' => $student->id,
                'student_name' => $user->name,
                'progress' => $progress,
                'avg_score' => round($avgScore, 2),
                'submit_rate' => $submitRate,
                'attendance_count' => $attendanceCount,
                'need_support' => $needSupport,
            ];
        }
        $this->reportData = $reportData;
    }

    public function render()
    {
        return view('admin.reports.class-report', [
            'classroom' => $this->classroom,
            'reportData' => $this->reportData,
        ]);
    }
}
