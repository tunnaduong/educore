<?php

namespace App\Livewire\Student\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use App\Models\User;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $filterClass = '';
    public $search = '';

    public function updatingFilterClass() { $this->resetPage(); }

    public function render()
    {
        $user = Auth::user();
        /** @var User $user */
        $classrooms = $user->enrolledClassrooms;
        $classroomIds = $classrooms->pluck('id');
        $lessons = Lesson::whereIn('classroom_id', $classroomIds)
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('description', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterClass, function($query) {
                $query->where('classroom_id', $this->filterClass);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        // Lấy trạng thái hoàn thành từng bài học cho user hiện tại
        $completedLessons = [];
        foreach ($lessons as $lesson) {
            $completedLessons[$lesson->id] = $user->lessons()->where('lesson_id', $lesson->id)->whereNotNull('lesson_user.completed_at')->exists();
        }

        return view('student.lessons.index', [
            'lessons' => $lessons,
            'classrooms' => $classrooms,
            'filterClass' => $this->filterClass,
            'completedLessons' => $completedLessons,
        ]);
    }
}
