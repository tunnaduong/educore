<?php

namespace App\Livewire\Student\Lessons;

use Livewire\Component;
use App\Models\Lesson;

class Show extends Component
{
    public $lessonId;
    public $lesson;

    public function mount($lessonId)
    {
        $this->lessonId = $lessonId;
        $this->lesson = Lesson::findOrFail($lessonId);
    }

    public function render()
    {
        return view('student.lessons.show', [
            'lesson' => $this->lesson
        ]);
    }
}
