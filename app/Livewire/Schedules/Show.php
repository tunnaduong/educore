<?php

namespace App\Livewire\Schedules;

use App\Models\Classroom;
use Livewire\Component;

class Show extends Component
{
    public Classroom $classroom;

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom->load(['teacher', 'students']);
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

    public function getStatusBadgeClass($status)
    {
        return match ($status) {
            'active' => 'bg-success',
            'inactive' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    public function getStatusText($status)
    {
        return match ($status) {
            'active' => 'Đang hoạt động',
            'inactive' => 'Tạm dừng',
            default => $status
        };
    }

    public function render()
    {
        return view('livewire.schedules.show');
    }
}
