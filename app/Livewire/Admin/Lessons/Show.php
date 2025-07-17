<?php

namespace App\Livewire\Admin\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use Illuminate\Support\Facades\Storage;

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

        // Xóa file đính kèm nếu có
        if ($lesson->attachment && Storage::disk('public')->exists($lesson->attachment)) {
            Storage::disk('public')->delete($lesson->attachment);
        }

        // Xóa file video nếu có
        if ($lesson->video && Storage::disk('public')->exists($lesson->video)) {
            Storage::disk('public')->delete($lesson->video);
        }

        $lesson->delete();
        session()->flash('success', 'Đã xoá bài học thành công!');
        return $this->redirect(route('lessons.index'), true);
    }

    public function render()
    {
        return view('admin.lessons.show', [
            'lesson' => $this->lesson
        ]);
    }
}
