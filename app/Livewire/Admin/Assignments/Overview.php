<?php

namespace App\Livewire\Admin\Assignments;

use Livewire\Component;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Overview extends Component
{
    public $overviewStats = [];
    public $topClasses = [];
    public $recentAssignments = [];
    public $topStudents = [];
    public $monthlyStats = [];
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
        $onTimeSubmissions = $submissions->filter(function ($s) {
            return $s->submitted_at && $s->assignment && $s->submitted_at <= $s->assignment->deadline;
        })->count();
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
        $this->topClasses = $assignments->groupBy('class_id')->map(function ($items, $classId) {
            return [
                'classroom' => Classroom::find($classId),
                'total_assignments' => $items->count(),
            ];
        })->sortByDesc('total_assignments')->take(5);
        // Bài tập gần đây
        $this->recentAssignments = $assignments->sortByDesc('created_at')->take(10);
        // Top học viên nộp bài đúng hạn
        $studentStats = $submissions->groupBy('student_id')->map(function ($subs, $studentId) {
            $onTime = $subs->filter(function ($s) {
                return $s->submitted_at && $s->assignment && $s->submitted_at <= $s->assignment->deadline;
            })->count();
            return [
                'student' => Student::find($studentId)->user,
                'total_submissions' => $subs->count(),
                'on_time' => $onTime,
                'on_time_rate' => $subs->count() > 0 ? round($onTime / $subs->count() * 100, 1) : 0,
            ];
        })->sortByDesc('on_time_rate')->take(5);
        $this->topStudents = $studentStats;

        // Thống kê theo tháng
        $this->monthlyStats = collect(range(1, 12))->map(function ($month) use ($year) {
            $monthAssignments = Assignment::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            $monthSubmissions = AssignmentSubmission::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            return [
                'month' => $month,
                'month_name' => $this->getMonthName($month),
                'assignments_count' => $monthAssignments,
                'submissions_count' => $monthSubmissions,
            ];
        })->filter(function ($stat) {
            return $stat['assignments_count'] > 0 || $stat['submissions_count'] > 0;
        });
    }

    public function getMonthName($month)
    {
        $months = [1 => 'Tháng 1', 2 => 'Tháng 2', 3 => 'Tháng 3', 4 => 'Tháng 4', 5 => 'Tháng 5', 6 => 'Tháng 6', 7 => 'Tháng 7', 8 => 'Tháng 8', 9 => 'Tháng 9', 10 => 'Tháng 10', 11 => 'Tháng 11', 12 => 'Tháng 12'];
        return $months[$month] ?? '';
    }

    public function editAssignment($id)
    {
        return redirect()->route('assignments.edit', $id);
    }

    public function deleteAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        // Xóa file đính kèm nếu có
        if ($assignment->attachment_path) {
            Storage::disk('public')->delete($assignment->attachment_path);
        }
        if ($assignment->video_path) {
            Storage::disk('public')->delete($assignment->video_path);
        }
        $assignment->delete();
        session()->flash('success', 'Đã xóa bài tập thành công!');
        $this->loadStats();
    }

    public function render()
    {
        // if user is student then render student.assignments.overview
        // if user is teacher then render teacher.assignments.overview
        // if user is admin then render admin.assignments.overview
        $user = Auth::user();
        if ($user->role === 'student') {
            return view('student.assignments.overview');
        }
        if ($user->role === 'teacher') {
            return view('teacher.assignments.overview');
        }
        if ($user->role === 'admin') {
            return view('admin.assignments.overview');
        }
    }
}
