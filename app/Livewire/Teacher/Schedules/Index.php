<?php

namespace App\Livewire\Teacher\Schedules;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Classroom;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $events = [];
    public $selectedDate = null;
    public $selectedEvents = [];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $teacher = Auth::user();
        $events = [];

        // Lấy các lớp học của giảng viên
        $classrooms = $teacher->teachingClassrooms;

        // Lấy các bài học từ lịch học của lớp
        foreach ($classrooms as $classroom) {
            if ($classroom->schedule) {
                $schedule = $classroom->schedule;
                if (is_array($schedule) && isset($schedule['days']) && isset($schedule['time'])) {
                    $days = $schedule['days'];
                    $time = $schedule['time'];

                    // Parse thời gian
                    $timeParts = explode(' - ', $time);
                    $startTime = $timeParts[0] ?? '';
                    $endTime = $timeParts[1] ?? '';

                    if ($startTime && $endTime) {
                        // Tạo events cho 4 tuần tới
                        for ($week = 0; $week < 4; $week++) {
                            foreach ($days as $day) {
                                $startDate = now()->startOfWeek()->addWeeks($week)->addDays($this->getDayNumber($day));
                                $startDateTime = $startDate->format('Y-m-d') . 'T' . $startTime;
                                $endDateTime = $startDate->format('Y-m-d') . 'T' . $endTime;

                                $events[] = [
                                    'id' => 'schedule_' . $classroom->id . '_' . $day . '_' . $week,
                                    'title' => 'Lịch học - ' . $classroom->name,
                                    'start' => $startDateTime,
                                    'end' => $endDateTime,
                                    'backgroundColor' => '#0d6efd',
                                    'borderColor' => '#0d6efd',
                                    'extendedProps' => [
                                        'type' => 'schedule',
                                        'classroom' => $classroom->name,
                                        'description' => 'Lịch học định kỳ',
                                        'location' => 'Chưa cập nhật',
                                        'day' => $day,
                                        'time' => $time
                                    ]
                                ];
                            }
                        }
                    }
                }
            }
        }

        // Lấy các bài tập
        $assignments = Assignment::whereIn('class_id', $classrooms->pluck('id'))
            ->where('deadline', '>=', now()->startOfMonth())
            ->where('deadline', '<=', now()->endOfMonth()->addMonth())
            ->get();

        foreach ($assignments as $assignment) {
            $classroom = $classrooms->where('id', $assignment->class_id)->first();
            $events[] = [
                'id' => 'assignment_' . $assignment->id,
                'title' => $assignment->title . ' (Bài tập) - ' . $classroom->name,
                'start' => $assignment->deadline,
                'end' => $assignment->deadline,
                'allDay' => true,
                'backgroundColor' => '#fd7e14',
                'borderColor' => '#fd7e14',
                'extendedProps' => [
                    'type' => 'assignment',
                    'classroom' => $classroom->name,
                    'description' => $assignment->description,
                    'deadline' => $assignment->deadline
                ]
            ];
        }

        // Lấy các bài kiểm tra
        $quizzes = Quiz::whereIn('class_id', $classrooms->pluck('id'))
            ->where('deadline', '>=', now()->startOfMonth())
            ->where('deadline', '<=', now()->endOfMonth()->addMonth())
            ->get();

        foreach ($quizzes as $quiz) {
            $classroom = $classrooms->where('id', $quiz->class_id)->first();
            $events[] = [
                'id' => 'quiz_' . $quiz->id,
                'title' => $quiz->title . ' (Kiểm tra) - ' . $classroom->name,
                'start' => $quiz->deadline,
                'end' => $quiz->deadline,
                'allDay' => true,
                'backgroundColor' => '#20c997',
                'borderColor' => '#20c997',
                'extendedProps' => [
                    'type' => 'quiz',
                    'classroom' => $classroom->name,
                    'description' => $quiz->description,
                    'deadline' => $quiz->deadline
                ]
            ];
        }

        $this->events = $events;
    }

    public function getEventsForDate($date)
    {
        $dateStr = Carbon::parse($date)->format('Y-m-d');
        return collect($this->events)->filter(function ($event) use ($dateStr) {
            $eventDate = Carbon::parse($event['start'])->format('Y-m-d');
            return $eventDate === $dateStr;
        })->values()->toArray();
    }

    private function getDayNumber($day)
    {
        $days = [
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 0
        ];

        return $days[$day] ?? 0;
    }

    public function showEventDetail($eventId, $eventType)
    {
        $this->dispatch('showEventDetail', eventId: $eventId, eventType: $eventType);
    }

    protected $listeners = ['eventCreated' => 'loadEvents'];

    public function render()
    {
        return view('teacher.schedules.index');
    }
}
