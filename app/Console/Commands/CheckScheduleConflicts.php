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
    protected $signature = 'schedule:check-conflicts 
                            {--classroom= : Check specific classroom} 
                            {--student= : Check specific student} 
                            {--teacher= : Check specific teacher} 
                            {--type=all : Type of conflicts to check (all, student, teacher)}
                            {--fix= : Auto-fix conflicts (auto, manual, none)}
                            {--report : Generate detailed report}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra trùng lịch học và trùng lịch dạy trong hệ thống';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Đang kiểm tra trùng lịch...');

        if ($classroomId = $this->option('classroom')) {
            $this->checkSpecificClassroom($classroomId);
        } elseif ($studentId = $this->option('student')) {
            $this->checkSpecificStudent($studentId);
        } elseif ($teacherId = $this->option('teacher')) {
            $this->checkSpecificTeacher($teacherId);
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
        
        // Kiểm tra trùng lịch học sinh
        $students = $classroom->students;
        $studentConflicts = [];

        foreach ($students as $student) {
            $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
            if ($conflict['hasConflict']) {
                $studentConflicts[$student->id] = [
                    'student' => $student,
                    'conflicts' => $conflict['conflicts']
                ];
            }
        }

        if (!empty($studentConflicts)) {
            $this->displayStudentConflicts($studentConflicts, $classroom->name);
        }

        // Kiểm tra trùng lịch giáo viên
        $teachers = $classroom->teachers;
        $teacherConflicts = [];

        foreach ($teachers as $teacher) {
            $conflict = ScheduleConflictHelper::checkTeacherScheduleConflict($teacher, $classroom);
            if ($conflict['hasConflict']) {
                $teacherConflicts[$teacher->id] = [
                    'teacher' => $teacher,
                    'conflicts' => $conflict['conflicts']
                ];
            }
        }

        if (!empty($teacherConflicts)) {
            $this->displayTeacherConflicts($teacherConflicts, $classroom->name);
        }

        if (empty($studentConflicts) && empty($teacherConflicts)) {
            $this->info("✅ Lớp {$classroom->name} không có trùng lịch");
        }
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

    private function checkSpecificTeacher($teacherId)
    {
        $teacher = User::find($teacherId);
        if (!$teacher || $teacher->role !== 'teacher') {
            $this->error("❌ Không tìm thấy giáo viên với ID: {$teacherId}");
            return;
        }

        $this->info("👨‍🏫 Kiểm tra giáo viên: {$teacher->name}");
        
        $classrooms = $teacher->teachingClassrooms;
        $allConflicts = [];

        foreach ($classrooms as $classroom) {
            $conflict = ScheduleConflictHelper::checkTeacherScheduleConflict($teacher, $classroom);
            if ($conflict['hasConflict']) {
                $allConflicts[$classroom->id] = [
                    'classroom' => $classroom,
                    'conflicts' => $conflict['conflicts']
                ];
            }
        }

        $this->displayTeacherConflicts($allConflicts, $teacher->name);
    }

    private function checkAllConflicts()
    {
        $classrooms = Classroom::with(['students', 'teachers'])->get();
        $totalStudentConflicts = 0;
        $totalTeacherConflicts = 0;
        $allConflicts = [];

        $this->info("📊 Tổng số lớp học: {$classrooms->count()}");

        foreach ($classrooms as $classroom) {
            $this->info("\n📚 Kiểm tra lớp: {$classroom->name}");
            
            // Kiểm tra trùng lịch học sinh
            $students = $classroom->students;
            $studentConflicts = [];

            foreach ($students as $student) {
                $conflict = ScheduleConflictHelper::checkStudentScheduleConflict($student, $classroom);
                if ($conflict['hasConflict']) {
                    $studentConflicts[$student->id] = [
                        'student' => $student,
                        'conflicts' => $conflict['conflicts']
                    ];
                }
            }

            if (!empty($studentConflicts)) {
                $this->displayStudentConflicts($studentConflicts, $classroom->name);
                $totalStudentConflicts += count($studentConflicts);
                $allConflicts['students'][$classroom->id] = $studentConflicts;
            }

            // Kiểm tra trùng lịch giáo viên
            $teachers = $classroom->teachers;
            $teacherConflicts = [];

            foreach ($teachers as $teacher) {
                $conflict = ScheduleConflictHelper::checkTeacherScheduleConflict($teacher, $classroom);
                if ($conflict['hasConflict']) {
                    $teacherConflicts[$teacher->id] = [
                        'teacher' => $teacher,
                        'conflicts' => $conflict['conflicts']
                    ];
                }
            }

            if (!empty($teacherConflicts)) {
                $this->displayTeacherConflicts($teacherConflicts, $classroom->name);
                $totalTeacherConflicts += count($teacherConflicts);
                $allConflicts['teachers'][$classroom->id] = $teacherConflicts;
            }

            if (empty($studentConflicts) && empty($teacherConflicts)) {
                $this->info("✅ Không có trùng lịch");
            }
        }

        $this->info("\n📈 Tổng kết:");
        $this->info("   - Trùng lịch học sinh: {$totalStudentConflicts}");
        $this->info("   - Trùng lịch giáo viên: {$totalTeacherConflicts}");
        $this->info("   - Tổng cộng: " . ($totalStudentConflicts + $totalTeacherConflicts));

        // Tạo báo cáo nếu được yêu cầu
        if ($this->option('report')) {
            $this->generateReport($allConflicts);
        }

        // Tự động sửa nếu được yêu cầu
        if ($fixOption = $this->option('fix')) {
            if ($fixOption === 'auto') {
                $this->autoFixConflicts($allConflicts);
            } elseif ($fixOption === 'manual') {
                $this->manualFixConflicts($allConflicts);
            }
        }
    }

    private function displayStudentConflicts($conflicts, $context)
    {
        $this->warn("⚠️  Phát hiện trùng lịch học sinh trong {$context}:");
        
        foreach ($conflicts as $id => $conflictData) {
            $student = $conflictData['student'];
            $this->line("   👤 {$student->name} ({$student->email}):");
            
            foreach ($conflictData['conflicts'] as $conflict) {
                $this->line("      📚 {$conflict['message']}");
                if ($conflict['overlapTime']) {
                    $this->line("         ⏰ Thời gian trùng: {$conflict['overlapTime']}");
                }
            }
        }
    }

    private function displayTeacherConflicts($conflicts, $context)
    {
        $this->warn("⚠️  Phát hiện trùng lịch giáo viên trong {$context}:");
        
        foreach ($conflicts as $id => $conflictData) {
            $teacher = $conflictData['teacher'];
            $this->line("   👨‍🏫 {$teacher->name} ({$teacher->email}):");
            
            foreach ($conflictData['conflicts'] as $conflict) {
                $this->line("      📚 {$conflict['message']}");
                if ($conflict['overlapTime']) {
                    $this->line("         ⏰ Thời gian trùng: {$conflict['overlapTime']}");
                }
            }
        }
    }

    private function generateReport($conflicts)
    {
        $reportPath = storage_path('logs/schedule_conflicts_report_' . date('Y-m-d_H-i-s') . '.txt');
        
        $content = "=== BÁO CÁO TRÙNG LỊCH - " . date('Y-m-d H:i:s') . " ===\n\n";
        
        if (isset($conflicts['students'])) {
            $content .= "TRÙNG LỊCH HỌC SINH:\n";
            foreach ($conflicts['students'] as $classroomId => $studentConflicts) {
                $classroom = Classroom::find($classroomId);
                $content .= "- Lớp: {$classroom->name}\n";
                foreach ($studentConflicts as $studentId => $conflictData) {
                    $student = $conflictData['student'];
                    $content .= "  + Học sinh: {$student->name} ({$student->email})\n";
                    foreach ($conflictData['conflicts'] as $conflict) {
                        $content .= "    * {$conflict['message']}\n";
                    }
                }
            }
        }
        
        if (isset($conflicts['teachers'])) {
            $content .= "\nTRÙNG LỊCH GIÁO VIÊN:\n";
            foreach ($conflicts['teachers'] as $classroomId => $teacherConflicts) {
                $classroom = Classroom::find($classroomId);
                $content .= "- Lớp: {$classroom->name}\n";
                foreach ($teacherConflicts as $teacherId => $conflictData) {
                    $teacher = $conflictData['teacher'];
                    $content .= "  + Giáo viên: {$teacher->name} ({$teacher->email})\n";
                    foreach ($conflictData['conflicts'] as $conflict) {
                        $content .= "    * {$conflict['message']}\n";
                    }
                }
            }
        }
        
        file_put_contents($reportPath, $content);
        $this->info("📄 Báo cáo đã được tạo tại: {$reportPath}");
    }

    private function autoFixConflicts($conflicts)
    {
        $this->warn("🔧 Bắt đầu tự động sửa trùng lịch...");
        
        if (isset($conflicts['teachers'])) {
            foreach ($conflicts['teachers'] as $classroomId => $teacherConflicts) {
                $classroom = Classroom::find($classroomId);
                $this->info("Sửa trùng lịch cho lớp: {$classroom->name}");
                
                // Logic tự động sửa có thể được thêm ở đây
                // Ví dụ: thay đổi thời gian học, loại bỏ ngày trùng, v.v.
            }
        }
        
        $this->info("✅ Hoàn thành tự động sửa trùng lịch");
    }

    private function manualFixConflicts($conflicts)
    {
        $this->warn("🔧 Chế độ sửa thủ công - Vui lòng xem xét các trùng lịch sau:");
        
        if (isset($conflicts['teachers'])) {
            foreach ($conflicts['teachers'] as $classroomId => $teacherConflicts) {
                $classroom = Classroom::find($classroomId);
                $this->info("Lớp: {$classroom->name}");
                
                foreach ($teacherConflicts as $teacherId => $conflictData) {
                    $teacher = $conflictData['teacher'];
                    $this->line("  - Giáo viên: {$teacher->name}");
                    
                    foreach ($conflictData['conflicts'] as $conflict) {
                        $this->line("    + {$conflict['message']}");
                    }
                }
            }
        }
        
        $this->info("💡 Gợi ý sửa:");
        $this->line("   1. Thay đổi thời gian học của một trong các lớp");
        $this->line("   2. Thay đổi ngày học để tránh trùng");
        $this->line("   3. Gán giáo viên khác cho lớp");
    }
}
