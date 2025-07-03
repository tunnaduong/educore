<?php

namespace App\Livewire\Admin\Schedules;

use Livewire\Component;

class Create extends Component
{
    public function mount()
    {
        // Redirect to classrooms index since schedules are created with classrooms
        return redirect()->route('classrooms.index');
    }

    public function render()
    {
        return view('admin.schedules.create');
    }
}
