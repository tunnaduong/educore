<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Classroom;

class QuizTestSeeder extends Seeder
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
            $quiz = Quiz::create([
                'class_id' => $classroom->id,
                'title' => 'Bài kiểm tra test',
                'description' => 'Bài kiểm tra để test chức năng',
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

            $this->command->info('Tạo quiz test thành công! ID: ' . $quiz->id);
        } catch (\Exception $e) {
            $this->command->error('Lỗi khi tạo quiz test: ' . $e->getMessage());
        }
    }
}
