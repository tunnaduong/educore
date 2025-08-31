<?php

namespace App\Livewire\Teacher\Schedules;

use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CreateEvent extends Component
{
    public $showModal = false;

    public $eventType = 'lesson';

    public $selectedDate;

    public $classroomId;

    public $title;

    public $description;

    public $startTime;

    public $endTime;

    public $location;

    public $dueDate;

    public $maxScore;

    public $duration;

    protected $listeners = ['openCreateEventModal' => 'openModal'];

    protected $rules = [
        'eventType' => 'required|in:lesson,assignment,quiz',
        'classroomId' => 'required|exists:classrooms,id',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'startTime' => 'required_if:eventType,lesson,quiz|date',
        'endTime' => 'required_if:eventType,lesson,quiz|date|after:startTime',
        'location' => 'nullable|string|max:255',
        'dueDate' => 'required_if:eventType,assignment|date|after:now',
        'maxScore' => 'nullable|numeric|min:0|max:10',
        'duration' => 'required_if:eventType,quiz|numeric|min:1',
    ];

    protected $messages = [
        'classroomId.required' => 'Vui lòng chọn lớp học.',
        'title.required' => 'Vui lòng nhập tiêu đề.',
        'startTime.required_if' => 'Vui lòng nhập thời gian bắt đầu.',
        'endTime.required_if' => 'Vui lòng nhập thời gian kết thúc.',
        'endTime.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        'dueDate.required_if' => 'Vui lòng nhập hạn nộp.',
        'dueDate.after' => 'Hạn nộp không được trong quá khứ.',
        'duration.required_if' => 'Vui lòng nhập thời gian làm bài.',
        'duration.min' => 'Thời gian làm bài phải lớn hơn 0.',
        'maxScore.numeric' => 'Điểm phải là số hợp lệ.',
        'maxScore.min' => 'Điểm không được nhỏ hơn 0.',
        'maxScore.max' => 'Điểm không được vượt quá 10.',
    ];

    public function mount($selectedDate = null)
    {
        $this->selectedDate = $selectedDate ?? now()->format('Y-m-d');
        $this->startTime = $this->selectedDate.' 08:00';
        $this->endTime = $this->selectedDate.' 09:30';
        $this->dueDate = $this->selectedDate;
    }

    public function openModal()
    {
        $this->showModal = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->eventType = 'lesson';
        $this->classroomId = '';
        $this->title = '';
        $this->description = '';
        $this->startTime = $this->selectedDate.' 08:00';
        $this->endTime = $this->selectedDate.' 09:30';
        $this->location = '';
        $this->dueDate = $this->selectedDate;
        $this->maxScore = '';
        $this->duration = '';
        $this->resetErrorBag();
    }

    public function updatedEventType()
    {
        if ($this->eventType === 'assignment') {
            $this->startTime = '';
            $this->endTime = '';
            $this->location = '';
            $this->duration = '';
        } else {
            $this->dueDate = '';
            $this->startTime = $this->selectedDate.' 08:00';
            $this->endTime = $this->selectedDate.' 09:30';
        }
    }

    public function updatedSelectedDate()
    {
        if ($this->eventType !== 'assignment') {
            $this->startTime = $this->selectedDate.' 08:00';
            $this->endTime = $this->selectedDate.' 09:30';
        }
        $this->dueDate = $this->selectedDate;
    }

    public function save()
    {
        $this->validate();

        try {
            switch ($this->eventType) {
                case 'lesson':
                    // Tạo lesson content thay vì schedule
                    $lesson = Lesson::create([
                        'classroom_id' => $this->classroomId,
                        'title' => $this->title,
                        'description' => $this->description,
                        'content' => 'Nội dung bài học: '.$this->description,
                    ]);
                    $this->dispatch('eventCreated', type: 'lesson', id: $lesson->id);
                    break;

                case 'assignment':
                    $data = [
                        'class_id' => $this->classroomId,
                        'title' => $this->title,
                        'description' => $this->description,
                        'deadline' => $this->dueDate,
                        'types' => json_encode(['text']), // Default type
                    ];

                    // Chỉ thêm max_score nếu có giá trị
                    if (!empty($this->maxScore)) {
                        $data['max_score'] = $this->maxScore;
                    }

                    $assignment = Assignment::create($data);
                    $this->dispatch('eventCreated', type: 'assignment', id: $assignment->id);
                    break;

                case 'quiz':
                    $quiz = Quiz::create([
                        'class_id' => $this->classroomId,
                        'title' => $this->title,
                        'description' => $this->description,
                        'deadline' => $this->endTime,
                        'questions' => json_encode([]), // Empty questions array
                    ]);
                    $this->dispatch('eventCreated', type: 'quiz', id: $quiz->id);
                    break;
            }

            $this->closeModal();
            session()->flash('message', 'Sự kiện đã được tạo thành công!');
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra khi tạo sự kiện: '.$e->getMessage());
        }
    }

    public function getClassroomsProperty()
    {
        $teacher = Auth::user();

        return $teacher->teachingClassrooms;
    }

    public function render()
    {
        return view('teacher.schedules.create-event');
    }
}
