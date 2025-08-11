<?php

namespace App\Livewire\Admin\Reports;

use Livewire\Component;
use App\Models\Classroom;
use App\Models\User;
use App\Helpers\ScheduleConflictHelper;
use Livewire\WithPagination;

class ScheduleConflictReport extends Component
{
    use WithPagination;

    public $search = '';
    public $filterClassroom = '';
    public $filterStudent = '';
    public $showDetails = false;
    public $selectedConflict = null;

    protected $queryString = ['search', 'filterClassroom', 'filterStudent'];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterClassroom()
    {
        $this->resetPage();
    }

    public function updatedFilterStudent()
    {
        $this->resetPage();
    }

    public function showConflictDetails($classroomId, $studentId)
    {
        $classroom = Classroom::find($classroomId);
        $student = User::find($studentId);

        if ($classroom && $student) {
            $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
            $this->selectedConflict = [
                'classroom' => $classroom,
                'student' => $student,
                'conflicts' => $conflict['conflicts']
            ];
            $this->showDetails = true;
        }
    }

    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedConflict = null;
    }

    public function getConflictsProperty()
    {
        $query = Classroom::with('students');

        if ($this->filterClassroom) {
            $query->where('id', $this->filterClassroom);
        }

        $classrooms = $query->get();
        $allConflicts = [];

        foreach ($classrooms as $classroom) {
            $students = $classroom->students;

            if ($this->filterStudent) {
                $students = $students->filter(function ($student) {
                    return str_contains(strtolower($student->name), strtolower($this->filterStudent)) ||
                        str_contains(strtolower($student->email), strtolower($this->filterStudent));
                });
            }

            foreach ($students as $student) {
                $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
                if ($conflict['hasConflict']) {
                    $allConflicts[] = [
                        'classroom' => $classroom,
                        'student' => $student,
                        'conflicts' => $conflict['conflicts']
                    ];
                }
            }
        }

        // Filter by search
        if ($this->search) {
            $allConflicts = collect($allConflicts)->filter(function ($conflict) {
                return str_contains(strtolower($conflict['classroom']->name), strtolower($this->search)) ||
                    str_contains(strtolower($conflict['student']->name), strtolower($this->search));
            })->toArray();
        }

        return collect($allConflicts)->paginate(10);
    }

    public function getClassroomsProperty()
    {
        return Classroom::orderBy('name')->get();
    }

    public function getStudentsProperty()
    {
        return User::where('role', 'student')
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        return view('admin.reports.schedule-conflict-report', [
            'conflicts' => $this->conflicts,
            'classrooms' => $this->classrooms,
            'students' => $this->students,
        ]);
    }
}
