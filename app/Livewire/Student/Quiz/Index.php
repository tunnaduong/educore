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

    protected $queryString = [
        'search' => ['except' => ''],
        'filterClass' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterClass() { $this->resetPage(); }
    public function updatingFilterStatus() { $this->resetPage(); }

    public function render()
    {
        $user = Auth::user();
        $classIds = $user->enrolledClassrooms->pluck('id');
        $classrooms = $user->enrolledClassrooms;

        $quizzes = Quiz::query()
            ->with(['classroom'])
            ->whereIn('class_id', $classIds)
            ->when($this->search, function ($query) {
                $query->where(function($q) {
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
            ->paginate(10);

        // Lấy kết quả quiz của user
        $quizResults = QuizResult::where('student_id', $user->id)->get()->keyBy('quiz_id');

        return view('student.quiz.index', [
            'quizzes' => $quizzes,
            'classrooms' => $classrooms,
            'quizResults' => $quizResults,
        ]);
    }
}
