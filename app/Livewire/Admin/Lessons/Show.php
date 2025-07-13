<?php

namespace App\Livewire\Admin\Lessons;

use Livewire\Component;
use App\Models\Lesson;

class Show extends Component
{
    public $lesson;

    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    public function deleteLesson($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();
        session()->flash('success', 'Đã xoá bài học thành công!');
        return redirect()->route('lessons.index');
    }

    public function render()
    {
        return view('admin.lessons.show', [
            'lesson' => $this->lesson
        ]);
    }
}
