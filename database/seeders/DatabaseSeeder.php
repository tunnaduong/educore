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
                // 1. Tạo admin
            AdminSeeder::class,

                // 2. Tạo teachers
            TeacherSeeder::class,

                // 3. Tạo students
            StudentSeeder::class,

                // 4. Tạo classrooms và relationships
            ClassroomSeeder::class,

                // 5. Tạo assignments cho các lớp
            AssignmentSeeder::class,

                // 6. Tạo attendance data
            AttendanceSeeder::class,

                // 7. Tạo lessons cho các lớp
            LessonSeeder::class,

                // 8. Tạo quizzes cho các lớp
            QuizSeeder::class,

                // 9. Tạo question bank
            QuestionBankSeeder::class,

                // 10. Tạo assignment submissions
            AssignmentSubmissionSeeder::class,

                // 11. Tạo quiz results
            QuizResultSeeder::class,

                // 12. Tạo payments
            PaymentSeeder::class,

                // 13. Tạo expenses
            ExpenseSeeder::class,

                // 14. Tạo notifications (giữ nguyên seeder cũ)
            NotificationSeeder::class,
            TeacherNotificationSeeder::class,

                // 15. Tạo chat messages (giữ nguyên seeder cũ)
            ChatSeeder::class,
        ]);
    }
}
