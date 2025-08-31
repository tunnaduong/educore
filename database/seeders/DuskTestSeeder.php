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

        // Tạo classroom test
        $classroom = Classroom::create([
            'name' => 'Lớp 10A1',
            'description' => 'Lớp chuyên Toán',
            'teacher_id' => $teacher->id,
            'capacity' => 30,
        ]);

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
