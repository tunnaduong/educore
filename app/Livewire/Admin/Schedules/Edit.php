<?php

namespace App\Livewire\Admin\Schedules;

use App\Models\Classroom;
use Livewire\Component;

class Edit extends Component
{
    public Classroom $classroom;

    public $selectedDays = [];

    public $startTime = '';

    public $endTime = '';

    public $notes = '';

    protected $rules = [
        'selectedDays' => 'required|array|min:1',
        'startTime' => 'required|date_format:H:i',
        'endTime' => 'required|date_format:H:i|after:startTime',
        'notes' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'selectedDays.required' => 'Vui lòng chọn ít nhất một ngày trong tuần.',
        'selectedDays.min' => 'Vui lòng chọn ít nhất một ngày trong tuần.',
        'startTime.required' => 'Vui lòng nhập giờ bắt đầu.',
        'startTime.date_format' => 'Giờ bắt đầu không đúng định dạng.',
        'endTime.required' => 'Vui lòng nhập giờ kết thúc.',
        'endTime.date_format' => 'Giờ kết thúc không đúng định dạng.',
        'endTime.after' => 'Giờ kết thúc phải sau giờ bắt đầu.',
        'notes.max' => 'Ghi chú không được vượt quá 500 ký tự.',
    ];

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom;

        // Load existing schedule data
        if ($this->classroom->schedule) {
            $this->selectedDays = $this->classroom->schedule['days'] ?? [];

            if (isset($this->classroom->schedule['time'])) {
                $timeParts = explode(' - ', $this->classroom->schedule['time']);
                if (count($timeParts) === 2) {
                    $this->startTime = $timeParts[0];
                    $this->endTime = $timeParts[1];
                }
            }
        }

        $this->notes = $this->classroom->notes ?? '';
    }

    public function save()
    {
        $this->validate();

        $schedule = [
            'days' => $this->selectedDays,
            'time' => $this->startTime.' - '.$this->endTime,
        ];

        $this->classroom->update([
            'schedule' => $schedule,
            'notes' => $this->notes,
        ]);

        session()->flash('success_message', 'Lịch học đã được cập nhật thành công!');
        
        return redirect()->route('schedules.index');
    }

    public function render()
    {
        $availableDays = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật',
        ];

        return view('admin.schedules.edit', [
            'availableDays' => $availableDays,
        ]);
    }
}
