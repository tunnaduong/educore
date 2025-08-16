<?php

namespace Database\Seeders;

use App\Models\QuizResult;
use App\Models\Quiz;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

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
                // Tạo điểm số (0-100% của max_score)
                $scorePercentage = $faker->numberBetween(40, 100); // 40-100%
                $score = round(($quiz->max_score * $scorePercentage) / 100, 1);

                // Tạo thời gian làm bài
                $timeSpent = $faker->numberBetween(
                    $quiz->time_limit * 0.5, // Ít nhất 50% thời gian
                    $quiz->time_limit * 1.2  // Tối đa 120% thời gian
                );

                // Tạo thời gian bắt đầu và kết thúc
                $startTime = $quiz->start_time->addMinutes($faker->numberBetween(0, 30));
                $endTime = $startTime->addMinutes($timeSpent);

                // Tạo trạng thái
                $status = $this->determineStatus($score, $quiz->max_score, $faker);

                // Tạo ghi chú
                $notes = $this->generateNotes($score, $quiz->max_score, $faker);

                QuizResult::create([
                    'quiz_id' => $quiz->id,
                    'student_id' => $student->studentProfile->id,
                    'score' => $score,
                    'time_spent' => $timeSpent,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'status' => $status,
                    'notes' => $notes,
                    'answers' => $this->generateAnswers($quiz, $faker),
                ]);
            }
        }
    }

    /**
     * Xác định trạng thái dựa trên điểm số
     */
    private function determineStatus($score, $maxScore, $faker)
    {
        $percentage = ($score / $maxScore) * 100;

        if ($percentage >= 90) {
            return 'excellent';
        } elseif ($percentage >= 80) {
            return 'good';
        } elseif ($percentage >= 70) {
            return 'average';
        } elseif ($percentage >= 60) {
            return 'below_average';
        } else {
            return 'failed';
        }
    }

    /**
     * Tạo ghi chú cho kết quả
     */
    private function generateNotes($score, $maxScore, $faker)
    {
        $percentage = ($score / $maxScore) * 100;

        if ($percentage >= 90) {
            return $faker->randomElement([
                'Kết quả xuất sắc! Rất tốt.',
                'Hoàn thành bài kiểm tra một cách hoàn hảo.',
                'Hiểu bài sâu sắc, trả lời chính xác.',
                'Thể hiện kiến thức vững chắc.',
            ]);
        } elseif ($percentage >= 80) {
            return $faker->randomElement([
                'Kết quả tốt, cần cải thiện một số điểm nhỏ.',
                'Hiểu bài tốt, có một số lỗi nhỏ.',
                'Đáp ứng yêu cầu, cần chú ý chi tiết hơn.',
                'Có tiến bộ, tiếp tục phát huy.',
            ]);
        } elseif ($percentage >= 70) {
            return $faker->randomElement([
                'Kết quả trung bình, cần cải thiện.',
                'Hiểu bài cơ bản, cần ôn tập thêm.',
                'Có lỗi, cần chú ý hơn.',
                'Cần cố gắng hơn trong lần sau.',
            ]);
        } elseif ($percentage >= 60) {
            return $faker->randomElement([
                'Kết quả dưới trung bình, cần ôn tập nhiều.',
                'Hiểu bài chưa sâu, cần học lại.',
                'Có nhiều lỗi, cần cải thiện.',
                'Cần dành thời gian học tập nhiều hơn.',
            ]);
        } else {
            return $faker->randomElement([
                'Kết quả không đạt, cần làm lại.',
                'Hiểu sai nhiều, cần ôn tập từ đầu.',
                'Cần học lại kiến thức cơ bản.',
                'Chưa đáp ứng yêu cầu tối thiểu.',
            ]);
        }
    }

    /**
     * Tạo câu trả lời mẫu
     */
    private function generateAnswers($quiz, $faker)
    {
        // Tạo câu trả lời mẫu dựa trên loại bài kiểm tra
        $answers = [];

        switch ($quiz->quiz_type) {
            case 'multiple_choice':
                $answers = [
                    'question_1' => $faker->randomElement(['A', 'B', 'C', 'D']),
                    'question_2' => $faker->randomElement(['A', 'B', 'C', 'D']),
                    'question_3' => $faker->randomElement(['A', 'B', 'C', 'D']),
                    'question_4' => $faker->randomElement(['A', 'B', 'C', 'D']),
                    'question_5' => $faker->randomElement(['A', 'B', 'C', 'D']),
                ];
                break;
            case 'essay':
                $answers = [
                    'essay_1' => $faker->paragraphs(rand(1, 3), true),
                    'essay_2' => $faker->paragraphs(rand(1, 2), true),
                ];
                break;
            case 'mixed':
                $answers = [
                    'mc_1' => $faker->randomElement(['A', 'B', 'C', 'D']),
                    'mc_2' => $faker->randomElement(['A', 'B', 'C', 'D']),
                    'essay_1' => $faker->paragraphs(rand(1, 2), true),
                    'tf_1' => $faker->randomElement(['true', 'false']),
                    'tf_2' => $faker->randomElement(['true', 'false']),
                ];
                break;
            default:
                $answers = [
                    'answer_1' => $faker->sentence(),
                    'answer_2' => $faker->sentence(),
                    'answer_3' => $faker->sentence(),
                ];
        }

        return json_encode($answers);
    }
}