<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Assignment;
use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Student;
use Livewire\Component;

class Index extends Component
{
    public $selectedClass = '';

    public $selectedStudent = '';

    public $classrooms = [];

    public $students = [];

    public $reportData = [];

    public function mount()
    {
        $this->classrooms = Classroom::all();
        $this->students = Student::with('user')->get();
    }

    public function render()
    {
        $query = Student::with(['user', 'classrooms']);
        if ($this->selectedClass) {
            $query->whereHas('classrooms', function ($q) {
                $q->where('class_id', $this->selectedClass);
            });
        }
        if ($this->selectedStudent) {
            $query->where('id', $this->selectedStudent);
        }
        $students = $query->get();
        $reportData = [];
        foreach ($students as $student) {
            $classNames = $student->classrooms->pluck('name')->toArray();

            if ($this->selectedClass) {
                $class = $student->classrooms->where('id', $this->selectedClass)->first();
                if (! $class) {
                    continue;
                }
                $assignments = Assignment::where('class_id', $class->id)->get();
                $submissions = $student->assignmentSubmissions->filter(function ($sub) use ($class) {
                    return $sub->assignment && $sub->assignment->class_id == $class->id;
                });
                $attendanceCount = Attendance::where('student_id', $student->id)
                    ->where('class_id', $class->id)
                    ->where('present', true)->count();
                $quizResults = $student->quizResults->filter(function ($qr) use ($class) {
                    return $qr->quiz && $qr->quiz->class_id == $class->id;
                });
                // Tiến độ học tập theo lesson
                $userModel = $student->user;
                $lessonIds = \App\Models\Lesson::where('classroom_id', $class->id)->pluck('id');
                $completedLessons = $userModel->lessons()->whereIn('lesson_id', $lessonIds)->whereNotNull('lesson_user.completed_at')->count();
                $totalLessons = $lessonIds->count();
                $progress = $totalLessons > 0 ? round($completedLessons / $totalLessons * 100) : 0;
            } else {
                // Tổng hợp tất cả lớp
                $classIds = $student->classrooms->pluck('id');
                if ($classIds->count() == 0) {
                    continue;
                }
                $assignments = Assignment::whereIn('class_id', $classIds)->get();
                $submissions = $student->assignmentSubmissions->filter(function ($sub) use ($classIds) {
                    return $sub->assignment && in_array($sub->assignment->class_id, $classIds->toArray());
                });
                $attendanceCount = Attendance::where('student_id', $student->id)
                    ->whereIn('class_id', $classIds)
                    ->where('present', true)->count();
                $quizResults = $student->quizResults->filter(function ($qr) use ($classIds) {
                    return $qr->quiz && in_array($qr->quiz->class_id, $classIds->toArray());
                });
                // Tiến độ học tập theo lesson
                $userModel = $student->user;
                $lessonIds = \App\Models\Lesson::whereIn('classroom_id', $classIds)->pluck('id');
                $completedLessons = $userModel->lessons()->whereIn('lesson_id', $lessonIds)->whereNotNull('lesson_user.completed_at')->count();
                $totalLessons = $lessonIds->count();
                $progress = $totalLessons > 0 ? round($completedLessons / $totalLessons * 100) : 0;
            }

            $avgScore = $quizResults->avg('score') ?? 0;
            $submitRate = $assignments->count() > 0
                ? round($submissions->count() / $assignments->count() * 100)
                : 0;
            $needSupport = $avgScore < 5 || $submitRate < 60 || $progress < 60;
            $reportData[] = [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'class_names' => $classNames,
                'progress' => $progress,
                'avg_score' => round($avgScore, 2),
                'submit_rate' => $submitRate,
                'attendance_count' => $attendanceCount,
                'need_support' => $needSupport,
            ];
        }
        $this->reportData = $reportData;

        return view('admin.reports.index', [
            'classrooms' => $this->classrooms,
            'students' => $this->students,
            'reportData' => $this->reportData,
        ]);
    }
}
