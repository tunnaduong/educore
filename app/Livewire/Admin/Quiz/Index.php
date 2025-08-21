<?php

namespace App\Livewire\Admin\Quiz;

use App\Models\Classroom;
use App\Models\Quiz;
use Livewire\Component;
use Livewire\WithPagination;

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

    public function deleteQuiz($quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $quiz->delete();

        session()->flash('message', 'Bài kiểm tra đã được xóa thành công.');
    }

    public function render()
    {
        $quizzes = Quiz::query()
            ->with(['classroom'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
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

        $classrooms = Classroom::orderBy('name')->get();

        return view('admin.quiz.index', [
            'quizzes' => $quizzes,
            'classrooms' => $classrooms,
        ]);
    }
}
