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
        // Chỉ lấy các lớp học mà giáo viên hiện tại đã tham gia
        $classrooms = Classroom::whereHas('teachers', function ($query) {
            $query->where('users.id', Auth::id());
        })->orderBy('name')->get();
        
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