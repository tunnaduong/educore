<?php

namespace Database\Seeders;

use App\Models\Assignment;
use App\Models\Classroom;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class DuskTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo admin test
        User::factory()->admin()->create([
            'name' => 'Admin Test',
            'email' => 'admin@educore.com',
            'password' => bcrypt('password'),
        ]);

        // Tạo teacher test
        $teacher = User::factory()->teacher()->create([
            'name' => 'Teacher Test',
            'email' => 'teacher@educore.com',
            'password' => bcrypt('password'),
        ]);

        // Tạo student test
        $student = User::factory()->student()->create([
            'name' => 'Student Test',
            'email' => 'student@educore.com',
            'password' => bcrypt('password'),
        ]);

        // Tạo classroom test với lịch học
        $classroom = Classroom::create([
            'name' => 'Lớp 10A1',
            'description' => 'Lớp chuyên Toán',
            'level' => 'HSK 1',
            'schedule' => [
                'days' => ['Monday', 'Thursday', 'Saturday'], // Thứ 2, Thứ 5, Thứ 7
                'time' => '19:00 - 20:30',
            ],
            'notes' => 'Lớp test cho hệ thống',
            'status' => 'active',
        ]);

        // Gán giáo viên cho lớp
        $classroom->users()->attach($teacher->id, ['role' => 'teacher']);

        // Gán học viên cho lớp
        $classroom->users()->attach($student->id, ['role' => 'student']);

        // Tạo lesson test
        Lesson::create([
            'title' => 'Bài 1: Giới thiệu về Toán học',
            'content' => 'Nội dung bài học về toán học cơ bản',
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'duration' => 45,
        ]);

        // Tạo assignment test
        Assignment::create([
            'title' => 'Bài tập về nhà số 1',
            'description' => 'Làm bài tập 1-10 trong sách giáo khoa',
            'class_id' => $classroom->id,
            'deadline' => now()->addDays(7),
            'types' => ['text', 'essay'],
            'max_score' => 10.0,
        ]);

        // Tạo lớp test chính xác như user mô tả
        $testClassroom = Classroom::create([
            'name' => 'Lớp học - test',
            'description' => 'Lớp test để kiểm tra lịch học',
            'level' => 'HSK 1',
            'schedule' => [
                'days' => ['Monday', 'Wednesday', 'Saturday'], // Thứ 2, Thứ 4, Thứ 7
                'time' => '19:00 - 20:30',
            ],
            'notes' => 'Lớp test cho việc kiểm tra lịch học',
            'status' => 'active',
        ]);

        // Gán giáo viên cho lớp test
        $testClassroom->users()->attach($teacher->id, ['role' => 'teacher']);

        // Gán học viên cho lớp test
        $testClassroom->users()->attach($student->id, ['role' => 'student']);

        // Tạo thêm một số user để test
        User::factory()->admin()->count(2)->create();
        User::factory()->teacher()->count(3)->create();
        User::factory()->student()->count(5)->create();

        $this->command->info('Dusk test data seeded successfully!');
        $this->command->info('Test accounts:');
        $this->command->info('- Admin: admin@educore.com / password');
        $this->command->info('- Teacher: teacher@educore.com / password');
        $this->command->info('- Student: student@educore.com / password');
    }
}
