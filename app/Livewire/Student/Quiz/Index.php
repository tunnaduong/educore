<?php

namespace App\Livewire\Student\Quiz;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Quiz;
use App\Models\Classroom;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterClass = '';
    public $filterStatus = '';
    public $filterSubmissionStatus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterClass' => ['except' => ''],
        'filterStatus' => ['except' => ''],
        'filterSubmissionStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterClass()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    public function updatingFilterSubmissionStatus()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterClass = '';
        $this->filterStatus = '';
        $this->filterSubmissionStatus = '';
        $this->resetPage();
    }

    public function render()
    {
        $user = Auth::user();

        // Kiểm tra xem user có student profile không
        if (!$user->studentProfile) {
            return view('student.quiz.index', [
                'quizzes' => collect(),
                'classrooms' => collect(),
                'quizResults' => collect(),
            ]);
        }

        $classIds = $user->enrolledClassrooms->pluck('id');
        $classrooms = $user->enrolledClassrooms;

        $quizzes = Quiz::query()
            ->with(['classroom'])
            ->whereIn('class_id', $classIds)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterClass, function ($query) {
                $query->where('class_id', $this->filterClass);
            })
            ->when($this->filterStatus, function ($query) {
                if ($this->filterStatus === 'active') {
                    $query->where('deadline', '>', now());
                } elseif ($this->filterStatus === 'expired') {
                    $query->where('deadline', '<=', now());
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Lấy kết quả quiz của user
        $quizResults = $user->quizResults->keyBy('quiz_id');

        // Lọc quiz dựa trên trạng thái lớp học
        $filteredQuizzes = $quizzes->filter(function ($quiz) use ($quizResults) {
            // Nếu lớp đã kết thúc, chỉ hiển thị bài đã làm
            if ($quiz->classroom && $quiz->classroom->status === 'completed') {
                return $quizResults->has($quiz->id);
            }
            // Nếu lớp chưa kết thúc, hiển thị tất cả bài kiểm tra
            return true;
        });

        // Lọc theo trạng thái làm bài
        if ($this->filterSubmissionStatus) {
            $filteredQuizzes = $filteredQuizzes->filter(function ($quiz) use ($quizResults) {
                $result = $quizResults->get($quiz->id);

                switch ($this->filterSubmissionStatus) {
                    case 'not_started':
                        return !$result;
                    case 'in_progress':
                        return $result && !$result->submitted_at;
                    case 'submitted':
                        return $result && $result->submitted_at;
                    case 'completed':
                        return $result;
                    default:
                        return true;
                }
            });
        }

        // Phân trang thủ công
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $offset = ($currentPage - 1) * $perPage;
        $paginatedQuizzes = $filteredQuizzes->slice($offset, $perPage);

        return view('student.quiz.index', [
            'quizzes' => $paginatedQuizzes,
            'classrooms' => $classrooms,
            'quizResults' => $quizResults,
            'totalQuizzes' => $filteredQuizzes->count(),
            'perPage' => $perPage,
            'currentPage' => $currentPage,
        ]);
    }
}
