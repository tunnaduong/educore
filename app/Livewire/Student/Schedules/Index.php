<?php

namespace App\Livewire\Student\Schedules;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
use App\Models\Assignment;
use App\Models\Quiz;

class Index extends Component
{
    public function render()
    {
        $user = Auth::user();
        $classrooms = $user->enrolledClassrooms()->with('teacher')->get();
        $events = [];
        foreach ($classrooms as $classroom) {
            $schedule = $classroom->schedule;
            // Lịch học định kỳ
            if (is_array($schedule) && !empty($schedule['days']) && !empty($schedule['time'])) {
                $timeParts = explode(' - ', $schedule['time']);
                $startTime = $timeParts[0] ?? null;
                $endTime = $timeParts[1] ?? null;
                foreach ($schedule['days'] as $day) {
                    $date = now()->startOfWeek();
                    $weekdayMap = [
                        'Monday' => 0,
                        'Tuesday' => 1,
                        'Wednesday' => 2,
                        'Thursday' => 3,
                        'Friday' => 4,
                        'Saturday' => 5,
                        'Sunday' => 6,
                    ];
                    if (isset($weekdayMap[$day])) {
                        $date = $date->addDays($weekdayMap[$day]);
                        for ($i = 0; $i < 8; $i++) {
                            $eventDate = $date->copy()->addWeeks($i);
                            $events[] = [
                                'title' => '' . $classroom->name . ($classroom->teacher ? ' (' . $classroom->teacher->name . ')' : ''),
                                'start' => $eventDate->format('Y-m-d') . 'T' . $startTime,
                                'end' => $eventDate->format('Y-m-d') . ($endTime ? 'T' . $endTime : ''),
                                'allDay' => false,
                                'backgroundColor' => '#0d6efd',
                                'borderColor' => '#0d6efd',
                            ];
                        }
                    }
                }
            }
            // Lịch bài tập
            foreach ($classroom->assignments as $assignment) {
                if ($assignment->deadline) {
                    $events[] = [
                        'title' => '' . $assignment->title . ' (' . $classroom->name . ')',
                        'start' => $assignment->deadline->format('Y-m-d\TH:i'),
                        'end' => $assignment->deadline->format('Y-m-d\TH:i'),
                        'allDay' => false,
                        'backgroundColor' => '#fd7e14',
                        'borderColor' => '#fd7e14',
                    ];
                }
            }
            // Lịch kiểm tra
            $quizzes = Quiz::where('class_id', $classroom->id)->get();
            foreach ($quizzes as $quiz) {
                if ($quiz->deadline) {
                    $events[] = [
                        'title' => '' . $quiz->title . ' (' . $classroom->name . ')',
                        'start' => $quiz->deadline->format('Y-m-d\TH:i'),
                        'end' => $quiz->deadline->format('Y-m-d\TH:i'),
                        'allDay' => false,
                        'backgroundColor' => '#20c997',
                        'borderColor' => '#20c997',
                    ];
                }
            }
        }
        return view('student.schedules.index', compact('events'));
    }
}
