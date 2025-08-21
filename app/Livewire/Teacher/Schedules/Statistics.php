<?php

namespace App\Livewire\Teacher\Schedules;

use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Statistics extends Component
{
    public $totalLessons = 0;

    public $totalAssignments = 0;

    public $totalQuizzes = 0;

    public $upcomingLessons = 0;

    public $pendingAssignments = 0;

    public $todayEvents = 0;

    public $thisWeekEvents = 0;

    public $thisMonthEvents = 0;

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $teacher = Auth::user();
        $classrooms = $teacher->teachingClassrooms;
        $classroomIds = $classrooms->pluck('id');

        // Tổng số sự kiện
        $this->totalLessons = Lesson::whereIn('classroom_id', $classroomIds)->count();
        $this->totalAssignments = Assignment::whereIn('class_id', $classroomIds)->count();
        $this->totalQuizzes = Quiz::whereIn('class_id', $classroomIds)->count();

        // Sự kiện sắp tới (từ lịch học của lớp)
        $this->upcomingLessons = 0;
        foreach ($classrooms as $classroom) {
            if ($classroom->schedule) {
                $schedule = $classroom->schedule;
                if (is_array($schedule)) {
                    foreach ($schedule as $day => $timeSlots) {
                        if (is_array($timeSlots)) {
                            foreach ($timeSlots as $timeSlot) {
                                if (isset($timeSlot['start_time'])) {
                                    $this->upcomingLessons++;
                                }
                            }
                        }
                    }
                }
            }
        }

        // Bài tập chưa đến hạn
        $this->pendingAssignments = Assignment::whereIn('class_id', $classroomIds)
            ->where('deadline', '>', now())
            ->count();

        // Sự kiện hôm nay
        $today = now()->format('Y-m-d');
        $this->todayEvents = Assignment::whereIn('class_id', $classroomIds)
            ->whereDate('deadline', $today)
            ->count();
        $this->todayEvents += Quiz::whereIn('class_id', $classroomIds)
            ->whereDate('deadline', $today)
            ->count();

        // Sự kiện tuần này
        $this->thisWeekEvents = Assignment::whereIn('class_id', $classroomIds)
            ->whereBetween('deadline', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $this->thisWeekEvents += Quiz::whereIn('class_id', $classroomIds)
            ->whereBetween('deadline', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Sự kiện tháng này
        $this->thisMonthEvents = Assignment::whereIn('class_id', $classroomIds)
            ->whereBetween('deadline', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
        $this->thisMonthEvents += Quiz::whereIn('class_id', $classroomIds)
            ->whereBetween('deadline', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
    }

    public function render()
    {
        return view('teacher.schedules.statistics');
    }
}
