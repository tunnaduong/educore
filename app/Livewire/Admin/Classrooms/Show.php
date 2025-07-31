<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public function mount($classroom)
    {
        $this->classroom = $classroom->load(['teachers', 'students', 'attendances']);
    }

    public function formatSchedule($schedule)
    {
        if (!$schedule || !is_array($schedule)) {
            return 'Chưa có lịch học';
        }

        $days = $schedule['days'] ?? [];
        $time = $schedule['time'] ?? '';

        if (empty($days) || empty($time)) {
            return 'Chưa có lịch học';
        }

        $dayNames = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật'
        ];

        $formattedDays = array_map(function ($day) use ($dayNames) {
            return $dayNames[$day] ?? $day;
        }, $days);

        return implode(', ', $formattedDays) . ' - ' . $time;
    }

    public function render()
    {
        return view('admin.classrooms.show');
    }
}
