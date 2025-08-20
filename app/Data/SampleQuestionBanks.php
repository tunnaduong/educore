<?php

namespace App\Data;

class SampleQuestionBanks
{
    /**
     * Ngân hàng câu hỏi mẫu cho tiếng Trung - Chủ đề Gia đình
     */
    public static function getFamilyQuestions()
    {
        return [
            'questions' => [
                [
                    'id' => 1,
                    'question' => '爸爸 (bàba) có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['Bố', 'Mẹ', 'Anh trai', 'Em gái'],
                    'correct_answer' => 'Bố',
                    'explanation' => '爸爸 (bàba) có nghĩa là bố trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'cơ bản'],
                    'score' => 1
                ],
                [
                    'id' => 2,
                    'question' => '妈妈 (māma) có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['Bố', 'Mẹ', 'Chị gái', 'Em trai'],
                    'correct_answer' => 'Mẹ',
                    'explanation' => '妈妈 (māma) có nghĩa là mẹ trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'cơ bản'],
                    'score' => 1
                ],
                [
                    'id' => 3,
                    'question' => 'Điền từ thích hợp: 我的___很高。(Bố tôi rất cao)',
                    'type' => 'fill_blank',
                    'difficulty' => 'medium',
                    'options' => [],
                    'correct_answer' => '爸爸',
                    'explanation' => '爸爸 (bàba) là từ chỉ bố trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'ngữ pháp'],
                    'score' => 2
                ],
                [
                    'id' => 4,
                    'question' => '哥哥 (gēge) có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['Em trai', 'Anh trai', 'Em gái', 'Chị gái'],
                    'correct_answer' => 'Anh trai',
                    'explanation' => '哥哥 (gēge) có nghĩa là anh trai trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'anh chị em'],
                    'score' => 1
                ],
                [
                    'id' => 5,
                    'question' => '姐姐 (jiějie) có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['Chị gái', 'Em gái', 'Anh trai', 'Em trai'],
                    'correct_answer' => 'Chị gái',
                    'explanation' => '姐姐 (jiějie) có nghĩa là chị gái trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'anh chị em'],
                    'score' => 1
                ],
                [
                    'id' => 6,
                    'question' => 'Điền từ thích hợp: 我有一个___。(Tôi có một em gái)',
                    'type' => 'fill_blank',
                    'difficulty' => 'medium',
                    'options' => [],
                    'correct_answer' => '妹妹',
                    'explanation' => '妹妹 (mèimei) có nghĩa là em gái trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'anh chị em'],
                    'score' => 2
                ],
                [
                    'id' => 7,
                    'question' => '爷爷 (yéye) có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'medium',
                    'options' => ['Ông nội', 'Bà nội', 'Ông ngoại', 'Bà ngoại'],
                    'correct_answer' => 'Ông nội',
                    'explanation' => '爷爷 (yéye) có nghĩa là ông nội (bố của bố) trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'ông bà'],
                    'score' => 2
                ],
                [
                    'id' => 8,
                    'question' => '奶奶 (nǎinai) có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'medium',
                    'options' => ['Bà nội', 'Ông nội', 'Bà ngoại', 'Ông ngoại'],
                    'correct_answer' => 'Bà nội',
                    'explanation' => '奶奶 (nǎinai) có nghĩa là bà nội (mẹ của bố) trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'ông bà'],
                    'score' => 2
                ],
                [
                    'id' => 9,
                    'question' => 'Viết một câu ngắn giới thiệu về gia đình bạn bằng tiếng Trung (ít nhất 3 thành viên)',
                    'type' => 'essay',
                    'difficulty' => 'hard',
                    'options' => [],
                    'correct_answer' => 'Câu trả lời mẫu: 我家有四个人：爸爸、妈妈、哥哥和我。(Gia đình tôi có 4 người: bố, mẹ, anh trai và tôi.)',
                    'explanation' => 'Câu trả lời cần sử dụng từ vựng gia đình đã học và cấu trúc câu đơn giản',
                    'tags' => ['từ vựng', 'gia đình', 'viết', 'ngữ pháp'],
                    'score' => 3
                ],
                [
                    'id' => 10,
                    'question' => '弟弟 (dìdi) có nghĩa là gì?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['Em trai', 'Anh trai', 'Em gái', 'Chị gái'],
                    'correct_answer' => 'Em trai',
                    'explanation' => '弟弟 (dìdi) có nghĩa là em trai trong tiếng Trung',
                    'tags' => ['từ vựng', 'gia đình', 'anh chị em'],
                    'score' => 1
                ]
            ],
            'statistics' => [
                'total_questions' => 10,
                'easy_count' => 6,
                'medium_count' => 3,
                'hard_count' => 1,
                'multiple_choice_count' => 7,
                'fill_blank_count' => 2,
                'essay_count' => 1
            ]
        ];
    }

