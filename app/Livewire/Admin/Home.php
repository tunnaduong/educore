<?php

namespace App\Livewire\Admin;

use App\Models\Attendance;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public $unreadCount = 0;

    public $unreadNotification = 0;

    public $attendanceStatusCounts = [
        'present' => 0,
        'absent' => 0,
        'late' => 0,
    ];

    public function mount()
    {
        $userId = Auth::id();
        $this->unreadCount = Message::unread($userId)->count();
        $this->unreadNotification = Notification::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhereNull('user_id');
        })->where('is_read', false)->count();

        // Tính số liệu điểm danh tháng hiện tại cho biểu đồ trên Dashboard admin
        $year = (int) now()->year;
        $month = (int) now()->month;
        $monthlyAttendances = Attendance::forMonth($year, $month)->get();
        $present = $monthlyAttendances->where('present', true)->count();
        $absent = $monthlyAttendances->where('present', false)->count();
        // Hệ thống chưa có cột 'late' -> mặc định 0
        $this->attendanceStatusCounts = [
            'present' => $present,
            'absent' => $absent,
            'late' => 0,
        ];
    }

    public function render()
    {
        // if user is admin then render admin.home
        // if user is teacher then render teacher.home
        // if user is student then render student.home
        $user = Auth::user();
        if ($user->role == 'admin') {
            return view('admin.home');
        }
        if ($user->role == 'teacher') {
            return view('teacher.home');
        }
        if ($user->role == 'student') {
            return view('student.home');
        }
    }
}
