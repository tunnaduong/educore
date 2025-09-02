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

    // New properties for enhanced reports
    public $activeTab = 'assignments';
    public $studentClasses = [];
    public $totalLessons = 0;
    public $totalAssignments = 0;
    public $totalStudents = 0;
    public $totalSessions = 0;
    public $classStudents = [];

    // Pagination properties
    public $perPage = 10;
    public $assignmentPage = 1;
    public $quizPage = 1;
    public $attendancePage = 1;

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

            // Get student classes and calculate statistics
            $this->studentClasses = $student->classrooms;
            $this->calculateQuickStatistics();
            $this->loadClassStudents();
        }
        // Reset pagination states
        $this->assignmentPage = 1;
        $this->quizPage = 1;
        $this->attendancePage = 1;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        if ($tab === 'students') {
            $this->loadClassStudents();
        }
        // Reset page when switching tab
        if ($tab === 'assignments') {
            $this->assignmentPage = 1;
        } elseif ($tab === 'quizzes') {
            $this->quizPage = 1;
        } elseif ($tab === 'attendance') {
            $this->attendancePage = 1;
        }
    }

    private function calculateQuickStatistics()
    {
        if ($this->studentClasses->count() > 0) {
            $classIds = $this->studentClasses->pluck('id');
            
            // Total lessons across all classes
            $this->totalLessons = \App\Models\Lesson::whereIn('classroom_id', $classIds)->count();
            
            // Total assignments across all classes
            $this->totalAssignments = \App\Models\Assignment::whereIn('class_id', $classIds)->count();
            
            // Total students across all classes (unique)
            $this->totalStudents = \App\Models\Student::whereHas('classrooms', function($query) use ($classIds) {
                $query->whereIn('class_id', $classIds);
            })->distinct()->count();
            
            // Total sessions (lessons + assignments + quizzes)
            $this->totalSessions = $this->totalLessons + $this->totalAssignments + 
                \App\Models\Quiz::whereIn('classroom_id', $classIds)->count();
        }
    }

    private function loadClassStudents()
    {
        if ($this->studentClasses->count() > 0) {
            $classIds = $this->studentClasses->pluck('id');
            
            foreach ($this->studentClasses as $class) {
                $students = \App\Models\Student::with(['user', 'assignmentSubmissions', 'quizResults'])
                    ->whereHas('classrooms', function($query) use ($class) {
                        $query->where('class_id', $class->id);
                    })->get();
                
                $this->classStudents[$class->id] = $students->map(function($student) use ($class) {
                    // Calculate student statistics for this class
                    $assignments = \App\Models\Assignment::where('class_id', $class->id)->get();
                    $submissions = $student->assignmentSubmissions->filter(function ($sub) use ($class) {
                        return $sub->assignment && $sub->assignment->class_id == $class->id;
                    });
                    $quizResults = $student->quizResults->filter(function ($qr) use ($class) {
                        return $qr->quiz && $qr->quiz->classroom_id == $class->id;
                    });
                    
                    $avgScore = $quizResults->count() > 0 ? round($quizResults->avg('score'), 2) : 0;
                    $submitRate = $assignments->count() > 0 ? round($submissions->count() / $assignments->count() * 100) : 0;
                    
                    return [
                        'id' => $student->id,
                        'name' => $student->user->name,
                        'email' => $student->user->email,
                        'avg_score' => $avgScore,
                        'submit_rate' => $submitRate,
                        'assignments_count' => $submissions->count(),
                        'quizzes_count' => $quizResults->count(),
                    ];
                });
            }
        }
    }

    // Pagination helpers
    public function nextPage($type)
    {
        switch ($type) {
            case 'assignment':
                if ($this->assignmentPage < $this->getTotalPages('assignment')) {
                    $this->assignmentPage++;
                }
                break;
            case 'quiz':
                if ($this->quizPage < $this->getTotalPages('quiz')) {
                    $this->quizPage++;
                }
                break;
            case 'attendance':
                if ($this->attendancePage < $this->getTotalPages('attendance')) {
                    $this->attendancePage++;
                }
                break;
        }
    }

    public function previousPage($type)
    {
        switch ($type) {
            case 'assignment':
                if ($this->assignmentPage > 1) {
                    $this->assignmentPage--;
                }
                break;
            case 'quiz':
                if ($this->quizPage > 1) {
                    $this->quizPage--;
                }
                break;
            case 'attendance':
                if ($this->attendancePage > 1) {
                    $this->attendancePage--;
                }
                break;
        }
    }

    public function goToPage($type, $page)
    {
        switch ($type) {
            case 'assignment':
                $this->assignmentPage = max(1, min($page, $this->getTotalPages('assignment')));
                break;
            case 'quiz':
                $this->quizPage = max(1, min($page, $this->getTotalPages('quiz')));
                break;
            case 'attendance':
                $this->attendancePage = max(1, min($page, $this->getTotalPages('attendance')));
                break;
        }
    }

    public function getPaginatedAssignments()
    {
        $start = ($this->assignmentPage - 1) * $this->perPage;
        return $this->assignmentSubmissions->slice($start, $this->perPage);
    }

    public function getPaginatedQuizzes()
    {
        $start = ($this->quizPage - 1) * $this->perPage;
        return $this->quizResults->slice($start, $this->perPage);
    }

    public function getPaginatedAttendances()
    {
        $attendances = Auth::user()->studentProfile ? Auth::user()->studentProfile->attendances : (Auth::user()->student ? Auth::user()->student->attendances : collect());
        $start = ($this->attendancePage - 1) * $this->perPage;
        return $attendances->slice($start, $this->perPage);
    }

    public function getTotalPages($type)
    {
        switch ($type) {
            case 'assignment':
                return max(1, (int) ceil($this->assignmentSubmissions->count() / $this->perPage));
            case 'quiz':
                return max(1, (int) ceil($this->quizResults->count() / $this->perPage));
            case 'attendance':
                $attendances = Auth::user()->studentProfile ? Auth::user()->studentProfile->attendances : (Auth::user()->student ? Auth::user()->student->attendances : collect());
                return max(1, (int) ceil($attendances->count() / $this->perPage));
            default:
                return 1;
        }
    }

    public function render()
    {
        return view('student.reports.index');
    }
}
