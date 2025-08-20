<?php

namespace App\Livewire\Admin\Reports;

use App\Helpers\ScheduleConflictHelper;
use App\Models\Classroom;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleConflictReport extends Component
{
    use WithPagination;

    public $studentConflicts = [];

    public $teacherConflicts = [];

    public $totalStudentConflicts = 0;

    public $totalTeacherConflicts = 0;

    public $loading = true;

    public $lastChecked = null;

    public $search = '';

    public $filterClassroom = '';

    public $filterStudent = '';

    public $showDetails = false;

    public $selectedConflict = null;

    public function mount()
    {
        $this->checkConflicts();
    }

    public function checkConflicts()
    {
        $this->loading = true;

        $classrooms = Classroom::with(['students', 'teachers'])->get();
        $allStudentConflicts = [];
        $allTeacherConflicts = [];

        foreach ($classrooms as $classroom) {
            // Kiểm tra trùng lịch học sinh
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
                    $allStudentConflicts[] = [
                        'classroom' => $classroom,
                        'student' => $student,
                        'conflicts' => $conflict['conflicts'],
                    ];
                }
            }

            // Kiểm tra trùng lịch giáo viên
            $teachers = $classroom->teachers;
            foreach ($teachers as $teacher) {
                $conflict = ScheduleConflictHelper::checkTeacherScheduleConflict($teacher, $classroom);
                if ($conflict['hasConflict']) {
                    $allTeacherConflicts[] = [
                        'classroom' => $classroom,
                        'teacher' => $teacher,
                        'conflicts' => $conflict['conflicts'],
                    ];
                }
            }
        }

        $this->studentConflicts = $allStudentConflicts;
        $this->teacherConflicts = $allTeacherConflicts;
        $this->totalStudentConflicts = count($allStudentConflicts);
        $this->totalTeacherConflicts = count($allTeacherConflicts);
        $this->lastChecked = now();
        $this->loading = false;
    }

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
                'conflicts' => $conflict['conflicts'],
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
            // Kiểm tra trùng lịch học sinh
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
                        'conflicts' => $conflict['conflicts'],
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
        return User::where('role', 'student')->orderBy('name')->get();
    }

    public function refreshConflicts()
    {
        $this->checkConflicts();
        session()->flash('message', 'Đã cập nhật báo cáo trùng lịch!');
    }

    public function exportReport()
    {
        $filename = 'schedule_conflicts_report_'.date('Y-m-d_H-i-s').'.txt';
        $content = '=== BÁO CÁO TRÙNG LỊCH - '.date('Y-m-d H:i:s')." ===\n\n";

        if (! empty($this->studentConflicts)) {
            $content .= "TRÙNG LỊCH HỌC SINH:\n";
            foreach ($this->studentConflicts as $conflict) {
                $content .= "- Lớp: {$conflict['classroom']->name}\n";
                $content .= "  + Học sinh: {$conflict['student']->name} ({$conflict['student']->email})\n";
                foreach ($conflict['conflicts'] as $conf) {
                    $content .= "    * {$conf['message']}\n";
                }
            }
        }

        if (! empty($this->teacherConflicts)) {
            $content .= "\nTRÙNG LỊCH GIÁO VIÊN:\n";
            foreach ($this->teacherConflicts as $conflict) {
                $content .= "- Lớp: {$conflict['classroom']->name}\n";
                $content .= "  + Giáo viên: {$conflict['teacher']->name} ({$conflict['teacher']->email})\n";
                foreach ($conflict['conflicts'] as $conf) {
                    $content .= "    * {$conf['message']}\n";
                }
            }
        }

        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
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
