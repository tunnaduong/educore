<?php

namespace Database\Seeders;

use App\Models\QuestionBank;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Danh sách câu hỏi theo cấp độ và loại
        $questions = [
            'HSK 1' => [
                // Câu hỏi trắc nghiệm
                [
                    'question' => '你好 có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'options' => ['Xin chào', 'Tạm biệt', 'Cảm ơn', 'Xin lỗi'],
                    'correct_answer' => 'Xin chào',
                    'explanation' => '你好 (nǐ hǎo) có nghĩa là "Xin chào" trong tiếng Trung.',
                    'difficulty' => 'easy',
                ],
                [
                    'question' => 'Số "5" trong tiếng Trung viết như thế nào?',
                    'type' => 'multiple_choice',
                    'options' => ['一', '二', '三', '五'],
                    'correct_answer' => '五',
                    'explanation' => 'Số 5 trong tiếng Trung viết là 五 (wǔ).',
                    'difficulty' => 'easy',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "cảm ơn"?',
                    'type' => 'multiple_choice',
                    'options' => ['你好', '谢谢', '再见', '对不起'],
                    'correct_answer' => '谢谢',
                    'explanation' => '谢谢 (xiè xie) có nghĩa là "cảm ơn".',
                    'difficulty' => 'easy',
                ],
                // Câu hỏi đúng/sai
                [
                    'question' => '你好 có nghĩa là "Tạm biệt"',
                    'type' => 'true_false',
                    'correct_answer' => 'false',
                    'explanation' => '你好 có nghĩa là "Xin chào", không phải "Tạm biệt".',
                    'difficulty' => 'easy',
                ],
                [
                    'question' => 'Số 1 trong tiếng Trung viết là 一',
                    'type' => 'true_false',
                    'correct_answer' => 'true',
                    'explanation' => 'Đúng, số 1 trong tiếng Trung viết là 一 (yī).',
                    'difficulty' => 'easy',
                ],
                // Câu hỏi tự luận
                [
                    'question' => 'Viết câu giới thiệu bản thân bằng tiếng Trung',
                    'type' => 'essay',
                    'correct_answer' => '我叫... (Tôi tên là...)',
                    'explanation' => 'Cách giới thiệu tên trong tiếng Trung: 我叫 + tên.',
                    'difficulty' => 'medium',
                ],
            ],
            'HSK 2' => [
                [
                    'question' => 'Từ nào có nghĩa là "công việc"?',
                    'type' => 'multiple_choice',
                    'options' => ['工作', '学习', '生活', '时间'],
                    'correct_answer' => '工作',
                    'explanation' => '工作 (gōng zuò) có nghĩa là "công việc".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "thời gian"?',
                    'type' => 'multiple_choice',
                    'options' => ['工作', '学习', '生活', '时间'],
                    'correct_answer' => '时间',
                    'explanation' => '时间 (shí jiān) có nghĩa là "thời gian".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "học tập"?',
                    'type' => 'multiple_choice',
                    'options' => ['工作', '学习', '生活', '时间'],
                    'correct_answer' => '学习',
                    'explanation' => '学习 (xué xí) có nghĩa là "học tập".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => '工作 có nghĩa là "học tập"',
                    'type' => 'true_false',
                    'correct_answer' => 'false',
                    'explanation' => '工作 có nghĩa là "công việc", không phải "học tập".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => 'Viết câu hỏi "Bạn làm gì?" bằng tiếng Trung',
                    'type' => 'essay',
                    'correct_answer' => '你做什么工作？',
                    'explanation' => 'Cách hỏi về công việc: 你做什么工作？(nǐ zuò shén me gōng zuò?)',
                    'difficulty' => 'medium',
                ],
            ],
            'HSK 3' => [
                [
                    'question' => 'Từ nào có nghĩa là "văn hóa"?',
                    'type' => 'multiple_choice',
                    'options' => ['文化', '历史', '地理', '经济'],
                    'correct_answer' => '文化',
                    'explanation' => '文化 (wén huà) có nghĩa là "văn hóa".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "lịch sử"?',
                    'type' => 'multiple_choice',
                    'options' => ['文化', '历史', '地理', '经济'],
                    'correct_answer' => '历史',
                    'explanation' => '历史 (lì shǐ) có nghĩa là "lịch sử".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "kinh tế"?',
                    'type' => 'multiple_choice',
                    'options' => ['文化', '历史', '地理', '经济'],
                    'correct_answer' => '经济',
                    'explanation' => '经济 (jīng jì) có nghĩa là "kinh tế".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => '文化 có nghĩa là "lịch sử"',
                    'type' => 'true_false',
                    'correct_answer' => 'false',
                    'explanation' => '文化 có nghĩa là "văn hóa", không phải "lịch sử".',
                    'difficulty' => 'medium',
                ],
                [
                    'question' => 'Viết câu "Tôi thích văn hóa Trung Quốc" bằng tiếng Trung',
                    'type' => 'essay',
                    'correct_answer' => '我喜欢中国文化',
                    'explanation' => '我喜欢中国文化 (wǒ xǐ huān zhōng guó wén huà)',
                    'difficulty' => 'medium',
                ],
            ],
            'HSK 4' => [
                [
                    'question' => 'Từ nào có nghĩa là "phát triển"?',
                    'type' => 'multiple_choice',
                    'options' => ['发展', '进步', '提高', '改善'],
                    'correct_answer' => '发展',
                    'explanation' => '发展 (fā zhǎn) có nghĩa là "phát triển".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "cải thiện"?',
                    'type' => 'multiple_choice',
                    'options' => ['发展', '进步', '提高', '改善'],
                    'correct_answer' => '改善',
                    'explanation' => '改善 (gǎi shàn) có nghĩa là "cải thiện".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "tiến bộ"?',
                    'type' => 'multiple_choice',
                    'options' => ['发展', '进步', '提高', '改善'],
                    'correct_answer' => '进步',
                    'explanation' => '进步 (jìn bù) có nghĩa là "tiến bộ".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => '发展 có nghĩa là "cải thiện"',
                    'type' => 'true_false',
                    'correct_answer' => 'false',
                    'explanation' => '发展 có nghĩa là "phát triển", không phải "cải thiện".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Viết câu "Việt Nam đang phát triển nhanh" bằng tiếng Trung',
                    'type' => 'essay',
                    'correct_answer' => '越南发展很快',
                    'explanation' => '越南发展很快 (yuè nán fā zhǎn hěn kuài)',
                    'difficulty' => 'hard',
                ],
            ],
            'HSK 5' => [
                [
                    'question' => 'Từ nào có nghĩa là "nghiên cứu"?',
                    'type' => 'multiple_choice',
                    'options' => ['研究', '调查', '分析', '观察'],
                    'correct_answer' => '研究',
                    'explanation' => '研究 (yán jiū) có nghĩa là "nghiên cứu".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "phân tích"?',
                    'type' => 'multiple_choice',
                    'options' => ['研究', '调查', '分析', '观察'],
                    'correct_answer' => '分析',
                    'explanation' => '分析 (fēn xī) có nghĩa là "phân tích".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "điều tra"?',
                    'type' => 'multiple_choice',
                    'options' => ['研究', '调查', '分析', '观察'],
                    'correct_answer' => '调查',
                    'explanation' => '调查 (diào chá) có nghĩa là "điều tra".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => '研究 có nghĩa là "điều tra"',
                    'type' => 'true_false',
                    'correct_answer' => 'false',
                    'explanation' => '研究 có nghĩa là "nghiên cứu", không phải "điều tra".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Viết câu "Tôi đang nghiên cứu văn hóa Việt Nam" bằng tiếng Trung',
                    'type' => 'essay',
                    'correct_answer' => '我正在研究越南文化',
                    'explanation' => '我正在研究越南文化 (wǒ zhèng zài yán jiū yuè nán wén huà)',
                    'difficulty' => 'hard',
                ],
            ],
            'HSK 6' => [
                [
                    'question' => 'Từ nào có nghĩa là "triết học"?',
                    'type' => 'multiple_choice',
                    'options' => ['哲学', '科学', '文学', '艺术'],
                    'correct_answer' => '哲学',
                    'explanation' => '哲学 (zhé xué) có nghĩa là "triết học".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "nghệ thuật"?',
                    'type' => 'multiple_choice',
                    'options' => ['哲学', '科学', '文学', '艺术'],
                    'correct_answer' => '艺术',
                    'explanation' => '艺术 (yì shù) có nghĩa là "nghệ thuật".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Từ nào có nghĩa là "văn học"?',
                    'type' => 'multiple_choice',
                    'options' => ['哲学', '科学', '文学', '艺术'],
                    'correct_answer' => '文学',
                    'explanation' => '文学 (wén xué) có nghĩa là "văn học".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => '哲学 có nghĩa là "văn học"',
                    'type' => 'true_false',
                    'correct_answer' => 'false',
                    'explanation' => '哲学 có nghĩa là "triết học", không phải "văn học".',
                    'difficulty' => 'hard',
                ],
                [
                    'question' => 'Viết câu "Triết học Trung Quốc rất sâu sắc" bằng tiếng Trung',
                    'type' => 'essay',
                    'correct_answer' => '中国哲学很深刻',
                    'explanation' => '中国哲学很深刻 (zhōng guó zhé xué hěn shēn kè)',
                    'difficulty' => 'hard',
                ],
            ],
        ];

        // Tạo câu hỏi cho mỗi cấp độ
        foreach ($questions as $level => $levelQuestions) {
            foreach ($levelQuestions as $questionData) {
                QuestionBank::create([
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => $questionData['options'] ?? null,
                    'correct_answer' => $questionData['correct_answer'],
                    'explanation' => $questionData['explanation'],
                    'difficulty' => $questionData['difficulty'],
                    'level' => $level,
                    'is_active' => $faker->boolean(90), // 90% khả năng active
                ]);
            }
        }

        // Tạo thêm một số câu hỏi ngẫu nhiên
        $additionalQuestions = [
            'Viết câu "Tôi thích học tiếng Trung" bằng tiếng Trung',
            'Từ nào có nghĩa là "gia đình"?',
            'Số 10 trong tiếng Trung viết như thế nào?',
            'Từ nào có nghĩa là "bạn bè"?',
            'Viết câu "Cảm ơn bạn" bằng tiếng Trung',
            'Từ nào có nghĩa là "thành phố"?',
            'Số 100 trong tiếng Trung viết như thế nào?',
            'Từ nào có nghĩa là "quốc gia"?',
            'Viết câu "Tôi đến từ Việt Nam" bằng tiếng Trung',
            'Từ nào có nghĩa là "ngôn ngữ"?',
        ];

        foreach ($additionalQuestions as $question) {
            $type = $faker->randomElement(['multiple_choice', 'true_false', 'essay']);
            $difficulty = $faker->randomElement(['easy', 'medium', 'hard']);
            $level = $faker->randomElement(['HSK 1', 'HSK 2', 'HSK 3', 'HSK 4', 'HSK 5', 'HSK 6']);

            QuestionBank::create([
                'question' => $question,
                'type' => $type,
                'options' => $type === 'multiple_choice' ? ['A', 'B', 'C', 'D'] : null,
                'correct_answer' => $faker->sentence(),
                'explanation' => $faker->paragraph(),
                'difficulty' => $difficulty,
                'level' => $level,
                'is_active' => $faker->boolean(90),
            ]);
        }
    }
}