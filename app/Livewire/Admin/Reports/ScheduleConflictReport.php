<?php

namespace App\Livewire\Admin\Reports;

use App\Models\Classroom;
use App\Models\User;
use App\Helpers\ScheduleConflictHelper;
use Livewire\Component;

class ScheduleConflictReport extends Component
{
    public $studentConflicts = [];
    public $teacherConflicts = [];
    public $totalStudentConflicts = 0;
    public $totalTeacherConflicts = 0;
    public $loading = true;
    public $lastChecked = null;

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
            foreach ($students as $student) {
                $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
                if ($conflict['hasConflict']) {
                    $allStudentConflicts[] = [
                        'classroom' => $classroom,
                        'student' => $student,
                        'conflicts' => $conflict['conflicts']
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
                        'conflicts' => $conflict['conflicts']
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

    public function refreshConflicts()
    {
        $this->checkConflicts();
        session()->flash('message', 'Đã cập nhật báo cáo trùng lịch!');
    }

    public function exportReport()
    {
        $filename = 'schedule_conflicts_report_' . date('Y-m-d_H-i-s') . '.txt';
        $content = "=== BÁO CÁO TRÙNG LỊCH - " . date('Y-m-d H:i:s') . " ===\n\n";
        
        if (!empty($this->studentConflicts)) {
            $content .= "TRÙNG LỊCH HỌC SINH:\n";
            foreach ($this->studentConflicts as $conflict) {
                $content .= "- Lớp: {$conflict['classroom']->name}\n";
                $content .= "  + Học sinh: {$conflict['student']->name} ({$conflict['student']->email})\n";
                foreach ($conflict['conflicts'] as $conf) {
                    $content .= "    * {$conf['message']}\n";
                }
            }
        }
        
        if (!empty($this->teacherConflicts)) {
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
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function render()
    {
        return view('admin.reports.schedule-conflict-report');
    }
}
