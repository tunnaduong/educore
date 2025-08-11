<?php

namespace App\Livewire\Admin\Classrooms;

use App\Models\Classroom;
use App\Helpers\ScheduleConflictHelper;
use Livewire\Component;

class ScheduleConflictAlert extends Component
{
    public Classroom $classroom;
    public $conflicts = [];
    public $showConflicts = false;

    protected $listeners = ['refreshConflicts' => 'checkConflicts'];

    public function mount(Classroom $classroom)
    {
        $this->classroom = $classroom;
        $this->checkConflicts();
    }

    public function checkConflicts()
    {
        try {
            $students = $this->classroom->students;
            $allConflicts = [];

            foreach ($students as $student) {
                $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $this->classroom);
                if ($conflict['hasConflict']) {
                    $allConflicts[$student->id] = [
                        'student' => $student,
                        'conflicts' => $conflict['conflicts']
                    ];
                }
            }

            $this->conflicts = $allConflicts;
            $this->showConflicts = !empty($allConflicts);
        } catch (\Exception $e) {
            $this->conflicts = [];
            $this->showConflicts = false;
        }
    }

    public function toggleConflicts()
    {
        $this->showConflicts = !$this->showConflicts;
    }

    public function render()
    {
        return view('admin.classrooms.schedule-conflict-alert');
    }
}
