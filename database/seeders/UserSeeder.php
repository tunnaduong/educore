<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Assignment;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'id' => 1,
            'name' => 'Dương Tùng Anh',
            'email' => 'tunnaduong@gmail.com',
            'phone' => '0707006421',
            'password' => bcrypt('tunganh2003'),
            'role' => 'admin',
        ]);

        // Teacher
        $teacher = User::create([
            'name' => 'Đinh Đăng Hùng',
            'email' => 'dinhdanghung@gmail.com',
            'phone' => '0987654321',
            'password' => bcrypt('password'),
            'role' => 'teacher',
        ]);

        // Students
        $studentIds = [];
        for ($i = 1; $i <= 3; $i++) {
            $student = User::create([
                'name' => "Student $i",
                'email' => "student$i@educore.test",
                'phone' => '090000000' . ($i + 2), // e.g. 0900000003, 0900000004...
                'password' => bcrypt('password'),
                'role' => 'student',
            ]);

            Student::create([
                'user_id' => $student->id,
                'status' => 'active',
                'joined_at' => now(),
            ]);

            $studentIds[] = $student->id;
        }

        // Create a class and assign teacher + students
        $class = Classroom::create([
            'name' => 'HSK 1 - Tối thứ 2/6',
            'level' => 'HSK 1',
            'schedule' => json_encode([
                'days' => ['Monday', 'Friday'],
                'time' => '19:15 - 20:45',
            ]),
        ]);

        $class->users()->attach($teacher->id, ['role' => 'teacher']);
        foreach ($studentIds as $studentId) {
            $class->users()->attach($studentId, ['role' => 'student']);
        }

        // Create an example assignment
        Assignment::create([
            'class_id' => $class->id,
            'title' => 'Bài tập mẫu',
            'description' => 'Mô tả cho bài tập mẫu',
            'deadline' => now()->addDays(1),
            'types' => json_encode(['upload_image', 'video', 'text']),
            'attachment_path' => null,
            'video_path' => null,
        ]);
    }
}
