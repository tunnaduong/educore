<?php

namespace Database\Seeders;

use App\Models\QuestionBank;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        // Lấy tất cả giáo viên
        $teachers = User::where('role', 'teacher')->get();

        if ($teachers->isEmpty()) {
            return;
        }

        // Danh sách ngân hàng câu hỏi theo cấp độ
        $questionBanks = [
            'HSK 1' => [
                [
                    'name' => 'Ngân hàng câu hỏi HSK 1 - Từ vựng cơ bản',
                    'description' => 'Bộ câu hỏi về từ vựng cơ bản cho HSK 1',
                    'subject' => 'Tiếng Trung',
                    'topic' => 'Từ vựng cơ bản',
                    'questions' => [
                        [
                            'id' => 1,
                            'question' => '你好 có nghĩa là gì?',
                            'type' => 'multiple_choice',
                            'options' => ['Xin chào', 'Tạm biệt', 'Cảm ơn', 'Xin lỗi'],
                            'correct_answer' => 0,
                            'explanation' => '你好 (nǐ hǎo) có nghĩa là "Xin chào" trong tiếng Trung.',
                            'difficulty' => 'easy',
                        ],
                        [
                            'id' => 2,
                            'question' => 'Số "5" trong tiếng Trung viết như thế nào?',
                            'type' => 'multiple_choice',
                            'options' => ['一', '二', '三', '五'],
                            'correct_answer' => 3,
                            'explanation' => 'Số 5 trong tiếng Trung viết là 五 (wǔ).',
                            'difficulty' => 'easy',
                        ],
                        [
                            'id' => 3,
                            'question' => 'Từ nào có nghĩa là "cảm ơn"?',
                            'type' => 'multiple_choice',
                            'options' => ['你好', '谢谢', '再见', '对不起'],
                            'correct_answer' => 1,
                            'explanation' => '谢谢 (xiè xie) có nghĩa là "cảm ơn".',
                            'difficulty' => 'easy',
                        ],
                    ],
                ],
                [
                    'name' => 'Ngân hàng câu hỏi HSK 1 - Ngữ pháp cơ bản',
                    'description' => 'Bộ câu hỏi về ngữ pháp cơ bản cho HSK 1',
                    'subject' => 'Tiếng Trung',
                    'topic' => 'Ngữ pháp cơ bản',
                    'questions' => [
                        [
                            'id' => 1,
                            'question' => '你好 có nghĩa là "Tạm biệt"',
                            'type' => 'true_false',
                            'correct_answer' => false,
                            'explanation' => '你好 có nghĩa là "Xin chào", không phải "Tạm biệt".',
                            'difficulty' => 'easy',
                        ],
                        [
                            'id' => 2,
                            'question' => 'Số 1 trong tiếng Trung viết là 一',
                            'type' => 'true_false',
                            'correct_answer' => true,
                            'explanation' => 'Đúng, số 1 trong tiếng Trung viết là 一 (yī).',
                            'difficulty' => 'easy',
                        ],
                    ],
                ],
            ],
            'HSK 2' => [
                [
                    'name' => 'Ngân hàng câu hỏi HSK 2 - Từ vựng trung cấp',
                    'description' => 'Bộ câu hỏi về từ vựng trung cấp cho HSK 2',
                    'subject' => 'Tiếng Trung',
                    'topic' => 'Từ vựng trung cấp',
                    'questions' => [
                        [
                            'id' => 1,
                            'question' => 'Từ nào có nghĩa là "công việc"?',
                            'type' => 'multiple_choice',
                            'options' => ['工作', '学习', '生活', '时间'],
                            'correct_answer' => 0,
                            'explanation' => '工作 (gōng zuò) có nghĩa là "công việc".',
                            'difficulty' => 'medium',
                        ],
                        [
                            'id' => 2,
                            'question' => 'Từ nào có nghĩa là "thời gian"?',
                            'type' => 'multiple_choice',
                            'options' => ['工作', '学习', '生活', '时间'],
                            'correct_answer' => 3,
                            'explanation' => '时间 (shí jiān) có nghĩa là "thời gian".',
                            'difficulty' => 'medium',
                        ],
                        [
                            'id' => 3,
                            'question' => 'Từ nào có nghĩa là "học tập"?',
                            'type' => 'multiple_choice',
                            'options' => ['工作', '学习', '生活', '时间'],
                            'correct_answer' => 1,
                            'explanation' => '学习 (xué xí) có nghĩa là "học tập".',
                            'difficulty' => 'medium',
                        ],
                    ],
                ],
            ],
            'HSK 3' => [
                [
                    'name' => 'Ngân hàng câu hỏi HSK 3 - Từ vựng nâng cao',
                    'description' => 'Bộ câu hỏi về từ vựng nâng cao cho HSK 3',
                    'subject' => 'Tiếng Trung',
                    'topic' => 'Từ vựng nâng cao',
                    'questions' => [
                        [
                            'id' => 1,
                            'question' => 'Từ nào có nghĩa là "văn hóa"?',
                            'type' => 'multiple_choice',
                            'options' => ['文化', '历史', '地理', '经济'],
                            'correct_answer' => 0,
                            'explanation' => '文化 (wén huà) có nghĩa là "văn hóa".',
                            'difficulty' => 'hard',
                        ],
                        [
                            'id' => 2,
                            'question' => 'Từ nào có nghĩa là "lịch sử"?',
                            'type' => 'multiple_choice',
                            'options' => ['文化', '历史', '地理', '经济'],
                            'correct_answer' => 1,
                            'explanation' => '历史 (lì shǐ) có nghĩa là "lịch sử".',
                            'difficulty' => 'hard',
                        ],
                    ],
                ],
            ],
        ];

        // Tạo ngân hàng câu hỏi cho mỗi cấp độ
        foreach ($questionBanks as $level => $banks) {
            foreach ($banks as $bank) {
                $teacher = $teachers->random();

                // Tạo thống kê mẫu
                $statistics = [
                    'total_questions' => count($bank['questions']),
                    'multiple_choice' => count(array_filter($bank['questions'], fn ($q) => $q['type'] === 'multiple_choice')),
                    'true_false' => count(array_filter($bank['questions'], fn ($q) => $q['type'] === 'true_false')),
                    'essay' => count(array_filter($bank['questions'], fn ($q) => $q['type'] === 'essay')),
                    'easy' => count(array_filter($bank['questions'], fn ($q) => $q['difficulty'] === 'easy')),
                    'medium' => count(array_filter($bank['questions'], fn ($q) => $q['difficulty'] === 'medium')),
                    'hard' => count(array_filter($bank['questions'], fn ($q) => $q['difficulty'] === 'hard')),
                ];

                QuestionBank::create([
                    'name' => $bank['name'],
                    'description' => $bank['description'],
                    'subject' => $bank['subject'],
                    'topic' => $bank['topic'],
                    'questions' => $bank['questions'],
                    'statistics' => $statistics,
                    'ai_generated' => $faker->boolean(20), // 20% được tạo bởi AI
                    'ai_generation_params' => $faker->boolean(20) ? [
                        'model' => 'gpt-3.5-turbo',
                        'prompt' => 'Tạo câu hỏi tiếng Trung cho '.$level,
                        'temperature' => 0.7,
                    ] : null,
                    'ai_generated_at' => $faker->boolean(20) ? $faker->dateTimeBetween('-1 month', 'now') : null,
                    'created_by' => $teacher->id,
                ]);
            }
        }
    }
}
