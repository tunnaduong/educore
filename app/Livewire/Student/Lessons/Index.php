<?php

namespace App\Livewire\Student\Lessons;

use Livewire\Component;
use App\Models\Lesson;

class Index extends Component
{
    public function render()
    {
        $lessons = Lesson::orderByDesc('created_at')->get();
        return view('student.lessons.index', compact('lessons'));
    }
}
