<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EvaluationQuestion;

class EvaluationQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            // Câu hỏi về giáo viên
            [
                'category' => 'teacher',
                'question' => 'Giáo viên có kiến thức chuyên môn tốt và truyền đạt rõ ràng không?',
                'order' => 0,
                'is_active' => true,
            ],
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

            // Câu hỏi về khóa học
            [
                'category' => 'course',
                'question' => 'Nội dung khóa học có phù hợp với mục tiêu học tập không?',
                'order' => 0,
                'is_active' => true,
            ],
            [
                'category' => 'course',
                'question' => 'Tài liệu học tập có đầy đủ và chất lượng tốt không?',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'course',
                'question' => 'Thời gian học tập có hợp lý và hiệu quả không?',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'course',
                'question' => 'Cơ sở vật chất và trang thiết bị có đáp ứng nhu cầu học tập không?',
                'order' => 3,
                'is_active' => true,
            ],

            // Câu hỏi về cảm nhận cá nhân
            [
                'category' => 'personal',
                'question' => 'Bạn có hài lòng với chất lượng học tập tại trung tâm không?',
                'order' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($questions as $question) {
            EvaluationQuestion::create($question);
        }
    }
}
