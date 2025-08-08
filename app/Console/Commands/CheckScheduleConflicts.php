<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Classroom;
use App\Models\User;
use App\Helpers\ScheduleConflictHelper;

class CheckScheduleConflicts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:check-conflicts {--classroom= : Check specific classroom} {--student= : Check specific student}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra trùng lịch học trong hệ thống';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Đang kiểm tra trùng lịch học...');

        if ($classroomId = $this->option('classroom')) {
            $this->checkSpecificClassroom($classroomId);
        } elseif ($studentId = $this->option('student')) {
            $this->checkSpecificStudent($studentId);
        } else {
            $this->checkAllConflicts();
        }

        $this->info('✅ Hoàn thành kiểm tra trùng lịch!');
    }

    private function checkSpecificClassroom($classroomId)
    {
        $classroom = Classroom::find($classroomId);
        if (!$classroom) {
            $this->error("❌ Không tìm thấy lớp học với ID: {$classroomId}");
            return;
        }

        $this->info("📚 Kiểm tra lớp: {$classroom->name}");
        
        $students = $classroom->students;
        $conflicts = [];

        foreach ($students as $student) {
            $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
            if ($conflict['hasConflict']) {
                $conflicts[$student->id] = [
                    'student' => $student,
                    'conflicts' => $conflict['conflicts']
                ];
            }
        }

        $this->displayConflicts($conflicts, $classroom->name);
    }

    private function checkSpecificStudent($studentId)
    {
        $student = User::find($studentId);
        if (!$student || $student->role !== 'student') {
            $this->error("❌ Không tìm thấy học sinh với ID: {$studentId}");
            return;
        }

        $this->info("👤 Kiểm tra học sinh: {$student->name}");
        
        $classrooms = $student->enrolledClassrooms;
        $allConflicts = [];

        foreach ($classrooms as $classroom) {
            $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
            if ($conflict['hasConflict']) {
                $allConflicts[$classroom->id] = [
                    'classroom' => $classroom,
                    'conflicts' => $conflict['conflicts']
                ];
            }
        }

        $this->displayStudentConflicts($allConflicts, $student->name);
    }

    private function checkAllConflicts()
    {
        $classrooms = Classroom::with('students')->get();
        $totalConflicts = 0;

        $this->info("📊 Tổng số lớp học: {$classrooms->count()}");

        foreach ($classrooms as $classroom) {
            $students = $classroom->students;
            $conflicts = [];

            foreach ($students as $student) {
                $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
                if ($conflict['hasConflict']) {
                    $conflicts[$student->id] = [
                        'student' => $student,
                        'conflicts' => $conflict['conflicts']
                    ];
                }
            }

            if (!empty($conflicts)) {
                $this->displayConflicts($conflicts, $classroom->name);
                $totalConflicts += count($conflicts);
            }
        }

        if ($totalConflicts === 0) {
            $this->info('✅ Không phát hiện trùng lịch nào trong hệ thống!');
        } else {
            $this->warn("⚠️  Tổng cộng phát hiện {$totalConflicts} trường hợp trùng lịch!");
        }
    }

    private function displayConflicts($conflicts, $classroomName)
    {
        if (empty($conflicts)) {
            $this->info("✅ Lớp {$classroomName}: Không có trùng lịch");
            return;
        }

        $this->warn("⚠️  Lớp {$classroomName}: Phát hiện " . count($conflicts) . " học sinh trùng lịch");

        foreach ($conflicts as $studentId => $conflictData) {
            $student = $conflictData['student'];
            $this->line("   👤 {$student->name} ({$student->email})");
            
            foreach ($conflictData['conflicts'] as $conflict) {
                $this->line("      🔴 " . $conflict['message']);
            }
        }
    }

    private function displayStudentConflicts($conflicts, $studentName)
    {
        if (empty($conflicts)) {
            $this->info("✅ Học sinh {$studentName}: Không có trùng lịch");
            return;
        }

        $this->warn("⚠️  Học sinh {$studentName}: Phát hiện " . count($conflicts) . " lớp trùng lịch");

        foreach ($conflicts as $classroomId => $conflictData) {
            $classroom = $conflictData['classroom'];
            $this->line("   📚 {$classroom->name}");
            
            foreach ($conflictData['conflicts'] as $conflict) {
                $this->line("      🔴 " . $conflict['message']);
            }
        }
    }
}
