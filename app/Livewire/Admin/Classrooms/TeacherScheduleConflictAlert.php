<?php

namespace App\Livewire\Admin\Classrooms;

use App\Helpers\ScheduleConflictHelper;
use App\Models\Classroom;
use Livewire\Component;

class TeacherScheduleConflictAlert extends Component
{
    public Classroom $classroom;

    public $conflicts = [];

    public $showConflicts = false;

    protected $listeners = ['refreshTeacherConflicts' => 'checkConflicts'];

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom;
        $this->checkConflicts();
    }

    public function checkConflicts()
    {
        try {
            $teachers = $this->classroom->teachers;
            $allConflicts = [];

            foreach ($teachers as $teacher) {
                $conflict = ScheduleConflictHelper::checkTeacherScheduleConflict($teacher, $this->classroom);
                if ($conflict['hasConflict']) {
                    $allConflicts[$teacher->id] = [
                        'teacher' => $teacher,
                        'conflicts' => $conflict['conflicts'],
                    ];
                }
            }

            $this->conflicts = $allConflicts;
            $this->showConflicts = ! empty($allConflicts);
        } catch (\Exception $e) {
            \Log::error('TeacherScheduleConflictAlert checkConflicts error: '.$e->getMessage());
            $this->conflicts = [];
            $this->showConflicts = false;
        }
    }

    public function toggleConflicts()
    {
        $this->showConflicts = ! $this->showConflicts;
    }

    public function render()
    {
        return view('livewire.admin.classrooms.teacher-schedule-conflict-alert');
    }
}
