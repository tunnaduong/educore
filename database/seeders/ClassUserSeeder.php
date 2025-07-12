<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ClassUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $classrooms = Classroom::all();
        $students = Student::all();

        if ($users->isEmpty() || $classrooms->isEmpty()) {
            $this->command->info('Không có users hoặc classrooms để tạo dữ liệu. Vui lòng chạy UserSeeder trước.');
            return;
        }

        // Xóa dữ liệu cũ
        DB::table('class_user')->truncate();

        // Tạo dữ liệu mẫu
        foreach ($classrooms as $classroom) {
            // Thêm teacher vào lớp
            if ($classroom->teacher_id) {
                DB::table('class_user')->insert([
                    'class_id' => $classroom->id,
                    'user_id' => $classroom->teacher_id,
                    'role' => 'teacher',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Thêm một số students vào lớp
            $studentUsers = $users->where('role', 'student')->take(3);
            foreach ($studentUsers as $studentUser) {
                DB::table('class_user')->insert([
                    'class_id' => $classroom->id,
                    'user_id' => $studentUser->id,
                    'role' => 'student',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Đã tạo dữ liệu mẫu cho bảng class_user.');
    }
}
