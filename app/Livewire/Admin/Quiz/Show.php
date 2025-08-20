<?php

namespace App\Livewire\Admin\Quiz;

use App\Models\Quiz;
use Livewire\Component;

class Show extends Component
{
    public Quiz $quiz;

    public $results;

    public function mount($quiz)
    {
        $this->quiz = $quiz;
        $this->loadResults();
    }

    public function loadResults()
    {
        $this->results = $this->quiz->results()
            ->with('student')
            ->orderBy('submitted_at', 'desc')
            ->get();
    }

    public function deleteQuiz()
    {
        $this->quiz->delete();

        session()->flash('message', 'Bài kiểm tra đã được xóa thành công.');

        return redirect()->route('quizzes.index');
    }

    public function render()
    {
        $classroom = $this->quiz->classroom;
        $students = $classroom ? $classroom->students : collect();

        return view('admin.quiz.show', [
            'classroom' => $classroom,
            'students' => $students,
        ]);
    }
}
