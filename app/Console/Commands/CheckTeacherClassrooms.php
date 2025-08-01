<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;

class CheckTeacherClassrooms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teacher:check-classrooms {--fix : Tự động thêm teacher vào lớp học}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và sửa lỗi teacher không có lớp học';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Kiểm tra dữ liệu teacher và lớp học...');

        // Lấy tất cả teacher
        $teachers = User::where('role', 'teacher')->get();
        $this->info("📚 Tìm thấy {$teachers->count()} teacher");

        // Lấy tất cả lớp học
        $classrooms = Classroom::all();
        $this->info("🏫 Tìm thấy {$classrooms->count()} lớp học");

        // Kiểm tra dữ liệu trong bảng class_user
        $classUserData = DB::table('class_user')->get();
        $this->info("👥 Dữ liệu trong bảng class_user: {$classUserData->count()} records");

        foreach ($teachers as $teacher) {
            $this->info("\n👨‍🏫 Teacher: {$teacher->name} (ID: {$teacher->id})");
            
            $teachingClassrooms = $teacher->teachingClassrooms;
            $this->info("   📖 Lớp học đang dạy: {$teachingClassrooms->count()}");

            if ($teachingClassrooms->isEmpty()) {
                $this->warn("   ⚠️  Teacher này chưa được gán vào lớp học nào!");
                
                if ($this->option('fix')) {
                    // Tự động gán teacher vào lớp học đầu tiên
                    if ($classrooms->isNotEmpty()) {
                        $firstClassroom = $classrooms->first();
                        DB::table('class_user')->insert([
                            'class_id' => $firstClassroom->id,
                            'user_id' => $teacher->id,
                            'role' => 'teacher',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        $this->info("   ✅ Đã gán teacher vào lớp: {$firstClassroom->name}");
                    }
                }
            } else {
                foreach ($teachingClassrooms as $classroom) {
                    $this->info("   - {$classroom->name}");
                }
            }
        }

        if ($this->option('fix')) {
            $this->info("\n🎉 Đã hoàn thành việc sửa lỗi!");
        } else {
            $this->info("\n💡 Chạy lệnh với --fix để tự động sửa lỗi");
        }
    }
} 