<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Student;
use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\QuizResult;
use App\Models\Classroom;

class StudentReport extends Component
{
    public $student;
    public $class;
    public $progress = 0;
    public $avgScore = 0;
    public $submitRate = 0;
    public $attendanceCount = 0;
    public $notSubmittedAssignments = [];
    public $needSupport = false;
    public $classNames = [];

    public function mount($student)
    {
        $this->student = Student::with(['user', 'classrooms', 'assignmentSubmissions', 'quizResults'])->findOrFail($student);
        $this->classNames = $this->student->classrooms->pluck('name')->toArray();
        $assignments = \App\Models\Assignment::whereIn('class_id', $this->student->classrooms->pluck('id'))->get();
        $submissions = $this->student->assignmentSubmissions->filter(function($sub) {
            return $sub->assignment && in_array($sub->assignment->class_id, $this->student->classrooms->pluck('id')->toArray());
        });
        $this->attendanceCount = Attendance::where('student_id', $this->student->id)
            ->whereIn('class_id', $this->student->classrooms->pluck('id'))
            ->where('present', true)->count();
        $quizResults = $this->student->quizResults->filter(function($qr) {
            return $qr->quiz && in_array($qr->quiz->class_id, $this->student->classrooms->pluck('id')->toArray());
        });
        $this->avgScore = round($quizResults->avg('score') ?? 0, 2);
        $this->submitRate = $assignments->count() > 0
            ? round($submissions->count() / $assignments->count() * 100)
            : 0;

        // Tính tiến độ học tập dựa trên lesson_user
        $user = $this->student->user;
        $lessonIds = \App\Models\Lesson::whereIn('classroom_id', $this->student->classrooms->pluck('id'))->pluck('id');
        $completedLessons = $user->lessons()->whereIn('lesson_id', $lessonIds)->whereNotNull('lesson_user.completed_at')->count();
        $totalLessons = $lessonIds->count();
        $this->progress = $totalLessons > 0 ? round($completedLessons / $totalLessons * 100) : 0;

        $this->notSubmittedAssignments = $assignments->filter(function($a) use ($submissions) {
            return !$submissions->where('assignment_id', $a->id)->count();
        });
        $this->needSupport = $this->avgScore < 5 || $this->submitRate < 60 || $this->progress < 60;
    }

    public function render()
    {
        return view('admin.reports.student-report', [
            'student' => $this->student,
            'classNames' => $this->classNames,
            'progress' => $this->progress,
            'avgScore' => $this->avgScore,
            'submitRate' => $this->submitRate,
            'attendanceCount' => $this->attendanceCount,
            'notSubmittedAssignments' => $this->notSubmittedAssignments,
            'needSupport' => $this->needSupport,
        ]);
    }
}
