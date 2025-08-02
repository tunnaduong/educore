<?php

namespace App\Livewire\Teacher\MyClass;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;
    public $activeTab = 'overview';
    public $showAddStudentModal = false;
    public $showAddLessonModal = false;
    public $showAddAssignmentModal = false;

    public function mount($classroomId)
    {
        $teacher = Auth::user();

        $this->classroom = Classroom::whereHas('users', function ($query) use ($teacher) {
            $query->where('user_id', $teacher->id)
                ->where('class_user.role', 'teacher');
        })
            ->with(['students', 'lessons', 'assignments'])
            ->findOrFail($classroomId);
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function showAddStudentModal()
    {
        $this->showAddStudentModal = true;
    }

    public function showAddLessonModal()
    {
        $this->showAddLessonModal = true;
    }

    public function showAddAssignmentModal()
    {
        $this->showAddAssignmentModal = true;
    }

    public function closeModals()
    {
        $this->showAddStudentModal = false;
        $this->showAddLessonModal = false;
        $this->showAddAssignmentModal = false;
    }

    public function render()
    {
        return view('teacher.my-class.show', [
            'classroom' => $this->classroom,
            'teacher' => Auth::user()
        ]);
    }
}
