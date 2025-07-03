<?php

namespace App\Livewire\Admin\Students;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $student;

    public function mount($student)
    {
        $this->student = $student->load(['studentProfile', 'enrolledClassrooms']);
    }

    public function render()
    {
        return view('admin.students.show');
    }
}
