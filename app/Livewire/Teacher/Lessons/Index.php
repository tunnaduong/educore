<?php

namespace App\Livewire\Teacher\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use Livewire\WithPagination;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showDeleteModal = false;
    public $lessonToDelete = null;
    public $lessonTitleToDelete = '';
    public $filterClass = '';

    protected $listeners = [
        'lessonCreated' => '$refresh',
        'lessonUpdated' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }
    public function updatingFilterClass() { $this->resetPage(); }

    public function confirmDelete($id, $title)
    {
        $this->lessonToDelete = $id;
        $this->lessonTitleToDelete = $title;
        $this->showDeleteModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->lessonToDelete = null;
        $this->lessonTitleToDelete = '';
    }

    public function deleteLesson()
    {
        $lesson = Lesson::findOrFail($this->lessonToDelete);
        $lesson->delete();
        $this->showDeleteModal = false;
        $this->lessonToDelete = null;
        $this->lessonTitleToDelete = '';
        session()->flash('success', 'Đã xoá bài học thành công!');
    }

    public function render()
    {
        $user = Auth::user();
        $classrooms = $user->teachingClassrooms;
        
        // Fallback: nếu teacher chưa được gán vào lớp nào, hiển thị tất cả lớp học
        if ($classrooms->isEmpty()) {
            $classrooms = Classroom::all();
        }
        
        $lessons = Lesson::query()
            ->when($classrooms->isNotEmpty(), function($query) use ($classrooms) {
                $query->whereIn('classroom_id', $classrooms->pluck('id'));
            })
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('number', 'like', '%'.$this->search.'%');
            })
            ->when($this->filterClass, function($query) {
                $query->where('classroom_id', $this->filterClass);
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('teacher.lessons.index', [
            'lessons' => $lessons,
            'classrooms' => $classrooms,
            'filterClass' => $this->filterClass,
        ]);
    }
} 