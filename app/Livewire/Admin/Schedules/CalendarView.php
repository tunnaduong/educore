<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Quiz;
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

                if (! empty($days) && ! empty($time)) {
                    // Tạo events cho tháng hiện tại
                    $currentMonth = now()->month;
                    $currentYear = now()->year;
                    $startOfMonth = now()->startOfMonth();
                    $endOfMonth = now()->endOfMonth();
                    
                    // Tạo events cho từng ngày trong tháng
                    $currentDate = $startOfMonth->copy();
                    
                    while ($currentDate->lte($endOfMonth)) {
                        $dayName = $currentDate->format('l'); // Monday, Tuesday, etc.
                        
                        // Kiểm tra xem ngày này có phải là ngày học không
                        if (in_array($dayName, $days)) {
                            $timeParts = explode(':', $time);
                            $hour = (int) $timeParts[0];
                            $minute = (int) $timeParts[1];
                            
                            $startDateTime = $currentDate->copy()->setTime($hour, $minute);
                            $endDateTime = $startDateTime->copy()->addMinutes(90); // 90 phút
                            
                            $events[] = [
                                'id' => 'schedule_'.$classroom->id.'_'.$dayName.'_'.$currentDate->format('Y-m-d'),
                                'title' => $classroom->name,
                                'start' => $startDateTime->format('Y-m-d\TH:i:s'),
                                'end' => $endDateTime->format('Y-m-d\TH:i:s'),
                                'backgroundColor' => '#0d6efd',
                                'borderColor' => '#0d6efd',
                                'extendedProps' => [
                                    'type' => 'schedule',
                                    'classroom' => $classroom->name,
                                    'level' => $classroom->level,
                                    'teachers' => $classroom->teachers->pluck('name')->join(', '),
                                    'location' => 'Chưa có địa điểm',
                                    'studentCount' => $classroom->students->count(),
                                ],
                            ];
                        }
                        
                        $currentDate->addDay();
                    }
                }
            }
        }

        // Lấy assignments
        $assignments = Assignment::with(['classroom'])->get();
        foreach ($assignments as $assignment) {
            if ($assignment->deadline) {
                $events[] = [
                    'id' => 'assignment_'.$assignment->id,
                    'title' => 'Bài tập: '.$assignment->title,
                    'start' => $assignment->deadline->format('Y-m-d\TH:i:s'),
                    'backgroundColor' => '#fd7e14',
                    'borderColor' => '#fd7e14',
                    'extendedProps' => [
                        'type' => 'assignment',
                        'classroom' => $assignment->classroom->name ?? 'N/A',
                        'description' => $assignment->description,
                        'points' => $assignment->max_score,
                    ],
                ];
            }
        }

        // Lấy quizzes
        $quizzes = Quiz::with(['classroom'])->get();
        foreach ($quizzes as $quiz) {
            if ($quiz->deadline) {
                $events[] = [
                    'id' => 'quiz_'.$quiz->id,
                    'title' => 'Bài kiểm tra: '.$quiz->title,
                    'start' => $quiz->deadline->format('Y-m-d\TH:i:s'),
                    'backgroundColor' => '#20c997',
                    'borderColor' => '#20c997',
                    'extendedProps' => [
                        'type' => 'quiz',
                        'classroom' => $quiz->classroom->name ?? 'N/A',
                        'description' => $quiz->description,
                        'duration' => $quiz->time_limit ?? 0,
                    ],
                ];
            }
        }

        $this->events = $events;
    }

    private function getDayNumber($day)
    {
        // Mapping theo Carbon's dayOfWeek: Sunday = 0, Monday = 1, ..., Saturday = 6
        $days = [
            'Sunday' => 0,
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
        ];

        return $days[$day] ?? 1;
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
                'duration' => $event['extendedProps']['duration'] ?? '',
            ]);
        }
    }

    public function render()
    {
        return view('admin.schedules.calendar-view');
    }
}
