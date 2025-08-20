<?php

namespace App\Livewire\Student\Lessons;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public $lessonId;

    public $lesson;

    public $completed = false;

    public function mount($lessonId)
    {
        $this->lessonId = $lessonId;
        $this->lesson = Lesson::findOrFail($lessonId);
        /** @var User $user */
        $user = Auth::user();
        $this->completed = $user->lessons()->where('lesson_id', $lessonId)->whereNotNull('lesson_user.completed_at')->exists();
    }

    public function markAsCompleted()
    {
        /** @var User $user */
        $user = Auth::user();
        if (! $user->lessons()->where('lesson_id', $this->lessonId)->whereNotNull('lesson_user.completed_at')->exists()) {
            $user->lessons()->syncWithoutDetaching([
                $this->lessonId => ['completed_at' => now()],
            ]);
            $this->completed = true;
            session()->flash('success', 'Đã đánh dấu hoàn thành bài học!');
        }
    }

    public function render()
    {
        return view('student.lessons.show', [
            'lesson' => $this->lesson,
            'completed' => $this->completed,
        ]);
    }
}
