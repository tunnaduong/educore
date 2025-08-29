<?php

namespace Database\Seeders;

use App\Models\EvaluationQuestion;
use Illuminate\Database\Seeder;

class EvaluationQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // Câu hỏi đánh giá Khóa học - Hoạt động
            [
                'category' => 'course',
                'question' => 'Nội dung khóa học có phù hợp với mục tiêu học tập không?',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'course',
                'question' => 'Tài liệu học tập có đầy đủ và chất lượng tốt không?',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'course',
                'question' => 'Thời gian học tập có hợp lý và hiệu quả không?',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'category' => 'course',
                'question' => 'Cơ sở vật chất và trang thiết bị có đáp ứng nhu cầu học tập không?',
                'order' => 4,
                'is_active' => true,
            ],

            // Câu hỏi đánh giá Cá nhân - Hoạt động
            [
                'category' => 'personal',
                'question' => 'Bạn có hài lòng với chất lượng học tập tại trung tâm không?',
                'order' => 1,
                'is_active' => true,
            ],

            // Câu hỏi đánh giá Giáo viên - Hoạt động
            [
                'category' => 'teacher',
                'question' => 'Giáo viên có nhiệt tình và tạo không khí học tập tích cực không?',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'teacher',
                'question' => 'Giáo viên có sẵn sàng giải đáp thắc mắc và hỗ trợ học viên không?',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'teacher',
                'question' => 'Giáo viên có sử dụng phương pháp giảng dạy hiệu quả và phù hợp không?',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'category' => 'teacher',
                'question' => 'Giáo viên có đánh giá công bằng và khách quan không?',
                'order' => 4,
                'is_active' => true,
            ],

            // Câu hỏi đánh giá Giáo viên - Không hoạt động
            [
                'category' => 'teacher',
                'question' => 'Giáo viên có nhiệt tình và tạo môi trường học tích cực không?',
                'order' => 5,
                'is_active' => false,
            ],
            [
                'category' => 'teacher',
                'question' => 'Giáo viên có giải đáp thắc mắc kịp thời và đầy đủ không?',
                'order' => 6,
                'is_active' => false,
            ],
            [
                'category' => 'teacher',
                'question' => 'Phương pháp giảng dạy của giáo viên có phù hợp và hiệu quả không?',
                'order' => 7,
                'is_active' => false,
            ],
            [
                'category' => 'teacher',
                'question' => 'Giáo viên đánh giá kết quả học tập công bằng và khách quan chứ?',
                'order' => 8,
                'is_active' => false,
            ],

            // Câu hỏi đánh giá Khóa học - Không hoạt động
            [
                'category' => 'course',
                'question' => 'Tài liệu và giáo trình có đầy đủ, dễ hiểu, cập nhật không?',
                'order' => 5,
                'is_active' => false,
            ],
            [
                'category' => 'course',
                'question' => 'Bài tập và kiểm tra có hợp lý, phản ánh đúng kiến thức không?',
                'order' => 6,
                'is_active' => false,
            ],
            [
                'category' => 'course',
                'question' => 'Cơ sở vật chất và hạ tầng kỹ thuật có đáp ứng nhu cầu học tập không?',
                'order' => 7,
                'is_active' => false,
            ],
            [
                'category' => 'course',
                'question' => 'Giáo trình và tài liệu học tập có rõ ràng, dễ hiểu không?',
                'order' => 8,
                'is_active' => false,
            ],
            [
                'category' => 'course',
                'question' => 'Cấu trúc buổi học có hợp lý và dễ theo dõi không?',
                'order' => 9,
                'is_active' => false,
            ],
        ];

        foreach ($questions as $questionData) {
            EvaluationQuestion::create($questionData);
        }

        $this->command->info('Đã tạo ' . count($questions) . ' câu hỏi đánh giá.');
        $this->command->info('- Câu hỏi hoạt động: ' . collect($questions)->where('is_active', true)->count());
        $this->command->info('- Câu hỏi không hoạt động: ' . collect($questions)->where('is_active', false)->count());
    }
}
