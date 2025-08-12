<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Classroom;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy tất cả các lớp học
        $classrooms = Classroom::all();

        if ($classrooms->isEmpty()) {
            return;
        }

        // Danh sách tiêu đề bài kiểm tra theo cấp độ
        $quizTitles = [
            'HSK 1' => [
                'Kiểm tra từ vựng cơ bản',
                'Kiểm tra ngữ pháp câu đơn',
                'Kiểm tra phát âm thanh mẫu',
                'Kiểm tra viết chữ Hán',
                'Kiểm tra nghe hiểu cơ bản',
                'Kiểm tra đọc hiểu đơn giản',
                'Kiểm tra tổng hợp HSK 1',
                'Kiểm tra cuối khóa HSK 1',
            ],
            'HSK 2' => [
                'Kiểm tra từ vựng trung cấp',
                'Kiểm tra ngữ pháp câu phức',
                'Kiểm tra kỹ năng giao tiếp',
                'Kiểm tra viết đoạn văn ngắn',
                'Kiểm tra nghe hiểu trung bình',
                'Kiểm tra đọc hiểu văn bản',
                'Kiểm tra tổng hợp HSK 2',
                'Kiểm tra cuối khóa HSK 2',
            ],
            'HSK 3' => [
                'Kiểm tra từ vựng nâng cao',
                'Kiểm tra ngữ pháp câu điều kiện',
                'Kiểm tra kỹ năng thuyết trình',
                'Kiểm tra viết bài luận ngắn',
                'Kiểm tra nghe hiểu nâng cao',
                'Kiểm tra đọc hiểu văn bản dài',
                'Kiểm tra tổng hợp HSK 3',
                'Kiểm tra cuối khóa HSK 3',
            ],
            'HSK 4' => [
                'Kiểm tra từ vựng chuyên ngành',
                'Kiểm tra ngữ pháp câu phức hợp',
                'Kiểm tra kỹ năng tranh luận',
                'Kiểm tra viết bài báo cáo',
                'Kiểm tra nghe hiểu chuyên sâu',
                'Kiểm tra đọc hiểu văn học',
                'Kiểm tra tổng hợp HSK 4',
                'Kiểm tra cuối khóa HSK 4',
            ],
            'HSK 5' => [
                'Kiểm tra từ vựng học thuật',
                'Kiểm tra ngữ pháp nâng cao',
                'Kiểm tra kỹ năng thuyết trình học thuật',
                'Kiểm tra viết bài nghiên cứu',
                'Kiểm tra nghe hiểu hội thảo',
                'Kiểm tra đọc hiểu văn bản học thuật',
                'Kiểm tra tổng hợp HSK 5',
                'Kiểm tra cuối khóa HSK 5',
            ],
            'HSK 6' => [
                'Kiểm tra từ vựng chuyên sâu',
                'Kiểm tra ngữ pháp cao cấp',
                'Kiểm tra kỹ năng hùng biện',
                'Kiểm tra viết luận văn',
                'Kiểm tra nghe hiểu hội nghị',
                'Kiểm tra đọc hiểu văn bản chuyên ngành',
                'Kiểm tra tổng hợp HSK 6',
                'Kiểm tra cuối khóa HSK 6',
            ],
        ];

        // Mô tả bài kiểm tra
        $quizDescriptions = [
            'Bài kiểm tra đánh giá kiến thức đã học trong khóa học.',
            'Kiểm tra khả năng hiểu và vận dụng kiến thức.',
            'Đánh giá kỹ năng thực hành và ứng dụng.',
            'Kiểm tra tổng hợp các kỹ năng nghe, nói, đọc, viết.',
            'Bài kiểm tra cuối khóa để đánh giá toàn diện.',
            'Kiểm tra kiến thức chuyên sâu và nâng cao.',
            'Đánh giá khả năng giao tiếp và thực hành.',
            'Kiểm tra kiến thức lý thuyết và thực tế.',
        ];

        // Tạo bài kiểm tra cho mỗi lớp
        foreach ($classrooms as $classroom) {
            $level = $classroom->level;
            $titles = $quizTitles[$level] ?? $quizTitles['HSK 1'];

            // Tạo 2-3 bài kiểm tra cho mỗi lớp
            $quizCount = rand(2, 3);

            for ($i = 0; $i < $quizCount; $i++) {
                $title = $titles[$i] ?? $faker->sentence(3);
                $description = $faker->randomElement($quizDescriptions);

                // Tạo thời gian làm bài ngẫu nhiên (15-60 phút)
                $timeLimit = $faker->randomElement([15, 30, 45, 60]);

                // Tạo điểm tối đa ngẫu nhiên (10-100 điểm)
                $maxScore = $faker->randomElement([10, 20, 50, 100]);

                // Tạo thời gian bắt đầu và kết thúc
                $startTime = now()->addDays(rand(1, 14));
                $endTime = $startTime->copy()->addDays(rand(1, 7));

                // Chọn loại bài kiểm tra
                $quizType = $faker->randomElement(['multiple_choice', 'essay', 'mixed']);

                Quiz::create([
                    'class_id' => $classroom->id,
                    'title' => $title,
                    'description' => $description,
                    'time_limit' => $timeLimit,
                    'max_score' => $maxScore,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'quiz_type' => $quizType,
                    'is_active' => $faker->boolean(80), // 80% khả năng active
                ]);
            }
        }
    }
}