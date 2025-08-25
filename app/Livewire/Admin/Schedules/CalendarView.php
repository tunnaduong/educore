<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Classroom;
use App\Models\Assignment;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CalendarView extends Component
{
    public $events = [];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $events = [];

        // Lấy lịch học từ các lớp học
        $classrooms = Classroom::with(['teachers', 'students'])->get();
        
        foreach ($classrooms as $classroom) {
            if ($classroom->schedule && is_array($classroom->schedule)) {
                $days = $classroom->schedule['days'] ?? [];
                $time = $classroom->schedule['time'] ?? '';
                
                if (!empty($days) && !empty($time)) {
                    // Tạo events cho mỗi ngày trong tuần
                    foreach ($days as $day) {
                        $events[] = [
                            'id' => 'schedule_' . $classroom->id . '_' . $day,
                            'title' => $classroom->name,
                            'start' => $this->getNextOccurrence($day, $time),
                            'end' => $this->getNextOccurrence($day, $time, 90), // 90 phút
                            'backgroundColor' => '#0d6efd',
                            'borderColor' => '#0d6efd',
                            'extendedProps' => [
                                'type' => 'schedule',
                                'classroom' => $classroom->name,
                                'level' => $classroom->level,
                                'teachers' => $classroom->teachers->pluck('name')->join(', '),
                                'location' => 'Chưa có địa điểm',
                                'studentCount' => $classroom->students->count()
                            ]
                        ];
                    }
                }
            }
        }

        // Lấy assignments
        $assignments = Assignment::with(['classroom'])->get();
        foreach ($assignments as $assignment) {
            if ($assignment->deadline) {
                $events[] = [
                    'id' => 'assignment_' . $assignment->id,
                    'title' => 'Bài tập: ' . $assignment->title,
                    'start' => $assignment->deadline->format('Y-m-d\TH:i:s'),
                    'backgroundColor' => '#fd7e14',
                    'borderColor' => '#fd7e14',
                    'extendedProps' => [
                        'type' => 'assignment',
                        'classroom' => $assignment->classroom->name ?? 'N/A',
                        'description' => $assignment->description,
                        'points' => $assignment->max_score
                    ]
                ];
            }
        }

        // Lấy quizzes
        $quizzes = Quiz::with(['classroom'])->get();
        foreach ($quizzes as $quiz) {
            if ($quiz->deadline) {
                $events[] = [
                    'id' => 'quiz_' . $quiz->id,
                    'title' => 'Bài kiểm tra: ' . $quiz->title,
                    'start' => $quiz->deadline->format('Y-m-d\TH:i:s'),
                    'backgroundColor' => '#20c997',
                    'borderColor' => '#20c997',
                    'extendedProps' => [
                        'type' => 'quiz',
                        'classroom' => $quiz->classroom->name ?? 'N/A',
                        'description' => $quiz->description,
                        'duration' => $quiz->time_limit ?? 0
                    ]
                ];
            }
        }

        $this->events = $events;
    }

    private function getNextOccurrence($day, $time, $addMinutes = 0)
    {
        $dayMap = [
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 0
        ];

        $dayNumber = $dayMap[$day] ?? 1;
        $currentDay = now()->dayOfWeek;
        $daysUntilNext = ($dayNumber - $currentDay + 7) % 7;
        
        if ($daysUntilNext === 0) {
            $daysUntilNext = 7;
        }

        $nextDate = now()->addDays($daysUntilNext);
        $timeParts = explode(':', $time);
        $hour = (int)$timeParts[0];
        $minute = (int)$timeParts[1];
        
        $dateTime = $nextDate->setTime($hour, $minute);
        
        if ($addMinutes > 0) {
            $dateTime = $dateTime->addMinutes($addMinutes);
        }
        
        return $dateTime->format('Y-m-d\TH:i:s');
    }

    public function showEventDetail($eventId, $eventType)
    {
        // Xử lý hiển thị chi tiết sự kiện
        $event = collect($this->events)->firstWhere('id', $eventId);
        
        if ($event) {
            $this->dispatch('showEventModal', [
                'title' => $event['title'],
                'type' => $event['extendedProps']['type'],
                'classroom' => $event['extendedProps']['classroom'] ?? '',
                'description' => $event['extendedProps']['description'] ?? '',
                'start' => $event['start'],
                'end' => $event['end'] ?? '',
                'location' => $event['extendedProps']['location'] ?? '',
                'teachers' => $event['extendedProps']['teachers'] ?? '',
                'studentCount' => $event['extendedProps']['studentCount'] ?? '',
                'points' => $event['extendedProps']['points'] ?? '',
                'duration' => $event['extendedProps']['duration'] ?? ''
            ]);
        }
    }

    public function render()
    {
        return view('admin.schedules.calendar-view');
    }
}
