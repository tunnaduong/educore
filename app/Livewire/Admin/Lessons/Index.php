<?php

namespace App\Livewire\Admin\Lessons;

use Livewire\Component;
use App\Models\Lesson;
use Livewire\WithPagination;
use App\Livewire\Admin\Lessons\Create;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showCreateForm = false;
    public $showDeleteModal = false;
    public $lessonToDelete = null;
    public $lessonTitleToDelete = '';

    protected $listeners = [
        'closeCreate' => 'closeCreateForm',
        'lessonCreated' => 'closeCreateForm',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch() { $this->resetPage(); }

    public function openCreateForm() { $this->showCreateForm = true; }
    public function closeCreateForm() { $this->showCreateForm = false; }

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
        $lessons = Lesson::query()
            ->when($this->search, function($query) {
                $query->where('title', 'like', '%'.$this->search.'%')
                      ->orWhere('number', 'like', '%'.$this->search.'%');
            })
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('admin.lessons.index', [
            'lessons' => $lessons,
            'showCreateForm' => $this->showCreateForm,
        ]);
    }
}
