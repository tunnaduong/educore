<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public function mount($classroom)
    {
        $this->classroom = $classroom->load(['teacher', 'students', 'attendances']);
    }

    public function render()
    {
        return view('admin.classrooms.show');
    }
}
