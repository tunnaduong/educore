<?php

namespace App\Livewire\Admin\Quiz;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\User;

class Results extends Component
{
    use WithPagination;

    public Quiz $quiz;
    public $selectedStudent = null;
    public $search = '';
    public $filterScore = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterScore' => ['except' => ''],
        'selectedStudent' => ['except' => ''],
    ];

    public function mount($quiz)
    {
        $this->quiz = $quiz;
        
        // Nếu có student parameter trong URL
        if (request()->has('student')) {
            $this->selectedStudent = request()->get('student');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterScore()
    {
        $this->resetPage();
    }

    public function selectStudent($studentId)
    {
        $this->selectedStudent = $studentId;
    }

    public function clearStudentFilter()
    {
        $this->selectedStudent = null;
    }

    public function render()
    {
        $query = $this->quiz->results()
            ->with('student')
            ->orderBy('submitted_at', 'desc');

        // Lọc theo học viên cụ thể
        if ($this->selectedStudent) {
            $query->where('student_id', $this->selectedStudent);
        }

        // Tìm kiếm theo tên học viên
        if ($this->search) {
            $query->whereHas('student', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        // Lọc theo điểm số
        if ($this->filterScore) {
            switch ($this->filterScore) {
                case 'excellent':
                    $query->where('score', '>=', 90);
                    break;
                case 'good':
                    $query->whereBetween('score', [80, 89]);
                    break;
                case 'average':
                    $query->whereBetween('score', [60, 79]);
                    break;
                case 'poor':
                    $query->where('score', '<', 60);
                    break;
            }
        }

        $results = $query->paginate(10);

        // Danh sách học viên cho filter
        $students = $this->quiz->classroom ? $this->quiz->classroom->students : collect();

        // Thống kê tổng quan
        $totalResults = $this->quiz->results()->count();
        $averageScore = $totalResults > 0 ? round($this->quiz->results()->avg('score'), 1) : 0;
        $maxScore = $totalResults > 0 ? $this->quiz->results()->max('score') : 0;
        $minScore = $totalResults > 0 ? $this->quiz->results()->min('score') : 0;
        $passCount = $this->quiz->results()->where('score', '>=', 80)->count();

        return view('admin.quiz.results', [
            'results' => $results,
            'students' => $students,
            'totalResults' => $totalResults,
            'averageScore' => $averageScore,
            'maxScore' => $maxScore,
            'minScore' => $minScore,
            'passCount' => $passCount,
        ]);
    }
}
