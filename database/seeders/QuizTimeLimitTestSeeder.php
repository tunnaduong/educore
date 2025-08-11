<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Classroom;

class QuizTimeLimitTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy lớp học đầu tiên
        $classroom = Classroom::first();

        if (!$classroom) {
            $this->command->error('Không có lớp học nào để tạo quiz test!');
            return;
        }

        try {
            // Tạo quiz có thời gian làm bài
            $quizWithTimeLimit = Quiz::create([
                'class_id' => $classroom->id,
                'title' => 'Bài kiểm tra có thời gian (30 phút)',
                'description' => 'Bài kiểm tra để test chức năng thời gian làm bài',
                'questions' => [
                    [
                        'question' => 'Câu hỏi trắc nghiệm test?',
                        'type' => 'multiple_choice',
                        'score' => 2,
                        'options' => ['A', 'B', 'C', 'D'],
                        'correct_answer' => 'A',
                        'explanation' => 'Đây là đáp án đúng'
                    ],
                    [
                        'question' => 'Câu hỏi đúng sai test?',
                        'type' => 'true_false',
                        'score' => 1,
                        'options' => [],
                        'correct_answer' => 'true',
                        'explanation' => 'Đây là đáp án đúng'
                    ]
                ],
                'deadline' => now()->addDays(7),
                'time_limit' => 30,
            ]);

            // Tạo quiz có thời gian 10 phút để test
            $quizWithShortTime = Quiz::create([
                'class_id' => $classroom->id,
                'title' => 'Bài kiểm tra 10 phút',
                'description' => 'Bài kiểm tra để test chức năng đếm ngược thời gian',
                'questions' => [
                    [
                        'question' => 'Câu hỏi test đếm ngược?',
                        'type' => 'multiple_choice',
                        'score' => 1,
                        'options' => ['1', '2', '3', '4'],
                        'correct_answer' => '1',
                        'explanation' => 'Đây là đáp án đúng'
                    ]
                ],
                'deadline' => now()->addDays(7),
                'time_limit' => 10,
            ]);

            // Tạo quiz không có thời gian làm bài
            $quizWithoutTimeLimit = Quiz::create([
                'class_id' => $classroom->id,
                'title' => 'Bài kiểm tra không giới hạn thời gian',
                'description' => 'Bài kiểm tra để test chức năng không giới hạn thời gian',
                'questions' => [
                    [
                        'question' => 'Câu hỏi trắc nghiệm test?',
                        'type' => 'multiple_choice',
                        'score' => 2,
                        'options' => ['A', 'B', 'C', 'D'],
                        'correct_answer' => 'A',
                        'explanation' => 'Đây là đáp án đúng'
                    ]
                ],
                'deadline' => now()->addDays(7),
                'time_limit' => null,
            ]);

            $this->command->info('Tạo quiz test thành công!');
            $this->command->info('Quiz có thời gian 30 phút: ID ' . $quizWithTimeLimit->id . ' - ' . $quizWithTimeLimit->title);
            $this->command->info('Quiz có thời gian 10 phút: ID ' . $quizWithShortTime->id . ' - ' . $quizWithShortTime->title);
            $this->command->info('Quiz không giới hạn thời gian: ID ' . $quizWithoutTimeLimit->id . ' - ' . $quizWithoutTimeLimit->title);
        } catch (\Exception $e) {
            $this->command->error('Lỗi khi tạo quiz test: ' . $e->getMessage());
        }
    }
}
