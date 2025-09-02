<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizResult;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class QuizResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy tất cả bài kiểm tra
        $quizzes = Quiz::all();

        if ($quizzes->isEmpty()) {
            return;
        }

        // Tạo kết quả cho mỗi bài kiểm tra
        foreach ($quizzes as $quiz) {
            // Lấy học viên của lớp
            $students = $quiz->classroom->students()->with('studentProfile')->get();

            if ($students->isEmpty()) {
                continue;
            }

            // Tạo kết quả cho 80-95% học viên
            $participationRate = rand(80, 95);
            $participationCount = ceil(count($students) * $participationRate / 100);

            // Chọn ngẫu nhiên học viên để làm bài
            $participatingStudents = $students->random($participationCount);

            foreach ($participatingStudents as $student) {
                // Tạo điểm số (0-100)
                $score = $faker->numberBetween(40, 100);

                // Tạo thời gian nộp bài
                $submittedAt = $quiz->deadline->subDays(rand(0, 2));

                QuizResult::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->studentProfile->id,
                    'score' => $score,
                    'submitted_at' => $submittedAt,
                    'answers' => $this->generateAnswers($quiz, $faker),
                ]);
            }
        }
    }

    /**
     * Tạo câu trả lời mẫu
     */
    private function generateAnswers($quiz, $faker)
    {
        // Lấy câu hỏi từ quiz
        $questions = $quiz->questions ?? [];
        $answers = [];

        foreach ($questions as $question) {
            $questionId = $question['id'] ?? 'question_' . rand(1, 10);

            if (isset($question['type'])) {
                switch ($question['type']) {
                    case 'multiple_choice':
                        $answers[$questionId] = $faker->randomElement(['A', 'B', 'C', 'D']);
                        break;
                    default:
                        $answers[$questionId] = $faker->sentence();
                }
            } else {
                $answers[$questionId] = $faker->sentence();
            }
        }

        // Nếu không có câu hỏi, tạo câu trả lời mẫu
        if (empty($answers)) {
            $answers = [
                'answer_1' => $faker->sentence(),
                'answer_2' => $faker->sentence(),
                'answer_3' => $faker->sentence(),
            ];
        }

        return json_encode($answers);
    }
}
