<?php

namespace App\Livewire\Teacher\Schedules;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\Classroom;

class EventDetail extends Component
{
    public $eventId;
    public $eventType;
    public $eventData;
    public $showModal = false;

    protected $listeners = ['showEventDetail' => 'showDetail'];

    public function mount()
    {
        $this->showModal = false;
    }

    public function showDetail($eventId, $eventType)
    {
        $this->eventId = $eventId;
        $this->eventType = $eventType;
        $this->loadEventData();
        $this->showModal = true;
    }

    public function loadEventData()
    {
        switch ($this->eventType) {
            case 'schedule':
                // Lấy thông tin từ classroom schedule
                $classroomId = explode('_', $this->eventId)[1];
                $classroom = Classroom::find($classroomId);
                $this->eventData = $classroom;
                break;
            case 'lesson':
                $this->eventData = Lesson::with('classroom')->find($this->eventId);
                break;
            case 'assignment':
                $this->eventData = Assignment::with('classroom')->find($this->eventId);
                break;
            case 'quiz':
                $this->eventData = Quiz::with('classroom')->find($this->eventId);
                break;
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->eventData = null;
    }

    public function render()
    {
        return view('teacher.schedules.event-detail');
    }
}
