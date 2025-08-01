<?php

namespace App\Livewire\Teacher\Quizzes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Support\Facades\Auth;

class Results extends Component
{
    use WithPagination;

    public Quiz $quiz;
    public $search = '';
    public $filterScore = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterScore' => ['except' => ''],
    ];

    public function mount($quizId)
    {
        $this->quiz = Quiz::with(['classroom'])->findOrFail($quizId);
        
        // Kiểm tra quyền xem
        $teacherClassIds = Auth::user()->teachingClassrooms->pluck('id');
        if (!$teacherClassIds->contains($this->quiz->class_id)) {
            session()->flash('error', 'Bạn không có quyền xem kết quả bài kiểm tra này.');
            return redirect()->route('teacher.quizzes.index');
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

    public function render()
    {
        $results = QuizResult::query()
            ->with(['student.user'])
            ->where('quiz_id', $this->quiz->id)
            ->when($this->search, function ($query) {
                $query->whereHas('student.user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterScore, function ($query) {
                switch ($this->filterScore) {
                    case 'excellent':
                        $query->where('score', '>=', $this->quiz->getMaxScore() * 0.9);
                        break;
                    case 'good':
                        $query->where('score', '>=', $this->quiz->getMaxScore() * 0.7)
                              ->where('score', '<', $this->quiz->getMaxScore() * 0.9);
                        break;
                    case 'average':
                        $query->where('score', '>=', $this->quiz->getMaxScore() * 0.5)
                              ->where('score', '<', $this->quiz->getMaxScore() * 0.7);
                        break;
                    case 'poor':
                        $query->where('score', '<', $this->quiz->getMaxScore() * 0.5);
                        break;
                }
            })
            ->orderBy('score', 'desc')
            ->paginate(15);

        // Thống kê
        $totalResults = $results->total();
        $avgScore = $results->avg('score') ?? 0;
        $maxScore = $results->max('score') ?? 0;
        $minScore = $results->min('score') ?? 0;
        $passRate = $totalResults > 0 ? ($results->where('score', '>=', $this->quiz->getMaxScore() * 0.5)->count() / $totalResults) * 100 : 0;

        return view('teacher.quizzes.results', [
            'results' => $results,
            'totalResults' => $totalResults,
            'avgScore' => $avgScore,
            'maxScore' => $maxScore,
            'minScore' => $minScore,
            'passRate' => $passRate,
        ]);
    }
} 