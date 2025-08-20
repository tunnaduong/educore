<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Thứ tự chạy seeder theo dependency
        $this->call([
            // 1. Tạo users, students, classrooms và relationships
            UserSeeder::class,

            // 2. Tạo assignments cho các lớp
            AssignmentSeeder::class,

            // 3. Tạo attendance data
            AttendanceSeeder::class,

            // 4. Tạo lessons cho các lớp
            LessonSeeder::class,

            // 5. Tạo quizzes cho các lớp
            QuizSeeder::class,

            // 6. Tạo question bank
            QuestionBankSeeder::class,

            // 7. Tạo assignment submissions
            AssignmentSubmissionSeeder::class,

            // 8. Tạo quiz results
            QuizResultSeeder::class,

            // 9. Tạo payments
            PaymentSeeder::class,

            // 10. Tạo expenses
            ExpenseSeeder::class,

            // 11. Tạo notifications (giữ nguyên seeder cũ)
            NotificationSeeder::class,
            TeacherNotificationSeeder::class,

            // 12. Tạo chat messages (giữ nguyên seeder cũ)
            ChatSeeder::class,
        ]);
    }
}
