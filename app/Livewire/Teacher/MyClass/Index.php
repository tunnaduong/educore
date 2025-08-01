<?php

namespace App\Livewire\Teacher\MyClass;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedClassroom = null;
    public $showClassroomDetails = false;

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        // Khởi tạo component
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showClassroom($classroomId)
    {
        $this->selectedClassroom = Classroom::with(['students', 'lessons', 'assignments'])
            ->findOrFail($classroomId);
        $this->showClassroomDetails = true;
    }

    public function closeClassroomDetails()
    {
        $this->showClassroomDetails = false;
        $this->selectedClassroom = null;
    }

    public function render()
    {
        $teacher = Auth::user();

        $classrooms = Classroom::whereHas('users', function ($query) use ($teacher) {
            $query->where('user_id', $teacher->id)
                ->where('class_user.role', 'teacher');
        })
            ->with(['students', 'lessons', 'assignments'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.teacher.my-class.index', [
            'classrooms' => $classrooms,
            'teacher' => $teacher
        ]);
    }
}
