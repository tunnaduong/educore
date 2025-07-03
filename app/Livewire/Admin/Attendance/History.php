<?php

namespace App\Livewire\Admin\Attendance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Attendance;

class History extends Component
{
    use WithPagination;

    public function render()
    {
        $attendances = Attendance::with(['classroom', 'student.user'])
            ->orderByDesc('date')
            ->paginate(20);

        return view('admin.attendance.history', [
            'attendances' => $attendances,
        ]);
    }
}
