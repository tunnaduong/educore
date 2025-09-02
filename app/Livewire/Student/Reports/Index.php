<?php

namespace App\Livewire\Student\Reports;

use App\Models\AssignmentSubmission;
use App\Models\QuizResult;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $assignmentSubmissions = [];

    public $quizResults = [];

    public $avgAssignmentScore = 0;

    public $avgQuizScore = 0;

    public $attendancePresent = 0;

    public $attendanceAbsent = 0;

    public $activeTab = 'assignments';

    public int $perPageAssignments = 10;

    public int $perPageQuizzes = 10;

    public int $perPageAttendances = 10;

    protected $queryString = [
        'activeTab' => ['except' => 'assignments'],
        'page' => ['except' => 1],
        'asPage' => ['except' => 1],
        'qrPage' => ['except' => 1],
        'atPage' => ['except' => 1],
    ];

    public function mount()
    {
        $user = Auth::user();
        $student = $user->student;

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
            $scores = collect($this->assignmentSubmissions)->pluck('score')->filter();
            $this->avgAssignmentScore = $scores->count() ? round($scores->avg(), 2) : 0;
            // Tính điểm trung bình kiểm tra
            $quizScores = collect($this->quizResults)->pluck('score')->filter();
            $this->avgQuizScore = $quizScores->count() ? round($quizScores->avg(), 2) : 0;
            // Điểm danh
            $attendances = $student->attendances;
            $this->attendancePresent = $attendances->where('present', true)->count();
            $this->attendanceAbsent = $attendances->where('present', false)->count();
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        if ($tab === 'assignments') {
            $this->resetPage('asPage');
        } elseif ($tab === 'quizzes') {
            $this->resetPage('qrPage');
        } elseif ($tab === 'attendance') {
            $this->resetPage('atPage');
        }
    }

    public function render()
    {
        $user = Auth::user();
        $student = $user->student;

        // Debug: kiểm tra user và student
        Log::info('Student Reports Debug', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'student_id' => $student?->id,
            'student_exists' => $student ? 'yes' : 'no'
        ]);

        // Nếu user có role student nhưng không có student record, tạo một
        if (!$student && $user->role === 'student') {
            $student = \App\Models\Student::create([
                'user_id' => $user->id,
                'status' => 'active',
                'joined_at' => now(),
            ]);
            Log::info('Auto-created student record', ['user_id' => $user->id, 'student_id' => $student->id]);
        }

        if ($student) {
            $assignmentSubmissionsPaginated = AssignmentSubmission::with(['assignment.classroom'])
                ->where('student_id', $student->id)
                ->orderByDesc('submitted_at')
                ->paginate($this->perPageAssignments, ['*'], 'asPage');

            $quizResultsPaginated = QuizResult::with(['quiz.classroom'])
                ->where('student_id', $student->id)
                ->orderByDesc('submitted_at')
                ->paginate($this->perPageQuizzes, ['*'], 'qrPage');

            $attendancesPaginated = Attendance::with(['classroom'])
                ->where('student_id', $student->id)
                ->orderByDesc('date')
                ->paginate($this->perPageAttendances, ['*'], 'atPage');
        } else {
            // Nếu không có student record, tạo empty paginator với 0 items
            $assignmentSubmissionsPaginated = AssignmentSubmission::query()->whereRaw('1 = 0')->paginate(1, ['*'], 'asPage');
            $quizResultsPaginated = QuizResult::query()->whereRaw('1 = 0')->paginate(1, ['*'], 'qrPage');
            $attendancesPaginated = Attendance::query()->whereRaw('1 = 0')->paginate(1, ['*'], 'atPage');
        }

        return view('student.reports.index', [
            'assignmentSubmissionsPaginated' => $assignmentSubmissionsPaginated,
            'quizResultsPaginated' => $quizResultsPaginated,
            'attendancesPaginated' => $attendancesPaginated,
        ]);
    }
}
