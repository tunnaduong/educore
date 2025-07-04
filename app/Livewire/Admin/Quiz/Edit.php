<?php

namespace App\Livewire\Admin\Quiz;

use Livewire\Component;
use App\Models\Quiz;
use App\Models\Classroom;

class Edit extends Component
{
    public Quiz $quiz;
    public $title = '';
    public $description = '';
    public $class_id = '';
    public $deadline = '';
    public $questions = [];
    public $currentQuestion = [
        'question' => '',
        'type' => 'multiple_choice',
        'options' => ['', '', '', ''],
        'correct_answer' => '',
        'score' => 1,
        'audio' => null,
    ];

    protected $rules = [
        'title' => 'required|min:3|max:255',
        'description' => 'nullable|max:1000',
        'class_id' => 'required|exists:classrooms,id',
        'deadline' => 'nullable|date',
        'questions' => 'required|array|min:1',
        'questions.*.question' => 'required|min:3',
        'questions.*.type' => 'required|in:multiple_choice,fill_blank,drag_drop,essay',
        'questions.*.score' => 'required|integer|min:1|max:10',
    ];

    public function mount($quiz)
    {
        $this->quiz = $quiz;
        $this->title = $quiz->title;
        $this->description = $quiz->description;
        $this->class_id = $quiz->class_id;
        $this->deadline = $quiz->deadline ? $quiz->deadline->format('Y-m-d\TH:i') : '';
        $this->questions = $quiz->questions ?? [];
    }

    public function addQuestion()
    {
        $this->validate([
            'currentQuestion.question' => 'required|min:3',
            'currentQuestion.type' => 'required|in:multiple_choice,fill_blank,drag_drop,essay',
            'currentQuestion.score' => 'required|integer|min:1|max:10',
        ]);

        if ($this->currentQuestion['type'] === 'multiple_choice') {
            $this->validate([
                'currentQuestion.options' => 'required|array|min:2',
                'currentQuestion.options.*' => 'required|min:1',
                'currentQuestion.correct_answer' => 'required|min:1',
            ]);
        }

        $this->questions[] = $this->currentQuestion;
        
        $this->resetCurrentQuestion();
        
        session()->flash('message', 'Câu hỏi đã được thêm thành công.');
    }

    public function editQuestion($index)
    {
        $this->currentQuestion = $this->questions[$index];
        $this->removeQuestion($index);
    }

    public function removeQuestion($index)
    {
        unset($this->questions[$index]);
        $this->questions = array_values($this->questions);
        
        session()->flash('message', 'Câu hỏi đã được xóa.');
    }

    public function moveQuestionUp($index)
    {
        if ($index > 0) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index - 1];
            $this->questions[$index - 1] = $temp;
        }
    }

    public function moveQuestionDown($index)
    {
        if ($index < count($this->questions) - 1) {
            $temp = $this->questions[$index];
            $this->questions[$index] = $this->questions[$index + 1];
            $this->questions[$index + 1] = $temp;
        }
    }

    public function addOption()
    {
        $this->currentQuestion['options'][] = '';
    }

    public function removeOption($index)
    {
        unset($this->currentQuestion['options'][$index]);
        $this->currentQuestion['options'] = array_values($this->currentQuestion['options']);
    }

    public function resetCurrentQuestion()
    {
        $this->currentQuestion = [
            'question' => '',
            'type' => 'multiple_choice',
            'options' => ['', '', '', ''],
            'correct_answer' => '',
            'score' => 1,
            'audio' => null,
        ];
    }

    public function save()
    {
        $this->validate();

        $this->quiz->update([
            'title' => $this->title,
            'description' => $this->description,
            'class_id' => $this->class_id,
            'deadline' => $this->deadline ? now()->parse($this->deadline) : null,
            'questions' => $this->questions,
        ]);

        session()->flash('message', 'Bài kiểm tra đã được cập nhật thành công.');
        
        return redirect()->route('quizzes.show', $this->quiz);
    }

    public function render()
    {
        $classrooms = Classroom::orderBy('name')->get();
        
        return view('admin.quiz.edit', [
            'classrooms' => $classrooms,
        ]);
    }
}