    /**
     * Ngân hàng câu hỏi mẫu cho tiếng Trung - Chủ đề Số đếm
     */
    public static function getNumbersQuestions()
    {
        return [
            'questions' => [
                [
                    'id' => 1,
                    'question' => '一 (yī) có nghĩa là số mấy?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['1', '2', '3', '4'],
                    'correct_answer' => '1',
                    'explanation' => '一 (yī) có nghĩa là số 1 trong tiếng Trung',
                    'tags' => ['từ vựng', 'số đếm', 'cơ bản'],
                    'score' => 1
                ],
                [
                    'id' => 2,
                    'question' => '二 (èr) có nghĩa là số mấy?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['1', '2', '3', '4'],
                    'correct_answer' => '2',
                    'explanation' => '二 (èr) có nghĩa là số 2 trong tiếng Trung',
                    'tags' => ['từ vựng', 'số đếm', 'cơ bản'],
                    'score' => 1
                ],
                [
                    'id' => 3,
                    'question' => 'Điền số thích hợp: 我有___本书。(Tôi có 3 quyển sách)',
                    'type' => 'fill_blank',
                    'difficulty' => 'medium',
                    'options' => [],
                    'correct_answer' => '三',
                    'explanation' => '三 (sān) có nghĩa là số 3 trong tiếng Trung',
                    'tags' => ['từ vựng', 'số đếm', 'ngữ pháp'],
                    'score' => 2
                ],
                [
                    'id' => 4,
                    'question' => '十 (shí) có nghĩa là số mấy?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['8', '9', '10', '11'],
                    'correct_answer' => '10',
                    'explanation' => '十 (shí) có nghĩa là số 10 trong tiếng Trung',
                    'tags' => ['từ vựng', 'số đếm', 'cơ bản'],
                    'score' => 1
                ],
                [
                    'id' => 5,
                    'question' => '五 (wǔ) có nghĩa là số mấy?',
                    'type' => 'multiple_choice',
                    'difficulty' => 'easy',
                    'options' => ['4', '5', '6', '7'],
                    'correct_answer' => '5',
                    'explanation' => '五 (wǔ) có nghĩa là số 5 trong tiếng Trung',
                    'tags' => ['từ vựng', 'số đếm', 'cơ bản'],
                    'score' => 1
                ]
            ],
            'statistics' => [
                'total_questions' => 5,
                'easy_count' => 4,
                'medium_count' => 1,
                'hard_count' => 0,
                'multiple_choice_count' => 4,
                'fill_blank_count' => 1,
                'essay_count' => 0
            ]
        ];
    }

    /**
     * Lấy ngân hàng câu hỏi mẫu theo chủ đề
     */
    public static function getSampleByTopic($topic)
    {
        $topic = strtolower(trim($topic));

        if (strpos($topic, 'gia đình') !== false || strpos($topic, 'family') !== false) {
            return self::getFamilyQuestions();
        }

        if (strpos($topic, 'số') !== false || strpos($topic, 'number') !== false) {
            return self::getNumbersQuestions();
        }

        // Trả về mẫu gia đình làm mặc định
        return self::getFamilyQuestions();
    }
}
