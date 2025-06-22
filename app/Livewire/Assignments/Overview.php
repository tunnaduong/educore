<?php

namespace App\Livewire\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class Overview extends Component
{
    public $overviewStats = [];
    public $topClasses = [];
    public $recentAssignments = [];
    public $topStudents = [];
    public $selectedMonth;
    public $selectedYear;

    public function mount()
    {
        $this->selectedMonth = now()->month;
        $this->selectedYear = now()->year;
        $this->loadStats();
    }

    public function updatedSelectedMonth()
    {
        $this->loadStats();
    }
    public function updatedSelectedYear()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $month = $this->selectedMonth;
        $year = $this->selectedYear;
        $user = Auth::user();
        $query = Assignment::query();
        if ($user->role === 'teacher') {
            $classIds = $user->teachingClassrooms->pluck('id');
            $query->whereIn('class_id', $classIds);
        }
        $assignments = $query->whereYear('created_at', $year)->whereMonth('created_at', $month)->get();
        $assignmentIds = $assignments->pluck('id');
        $submissions = AssignmentSubmission::whereIn('assignment_id', $assignmentIds)->get();
        $totalAssignments = $assignments->count();
        $totalClasses = $assignments->pluck('class_id')->unique()->count();
        $totalSubmissions = $submissions->count();
        $onTimeSubmissions = $submissions->filter(function($s) { return $s->submitted_at && $s->assignment && $s->submitted_at <= $s->assignment->deadline; })->count();
        $submissionRate = $totalAssignments > 0 ? round($totalSubmissions / $totalAssignments * 100, 1) : 0;
        $onTimeRate = $totalSubmissions > 0 ? round($onTimeSubmissions / $totalSubmissions * 100, 1) : 0;
        $this->overviewStats = [
            'total_assignments' => $totalAssignments,
            'total_classes' => $totalClasses,
            'total_submissions' => $totalSubmissions,
            'submission_rate' => $submissionRate,
            'on_time_rate' => $onTimeRate,
        ];
        // Top lớp nhiều bài tập nhất
        $this->topClasses = $assignments->groupBy('class_id')->map(function($items, $classId) {
            return [
                'classroom' => Classroom::find($classId),
                'total_assignments' => $items->count(),
            ];
        })->sortByDesc('total_assignments')->take(5);
        // Bài tập gần đây
        $this->recentAssignments = $assignments->sortByDesc('created_at')->take(10);
        // Top học viên nộp bài đúng hạn
        $studentStats = $submissions->groupBy('student_id')->map(function($subs, $studentId) {
            $onTime = $subs->filter(function($s) { return $s->submitted_at && $s->assignment && $s->submitted_at <= $s->assignment->deadline; })->count();
            return [
                'student' => User::find($studentId),
                'total_submissions' => $subs->count(),
                'on_time' => $onTime,
                'on_time_rate' => $subs->count() > 0 ? round($onTime / $subs->count() * 100, 1) : 0,
            ];
        })->sortByDesc('on_time_rate')->take(5);
        $this->topStudents = $studentStats;
    }

    public function getMonthName($month)
    {
        $months = [1=>'Tháng 1',2=>'Tháng 2',3=>'Tháng 3',4=>'Tháng 4',5=>'Tháng 5',6=>'Tháng 6',7=>'Tháng 7',8=>'Tháng 8',9=>'Tháng 9',10=>'Tháng 10',11=>'Tháng 11',12=>'Tháng 12'];
        return $months[$month] ?? '';
    }

    public function render()
    {
        return view('livewire.assignments.overview');
    }
}
