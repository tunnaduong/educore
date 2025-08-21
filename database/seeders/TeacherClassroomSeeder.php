<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔍 Đang kiểm tra và thêm teacher vào lớp học...');

        // Lấy tất cả teacher
        $teachers = User::where('role', 'teacher')->get();
        $classrooms = Classroom::all();

        if ($teachers->isEmpty()) {
            $this->command->warn('⚠️  Không có teacher nào trong hệ thống!');

            return;
        }

        if ($classrooms->isEmpty()) {
            $this->command->warn('⚠️  Không có lớp học nào trong hệ thống!');

            return;
        }

        $this->command->info("📚 Tìm thấy {$teachers->count()} teacher");
        $this->command->info("🏫 Tìm thấy {$classrooms->count()} lớp học");

        // Xóa dữ liệu cũ của teacher trong bảng class_user
        DB::table('class_user')->where('role', 'teacher')->delete();

        // Gán teacher vào lớp học
        foreach ($teachers as $index => $teacher) {
            // Gán teacher vào lớp học tương ứng hoặc lớp đầu tiên
            $classroom = $classrooms->get($index, $classrooms->first());

            DB::table('class_user')->insert([
                'class_id' => $classroom->id,
                'user_id' => $teacher->id,
                'role' => 'teacher',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info("✅ Đã gán teacher '{$teacher->name}' vào lớp '{$classroom->name}'");
        }

        $this->command->info('🎉 Hoàn thành việc gán teacher vào lớp học!');
    }
}
