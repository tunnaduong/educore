<?php

namespace App\Livewire\Admin\Students;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\QuizResult;
use App\Models\Lesson;
use Livewire\Component;

class Show extends Component
{
    public User $student;
    public $studySessions = 0;
    public $averageScore = 0;
    public $completedAssignments = 0;
    public $attendanceRate = 0;

    public function mount($student)
    {
        $this->student = $student->load(['studentProfile', 'enrolledClassrooms']);
        $this->calculateStatistics();
    }

    public function calculateStatistics()
    {
        if (!$this->student->studentProfile) {
            return;
        }

        $studentId = $this->student->studentProfile->id;
        $classIds = $this->student->enrolledClassrooms->pluck('id')->toArray();

        if (empty($classIds)) {
            return;
        }

        // Tính số buổi học (tổng số lần điểm danh)
        $this->studySessions = Attendance::where('student_id', $studentId)
            ->whereIn('class_id', $classIds)
            ->count();

        // Tính điểm trung bình từ quiz
        $quizResults = QuizResult::where('student_id', $studentId)
            ->whereHas('quiz', function ($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })
            ->get();

        $this->averageScore = $quizResults->count() > 0
            ? round($quizResults->avg('score'), 2)
            : 0;

        // Tính số bài tập đã hoàn thành
        $this->completedAssignments = AssignmentSubmission::where('student_id', $studentId)
            ->whereHas('assignment', function ($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })
            ->whereNotNull('score')
            ->count();

        // Tính tỷ lệ điểm danh
        $totalAttendanceDays = Attendance::where('student_id', $studentId)
            ->whereIn('class_id', $classIds)
            ->count();

        $presentDays = Attendance::where('student_id', $studentId)
            ->whereIn('class_id', $classIds)
            ->where('present', true)
            ->count();

        $this->attendanceRate = $totalAttendanceDays > 0
            ? round(($presentDays / $totalAttendanceDays) * 100, 1)
            : 0;
    }

    public function render()
    {
        return view('admin.students.show', [
            'studySessions' => $this->studySessions,
            'averageScore' => $this->averageScore,
            'completedAssignments' => $this->completedAssignments,
            'attendanceRate' => $this->attendanceRate,
        ]);
    }
}
