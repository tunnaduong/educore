<?php

namespace App\Livewire\Teacher\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{
    public $lesson;

    public function mount(Lesson $lesson)
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        // Fallback: nếu teacher chưa được gán vào lớp nào, hiển thị tất cả lớp học
        if ($classrooms->isEmpty()) {
            $classrooms = Classroom::all();
        }
        
        // Kiểm tra xem teacher có quyền xem bài học này không
        $this->lesson = Lesson::whereIn('classroom_id', $classrooms->pluck('id'))
            ->with('classroom')
            ->findOrFail($lesson->id);
    }

    public function render()
    {
        return view('teacher.lessons.show');
    }
} 